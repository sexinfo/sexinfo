#! /bin/bash

if [ ! -d "./Dropbox-Uploader/" ] || [ ! -f "./Dropbox-Uploader/dropbox_uploader.sh" ]; then
  echo -e "\e[0;31mError: Could not find dropbox_uploader.sh.\e[0m\nPlease run '\e[1;33mgit submodule update\e[0m' from your root sexinfo folder."
  exit 1;
fi

rootDBFolder="/Shared Webmaster/Database Exports/"

uploadDirectory="$rootDBFolder`date +'%Y'`/`date +'%m.%d.%y.%H%M'`/"

./Dropbox-Uploader/dropbox_uploader.sh mkdir "$uploadDirectory"

for arg in "$@"
do
	./Dropbox-Uploader/dropbox_uploader.sh upload "$arg" "${uploadDirectory}$arg"
done
