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