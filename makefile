build: docker compose build --no-cache

up: docker compose up -d

cache:
	docker exec novedades php artisan config:cache
	docker exec novedades php artisan route:cache
	docker exec novedades php artisan view:cache

link: docker exec -u root novedades php artisan storage:link

migrate: docker exec novedades php artisan migrate --force

deploy: build up cache

refresh: docker exec novedades php artisan migrate:refresh --force

# docker exec -u root novedades chown -R www-data:www-data /var/www/html/storage /var/www/html/public