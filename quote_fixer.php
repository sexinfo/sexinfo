<?php
//Fixer for Quote Marks being used inappropriately with URL formation.

require_once('./core/sex-core.php');

$mysql = mysql_connect($config['dbhost'], $config['dbuser'], $config['dbpass']);
$db = mysql_select_db($config['dbname'], $mysql);

$val1 = "%â€œ%";
$val2 = "%â€%";
$rep = "\"";

$q = sprintf("SELECT content_id from `sex_content_test` WHERE `content_body` LIKE '%s'", $val1);
$query = mysql_query($q);
$number = mysql_num_rows($query);

$queryf = sprintf("SELECT content_id from `sex_content_test` WHERE `content_body` LIKE '%s'", $val2);
$querynum = mysql_query($queryf) or die(mysql_error());

$number2 = mysql_num_rows($querynum);

for ($times = 0; $number >= $times; $times ++) {
    $query2f = sprintf("SELECT content_body, content_id from `sex_content_test` WHERE `content_body` LIKE '%s' LIMIT 1", $val1);
    $query2 = mysql_query($query2f) or die(mysql_error());

    $step1 = mysql_fetch_row($query2);
    $body = $step1[0];
    $id = $step1[1];

    $step2 = str_replace($val1, $rep, $body);

    $step3 = mysql_query("UPDATE `sex_content_test` SET content_body = '$step2'  WHERE content_id = '$id'");
}

$timest = $times-1;
echo "Updated $timest";

for ($times2 = 0; $number2 >= $times2; $times2 ++) {
   $query3f = sprintf("SELECT content_body, content_id from `sex_content_test` WHERE `content_body` LIKE '%s' LIMIT 1", $val2);
   $query3 = mysql_query($query3f) or die(mysql_error());
    $step4 = mysql_fetch_row($query3);
    $body2 = $step4[0];
    $id2 = $step4[1];

    $step5 = str_replace($val2, $rep, $body2);

    $step6 = mysql_query("UPDATE `sex_content_test` SET content_body = '$step6' WHERE content_id = '$id2'");
}

$times2t = $times2-1;
echo "<br>Updated $times2t";

?>
