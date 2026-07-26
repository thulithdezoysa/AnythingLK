Docker preview — quick start

This project includes a Docker Compose configuration to run a local preview of the AnythingLK e‑commerce app.

Prerequisites
- Docker and docker-compose installed (Docker Desktop on Windows/macOS or Docker Engine + docker-compose on Linux).

Quick start
1) (Optional) Copy the example env and edit values if you want to change passwords or ports:
   cp .env.example .env

2) Build and start the stack:
   docker-compose up -d --build

3) Wait a minute for MySQL to initialize. The first time the db container starts it will import anythinglk_db.sql into a database named `ecommerce_db`.

4) Edit config.ini in the repo to point to the database and app URL (if you didn't already):
   servername = "db"
   username   = "root"
   password   = "rootpassword"
   dbname     = "ecommerce_db"
   app_url    = "http://localhost:8080"

5) Open the site in your browser:
   - Frontend: http://localhost:8080
   - Admin:    http://localhost:8080/admin
   - phpMyAdmin: http://localhost:8081  (user: root)

Notes & caveats
- The MySQL init mechanism will only import the SQL on first run when the DB volume is empty. If you need to re-import, remove the `db_data` volume and restart (this will delete DB data):
    docker-compose down -v
    docker-compose up -d

- The PHP built-in server is NOT used; this preview uses Apache in the PHP image so .htaccess and rewrite rules will work.

Security
- The default credentials in .env.example are intentionally simple for local previews. Do NOT use these in production, and do not expose the stack to the public internet.

If you want, I can also open a pull request that adds these files instead of committing directly, or adjust the configuration (different ports, omit phpMyAdmin, or add a healthcheck).