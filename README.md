# AnimeZING Laravel + React setup

AnimeZING is split into two applications:

```text
AnimeZING/
├── backend/   Laravel 13 REST API
└── frontend/  React + Vite + Tailwind UI
```

The frontend no longer calls AniList, Kitsu, Jikan, or any other external
anime API. Anime records are stored in MySQL and are managed through the
Laravel CRUD API.

## Requirements

- PHP 8.3 or newer
- Composer
- Node.js 18 or newer and npm
- MySQL 8 or MariaDB (the MySQL server included with XAMPP is sufficient)

phpMyAdmin is not required. It is only a browser interface for MySQL; the
database can be created with the MySQL command-line client.

## 1. Create the MySQL database

Start **MySQL** in the XAMPP Control Panel. Open a terminal and run:

```powershell
mysql -u root -p
```

Enter the password for the MySQL `root` user, then run:

```sql
CREATE DATABASE animezing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

If the root account has no password, use `mysql -u root` instead.

## 2. Configure Laravel

Open `backend/.env` and set these values to match the local MySQL server:

```dotenv
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=animezing
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
FRONTEND_URL=http://localhost:5173
```

Do not commit `.env`; it contains local credentials. Install dependencies and
run the database migrations:

```powershell
cd C:\Laravel12\AnimeZINGG\backend
composer install
php artisan optimize:clear
php artisan migrate
```

The migration creates the `animes` table with title, JSON genres, a score
between 1.00 and 10.00, image URL, synopsis, and timestamps.

## 3. Start the Laravel API

Keep this terminal running:

```powershell
cd C:\Laravel12\AnimeZINGG\backend
php artisan serve --host=127.0.0.1 --port=8000
```

The API is available at `http://localhost:8000/api`.

## 4. Configure and start React

Optional: create `frontend/.env` if Laravel runs at a different URL:

```dotenv
VITE_API_URL=http://localhost:8000/api
```

Then start the frontend in a second terminal:

```powershell
cd C:\Laravel12\AnimeZINGG\frontend
npm install
npm run dev
```

Open the Vite URL, normally `http://localhost:5173`.

## 5. CRUD behavior

- **Create:** click **+ Add** in the header, complete the form, and submit.
- **Read:** Home and Browse load records from `GET /api/animes`; detail pages
  load `GET /api/animes/{id}`.
- **Update:** open a detail page and click **Edit**.
- **Delete:** open a detail page and click **Delete**, then confirm.
- **Search/filter:** Browse sends `q`, `genre`, `page`, and `limit` query
  parameters to Laravel.

Available endpoints:

| Method | Endpoint | Purpose |
| --- | --- | --- |
| GET | `/api/animes` | Paginated list, search, and genre filter |
| GET | `/api/animes/{id}` | One anime |
| POST | `/api/animes` | Create an anime |
| PUT/PATCH | `/api/animes/{id}` | Update an anime |
| DELETE | `/api/animes/{id}` | Delete an anime |
| GET | `/api/genres` | Genre options |

The API validates all submitted fields. `score` must be numeric and between
`1` and `10`, `image_url` must be an HTTP/HTTPS URL, and at least one genre is
required.

## Troubleshooting

- **MySQL connection refused:** ensure MySQL is running and verify the port
  in `.env` (XAMPP commonly uses `3306`, but it may be configured differently).
- **Unknown database:** run the `CREATE DATABASE animezing` command again.
- **Frontend CORS error:** confirm `FRONTEND_URL` matches the browser's Vite
  origin, then run `php artisan optimize:clear`.
- **Old API data still appears:** stop and restart Vite after changing
  frontend source files; this project intentionally has no external API
  fallback.
