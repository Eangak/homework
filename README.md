# Docker Compose Homework — PHP + MySQL

This project demonstrates the assignment requirements:
1. `app` serves HTTP using PHP and Apache.
2. `db` is the MySQL backing service.
3. `homework_db_data` is a named volume for MySQL data.
4. `homework_compose_network` is a custom bridge network. The app reaches MySQL by the service hostname `db`.
5. The visit counter lets you verify data persistence across `docker compose down` and `docker compose up`.

## Requirements
- Docker Desktop running on Windows
- PowerShell
- These four files in one folder: `docker-compose.yml`, `Dockerfile`, `index.php`, and `README.md`

## Start the application
In PowerShell, change to this folder, then run:

```powershell
docker compose config
docker compose up -d --build
docker compose ps
```

Wait until `homework_db` is healthy and `homework_app` is running. Open:

http://localhost:8081

Refresh the page several times. The visit counter should increase.

## Prove persistence
1. Note the visit count on the page.
2. Run `docker compose down` (do NOT add `-v`).
3. Run `docker compose up -d`.
4. Refresh http://localhost:8081. The count should continue increasing from the previous value.

The `down` command removes the containers and Compose network, but leaves the named volume intact by default.

## Useful verification commands

```powershell
docker compose ps
docker compose logs --tail=50 db
docker compose logs --tail=50 app
docker volume ls
docker network inspect homework_compose_network
docker compose down
docker compose up -d
```

## Important note about an old/corrupt database volume
This project uses the new named volume `homework_db_data`, which is separate from the earlier `db_data` volume used in the previous attempt. It avoids reusing that potentially damaged database directory. Do not run `docker compose down -v` if you want to preserve this project's database records.

If port 8081 is already occupied, change `"8081:80"` to another unused host port, such as `"8082:80"`, then open the matching localhost URL.
