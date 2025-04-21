# Workspace Setup

This repository provides a Docker-based workspace that includes Traefik, Nginx/Apache, SSL certificates, MailHog, Elasticsearch, MySQL, and Redis.

## Requirements

To ensure everything works flawlessly, follow these steps:
- For Windows users: Replace all occurrences of `.test` with `.localhost` in the instructions and files.
- For macOS users: Keep `.test` but also [create persistent loopback interface](https://github.com/pkosciak/local-dev?tab=readme-ov-file#1-create-persistent-loopback-interface-in-macos) and [install and configure dnsmasq](https://github.com/pkosciak/local-dev?tab=readme-ov-file#2-install-and-configure-dnsmasq)

## Step 1. Traefik setup

1. In `traefik` directory, run:

```sh
docker network create proxy
docker-compose up -d
```

2. Visit `traefik.test` in your browser.

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

2. Visit `mailhog.test` in your browser.

## Step 4. ElasticSearch/Redis/Memcached setup

1. In the `elasticsearch`,`redis`,`memcached` directory, run:

```sh
docker-compose up -d
```

2. To connect to service, use container name as a hostname.

## Step 5. Project setup

1. Clone a recipe from the `recipes` directory into the `projects` directory.
2. Rename the directory, e.g., `newproject1`.
3. Edit the `.env` file to name your project, preferably using the same name as the directory.
4. Place your application files into the `src` directory.
5. In the project directory, run:

```sh
docker-compose up -d
```

6. Copy `cert.pem` from `traefik/certificates/newproject1` to `projects/newproject1/.docker/php/certs`
7. Uncomment from `projects/newproject1/Dockerfile`

```
COPY ./.docker/php/certs/cert.pem /usr/local/share/ca-certificates/minica-ca.crt
RUN apk add --no-cache ca-certificates \
 && cp /usr/local/share/ca-certificates/minica-ca.crt /etc/ssl/certs/ \
 && update-ca-certificates
```

8. Run

```sh
docker-compose up -d --build --force-recreate
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

1. Enter bash inside container
```sh
docker exec -it APP_NAME-php bash
```
2. Execute command
```sh
composer install
```
3. Type `exit` to leave

## Recipes

These are out-of-the-box configurations for your projects. All of them come with Composer installed. The WordPress recipe is a bit more advanced, as I primarily work with WordPress on a daily basis.

## WordPress recipe

Additionally includes:
- WP-CLI
- Wordfence-CLI
  - ou can run a vulnerability scan with the following command:
  - `wordfence vuln-scan .`

## Setting up Xdebug in PHPStorm

Follow these steps for each of your projects:

1. File -> Settings -> PHP -> Debug

- Xdebug port: 9003
- Tick 'Can accept external connections'

2. File -> Settings -> PHP -> Servers

- Name: localhost
- Hostname localhost
- Tick 'Use path mapping'
- Set '/var/www/html' next to your projects `src` directory
- Add breakpoints in your code

3. Run -> Start listening for PHP Debug Connections

In chrome:
- Install the Xdebug helper extension
- Go to your project's URL
- Enable the extension
- Reload the page

Note: If you switch projects, remember to turn off listening for connections in the project you are no longer working on. Otherwise, the debugger might open in the wrong window.