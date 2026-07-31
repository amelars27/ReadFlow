# 📚 ReadFlow

ReadFlow is a web-based reading tracker application built with Laravel 13 and Docker. It helps users organize books and reading materials, track reading progress, take notes, set reading goals, and manage bookmarks in one place.

---

## Features

- 📖 Reading Materials Management
- 👤 Author Management
- 🏷️ Category Management
- ⏱️ Reading Sessions (Focus Reading with timer)
- 📝 Reading Notes
- 🎯 Reading Goals
- 🔖 Bookmarks
- ⭐ Rating & Progress Tracking
- 📊 Dashboard Analytics

---

## Tech Stack

- Laravel 13
- PHP 8.3
- MySQL 8
- Docker
- Nginx
- Blade
- Vite

---

## Project Structure

```
PROJECT_AM3
│
├── docker
├── src
│   ├── app
│   ├── database
│   ├── resources
│   ├── routes
│   └── ...
├── Dockerfile
└── compose.yaml
```

---

## Installation

Clone this repository

```bash
git clone https://github.com/amelars27/ReadFlow.git
```

Go to project

```bash
cd ReadFlow
```

Run Docker

```bash
docker compose up -d
```

Enter Laravel container

```bash
docker compose exec app bash
```

Run migration

```bash
php artisan migrate
```

Open browser

```
http://localhost:8080
```

---

## Current Status

✅ CRUD Reading Materials

✅ CRUD Categories

✅ CRUD Authors

✅ Reading Sessions

✅ Reading Notes

✅ Reading Goals

✅ Bookmarks

✅ Dashboard & Progress Tracking

---

## Author

Developed by **Almelia Restika**