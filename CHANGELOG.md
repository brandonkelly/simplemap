## 5.0.3 - 2024-09-16
### Fixed
- Fixed location filter affecting unrelated queries (Fixes #405)

## 5.0.2 - 2024-09-10
### Fixed
- Fixes location search (Fixes #394, #393, #392)

## 5.0.1 - 2024-06-27 [CRITICAL]
### Security
- Removed Polyfill.io (https://sansec.io/research/polyfill-supply-chain-attack)

## 5.0.0 - 2024-04-04
### Fixed
- Fix referred on Mapbox geo requests (Fixes #338, via @maxdmyers)
- Fix type errors in front-end usage (Fixes #379, via @samhibberd)
- Fix error when normalizing invalid location (Fixes #368, #380, via @Decyphr)
- Fix intermittent issues w/ Google Maps API loading (Fixes #294, via @davidwebca)
- Allow nullable zoom value (Fixes #381)
- Pass site language to embedded Google map (Fixes #373)
- Support casting map to string (Fixes #362)
- Remove reference to MaxMind Lite from docs (Fixes #358)

## 5.0.0-rc1 - 2024-03-01
### Changed
- Add support for Craft 5

## 4.0.4 - 2023-06-26
### Fixed
- Update settings autosuggest input (Fixes #374)

## 4.0.3 - 2022-07-11
### Fixed
- Fixed $id embed issue (Fixes #353)
- Allow all Parts to be null as well as an empty string (Fixes #349)
- Fix issue placing marker along coastline when using Mapbox (Fixes #322)

### Changed
- Removed WikiMedia tiles, falling back to OpenStreetMap (Fixes #307)

## 4.0.2 - 2022-06-08
### Fixed
- Misc fixes via @davidwebca & @jamesedmonston

## 4.0.1 - 2022-05-18
### Fixed
- Fix Geo Location Token only allowing string (Fixes #343)

## 4.0.0 - 2022-05-10
### Fixed
- Mostly functional, only slightly buggy, Craft 4 Support

## 3.9.3 - 2022-01-17
### Fixed
- Fix Mapbox Forbidden issue (Fixes #218, via @Saboteur777)

## 3.9.2 - 2021-08-26
### Changed
- Change default map tileset to Carto Voyager

### Fixed
- Fix some geolocation services conversion failing when the given location is not a valid address (Fixes #322)
- Fix error when upgrading due to missing `type` field (via @cornernote)

## 3.9.1 - 2021-04-30
### Added
- Add Guzzle 7 support (via @dwheeldo)

## 3.9.0.2 - 2020-12-01
### Improved
- Improved GQL docs

### Fixed
- Fix GQL Coords type missing `lng`

## 3.9.0.1 - 2020-11-27
### Fixed
- Removed distance field from GQL mutation input

## 3.9.0 - 2020-11-27
> {warning} **BREAKING**: This release changes how GraphQL querying works for
the map field. You should now pass the search query as an input rather than a 
JSON string.

### Added
- Added GraphQL support for query filtering and mutations

### Improved
- Address inputs will span full width when map is disabled (Fixes #282)

## 3.8.5 - 2020-10-08
### Added
- Add `centerFallback` option to map embeds (Fixes #263)

### Changed
- Prefix name of `Map` graphql elements

### Fixed
- Reduce search radius to 0 if location is not valid (Fixes #277)
- Fix mapbox geocode error when country is not valid ISO code (Fixes #276)

### Removed
- Remove MaxMind Lite option

## 3.8.4.1 - 2020-07-03
### Fixed
- Fix url encode issue for markers in static map images

## 3.8.4 - 2020-06-12
### Changed
- Allow zoom override on Google/Mapbox embeds
- Allow style override on Mapbox embeds (Fixes #256)

### Fixed
- Cast embed center coordinates to floats
- Upgraded Mapbox to use new Static Tiles API

## 3.8.3 - 2020-04-09
### Added
- Added JSON support when filtering by a map field. Very useful for Crafts current GQL implementation (Fixes #248)

### Fixed
- Markers coodinates are now cast to floats (via [@Sekonda](https://github.com/Sekonda))
- Exclude empty map fields when sorting by distance (Fixes #245)

## 3.8.2 - 2020-03-04
### Fixed
- Fix issue where elements failed to save when searching for a location via Google or Here (Fixes #242)
- Fix what3words not updating when searching for a location

## 3.8.1 - 2020-02-27
### Added
- Add env support to settings (Closes #241)

### Improved
- Improve settings page appearance during load

## 3.8.0 - 2020-02-25
### Added
- Add What3Words support (Closes #236)

### Fixed
- Fix issue when trying to render a static map without markers (Fixes #225)

## 3.7.7 - 2020-01-17
### Fixes
- Fix error when attempts to populate missing data return null (Fixes #233)

## 3.7.6 - 2019-12-18
### Fixed
- Fix issue with getting distance when lat/lng was stored as string (Fixes #230)
- Fix issue when upgrading maps for fields that no longer exist (Fixes #227)

## 3.7.5 - 2019-12-04
### Added
- Add config option to disable missing field data population (Fixes #226)
- Add Current Location button to Map (Closes #219)

## 3.7.4 - 2019-12-03
### Improved
- 🔥 Improved location searching and distance sorting by up to 1800%! ⚡️

## 3.7.3 - 2019-11-29
### Fixed
- Fix issue with Mapbox parts lookup when address property is missing
- Fix issue with count when doing a location search

## 3.7.2 - 2019-11-19
### Changed
- Embed map width and height can now be set to `null` (Fixes #221)

### Fixed
- Fix map embed / static options not converting center string correctly
- Fix multiple leaflet maps not rendering correctly
- Fix map not being draggable on mobile (Fixes #220)
- Fix mini-map going off screen on small laptops (Fixes #222)

## 3.7.1 - 2019-10-24
### Added
- Add support for `:empty:` and `:notempty:` (Fixes #214)

### Fixed
- Fix `embed` and `imgSrcSet` not setting options correctly when outputting from a map field (Fixes #215)

## 3.7.0 - 2019-10-15
### Added
- Add docs
- Add Craft GraphQL support
- Add Pro edition
- Add static map image support
- Add new map field size options
- Add IP based user location lookup
- Add ability to redirect to a specific site based off user location
- Add `coordinate` query argument to CraftQL (Closes #205)
- Add "mini" size for a tiny field footprint (Closes #203)
- Add `address()` method to map value for easy address formatting
- Add galactic address parts

### Changed
- 🍆 New, sexier UI! 💦
- Mapbox, Apple Maps, and Here are now only available in Maps Pro
- Now requires Craft 3.2.1 or newer

### Improved
- Remove Vue from JS bundle to reduce file size
- Removed fly animation when updating map location for snappier UI (Closes #202)

### Fixed
- Fix map not showing when other Vue based plugins interfere (Fixes #196)
- Fix issue when migrating from an older version of Maps (Fixes #195)
- Fix project config migration issue (Fixes #207)
- Fix issues upgrading Maps from Craft 2 to 3 (via [@roelvanhintum](https://github.com/roelvanhintum))

## 3.6.4.3 - 2019-08-30
### Fixed
- Fix issue when trying to save map field on initial draft entry

## 3.6.4.2 - 2019-08-01
### Changed
- Don't update project config unnecessarily during migration (Closes #194)

### Fixed
- Fix migration error when upgrading from 3.3.4 or lower (Fixes #192)

## 3.6.4.1 - 2019-07-30
### Fixed
- Fix error when populating legacy parts server-side from lat/lng
- Fix error when logging invalid legacy part

## 3.6.4 - 2019-07-30
### Fixed
- Remove errant debug code causing migration to run every request (Fixes #190)
- Fix migration trying to change a column type to a table (Fixes #189, #188)

## 3.6.3 - 2019-07-25
### Added
- Add min / max zoom settings to map field (Closes #186)

### Fixed
- Fix migration from Craft 2 (Fixes #153)
- Fix issue when column already exists during migration (Fixes #187)

## 3.6.2.2 - 2019-07-24
### Fixed
- Fix migration issue when matrix / super table blocks don't have any fields (Fixes #184)

## 3.6.2.1 - 2019-07-23
### Fixed
- Fix migration issue when no matrix or SuperTable blocks exist (Fixes #182)
- Fix issue with Google trying to set legacy parts that aren’t supported (Fixes #183)
- Fix getting top-level map value part if no parts exist (Fixes #181)

## 3.6.2 - 2019-07-23
### Added
- Add `postal_code_suffix` to `PartsLegacy` (Fixes #179)

### Fixed
- Fix migration error when upgrading from 3.4.x to 3.6.x (Fixes #178)
- Fix project config content column type being string instead of text (Fixes #180)

## 3.6.1 - 2019-07-19
### Added
- Add support for getting parts without having to go via the `parts` property. 
(i.e. `myMap.parts.number` can be simply `myMap.number`). This _doesn't_ work 
for the `address` part, which is already in use and returns the full address 
as a string (alternatively, use the `streetAddress` alias). (Closes #154)
- Add `streetAddress` alias of `address` to Parts.

### Changed
- `PartsLegacy` will be used when Google is the chosen Geo service, giving 
access to additional Google specific parts (Fixes #167)

### Fixed
- Fix error when normalizing value without an element (Fixes #174)
- Fix JS error when using two different API keys for Google maps services (Fixes #165)
- Fix parts being lost when moving from new to legacy (any other geo service to google)
- Fix issue with Mapbox geo service when country was unrestricted
- Fix JS issues when using Apple or Google Maps in an element edit HUD (Fixes #175)

## 3.6.0 - 2019-07-12

> {warning} This update changes how map data is stored, moving away from an 
element type. This means if you are eager loading the a map field, you'll want 
to remove the `with` from your query and `[0]` when outputting the map (if you 
have it). We also **strongly** recommend taking a backup before updating.

> {tip} If you get a `Column not found` error when upgrading, try running `./craft migrate/all`.

### Changed
- Reformat data structure to remove map element type and need for eager loading

### Fixed
- Fix missing postcode warning (Fixes #169)
- Fix map save DB issue in Craft 3.2 (Fixes #170)
- Fix map not retrieving saved values in Craft 3.2 (Fixes #171)
- Fix DB error on duplicate import via FeedMe (Fixes #168)
- Fix maps not propagating across sites (Fixes #141)

## 3.5.2 - 2019-06-20
### Improved
- FeedMe can now import the individual map parts

## 3.5.1 - 2019-06-20
### Added
- Maps can now populate address and lat/lng data based off only a postcode

## 3.5.0 - 2019-06-13
### Added
- Added FeedMe support!

### Fixed
- Fixed results being duplicated when searching by location when an entry has 
multiple map fields within the search catchment.
- Account for missing Craft 2 API keys
- Fix HERE search not working when no country restriction was set

### Changed
- Add default zoom to map element
- Update preferred country instructions to be clearer
- Support rendering a map field without a value
- Use field handle as table alias suffix, instead of random bytes

## 3.4.11 - 2019-04-05
### Fixed
- Map records are no longer double saved when upgrading to from Craft 2 to 3

## 3.4.10 - 2019-04-04
### Fixed
- Map records are no longer double saved when upgrading to >3.4.x

## 3.4.9 - 2019-04-01
### Added
- Added option to show lat / lng fields

### Fixed
- Fixed map not validating correctly
- Fixed wrong map value being shown on element index with multiple sites
- Fixed missing table prefix in map element query
- Fixed migration issue when upgrading due to duplicate element IDs

### Improved
- Scrolling to zoom disabled on map
- Clearing the map will no longer store the default data

## 3.4.8 - 2019-03-27
### Fixed
- Fix error when migrating a field from Craft 2 when `countryRestriction` isn't set
- Location search excludes elements that have been soft-deleted
- Fixed issue restoring trashed elements that have a map field
- Map field elements are trashed and deleted correctly
- Fixed syntax issue on PHP <7.1.0
- Fixed error during repair migration when element doesn't exist

## 3.4.7 - 2019-03-25
### Fixed
- Fixed JS error when clearing field
- Fixed missing parts when using Google maps for geo-coding

### Improved
- Clear button now translatable

## 3.4.6 - 2019-03-25
### Added
- Added "Clear" button
- Always show full address field even if address block is hidden

### Fixed
- The really shitty element stuff. Is good now. I think.

## 3.4.5 - 2019-03-25
### Fixed
- Fixed maps failing to get value after save

### Changed
- Using Google Maps geo service will result in legacy parts always being used, 
meaning you can access all available address components.

## 3.4.4 - 2019-03-22
### Fixed
- Fixed some issues when upgrading from older versions of Maps. We recommend 
upgrading from 3.3.4 or lower directly to this release or later.

## 3.4.3 - 2019-03-20
### Changed
- You can now pass a map to the location query (fixes #99)

### Fixed
- Fixed issue when `cp-field-inspect` plugin is installed (fixes #127)
- Fixed `elementId cannot be null` error on saving new entries with map fields (fixes #126)

## 3.4.2 - 2019-03-20
### Fixed
- Fixed issue setting old field settings after upgrade.

## 3.4.1 - 2019-03-20
### Fixed
- Fixed an issue where the map field class broke after upgrading.

## 3.4.0 - 2019-03-20

> {warning} This is a major update, we strongly recommend taking a database backup before updating!

### Changed
- SimpleMap is now Maps! We've re-written the plugin from the ground-up while 
keeping it backwards compatible (even back to Craft 2!)
- Maps is now powered by Vue!
- New icon yo

### Added 
- OpenStreetMap Support and map tiles
- Mapbox Support and map tiles
- Apple MapKit Map Tiles
- Here Maps Support and map tiles
- Wikimedia Map Tiles
- Carto Map Tiles
- Address inputs for manually settings address parts data.

### Improved
- We've normalized the map "Parts", so you'll always know what data you have available.
- CraftQL support: you can now query and mutate Maps fields via Graph!
- Field Customization: It's now possible to hide the location search, map, and address inputs.

### Fixed
- Maps are now multi-site aware and can be translated.

### Removed
- Removed lat/lng inputs from field
- Removed restrict by type
- Removed boundary restriction

## 3.3.4 - 2018-09-05
### Fixed
- Fixed a bug where SimpleMap would not validate required fields. (via @samhibberd)

## 3.3.3 - 2018-03-13
### Fixed
- Fixed a bug where SimpleMap would cause the `ResaveElements` job to error when triggered via console.

## 3.3.2 - 2018-03-05
### Added
- Added docs for using a config file to configure the plugin.

### Fixed
- Fixed JOIN alias issue when using the Element API plugin (via @idontmessabout)

## 3.3.1 - 2018-01-30
### Fixed
- Fixed JS bug on settings page

## 3.3.0 - 2018-01-30
### Fixed
- Added a fix for those annoying `Call to a member function getMap() on null` bugs

### Improved
- Map height no longer jumps when page loads
- Vastly improved the map fields settings UI/UX
	- No more nasty text fields!
	- Map height and position is now set by resizing and moving a map
	- Auto-complete search bounds can now be drawn directly onto a map
	- Radio buttons are now drop-downs

### Changed
- Now using the plugins `afterInstall` function instead of the plugin after install event
- The "Hide Lat/Lng" option is now true by default

## 3.2.0 - 2018-01-25
### Fixed
- Fixed bug where pagination would error when querying via a map field. #70

### Improved
- Updated CraftQL support (via @markhuot)
- Removed webonyx/graphql-php dependency #71
- Improved address and lat/lng input sizing on smaller screens and in a HUD #73
- Updated Mapbox example to use latest API #74

## 3.1.3 - 2017-12-18
### Fixed
- Map fields no longer cause global sets to fail to save!

## 3.1.2 - 2017-12-18
### Fixed
- Fixed settings not translating for non-English languages
- Fixed boundary settings fields not accepting decimals

## 3.1.1 - 2017-11-30
### Fixed
- Fixed bug where maps were failing to save.

## 3.1.0 - 2017-11-30
### Added
- [CraftQL](https://github.com/markhuot/craftql) support!
- Added `craft.simpleMap.getLatLngFromAddress($addressString[, $country])`.

### Improved
- The maps `parts` now contains all available options from [here](https://developers.google.com/maps/documentation/geocoding/intro#Types) (including the `_small` variants). Any options without values are returned as empty strings.

## 3.0.4 - 2017-11-28
### Added
- Added ability to restrict location search by country

### Changed
- New icon!

## 3.0.3 - 2017-11-08
### Added
- It's now possible to save the map field with only an address! Useful for populating the field from the front-end. (Requires the Geocoding API).

### Improved
- The address and lat/lng are now validated.

## 3.0.2 - 2017-11-03
### Fixed
- Fixed a bug where location searches would error if `orderBy` was not defined

## 3.0.1 - 2017-11-03
### Fixed
- Fixed maps not rendering

## 3.0.0 - 2017-11-03
### Changed
- Initial Craft 3 Release
