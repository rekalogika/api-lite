# CHANGELOG

## 0.8.3

* deps: bump rekapager

## 0.8.2

* deps: bump rekapager

## 0.8.1

* chore: static analysis
* deps: bump rekapager

## 0.8.0

* deps: support api-platform 4

## 0.7.7

* deps: bump rekapager

## 0.7.6

* test: add test for PHP 8.4

## 0.7.5

* deps: bump rekapager

## 0.7.3

* build: exclude buggy `symfony/property-info`,
  https://github.com/symfony/symfony/issues/57634
* fix: remove `api_lite_rekapager` check in `RekapagerPageablePaginatorApplier`

## 0.7.2

* deps: require rekapager 0.11
* feat: add `RekapagerPageablePaginatorApplier`
* build: add github-actions to dependabot

## 0.7.0

* fix: temporary workaround for DELETE error: "Controller
  "ApiPlatform\Action\PlaceholderAction::__invoke" requires the "$data" argument
  that could not be resolved. Either the argument is nullable and no null value
  has been provided, no default value has been provided or there is a
  non-optional argument after this one."
* build: add dump makefile target
* deps: update rekapager to 0.10

## 0.6.1

* feat: Initial Rekapager support

## 0.6.0

* refactor: Change return type from `PaginatorInterface` to `iterable` to
  anticipate future changes.
* refactor: `PaginatorApplierInterface::applyPaginator()` now takes raw
  operation and context.

## 0.5.3

* test: Add manual filtering example.

## 0.5.2

* fix(`ApiMapper`): Missing `kernel.reset` tag.

## 0.5.1

* fix(`AbstractState`): Make `$container` protected, so it can be extended by
  child classes
* build: Create `ApiMapperInterface` and use it for the service ID.
* refactor: Move `mapCollection()` to separate interface.
* refactor(`ApiCollectionMapper`): `mapCollection()` now accepts null target.

## 0.5.0

* build: fix deps
