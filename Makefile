PHP=php
COMPOSER=composer

.PHONY: test
all: composer-dump phpstan psalm test

.PHONY: composer-dump
composer-dump:
	$(COMPOSER) dump-autoload --optimize

.PHONY: phpstan
phpstan:
	$(PHP) vendor/bin/phpstan analyse

.PHONY: psalm
psalm:
	$(PHP) vendor/bin/psalm

.PHONY: test
test: testprepare phpunit

.PHONY: testprepare
testprepare:
	rm -rf var
	tests/bin/console doctrine:schema:create
	tests/bin/console doctrine:fixtures:load --no-interaction

.PHONY: phpunit
phpunit:
	$(eval c ?=)
	$(PHP) vendor/bin/phpunit $(c)

.PHONY: php-cs-fixer
php-cs-fixer: tools/php-cs-fixer
	$(PHP) $< fix --config=.php-cs-fixer.dist.php --verbose --allow-risky=yes
	$(PHP) $< fix --config=.php-cs-fixer.code-sample.php --verbose --allow-risky=yes

tools/php-cs-fixer:
	phive install php-cs-fixer

.PHONY: doctrine
doctrine:
	rm -f var/data.db
	tests/bin/console doctrine:schema:create
	tests/bin/console doctrine:fixtures:load --no-interaction

.PHONY: serve
serve:
	tests/bin/console cache:clear
	tests/bin/console asset:install tests/public/
	symfony server:start --document-root=tests/public

.PHONY: dump
dump:
	$(PHP) tests/bin/console server:dump
