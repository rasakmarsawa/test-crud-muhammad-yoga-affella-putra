# CRUD Test (Karyawan / Permit)

Simple PHP 5.6 (legacy `mysql_*`) CRUD application styled with AdminLTE v4. It manages **Division (Divisi)**, **Karyawan**, **Permit**, **Karyawan Permit** (quota per year), and **Permit Usage** (with quota deduction and false-entry rollback). DataTables is used on list views.

## Stack
- PHP 5.6 + Apache (`php:5.6-apache`), `ext/mysql`
- MySQL 5.7 (`utf8`)
- Docker Compose
- AdminLTE v4.10.1 (bundled under `frontend_template/`)

## Project structure
- `connection.php` — DB connection (`mysql_connect`), env-driven, UTF-8, `Asia/Jakarta`.
- `controller/` — CRUD/logic (`karyawan`, `divisi`, `permit`, `karyawan_permit`, `permit_usage`).
- `view/` — pages (AdminLTE). `view/sidebar.php` is the shared nav. Entry redirect: `index.php` -> `view/home.php`.
- `utils/validation.php` — shared validation helpers.
- `schema.sql` — schema-only export (no data).
- `seed_master.sql` — optional seed for master tables (`division`, `permit`, `karyawan`).
- `docker-compose.yml` / `Dockerfile` — containers.

## Prerequisites
- Docker + Docker Compose

## Install & run
1. Start the containers:
   ```bash
   docker compose up -d --build
   ```
   - Web: http://localhost:8080 (redirects to `view/home.php`)
   - MySQL (host): `localhost:3307` (user `app`, password `app`, db `test_crud`)

2. **Manually import the schema** into the Docker MySQL instance (required; there is no auto-migration):
   ```bash
   docker compose exec -T db mysql -uapp -papp test_crud < schema.sql
   ```

3. (Optional) Seed master data (`division`, `permit`, `karyawan`):
   ```bash
   docker compose exec -T db mysql -uapp -papp test_crud < seed_master.sql
   ```

## Configuration / connection
No connection changes are needed when using the Docker MySQL instance. `connection.php` defaults to the compose service and can be overridden via environment variables (set in `docker-compose.yml` for the `web` service):
- `DB_HOST` (default `db`), `DB_PORT` (`3306`), `DB_USER` (`app`), `DB_PASSWORD` (`app`), `DB_NAME` (`test_crud`)

## Features
- CRUD for karyawan, division, permit, and karyawan-permit quotas (per year).
- Use quota (`karyawan_permit_pakai.php`): validates dates/inclusive day count, checks remaining quota, records `permit_usage`, and decrements `karyawan_permit.days_amount` (transactional).
- Permit usage list/detail with **rollback** for non-false entries (confirmation): sets `is_false_entry=1` and restores quota (transactional). Detail page per karyawan-permit shows its usage history.
- DataTables on list views; permit usage has a custom date-range filter (both `start_date` and `end_date` must fall within the range).

## Notes
- Targets PHP 5.6/legacy `mysql_*`; run via the provided Docker setup.
- `seed_master.sql` upserts divisions/karyawan by unique keys; `permit.permit_name` has no unique constraint, so re-running may duplicate permit rows. Seeded passwords are plain text.
