# CHANGELOG

## 0.5.1

* fix(`AbstractState`): Make `$container` protected, so it can be extended by
  child classes
* build: Create `ApiMapperInterface` and use it for the service ID.
* refactor: Move `mapCollection()` to separate interface.
* refactor(`ApiCollectionMapper`): `mapCollection()` now accepts null target.

## 0.5.0

* build: fix deps
