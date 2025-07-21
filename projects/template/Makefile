# Makefile at project root

APP_NAME := $(shell grep '^APP_NAME=' .env | cut -d '=' -f2 | tr -d '"')
CONTAINER := $(APP_NAME)-php

ADMIN_USER ?= admin
ADMIN_PASS ?= admin
ADMIN_EMAIL ?= admin@example.com

# Create admin account with password admin
adminacc:
	@echo "Ensuring admin user '$(ADMIN_USER)' exists in container '$(CONTAINER)'..."
	@docker exec $(CONTAINER) bash -c '\
		if wp user get $(ADMIN_USER) --field=ID --skip-themes --skip-plugins > /dev/null 2>&1; then \
			echo "Updating existing user..."; \
			wp user update $(ADMIN_USER) --user_pass="$(ADMIN_PASS)" --user_email="$(ADMIN_EMAIL)" --role=administrator --skip-themes --skip-plugins; \
		else \
			echo "Creating new admin user..."; \
			wp user create $(ADMIN_USER) $(ADMIN_EMAIL) --user_pass="$(ADMIN_PASS)" --role=administrator --skip-themes --skip-plugins; \
		fi \
	'
	@echo "Admin user '$(ADMIN_USER)' with password '$(ADMIN_PASS)' ensured." 

# Install and activate wpvivid-backuprestore plugin
wpvivid:
	@echo "Installing and activating wpvivid-backuprestore plugin in container '$(CONTAINER)'..."
	@docker exec $(CONTAINER) wp plugin install wpvivid-backuprestore --activate --skip-themes --skip-plugins
	@echo "Plugin 'wpvivid-backuprestore' installed and activated." 

# Usage:
#   make core           # downloads all WordPress files
#   make core SKIP_CONTENT=yes  # skips wp-content during download

core:
	@echo "Downloading WordPress core in container '$(CONTAINER)'..."
	@if [ "$(SKIP_CONTENT)" = "yes" ]; then \
		docker exec $(CONTAINER) wp core download --skip-content --force; \
	else \
		docker exec $(CONTAINER) wp core download --force; \
	fi
	@echo "Generating wp-config.php in container '$(CONTAINER)'..."
	@docker exec $(CONTAINER) wp config create \
		--dbname="$(APP_NAME)" \
		--dbuser="root" \
		--dbpass="root" \
		--dbhost="mysql8"
	@echo "Installing WordPress core in container '$(CONTAINER)'..."
	@docker exec $(CONTAINER) wp core install \
		--url="https://$(APP_NAME).test" \
		--title="Example Site" \
		--admin_user="admin" \
		--admin_password="admin" \
		--admin_email="admin@example.com" \
		--skip-themes --skip-plugins
	@echo "WordPress core downloaded, configured, and installed. Adjust parameters as needed." 

# Add dev config include to wp-config.php and create wp-config.dev.php
config:
	@echo "Generating wp-config.dev.php..."
	@echo "<?php" > wp-config.dev.php
	@echo "define('WP_DEBUG', true);" >> wp-config.dev.php
	@echo "define('WP_DEBUG_LOG', true);" >> wp-config.dev.php
	@echo "define('WP_DEBUG_DISPLAY', false);" >> wp-config.dev.php
	@echo "define('SCRIPT_DEBUG', true);" >> wp-config.dev.php
	@echo "define('WP_DISABLE_FATAL_ERROR_HANDLER', true);" >> wp-config.dev.php
	@echo "" >> wp-config.dev.php
	@echo "@ini_set('display_errors', 0);" >> wp-config.dev.php
	@echo "//@ini_set('memory_limit', -1);" >> wp-config.dev.php
	@echo "" >> wp-config.dev.php
	@echo "/**" >> wp-config.dev.php
	@echo " * Disable sending emails" >> wp-config.dev.php
	@echo " * enable only if you are using \"WP Mail SMTP\" plugin" >> wp-config.dev.php
	@echo " */" >> wp-config.dev.php
	@echo "//define('WPMS_ON', true);" >> wp-config.dev.php
	@echo "//define('WPMS_DO_NOT_SEND', true);" >> wp-config.dev.php

	@echo "Copying wp-config.dev.php into container '$(CONTAINER)'..."
	@docker cp wp-config.dev.php $(CONTAINER):/var/www/html/wp-config.dev.php

	@echo "Patching wp-config.php in container '$(CONTAINER)' (inserting after <?php)..."
	@docker exec $(CONTAINER) bash -c "\
		if ! grep -q '/wp-config.dev.php' wp-config.php; then \
			awk 'NR==1{print; print \"if(file_exists(__DIR__ . \x27/wp-config.dev.php\x27)){\\ninclude_once __DIR__ . \x27/wp-config.dev.php\x27;\\n}\\n\"} NR>1' wp-config.php > wp-config.php.tmp && mv wp-config.php.tmp wp-config.php; \
		fi"

	@echo "Cleaning up..."
	@rm -f wp-config.dev.php

	@echo "wp-config.php updated and wp-config.dev.php created (if not present)."