# WordPress Playground

A local WordPress site for developing the Consultia theme and the Consultia
Ticker plugin. It runs under Docker Compose as two containers: `wp_app`
(WordPress, built from the local `Dockerfile`) and `wp_db` (MariaDB).

## Run

```bash
docker compose up -d
```

Then open <http://localhost:8080>.

| Command | Effect |
| --- | --- |
| `docker compose up -d` | Start the site, or apply `docker-compose.yml` changes |
| `docker compose down` | Stop and remove the containers (the database volume is kept) |
| `docker compose logs -f wordpress` | Follow the app container logs |

## What is mounted

Only `./wp-content` is bind-mounted, into `/var/www/html/wp-content`:

```yaml
volumes:
  - ./wp-content:/var/www/html/wp-content
```

Keeping the share small is what makes page loads fast on macOS. Everything else
under `/var/www/html`, including `wp-config.php` and `.htaccess`, lives in a
container volume. **The `wp-config.php` and `.htaccess` at this repo root are
therefore not the files WordPress loads.** Configuration the running site must
see belongs in the `environment:` block of `docker-compose.yml`; for example,
`WP_ENVIRONMENT_TYPE` is set there through `WORDPRESS_CONFIG_EXTRA`.

## Database

| | |
| --- | --- |
| Database | `wordpress_playground` |
| User / password | `root` / `password` |
| Host, from the app container | `db:3306` |
| Host, from macOS | `127.0.0.1:3306` |
| Data volume | `db_data` |

Read-only inspection:

```bash
docker exec wp_db mariadb -uroot -ppassword wordpress_playground -e "<SQL>"
```
