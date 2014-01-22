index_HTML=$(curl -c cookies.txt "http://localhost/sexinfo/install.php")
form_build_id=$(echo "$index_HTML" | grep form_build_id | cut -d'"' -f 6)
form_build_id=$(curl -L -b cookies.txt "http://localhost/sexinfo/install.php" --data "profile=standard&form_build_id=$form_build_id&form_id=install_select_profile_form&op=Save and continue" | grep form_build_id | cut -d'"' -f 6)
form_build_id=$(echo "$index_HTML" | grep form_build_id | cut -d'"' -f 6)
form_build_id=$(curl -L -b cookies.txt "http://localhost/sexinfo/install.php?profile=standard" --data "locale=en&form_build_id=$form_build_id&form_id=install_select_locale_form&op=Save and continue")
form_build_id=$(curl -L -b cookies.txt "http://localhost/sexinfo/install.php?profile=standard&locale=en" --data "locale=en&form_build_id=$form_build_id&form_id=install_select_locale_form&op=Save and continue")
echo "$form_build_id"
