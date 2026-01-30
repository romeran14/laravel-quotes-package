docker-compose exec app ./package/vendor/bin/pest -c package/phpunit.xml


docker-compose exec app php artisan vendor:publish --tag=quotes-assets --force

