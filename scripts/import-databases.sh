for arg in "$@"
do
	echo "Importing $arg"
	result=$(curl 'http://localhost/phpmyadmin/import.php' -H 'Cookie: phpMyAdmin=7298h40l57h2cjjkhr59appudtd823hb; pma_collation_connection=utf8_general_ci; pma_lang=en; pma_navi_width=200; SESS1b26a221601aaf499712a3ca2bf68263=-gGJmXgxgs1_D5OY9e0cZu9v3n9LXPrgWBbcZaWcMVg; has_js=1' --form "noplugin=519e6f1f384f3" --form "db=sexweb00" --form "token=e437b41a2384fd6d32a12994f78eaa8e" --form "import_type=database" --form "import_file=@$arg;type=application/gzip" --form "MAX_FILE_SIZE=8388608" --form "charset_of_file=utf-8" --form "allow_interrupt=yes" --form "skip_queries=0" --form "format=sql" --form "csv_terminated=," --form "csv_enclosed=\"" --form "csv_escaped=\"" --form "csv_new_line=auto" --form "docsql_table=" --form "ods_empty_rows=something" --form "ods_recognize_percentages=something" --form "ods_recognize_currency=something" --form "sql_compatibility=NONE" --form "sql_no_auto_value_on_zero=something" --compressed)
	echo "$result"
done
