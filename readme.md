# Workspace Setup

This repository provides a Docker-based workspace that includes Traefik, Nginx/Apache, MySQL, automatic SSL certificates and MailHog.

## Requirements

To ensure everything works flawlessly, follow these steps:
- For Windows users: Replace all occurrences of `.test` with `.localhost` in the instructions and files.
- For macOS users: Keep `.test` but also [create persistent loopback interface](https://github.com/pkosciak/local-dev?tab=readme-ov-file#1-create-persistent-loopback-interface-in-macos) and [install and configure dnsmasq](https://github.com/pkosciak/local-dev?tab=readme-ov-file#2-install-and-configure-dnsmasq)

## Step 1. Traefik setup

In `traefik` directory, run:

```sh
docker network create proxy
docker-compose up -d
```

## Step 2. MySQL setup

1. In `mysql8` and `mysql57` directories, run:

```sh
docker-compose up -d
```

2. You can access MySQL via MySQL Workbench, TablePlus, HeidiSQL, etc., using the following:

- `mysql8`: Hostname `127.0.0.1`, Port `3306`, Username: `root`, Password: `root`
- `mysql57`: Hostname `127.0.0.1`, Port `3307`, Username: `root`, Password: `root`

## Step 3. Mailhog setup

1. In `mailhog` directory, run:

```sh
docker-compose up -d
```

2. Visit `mailhog.test` in your browser to browse mails

## Step 4. Project setup

1. Clone a template from the `template` directory into the `projects` directory.
2. Rename the directory, e.g., `newproject1`.
3. Edit the `.env` file to name your project, preferably using the same name as the directory.
4. Optionally: setup project specific mysql container (commented out in docker-compose file) or switch nginx with apache
5. Place your application files into the `src` directory.
6. Copy `minica.pem` from `traefik/certificates` to `projects/newproject1/.docker/php/certs`
7. Run

```sh
docker-compose up -d
```

## Step 5. Setup SSL certificates:

This is one time step - you don't have to do it every time. MiniCA will create certificates automatically for each new project.

1. Copy `traefik/certificates/minica.pem` to your local machine
2. Run command
```sh
openssl x509 -outform der -in minica.pem -out minica.crt
```
3. Right click `minica.crt`
4. Install certificate -> Place certificate in the following store -> Trusted Root Certification Authorities
5. Restart browser

---

### Mysql data storage

For the `mysql57` container, data is stored in the `mysql57/data` directory.
For the `mysql8` container, data is stored in the `mysql8/data` directory.

To connect to the MySQL container in your project, use `mysql57` or `mysql8` as the hostname. For example, in a WordPress configuration:

```php
define('DB_NAME', 'newproject1');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_HOST', 'mysql8'); // Use the container name for MySQL 8.0
```

You can also set up each project with its own MySQL instance if specific configurations are needed. Ensure to use a unique port number (set up inside the `.env` file). If using a predefined MySQL configuration, your project’s MySQL instance container should be named `APP_NAME-mysql` - where APP_NAME is taken from the `.env` file.

For the project's MySQL instance, data is stored inside the `[project catalog]/.docker/mysql/data` directory.

### Executing commands in a project (e.g., composer install)

Replace `APP_NAME` with your app name:

1. Enter bash inside container
```sh
docker exec -it APP_NAME-php bash
```
2. Execute command e.g.
```sh
composer install
```
3. Type `exit` to leave

### Template

Include:
- Composer
- WP-CLI
- Wordfence-CLI
  - you can run a vulnerability scan with the following command:
  - `wordfence vuln-scan .`
- Makefile with a few usefull scripts
  - `make admin` - will create "admin" account with password "admin". To see list of all commands check Makefile