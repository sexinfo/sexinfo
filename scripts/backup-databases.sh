#! /bin/sh

username=$1
password=$2

# Root path for phpmyadmin
indexURL=https://secure.lsit.ucsb.edu/phpmyadmin/

# databaseName variable for future compatability with multiple databases to dump
# or perhaps non-conventional database name
databaseName="sexweb00"

# Which file to output the backup to
dataOutput="${databaseName}_dataECReplace.sql.gz"
structureOutput="${databaseName}_struct.sql.gz"

cookies="cookies.txt"

# Execute a query to phpmyadmin and store the response/cookies

# Authenticate
dataString="pma_username=$username&pma_password=$password&server=1&lang=en-iso-8859-1&convcharset=iso-8859-1"
curl --silent -c "$cookies" -D - -L "https://secure.lsit.ucsb.edu/phpmyadmin/" --data "$dataString" > output.txt
loginResponse=$(cat output.txt)

# Extract the token from the query response
token=$( awk -F"token=" '{print $2}' output.txt)
token=$( echo $token | cut -d'&' -f 1)

# Fetch the html showing the tables of our db
tableValues=$(curl --silent -b "$cookies" "https://secure.lsit.ucsb.edu/phpmyadmin/db_structure.php?db=sexweb00&token=$token")
rm output.txt -f

# parse the html so we have one tablename per line
tableValues=$(echo "$tableValues" | grep -A 1 "label for=\"checkbox_tbl_[0-9]\+\"" | grep "title" |  cut -d'"' -f 2)

# This block of code parses the values as individual strings and constructs a get request
OIFS=$IFS
IFS='
'
arr2=$tableValues

quote='"'
formData=""
prefix="db=$databaseName&token=$token&export_type=database"
postfix="&what=sql&csv_separator=;&csv_enclosed=\"&csv_escaped=\&csv_terminated=AUTO&csv_null=NULL&csv_data=&excel_null=NULL&excel_edition=win&excel_data=&htmlexcel_null=NULL&htmlexcel_data=&htmlword_structure=something&htmlword_data=something&htmlword_null=NULL&latex_caption=something&latex_structure=something&latex_structure_caption=Structure+of+table+__TABLE__&latex_structure_continued_caption=Structure+of+table+__TABLE__+(continued)&latex_structure_label=tab:__TABLE__-structure&latex_comments=something&latex_data=something&latex_columns=something&latex_data_caption=Content+of+table+__TABLE__&latex_data_continued_caption=Content+of+table+__TABLE__+(continued)&latex_data_label=tab:__TABLE__-data&latex_null=\textit{NULL}&ods_null=NULL&ods_data=&odt_structure=something&odt_comments=something&odt_data=something&odt_columns=something&odt_null=NULL&pdf_report_title=&pdf_data=1&sql_header_comment=&sql_compatibility=NONE&sql_if_not_exists=something&sql_auto_increment=something&sql_backquotes=something&sql_columns=something&sql_extended=something&sql_max_query_size=50000&sql_hex_for_blob=something&sql_type=REPLACE&xml_data=&yaml_data=&asfile=sendit&filename_template=__DB__&remember_template=on&compression=gzip"
formData="$prefix$formData"
for x in $arr2
do
	formData="$formData&table_select[]=$x"
done
formData="$formData$postfix"



# Execute a curl request to the export api endpoint using the cookies we 
# retrieved from the index.php request.  I've removed the cookies header, 
# replaced the database names with the variable, and the tokens with the variable.
# Also, we store the downloaded file as 'file.sql' in the local dir
echo "Downloading $structureOutput"
rm structure.sql.gz -f
curl -b "$cookies" "https://secure.lsit.ucsb.edu/phpmyadmin/export.php" --data "$formData&sql_structure=something" > "$structureOutput"

echo "Downloading $dataOutput"
rm data.sql.gz -f
curl -b "$cookies" "https://secure.lsit.ucsb.edu/phpmyadmin/export.php" --data "$formData&sql_data=something" > "$dataOutput"

echo "Removing cookies"
rm "$cookies" -f
