<?php
/**
 * SimpleMap for Craft CMS
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2019 Ether Creative
 */

namespace ether\simplemap\fields;

use Craft;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\base\Event;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\elements\db\ElementQuery;
use craft\elements\db\ElementQueryInterface;
use craft\errors\InvalidFieldException;
use craft\events\CancelableEvent;
use craft\helpers\Json;
use ether\simplemap\enums\GeoService as GeoEnum;
use ether\simplemap\enums\MapTiles;
use ether\simplemap\integrations\graphql\MapType;
use ether\simplemap\models\Settings;
use ether\simplemap\services\GeoService;
use ether\simplemap\SimpleMap;
use ether\simplemap\models\Map;
use ether\simplemap\web\assets\MapAsset;
use Exception;
use GraphQL\Type\Definition\Type;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Markup;
use yii\base\InvalidConfigException;
use yii\db\ExpressionInterface;
use yii\db\Schema;

/**
 * Class Map
 *
 * @author  Ether Creative
 * @package ether\simplemap\fields
 */
class MapField extends Field implements PreviewableFieldInterface
{

	// Properties
	// =========================================================================

	/**
	 * @var float - The maps latitude
	 */
	public float $lat = 51.272154;

	/**
	 * @var float - The maps longitude
	 */
	public float $lng = 0.514951;

	/**
	 * @var int - The maps zoom level
	 */
	public int $zoom = 15;

	/**
	 * @var float - The maps min zoom level (how far OUT it can be zoomed)
	 */
	public float $minZoom = 3;

	/**
	 * @var float - The maps max zoom level (how far IN it can be zoomed)
	 */
	public float $maxZoom = 18;

	/**
	 * @var string|null - The preferred country when searching
	 */
	public ?string $country = null;

	/**
	 * @var bool - If true, the location search will not be displayed
	 */
	public bool $hideSearch = false;

	/**
	 * @var bool - If true, the map will not be displayed
	 */
	public bool $hideMap = false;

	/**
	 * @var bool - If true, the address fields will not be displayed
	 */
	public bool $hideAddress = false;

	/**
	 * @var bool - If true, show the lat/lng fields
	 */
	public bool $showLatLng = false;

	/**
	 * @var bool - If true, will show a button to centre the map on the users
	 *   current location.
	 */
	public bool $showCurrentLocation = false;

	/**
	 * @var string - The size of the field
	 *   (can be either "normal", "mini")
	 */
	public string $size = 'normal';

	/**
	 * @var bool - Will show the what3words overlay when true
	 */
	public bool $showW3WGrid = false;

	/**
	 * @var bool - Show the what3words field on the map field
	 */
	public bool $showW3WField = false;

	/**
	 * @deprecated
	 */
	public $hideLatLng;
	/**
	 * @deprecated
	 */
	public $height;
	/**
	 * @deprecated
	 */
	public $countryRestriction;
	/**
	 * @deprecated
	 */
	public $typeRestriction;
	/**
	 * @deprecated
	 */
	public $boundaryRestrictionNELat;
	/**
	 * @deprecated
	 */
	public $boundaryRestrictionNELng;
	/**
	 * @deprecated
	 */
	public $boundaryRestrictionSWLat;
	/**
	 * @deprecated
	 */
	public $boundaryRestrictionSWLng;
	/**
	 * @deprecated
	 */
	public string $boundary = '""';

	private static $searchParams = null;

	// Methods
	// =========================================================================

	public function init(): void
	{
		Event::on(
			ElementQuery::class,
			ElementQuery::EVENT_AFTER_PREPARE,
			[$this, 'afterPrepareElementQuery'],
		);

		parent::init();
	}

	// Methods: Static
	// -------------------------------------------------------------------------

	public static function displayName (): string
	{
		return SimpleMap::t('Map');
	}

	public static function hasContentColumn (): bool
	{
		return true;
	}

	public static function icon (): string
	{
		return 'map-pin';
	}

	public function getContentColumnType (): string
	{
		return Schema::TYPE_TEXT;
	}

	public static function supportedTranslationMethods (): array
	{
		return [
			self::TRANSLATION_METHOD_NONE,
			self::TRANSLATION_METHOD_SITE,
			self::TRANSLATION_METHOD_SITE_GROUP,
			self::TRANSLATION_METHOD_LANGUAGE,
			self::TRANSLATION_METHOD_CUSTOM,
		];
	}

	public static function queryCondition(array $instances, mixed $value, array &$params): array|string|ExpressionInterface|false|null
	{
		if (empty($instances) || empty($value))
			return null;

		self::$searchParams = [
			'field' => $instances[0],
			'value' => $value,
		];

		return null;
	}

	// Methods: Instance
	// -------------------------------------------------------------------------

	public function setCountryRestriction ($value)
	{
		$this->country = $value;
	}

	public function rules (): array
	{
		$rules = parent::rules();

		$rules[] = [
			['zoom', 'minZoom', 'maxZoom'],
			'required',
		];

		$rules[] = [
			['minZoom', 'maxZoom'],
			'double',
			'min' => 0,
			'max' => 18,
		];

		$rules[] = [
			['lat'],
			'double',
			'min' => -90,
			'max' => 90,
		];

		$rules[] = [
			['lng'],
			'double',
			'min' => -180,
			'max' => 180,
		];

		return $rules;
	}

	public function normalizeValue (mixed $value, ElementInterface|Element $element = null): Map
	{
		if (is_string($value))
			$value = Json::decodeIfJson($value);

		if ($value instanceof Map)
			$map = $value;
		elseif (is_array($value))
			$map = new Map($value);
		else
			$map = new Map([
				'lat'  => null,
				'lng'  => null,
				'zoom' => $this->zoom,
			]);

		SimpleMap::getInstance()->map->populateMissingData($map, $this);

		$map->fieldId = $this->id;

		if ($element)
		{
			$map->ownerId     = $element->id;
			$map->ownerSiteId = $element->siteId;

			$handle = $this->handle;
			$element->setFieldValue($handle, $map);
		}

		return $map;
	}

	public function getSettingsHtml (): string
	{
		$value = new Map();

		$value->lat  = $this->lat;
		$value->lng  = $this->lng;
		$value->zoom = $this->zoom;

		$originalHandle              = $this->handle;
		$originalCountry             = $this->country;
		$originalHideSearch          = $this->hideSearch;
		$originalHideMap             = $this->hideMap;
		$originalHideAddress         = $this->hideAddress;
		$originalShowCurrentLocation = $this->showCurrentLocation;
		$originalSize                = $this->size;
		$originalShowW3WGrid         = $this->showW3WGrid;
		$originalShowW3WField        = $this->showW3WField;

		$this->handle              = '__settings__';
		$this->country             = null;
		$this->hideSearch          = false;
		$this->hideMap             = false;
		$this->hideAddress         = true;
		$this->showCurrentLocation = true;
		$this->size                = 'normal';
		$this->showW3WGrid         = false;
		$this->showW3WField        = false;

		$mapField = new Markup(
			$this->_renderMap($value, true),
			'utf-8'
		);

		$this->handle              = $originalHandle;
		$this->country             = $originalCountry;
		$this->hideSearch          = $originalHideSearch;
		$this->hideMap             = $originalHideMap;
		$this->hideAddress         = $originalHideAddress;
		$this->showCurrentLocation = $originalShowCurrentLocation;
		$this->size                = $originalSize;
		$this->showW3WGrid         = $originalShowW3WGrid;
		$this->showW3WField        = $originalShowW3WField;

		$view = Craft::$app->getView();

		$countries = array_merge([
			'*' => SimpleMap::t('All Countries'),
		], GeoService::$countries);

		return $view->renderTemplate('simplemap/field-settings', [
			'map' => $mapField,
			'field' => $this,
			'countries' => $countries,
			'settings' => SimpleMap::getInstance()->getSettings(),
		]);
	}

	public function getInputHtml ($value = null, ElementInterface $element = null): string
	{
		if ($element !== null && $element->hasEagerLoadedElements($this->handle))
			$value = $element->getEagerLoadedElements($this->handle);

		return new Markup(
			$this->_renderMap($value ?: new Map()),
			'utf-8'
		);
	}

	public function getTableAttributeHtml (mixed $value, ElementInterface $element): string
	{
		return $this->normalizeValue($value, $element)->address;
	}

	public function isValueEmpty ($value, ElementInterface $element): bool
	{
		return $this->normalizeValue($value)->isValueEmpty();
	}

	// GraphQl
	// -------------------------------------------------------------------------

	/**
	 * @inheritdoc
	 */
	public function getContentGqlType (): Type|array
	{
		return MapType::getType();
	}

	public function getContentGqlQueryArgumentType (): Type|array
	{
		return MapType::getQueryType();
	}

	public function getContentGqlMutationArgumentType (): Type|array
	{
		return MapType::getInputType();
	}

	// Methods: Events
	// -------------------------------------------------------------------------

	public function beforeSave (bool $isNew): bool
	{
		$this->lat  = (float) $this->lat;
		$this->lng  = (float) $this->lng;
		$this->zoom = (int) $this->zoom;

		if ($this->country === '*')
			$this->country = null;

		return parent::beforeSave($isNew);
	}

	public function beforeElementSave (ElementInterface $element, bool $isNew): bool
	{
		if (!SimpleMap::getInstance()->map->validateField($this, $element))
			return false;

		return parent::beforeElementSave($element, $isNew);
	}

	public function afterElementSave (ElementInterface $element, bool $isNew): void
	{
		SimpleMap::getInstance()->map->saveField($this, $element);

		parent::afterElementSave($element, $isNew);
	}

	public function afterPrepareElementQuery (CancelableEvent $event): void
	{
		if (!self::$searchParams) return;

		/** @var ElementQueryInterface $query */
		$query = $event->sender;

		SimpleMap::getInstance()->map->modifyElementsQuery(
			$query,
			self::$searchParams['value'],
			self::$searchParams['field'],
		);
	}

	// Helpers
	// =========================================================================

	/**
	 * Renders the map input
	 *
	 * @param Map  $value
	 * @param bool $isSettings
	 *
	 * @return string
	 * @throws InvalidConfigException
	 */
	private function _renderMap (Map $value, bool $isSettings = false): string
	{
		$view = Craft::$app->getView();

		$containerId = 'map-' . $this->id . 'container';
		$vueContainerId = $view->namespaceInputId($containerId);
		$view->registerAssetBundle(MapAsset::class);
		$view->registerJs('new Vue({ el: \'#' . $vueContainerId . '\' });');
		$view->registerTranslations('simplemap', [
			'Search for a location',
			'Clear address',
			'Full Address',
			'Name / Number',
			'Street Address',
			'Town / City',
			'Postcode',
			'County',
			'State',
			'Country',
			'Latitude',
			'Longitude',
			'Zoom In',
			'Zoom Out',
			'Center on Marker',
			'Current Location',
			'No address selected',
			'what3words',
		]);

		/** @var Settings $settings */
		$settings = SimpleMap::getInstance()->getSettings();

		$country = $this->country;
		// Convert ISO2 to ISO3 for Here autocomplete
		if ($country && $settings->geoService === GeoEnum::Here)
			$country = GeoService::$countriesIso3[$country];

		$tiles = $settings->mapTiles;
		if ($tiles === MapTiles::Wikimedia)
			$tiles = MapTiles::OpenStreetMap;

		$opts = [
			'config' => [
				'isSettings' => $isSettings,

				'name'                => $view->namespaceInputName($this->handle),
				'country'             => $country,
				'hideSearch'          => (bool) $this->hideSearch,
				'hideMap'             => (bool) $this->hideMap,
				'hideAddress'         => (bool) $this->hideAddress,
				'showLatLng'          => (bool) $this->showLatLng,
				'showCurrentLocation' => (bool) $this->showCurrentLocation,
				'minZoom'             => $isSettings ? 0 : (float) $this->minZoom,
				'maxZoom'             => $isSettings ? 18 : (float) $this->maxZoom,
				'size'                => $this->size,

				'mapTiles' => $tiles,
				'mapToken' => GeoService::getToken(
					$settings->getMapToken(),
					$tiles
				),

				'geoService' => $settings->geoService,
				'geoToken'   => GeoService::getToken(
					$settings->getGeoToken(),
					$settings->geoService
				),

				'w3wEnabled'   => $settings->isW3WEnabled(),
				'showW3WGrid'  => (bool) $this->showW3WGrid,
				'showW3WField' => (bool) $this->showW3WField,

				'locale' => Craft::$app->locale->getLanguageID(),
			],

			'value' => [
				'address'    => $value->address,
				'lat'        => self::_parseFloat($value->lat),
				'lng'        => self::_parseFloat($value->lng),
				'zoom'       => $value->zoom,
				'parts'      => $value->parts,
				'what3words' => $value->what3words,
			],

			'defaultValue' => [
				'address'    => null,
				'lat'        => self::_parseFloat($this->lat),
				'lng'        => self::_parseFloat($this->lng),
				'zoom'       => $this->zoom,
				'parts'      => null,
				'what3words' => null,
			],
		];

		// Map Services
		// ---------------------------------------------------------------------

		if (str_contains($tiles, 'google'))
		{
			if ($settings->getMapToken() !== $settings->getGeoToken())
			{
				$view->registerJsFile(
					'https://maps.googleapis.com/maps/api/js?key=' .
					$settings->getMapToken()
				);
			}
		}
		elseif (str_contains($tiles, 'mapkit'))
		{
			$view->registerJsFile(
				'https://cdn.apple-mapkit.com/mk/5.x.x/mapkit.js'
			);
		}

		// Geo Services
		// ---------------------------------------------------------------------

		if ($settings->geoService === GeoEnum::GoogleMaps)
		{
			$view->registerJsFile(
				'https://maps.googleapis.com/maps/api/js?libraries=places&key=' .
				$settings->getGeoToken()
			);
		}
		elseif ($settings->geoService === GeoEnum::AppleMapKit)
		{
			$view->registerJsFile(
				'https://cdn.apple-mapkit.com/mk/5.x.x/mapkit.js'
			);
		}

		// what3words
		// ---------------------------------------------------------------------

		if ($settings->w3wEnabled && !empty($settings->getW3WToken()))
		{
			$view->registerJsFile(
				'https://assets.what3words.com/sdk/v3/what3words.js?key=' .
				$settings->getW3WToken()
			);
		}

		// ---------------------------------------------------------------------

		$options = preg_replace(
			'/\'/',
			'&#039;',
			json_encode($opts)
		);

		if ($this->size === 'normal')
			$children = '<div style="height:360px"></div>';
		else
			$children = $value->address;

		return '<div id="' . $containerId . '"><simple-map options=\'' . $options . '\'>' . $children . '</simple-map></div>';
	}

	// Helpers
	// =========================================================================

	/**
	 * Will cast the given value to a float if not null
	 *
	 * @param null $value
	 *
	 * @return float|null
	 */
	private static function _parseFloat ($value = null): ?float
	{
		if ($value === null)
			return null;

		return (float) $value;
	}

}
