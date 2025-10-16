API quickstart

Prereqs (inside backend container):
- Create DB schema:
  bin/console doctrine:database:create --if-not-exists
  bin/console doctrine:schema:update --force
- Load fixtures:
  bin/console doctrine:fixtures:load -n

Endpoints (JSON):
- GET /customers
- GET /customers?search=<term>
- POST /customers/{id}/favorite
- DELETE /customers/{id}/favorite

Notes:
- Response shape per customer: { id, name, email, company, favorite }
- Uses MySQL via DATABASE_URL; adjust if needed in docker-compose.

