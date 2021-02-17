start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate --seed
	npm install

watch:
	npm run watch

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail storage/logs/laravel.log

test:
	php artisan test

deploy:
	git push heroku main

lint:
	composer run-script phpcs
	composer run-script phpstan-src

lint-fix:
	composer phpcbf
