all: test analyse

test:
	vendor/bin/phpunit -c tools/phpunit.xml.dist

analyse:
	vendor/bin/phpstan  analyse -c tools/phpstan.neon.dist
