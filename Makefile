-include .env

.PHONY: help up down test test-back test-front pint docs

help:
	@echo "make up          sobe o ambiente"
	@echo "make down        derruba o ambiente"
	@echo "make test        roda os testes do back e do front"
	@echo "make test-back   roda os testes do back"
	@echo "make test-front  roda os testes do front"
	@echo "make pint        formata o codigo do back"
	@echo "make docs        gera a doc do swagger"

up:
	[ -f .env ] || cp .env.example .env
	[ -f backend/.env ] || cp backend/.env.example backend/.env
	docker compose build
	docker compose run --rm back composer install
	docker compose run --rm back php artisan key:generate
	docker compose run --rm back php artisan migrate --seed
	docker compose run --rm front npm install
	docker compose up -d

down:
	docker compose down

test: test-back test-front

test-back:
	docker compose exec -e DB_USERNAME=root -e DB_PASSWORD=$(MYSQL_ROOT_PASSWORD) back php artisan test

test-front:
	docker compose exec front npx vitest run

pint:
	docker compose exec back ./vendor/bin/pint

docs:
	docker compose exec back php artisan l5-swagger:generate
