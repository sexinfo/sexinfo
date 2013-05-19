#! /bin/sh

# Root path for phpmyadmin
indexURL=https://localhost/phpmyadmin/

# databaseName variable for future compatability with multiple databases to dump
# or perhaps non-conventional database name
databaseName="sexweb00"

username=$1
password=$2

# Create the sexinfo database
curl --silent 'http://localhost/phpmyadmin/db_create.php' -H 'Cookie: phpMyAdmin=7298h40l57h2cjjkhr59appudtd823hb; pma_collation_connection=utf8_general_ci; pma_lang=en; pma_navi_width=200; SESS1b26a221601aaf499712a3ca2bf68263=X4svHPej_FXSP9r1YKU5X9mV6Sb_OPRA97K2Mlu-tq8; has_js=1' --data "token=a0353b61401d67825a531884eeffc52b&reload=1&new_db=$databaseName&db_collation=&ajax_request=true&_nocache=136892370767587397" --compressed > /dev/null

# Create and add the user with all the privileges for the specific database
curl --silent 'http://localhost/phpmyadmin/server_privileges.php' -H 'Cookie: phpMyAdmin=7298h40l57h2cjjkhr59appudtd823hb; pma_collation_connection=utf8_general_ci; pma_lang=en; pma_navi_width=200; SESS1b26a221601aaf499712a3ca2bf68263=X4svHPej_FXSP9r1YKU5X9mV6Sb_OPRA97K2Mlu-tq8; has_js=1' --data "token=a0353b61401d67825a531884eeffc52b&pred_username=userdefined&username=$username&pred_hostname=localhost&hostname=localhost&pred_password=userdefined&pma_pw=$password&pma_pw2=$password&generated_pw=&dbname=$databaseName&createdb=3&grant_count=27&Select_priv=Y&Insert_priv=Y&Update_priv=Y&Delete_priv=Y&File_priv=Y&Create_priv=Y&Alter_priv=Y&Index_priv=Y&Drop_priv=Y&Create_tmp_table_priv=Y&Show_view_priv=Y&Create_routine_priv=Y&Alter_routine_priv=Y&Execute_priv=Y&Create_view_priv=Y&Event_priv=Y&Trigger_priv=Y&Grant_priv=Y&Super_priv=Y&Process_priv=Y&Reload_priv=Y&Shutdown_priv=Y&Show_db_priv=Y&Lock_tables_priv=Y&References_priv=Y&Repl_client_priv=Y&Repl_slave_priv=Y&Create_user_priv=Y&max_questions=0&max_updates=0&max_connections=0&max_user_connections=0&ajax_request=true&adduser_submit=Go&_nocache=1368924422560699756" --compressed > /dev/null

