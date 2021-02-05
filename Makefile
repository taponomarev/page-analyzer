start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	php artisan key:gen --ansi
	touch database/database.sqlite
	npm install

watch:
	npm run watch

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

deploy:
	git push heroku master

lint:
	composer phpcs

lint-fix:
	composer phpcbf
