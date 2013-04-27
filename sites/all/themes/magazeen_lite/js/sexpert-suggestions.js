
document.onkeydown = check

var previousQuestionText;

function check() 
{
	if (document.getElementById("edit-submitted-message") != null)
	{
		var questionField = document.getElementById("edit-submitted-message");
		var questionText = questionField.value;
		if (questionText != previousQuestionText) {
			previousQuestionText = questionText;
			changed();
		}
	}
}

var xmlhttp;
function changed()
{
	if (xmlhttp != null) xmlhttp.abort();
	xmlhttp = new XMLHttpRequest();
	console.log(previousQuestionText);
	xmlhttp.open("GET", "./search/node/" + previousQuestionText, true);
	xmlhttp.send();
}

function readyStateChange() {
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		console.log(xmlhttp.responseText);
	}
}
