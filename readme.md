## Step 1. Traefik setup

- In traefik directory run commands:

```
docker network create proxy
docker-compose up -d
```

- Add hostname to C:\Windows\System32\drivers\etc\hosts file
```
127.0.0.1 traefik.test
```

- Visit:
```
traefik.test
```

## Step 2. MySQL setup

- In mysql8 and mysql57 directory run command:

```
docker-compose up -d
```

#### Accessing mysql via MySQL Workbench,TablePlus, HeidiSQL etc.

Use 127.0.0.1 as a hostname and port number

- mysql8 uses 3306
- mysql57 uses 3307

## Step 3. Mailhog setup

- In mailhog directory run command:

```
docker-compose up -d
```

- Add hostname to C:\Windows\System32\drivers\etc\hosts file
```
127.0.0.1 mailhog.test
```

- Visit:

```
mailhog.test
```

## Step 4. ElasticSearch setup

- In elasticsearch directory run command:

```
docker-compose up -d
```

- To connect to elastic search use hostname: elasticsearch

## Step 5. Project setup

- Clone recipe from recipes directory into projects directory

- Change directory name e.g. newproject1

- Edit .env file to name your project (directory name will work best)

- Put your application files into src directory

- In project directory (not src) run command:

```
docker-compose up -d
```

#### Creating ssl certificate:

In order to create certificates you need to install [mkcert](https://github.com/FiloSottile/mkcert) on your machine, you can install it via [chocolatey](https://chocolatey.org/install).

- Run on local machine:

```
choco install mkcert
mkcert -install
mkcert -cert-file certs/local-cert.pem -key-file certs/local-key.pem "newproject1.test" "*.newproject1.test"
```

- Copy .pem files to directory traefik/certs

- Recreate traefik container (instruction below)

- Add hostname to C:\Windows\System32\drivers\etc\hosts file
```
- 127.0.0.1 newproject1.test
```

## Recreating container

In case you need to make changes in containers, you can run this command afterward:

```
docker-compose up -d --build --force-recreate
```

## Setting up a new project

- Repeat step 5, including these changes:
- Generate new .pem files including your new project name and clone them into traefik/certs,
command will now look like this:
```
mkcert -cert-file local-cert.pem -key-file local-key.pem^
 "newproject1.test" "*.newproject1.test"^
 "newproject2.test" "*.newproject2.test"
```
- Recreate traefik container
- Add hostname to C:\Windows\System32\drivers\etc\hosts file
```
127.0.0.1 newproject2.test
```

## Mysql data storage

Mysql data is stored in [project catalog]/.docker/mysql/data. It can be lost if your WSL instance breaks, just in case back up data once in a while.

To connect to mysql container in your project use mysql57 or mysql8 as a hostname

e.g. wordpress configuration:
```
define('DB_NAME', 'newproject1');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_HOST', 'mysql8'); // mysql8 is container name for mysql 8.0
```

You can also set up each project to use its own mysql instance if you need specific configuration for it, just uncomment container setup inside docker-compose file, remember to apply unique port number (you can set it up inside .env file).
If you uncomment predefined configuration for mysql, your project mysql instance container name should be named projectname-mysql. Use this name instead mysql8 to connect.

## Executing commands in project (e.g. composer install)

Replace APP_NAME with your app name

```
docker exec -it APP_NAME-php bash
```
```
composer install
```

## WPCLI (wordpress recipe)

Replace APP_NAME with your app name

```
docker exec -it APP_NAME-cli bash
```
```
wp help
```