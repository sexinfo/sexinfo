<?php

require('./core/sex-core.php');




$email = '';
$verifyEmail = '';
$age = '';
$gender = '';
$location = '';
$message = '';
$reply = '';
$method = '';
$code = '';



if (isset($_POST['submit'])) {

	$email = trim($_POST['email']);
	$verifyEmail = trim($_POST['verifyEmail']);
	$age = $_POST['age'];
	$gender = $_POST['gender'];
	$location = trim($_POST['location']);
	$message = trim($_POST['message']);
	$reply = $_POST['reply'];
    $method = $_POST['method'];
    $code = $_POST['code'];
    
    /*if (strlen($code) != 5) {
    echo  "$code Fucking tits mayne $captcha";
    }*/
 
	
        if ($_POST['reply'] != "1" && $_POST['reply'] != "0") {
            $reply = 0;
        }
        
        
        if ($_POST['gender'] != "1" && $_POST['gender'] != "2" && $_POST['gender'] != "3" && $_POST['gender' != "4"]) {
        $gender = 0;
        }
        if ($_POST['method'] != "1" && $_POST['method'] != "2" && $_POST['method'] != "3" && $_POST['method'] != "4" && $_POST['method'] != "5" && $_POST['method'] != "6") {
        $method = 0;
        }
        $mysql = mysql_connect($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
        if(!is_resource($mysql)) {
        echo "Failed to connect to the server\n"; }
                    mysql_select_db("sexweb00");
                    //Spam Prevention when Submit = Disabled
                    $spam1 = mysql_query("select enablesubmit from sex_question_config");
                    $spam = mysql_fetch_row($spam1);
                    
                    if($spam[0] == 1) {
                        header('Location:./?slug=etc');
                    }
                    else {
                    $query = sprintf("INSERT INTO sex_question (`question_submission_date`, `question_body`, `question_guest_email`, `question_wants_reply`, `question_guest_gender`, `question_guest_location`, `question_guest_age`, `question_guest_method`) VALUES (unix_timestamp(), '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                    mysql_real_escape_string($message, $mysql),
                    mysql_real_escape_string($email, $mysql),
                    mysql_real_escape_string($reply, $mysql),
                    mysql_real_escape_string($gender, $mysql),
                    mysql_real_escape_string($location, $mysql),
                    mysql_real_escape_string($age, $mysql),
                    $method);
         
        mysql_query($query) or die(mysql_error());
      
	$mailmsg = "This is an automatic reply thanking you for submitting your question to SexInfo. Your question will be thoughtfully considered and discussed, then assigned to one of the Sexperts to be answered.  The response to your question is then carefully proofread and edited before being sent to your e-mail's inbox. This can be a very time consuming process, therefore you will likely receive an answer within two to three weeks.  We apologize if this delay is an inconvenience to you, but it helps to ensure the accuracy of material on our website, and that you will receive the highest quality response possible.\n\n";
	$mailmsg .= "-The Sexperts\n\n";
	$mailmsg .= "Please do not reply to this message.";

	mail($email,"Auto-Reply from SexInfo",$mailmsg,"From:auto-reply@SexInfoOnline.com");

		
        if (mysql_affected_rows($mysql) > 0) {

        header('Location:./?slug=etc');
        }
 }
 //}
}	 ?>