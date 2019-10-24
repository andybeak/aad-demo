#!/bin/bash
# Normally we don't include passwords in committed files, but for the purpose of the demo I don't want to have
# to explain to users that they need to enter the root password
mysql -u root -p${MYSQL_ROOT_PASSWORD} -e "GRANT ALL PRIVILEGES ON demo.* TO \`${DB_ADMIN_USERNAME}\`@\`%\` identified by '${DB_ADMIN_PASSWORD}'"

# The standard user is not granted privileges on the transaction table
mysql -u root -p${MYSQL_ROOT_PASSWORD} -e "GRANT ALL PRIVILEGES ON demo.users TO \`${DB_STANDARD_USERNAME}\`@\`%\` identified by '${DB_STANDARD_PASSWORD}'"
