all: analyse test infect

test:
	vendor/bin/phpunit -c tools/phpunit.xml.dist

analyse:
	vendor/bin/phpstan analyse -c tools/phpstan.neon.dist

infect:
	vendor/bin/infection run -c tools/infection.json.dist
