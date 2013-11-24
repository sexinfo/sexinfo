#! /bin/bash

# Download the databases
./download-databases.sh $1 $2

# Only upload the databases to dropbox if the download was successful
if [[ $? -eq 0 ]]; then
  ./upload-databases.sh *.sql.gz
fi
