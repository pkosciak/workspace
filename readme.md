# Workspace Setup

This repository provides a Docker-based workspace that includes Traefik, Nginx/Apache, SSL certificates, MailHog, Elasticsearch, MySQL, and Redis.

## Step 1. Traefik setup

1. In `traefik` directory, run:

```sh
docker network create proxy
docker-compose up -d
```

2. Add the hostname to your hosts file:

```
127.0.0.1 traefik.test
```

3. Visit `traefik.test` in your browser.

## Step 2. MySQL setup

1. In `mysql8` and `mysql57` directories, run:

```sh
docker-compose up -d
```

2. Access MySQL via MySQL Workbench, TablePlus, HeidiSQL, etc., using the following:

- `mysql8`: Hostname `127.0.0.1`, Port `3306`
- `mysql57`: Hostname `127.0.0.1`, Port `3307`

## Step 3. Mailhog setup

1. In `mailhog` directory, run:

```sh
docker-compose up -d
```

2. Add the hostname to your hosts file:

```
127.0.0.1 mailhog.test
```

3. Visit `mailhog.test` in your browser.

## Step 4. ElasticSearch setup

1. In the `elasticsearch` directory, run:

```sh
docker-compose up -d
```

2. To connect to Elasticsearch, use the hostname `elasticsearch`.

## Step 5. Project setup

1. Clone a recipe from the `recipes` directory into the `projects` directory.
2. Rename the directory, e.g., `newproject1`.
3. Edit the `.env` file to name your project.
4. Place your application files into the `src` directory.
5. In the project directory (not `src`), run:

```sh
docker-compose up -d
```
6. Add the hostname to your hosts file:

```
127.0.0.1 newproject1.test
```

## Step 6. Setup SSL certificates:

This is one time step. MiniCA will create certificates automatically for each new project.

1. Copy `traefik/certificates/minica.pem` to your local machine
2. Run command
```sh
openssl x509 -outform der -in minica.pem -out minica.crt
```
3. Right click `minica.crt`
4. Install certificate -> Place certificate in the following store -> Trusted Root Certification Authorities
5. Restart browser

## Recreating containers

After making changes to containers, run:

```sh
docker-compose up -d --build --force-recreate
```

## Mysql data storage

For the `mysql57` container, data is stored in the `mysql57/data` directory.
For the `mysql8` container, data is stored in the `mysql8/data` directory.

To connect to the MySQL container in your project, use `mysql57` or `mysql8` as the hostname. For example, in a WordPress configuration:

```php
define('DB_NAME', 'newproject1');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_HOST', 'mysql8'); // Use the container name for MySQL 8.0
```

You can also set up each project with its own MySQL instance if specific configurations are needed. Ensure to use a unique port number (set up inside the `.env` file). If using a predefined MySQL configuration, your project’s MySQL instance container should be named `projectname-mysql`.

For the project's MySQL instance, data is stored inside the `[project catalog]/.docker/mysql/data` directory.

**Backup your data periodically to avoid data loss.**

## Executing commands in a project (e.g., composer install)

Replace `APP_NAME` with your app name:

```sh
docker exec -it APP_NAME-php bash
composer install
```

## WPCLI (WordPress recipe)

Replace `APP_NAME` with your app name:

```sh
docker exec -it APP_NAME-cli bash
wp help
```