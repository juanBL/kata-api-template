.PHONY: tests/unit tests/application build

up: deps
	docker compose up -d

test: test/unit test/application
	docker compose -f docker-compose.test.yml down

test/coverage: .test/build deps
	docker compose -f docker-compose.test.yml run skeleton-php-symfony-fpm-test bin/phpunit --coverage-text --coverage-clover=coverage.xml --order-by=random

test/unit: .test/build
	docker compose -f docker-compose.test.yml run skeleton-php-symfony-fpm-test bin/phpunit --coverage-text --order-by=random --testsuite Unit --exclude=src/Shared

test/application: .test/build
	docker compose -f docker-compose.test.yml run skeleton-php-symfony-fpm-test bin/phpunit --coverage-text --order-by=random --testsuite Application

deps: build
	docker compose run --rm skeleton-php-symfony-fpm sh -c "\
			composer install --prefer-dist --no-progress --no-scripts --no-interaction --optimize-autoloader 	&& \
			composer dump-autoload --classmap-authoritative 													;"
bash:
	docker compose run --rm skeleton-php-symfony-fpm sh

build:
	docker compose build

down:
	docker compose -f docker-compose.yml -f docker-compose.test.yml down

.test/build:
	docker compose -f docker-compose.test.yml build

install:
	docker-compose run --rm skeleton-php-symfony-fpm composer require $(dependency)

coverage-html:
	docker compose -f docker-compose.test.yml run skeleton-php-symfony-fpm-test bin/phpunit --order-by=random

migration-diff:
	docker compose -f docker-compose.yml run skeleton-php-symfony-fpm php bin/console  doctrine:migrations:diff

migration-migrate:
	docker compose -f docker-compose.yml run skeleton-php-symfony-fpm php bin/console  doctrine:migrations:migrate -n

static-analysis:
	docker compose -f docker-compose.yml run skeleton-php-symfony-fpm ./vendor/bin/phpstan analyse -c phpstan.neon

install-assets:
	docker compose -f docker-compose.yml run skeleton-php-symfony-fpm php bin/console assets:install -q

api-doc-json:
	docker compose -f docker-compose.yml run skeleton-php-symfony-fpm php bin/console nelmio:apidoc:dump --format=json --no-pretty > public-api.json
