# File to temporarily store cookies into

rm cookies.txt
rm output.txt
cookies=cookies.txt
databaseName=sexweb00

html=$(curl --silent -c cookies.txt "http://localhost/phpmyadmin/db_import.php?db=sexweb00")
$(echo "$html" > output.txt)
noplugin=$(echo "$html" | grep 'noplugin' | cut -d'"' -f 6 | sed 's/ *$//g')

tokenDelimiter="name=\"token\""
#noplugin=$(echo "$html" | grep 'token=')
token=$( awk -F"token=" '{print $2}' output.txt | cut -d'"' -f 1 | sed 's/ *$//g')

echo "$noplugin"
echo "$token"

for arg in "$@"
do
	echo "Importing $arg"
#	result=$(curl -b cookies.txt 'echo.httpkit.com' --form "noplugin=$noplugin" --form "db=sexweb00" --form "token=$token" --form "import_type=database" --form "import_file=@$arg;type=application/gzip" --form "MAX_FILE_SIZE=8388608" --form "charset_of_file=utf-8" --form "allow_interrupt=yes" --form "skip_queries=0" --form "format=sql" --form "csv_terminated=," --form "csv_enclosed=\"" --form "csv_escaped=\"" --form "csv_new_line=auto" --form "docsql_table=" --form "ods_empty_rows=something" --form "ods_recognize_percentages=something" --form "ods_recognize_currency=something" --form "sql_compatibility=NONE" --form "sql_no_auto_value_on_zero=something" --compressed)
	result=$(curl -b cookies.txt 'http://localhost/phpmyadmin/import.php' --form "noplugin=$noplugin" --form "db=sexweb00" --form "token=$token" --form "import_type=database" --form "import_file=@$arg;type=application/gzip" --form "MAX_FILE_SIZE=8388608" --form "charset_of_file=utf-8" --form "allow_interrupt=yes" --form "skip_queries=0" --form "format=sql" --form "csv_terminated=," --form "csv_enclosed=\"" --form "csv_escaped=\"" --form "csv_new_line=auto" --form "docsql_table=" --form "ods_empty_rows=something" --form "ods_recognize_percentages=something" --form "ods_recognize_currency=something" --form "sql_compatibility=NONE" --form "sql_no_auto_value_on_zero=something" --compressed)
	echo "$result"
done
