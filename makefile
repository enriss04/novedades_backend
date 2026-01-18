build:
	docker compose build --no-cache

up:
	docker compose up -d

cache:
	docker exec novedades php artisan config:cache
	docker exec novedades php artisan route:cache
	docker exec novedades php artisan view:cache

link:
	docker exec novedades php artisan storage:link

migrate:
	docker exec novedades php artisan migrate

deploy: build up cache