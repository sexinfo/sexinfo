#! /bin/bash

# Sanity check
if [ $# -eq 0 ]
then
  echo -e "\033[0;31mNo Password Supplied\033[0m"
  exit
fi

# Download the databases
./download-databases.sh "sexweb00m" $1

# Only upload the databases to dropbox if the download was successful
if [[ $? -eq 0 ]]; then
  ./upload-databases.sh *.sql.gz
fi
