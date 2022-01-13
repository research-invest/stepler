init: up
up:
	docker-compose up -d
goapp:
	docker-compose exec app-php bash
gomysql:
	docker-compose exec app-mysql bash
recreate-php:
	docker-compose up -d --force-recreate --no-deps --build app-php
recreate-mysql:
	docker-compose up -d --force-recreate --no-deps --build app-mysql