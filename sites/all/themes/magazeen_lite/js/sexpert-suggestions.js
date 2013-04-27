
document.onkeydown = check
document.onkeyup = check;

var previousQuestionText;

function check() 
{
	if (document.getElementById("edit-submitted-message") != null)
	{
		var questionField = document.getElementById("edit-submitted-message");
		var questionText = questionField.value;
		if (questionText != previousQuestionText) {
			previousQuestionText = questionText;
			changed(previousQuestionText);
		}
	}
}

var xmlhttp;
function changed(text)
{
	// If there is already an async request sent, 
	// abort the previous one because it is no longer relevant
	if (xmlhttp != null) xmlhttp.abort();
	
	// If there hasn't been a request yet, create a new one
	else {
		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = readyStateChange;
	}
	console.log(previousQuestionText);
	xmlhttp.open("GET", "./search/node/" + text, true);
	xmlhttp.send();
}

function readyStateChange() {
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		// Parse the response as a new "document"
		var tempDocument = document.createElement('div');
		tempDocument.innerHTML = xmlhttp.responseText;
		
		// Extract the list of results from the document
		var list = getElementByClassname(tempDocument, "search-results node-results");

		// TODO: 
		// If there are no results, show the top viewed topics
		// If there are results, show those results


		console.log(list.innerHTML);
	}
}

function initIFrame() {
	// TODO: Create an iframe to display the search result
}

function setiframecontent(html) {
	// TODO: Set the content of the iframe
}


function getElementByClassname(document, classname) {
	// Iterate through all elements
	var elems = document.getElementsByTagName('*'), i;
	for (i in elems) {
		// If the class names match, return the element found
		if( (' ' + elems[i].className + ' ')
				.indexOf(' ' + classname + ' ') 
				> -1) {
			return elems[i];
		}
	}

	// Failure of search is to return null
	return null;
}
