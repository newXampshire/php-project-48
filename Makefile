install:
	composer install

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

test:
	vendor/bin/phpunit

test-with-simple-coverage:
	vendor/bin/phpunit --coverage-text

test-with-coverage:
	vendor/bin/phpunit --coverage-clover tests/reports/coverage.xml
