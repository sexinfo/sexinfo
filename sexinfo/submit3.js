<script language="Javascript1.2">

function validatesubmit()
	{
	var accept = document.submitquestion.accept;
	var name = document.submitquestion.name;
	var email = document.submitquestion.email;
	var sex = document.submitquestion.gender;
	var location = document.submitquestion.location;
	var age = document.submitquestion.age;
	var reply = document.submitquestion.reply;
	var message = document.submitquestion.message;

	if ( !(accept.checked) )
		{
		alert("Please check that you have accepted the terms of agreement");
		accept.focus();
		return false;
		}
	
	if ( getRadioVal(reply) == "Yes" && !emailvalidation(email) )
		{
		alert("You have chosen reply but have not entered a valid e-mail address");
		email.focus();
		return false;
		}

	if ( !validatefield(message) )
		{
		alert("Please enter a message");
		message.focus();
		return false;
		}

	if ( !validatefield(name) )
		{
		alert("Please enter a name by which we may refer to you by");
		name.focus();
		return false;
		}

	if ( !validatefield(location) )
		{
		alert("Please enter your Country/State so that we may understand your message better");
		location.focus();
		return false;
		}
	
	if ( getRadioVal(sex) == "" )
		{
		alert("Please select your sex so that we may understand your message better");
		sex.focus();
		return false;
		}

	if ( !validatefield(age) )
		{
		alert("Please enter your age");
		age.focus();
		return false;
		}
	
	return true;
	}

function getRadioVal(rb)
	{
	var L = rb.length;
	var ret = "";
	for(var i=0;i<L;i++)
		{
		if ( rb[i].checked )
			{
			ret = rb[i].value; break;
			}		
		}
	
	return(ret);
	}

function emailvalidation(entered)
	{
	with(entered)
		{
		apos = value.indexOf("@");
		dotpos = value.lastIndexOf(".");
		lastpos = value.length-1;

		if ( apos < 1 || dotpos-apos<2 || lastpos-dotpos > 3 || lastpos-dotpos<2)
			{ return false; }
		else
			{ return true; }
		}	
	}

function validatefield(entered)
	{
	with(entered)
		{
		if ( value == null || value == "" )
			{ return false; }
		else 
			{ return true;	}
		}
	}
</script>
