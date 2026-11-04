# teste-a4pm

Laravel 13 (PHP 8.4), Vue 3 com TypeScript e MySQL 8.4, tudo rodando no Docker.

## Como rodar

Precisa de Docker e Make.

```bash
make up
```

| Serviço | Endereço |
|---|---|
| Frontend | http://localhost:5178 |
| API | http://localhost:8088/api |
| Swagger | http://localhost:8088/api/documentation |
| MySQL | `localhost:3308` |

## Comandos

| Comando | O que faz |
|---|---|
| `make up` | Sobe o ambiente (instala dependências, roda migrations e seeders) |
| `make down` | Derruba o ambiente (os dados do MySQL ficam salvos) |
| `make test` | Roda os testes do back e do front |
| `make test-back` | Roda os testes do Laravel |
| `make test-front` | Roda os testes do Vue |
| `make pint` | Formata o código do back com o Laravel Pint |
| `make docs` | Gera a documentação do Swagger |
| `make help` | Lista os comandos |

## API

Todas as rotas, menos cadastro e login, precisam do token no header `Authorization: Bearer <token>`. O token vale 1 dia.

| Método | Rota | O que faz |
|---|---|---|
| POST | `/api/usuarios` | Cadastra um usuário |
| POST | `/api/login` | Faz login e retorna o token |
| POST | `/api/logout` | Faz logoff e invalida o token |
| GET | `/api/receitas?nome=&id_categorias=` | Pesquisa as receitas do usuário |
| POST | `/api/receitas` | Cadastra uma receita |
| GET | `/api/receitas/{id}` | Mostra uma receita (usada na impressão) |
| PUT | `/api/receitas/{id}` | Edita uma receita |
| DELETE | `/api/receitas/{id}` | Exclui uma receita |
| GET | `/api/categorias` | Lista as categorias |
| GET | `/api/categorias/{id}` | Mostra uma categoria |

Cada usuário só vê e altera as próprias receitas. As categorias são fixas e vêm da seeder.

## Banco

- Desenvolvimento: `teste_a4pm`
- Testes: `a4pm_test`, criado automaticamente na primeira vez que os testes rodam.

As senhas do MySQL ficam no `.env` da raiz e a do Laravel em `backend/.env`.

## Documentação da API

Feita com [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger). Os endpoints são documentados com atributos `#[OA\...]` (`use OpenApi\Spec as OA;`) nos controllers e a documentação é gerada a cada acesso ao `/api/documentation`.
