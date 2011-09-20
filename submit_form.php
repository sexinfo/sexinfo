<head>
<link rel="stylesheet" href="./theme/css/submit_form.css" type="text/css">

</head>
<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<script type="text/javascript" src="./theme/livevalidation_standalone.js"></script>
               <style type="text/css" media="screen">
.LV_validation_message{
    font-weight:bold;
    margin:0 0 0 5px;
}

.LV_valid {
    color:#00ee00;
}

.LV_invalid {
    color:#ff0000;
}

.LV_valid_field,
input.LV_valid_field:hover,
input.LV_valid_field:active,
textarea.LV_valid_field:hover,
textarea.LV_valid_field:active {
    border: 2px solid #00cc00;
}

.LV_invalid_field,
input.LV_invalid_field:hover,
input.LV_invalid_field:active,
textarea.LV_invalid_field:hover,
textarea.LV_invalid_field:active {
    border: 2px solid #CC0000;
}
</style>
	
<form name="submit" action="submit_handler.php" method="post">
   <!--<fieldset id="ask">-->
	<div id="contact">
	<h1>Ask the Sexperts</h1>
  	<p>
		<br>
      <label for="email" float:right;>Email</label>
      
      <input type="text" id="email" name="email" value="" size="33" tabindex="1" </>
    </p>

    <p>
      <label for="location">Country / State</label>
      <input type="text" name="location" id="location" value="" size="25" tabindex="1" </>
    </p>
    <p>
      
      <label for="message">Gender:</label>
        <select name="gender" id="gender" tabindex="1" style="width: 260px; margin: 10px 0 20px 0;">
      		&nbsp;&nbsp;&nbsp;&nbsp;<option value="1">Male</option>
	  		&nbsp;&nbsp;&nbsp;&nbsp; <option value="2">Female</option>
	  		&nbsp;&nbsp;&nbsp;&nbsp;<option value="3">Other</option>
	 		&nbsp;&nbsp;&nbsp;&nbsp;<option value="4">Prefer Not To State</option>
	  </select>
	</p>
	
    <p>   
      <label for="age">Age</label>
      <input type="text" id="age" name="age" value="" size="4" tabindex="1" </>
    </p>
    <p>
      <label for="message">Question:</label>
      <br />
      <textarea name="message" id="message" cols="40" rows="10" tabindex="1"></textarea>
    </p>
    
    <!-- CAPTCHA -->
   	<img id="captcha" src="./securimage/securimage_show.php" alt="CAPTCHA Image" class="captcha" /><br />
   
  	<label for="code">Enter the CAPTCHA:</label>
  	<input type="text" name="captcha_code" size="10" maxlength="6" /><br />
	<!--<a href="#" onclick="document.getElementById('captcha').src = './securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>-->
	

  	
	    <!--<p>
	    <label for="code">Enter the validation code:</label><br />
	    <img src="./core/sex-captcha.php"/><br />
	    <input type="text" name="code" id="code" /></p>-->
	    
	    
    <p>
		<br>
      <input name="valid" value="0" type="hidden" />
      <input type="submit" name="submit" value="Ask the Sexperts" tabindex="1" />
    </p>
   </div>
  <!--</fieldset>-->
</form>
         <script type="text/javascript">
    			var email = new LiveValidation( 'email', {onlyOnSubmit: true } );
    			email.add( Validate.Email, {failureMessage:"Please enter an email address." } );
                email.add( Validate.Presence, {failureMessage: "Please enter an email address."} );
    			var message = new LiveValidation( 'message', {onlyOnSubmit: true } );
    			message.add( Validate.Presence, {failureMessage: "Please include a message for us!" } );
    			var code = new LiveValidation( 'code', {onlyOnSubmit: true } );
    			code.add( Validate.Presence, {failureMessage: "Please enter the validation code." } );
    			code.add( Validate.Length, { is: 5, failureMessage: "Validation code incorrect." } );
                var loc = new LiveValidation( 'location', {onlyOnSubmit: true } );
                loc.add( Validate.Presence, {failureMessage: "Please include your Country/State."} );
                var age = new LiveValidation ( 'age', {onlyOnSubmit: true } );
                age.add( Validate.Numericality, { minimum: 1, failureMessage: "You're not negative years old!" } );
                age.add ( Validate.Presence, {failureMessage: "Please include your age." } );
                var gender = new LiveValidation( 'gender', {onlyOnSubmit: true } );
                gender.add( Validate.Presence, {failureMessage: "Please state your gender" } );
   		</script>