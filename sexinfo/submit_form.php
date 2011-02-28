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
   <fieldset id="ask">
	<legend style="font-size:3em;">Ask the Sexperts</legend>
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
      Gender
        <select name="gender" id="gender" tabindex="1">
      &nbsp;&nbsp;&nbsp;&nbsp;<option value="1">Male</option>
	  &nbsp;&nbsp;&nbsp;&nbsp; <option value="2">Female</option>
	  &nbsp;&nbsp;&nbsp;&nbsp;<option value="3">Other</option>
	  &nbsp;&nbsp;&nbsp;&nbsp;<option value="4">Prefer Not To State</option>
	  </select>
    
       
      &nbsp;&nbsp;&nbsp;&nbsp;<label for="age">Age</label>
      <input type="text" id="age" name="age" value="" size="4" tabindex="1" </>
    </p>
    <p>
      <label for="message">Message to the Sexperts:</label>
      <br />
      <textarea name="message" id="message" cols="40" rows="10" tabindex="1"></textarea>
    </p>
    <!--<p>
    <input name="accept" type="checkbox" id="accept" value="true" tabindex="1">
    <label for="accept">
    	By checking this box you are agreeing to the
    </label>
    <a href="./?slug=terms">terms</a>
    <label for="accept">
    	that have been put forth.
    </label>
    </p>-->
    <?
    require_once('./theme/recaptchalib.php');
    $publickey = "6LeuXAYAAAAAALUvTskgd5cAIspYSRPOun2oADAT";
    echo recaptcha_get_html($publickey, $error);
    ?>
    <p>
		<br>
      <input name="valid" value="0" type="hidden" />
      <input type="submit" name="submit" value="Ask the Sexperts" tabindex="1" />
    </p>
  </fieldset>
</form>
         <script type="text/javascript">
    			var email = new LiveValidation( 'email', {onlyOnSubmit: true } );
    			email.add( Validate.Email, {failureMessage:"Please enter an email address." } );
                email.add( Validate.Presence, {failureMessage: "Please enter an email address."} );
                var verifyEmail = new LiveValidation( 'verifyEmail', {onlyOnSubmit: true } );
                verifyEmail.add( Validate.Confirmation, { match: 'email', failureMessage: "Please ensure your email is correct." } );
                verifyEmail.add( Validate.Presence, {failureMessage: "Please verify your email address." } );
    			var accept = new LiveValidation( 'accept', {onlyOnSubmit: true } );
    			accept.add( Validate.Acceptance, {failureMessage: "Please accept the terms of agreement" } );
    			var message = new LiveValidation( 'message', {onlyOnSubmit: true } );
    			message.add( Validate.Presence, {failureMessage: "Please include a message for us!" } );
                var loc = new LiveValidation( 'location', {onlyOnSubmit: true } );
                loc.add( Validate.Presence, {failureMessage: "Please include your Country/State."} );
                var age = new LiveValidation ( 'age', {onlyOnSubmit: true } );
                age.add( Validate.Numericality, { minimum: 1, failureMessage: "You're not negative years old!" } );
                age.add ( Validate.Presence, {failureMessage: "Please include your age." } );
                var reply = new LiveValidation ( 'reply', {onlyOnSubmit: true } );
                reply.add( Validate.Presence, {failureMessage: "Please state whether you would like a response or not." } );
                var gender = new LiveValidation( 'gender', {onlyOnSubmit: true } );
                gender.add( Validate.Presence, {failureMessage: "Please state your gender" } );
               </script>