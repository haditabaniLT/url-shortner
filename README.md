# Simple URL Shortener with HAProxy

A lightweight PHP & MySQL-based URL shortener, fronted by HAProxy for reverse proxying and easy load balancing. This setup uses Docker Compose to provide:
1. **HAProxy** for managing incoming requests (on port 80).
2. **Apache/PHP** container for the web application.
3. **MariaDB** container for the database.

---

## Features
- Shorten long URLs into easy-to-share links.
- Track the number of clicks for each shortened URL.
- Simple admin panel to view, delete, and track click counts.
- HAProxy as a reverse proxy to the web container.

---

## Prerequisites
- [Docker](https://www.docker.com/) installed
- [Docker Compose](https://docs.docker.com/compose/) installed

---
Your folder might look like this:
url-shortener/
├─ app/
│  ├─ .htaccess
│  ├─ index.php
│  ├─ admin.php
│  ├─ redirect.php
│  ├─ healthcheck.php (optional)
│  └─ ...
├─ haproxy.cfg
├─ init.sql
├─ Dockerfile
├─ docker-compose.yml
├─ .env
└─ README.md

## Getting Started

### 1. Project Structure

Your folder might look like this:

## Getting Started

1. **Clone or download** this repository:
   ```bash
   git clone https://github.com/your-username/url-shortener.git
   cd url-shortener

2. **Set up environment variables in the .env file** (if needed):
    DB_HOST=db
    DB_USER=url_user
    DB_PASSWORD=userpass
    DB_NAME=url

3. **Build and start the containers:**
docker compose up --build

4. **Access the application in your browser**:
    Go to http://localhost to use the URL shortener form.
    Go to http://localhost/admin.php to manage shortened URLs.
