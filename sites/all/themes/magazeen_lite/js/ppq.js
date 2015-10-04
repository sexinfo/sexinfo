$quiz_id = '.quiz-body'
$question_id = '#ppq-question';
$answers_id = '#answers ul';
$response_id = '.response';
$tooltip_id = '.tooltip';
var questions;
var responses;
var tooltipsArray;

var term;
var termElement;
var termRect;
var tPosX;
var tPosY;

$(document).ready(function () {

    questions = loadJSON("questions.json");
    responses = loadJSON("responses.json");

    console.log(questions);
    console.log(responses);

    var startKey = questions['start'];
    var currentQuestion = questions[startKey];
    //var words = [];
    tooltipsArray = currentQuestion.tooltips;
    console.log(tooltipsArray);

    $('#start-quiz').click(function () {
        var processedMessage = processQuestion(currentQuestion.message);
        $($question_id).html(processedMessage);
        $('#ppq-introduction').fadeOut('slow');
        $('#terms-check').fadeOut('slow');
        $(this).fadeOut('slow', function () {
            for (var key in currentQuestion.options) {
                $($answers_id).append("<li class='question' hidden onclick='nextQuestion(this)' data-type=" + currentQuestion.options[key].type + " data-next=" + currentQuestion.options[key].next + " >" + key + "</li>")
            }
            $('.quiz-body').fadeIn('slow', function () {
                $("#answers ul li").each(function (index) {
                    $(this).delay(400 * index).fadeIn(300);
                    console.log("Response - 1");
                });
            });
        });

        console.log("Question processing complete!");

        $('span.tooltip').mouseover(function(event) {
            console.log("Term selected!");
            term = $(this).text();
            termElement = document.getElementById(term);
            termRect = termElement.getBoundingClientRect();
            //console.log(termRect);
            console.log(tooltipsArray[term]);
            //console.log(termRect.top, termRect.left);
            createTooltip(term, termRect);
        }).mouseout(function() {
            hideToolTip();
        });
    });
});

function processQuestion(question) {
    questions = loadJSON("questions.json");
    var startKey = questions['start'];
    var currentQuestion = questions[startKey];
    //tooltipsArray = currentQuestion.tooltips;
    var i = 0;
    var words = [];
    for (var key in tooltipsArray) {
    	words[i] = key;
    	i++;
    }
    console.log(words);

    words.forEach(function (word) {
        // 'g' is global flag
        question = question.replace(new RegExp(word), "<span id="+word+" class='tooltip'>"+word+"</span>");
    });
    return question;
}

function createTooltip(term, termRect) {
    console.log("Tooltip creator invoked!");
    var definition = tooltipsArray[term];
    //var definition = "The quick brown fox jumps over the lazy dog.";
    var $tooltip = $('<div class = "tooltip">'+definition+'</div>');
    $('.tooltip').after($tooltip);
    positionTooltip(termRect);
};

function hideToolTip() {
    console.log("Mouse exited.");
    $('div.tooltip').hide();
}

function positionTooltip(termRect) {
    console.log("Positioning tooltip...");
    console.log(termRect);
    tPosX = termRect.left;
    tPosY = termRect.bottom + 5;
    $('div.tooltip').css({'position': 'absolute', 'top': tPosY + 'px', 'left': tPosX + 'px'});
};

function loadJSON(filename) {
    var results = {};
    var filepath = "/sexinfo/data/" + filename;
    $.ajax({
        url: filepath,
        async: false,
        dataType: 'json',
        success: function (data) {
            results = data;
        }
    });
    return results;
}

function loadHTML(filename) {
    var results = {};
    var filepath = "/sexinfo/data/" + filename;
    $.ajax({
        url: filepath,
        async: false,
        dataType: 'html',
        success: function (data) {
            results = data;
        }
    });
    return results;
}

function nextQuestion(sender) {
    var type = $(sender).data('type');
    var next = $(sender).data('next');

    if (type == "question") {
        console.log("Populating tooltips array");
        var question = questions[next];
        tooltipsArray = question.tooltips;
        //var words = [];

        $(sender).siblings().each(function () {
            $(this).fadeOut('slow');
            console.log("Response - 2");
        });

        $($quiz_id).delay(1000).fadeOut('slow', function () {
            console.log("Processing message for new question.");
            console.log(question.message);
            var processedMessage = processQuestion(question.message);
            $($question_id).html(processedMessage);
            $($answers_id).html("");
            for (var key in question.options) {
                $($answers_id).append("<li class='question' hidden onclick='nextQuestion(this)' data-type=" + question.options[key].type + " data-next=" + question.options[key].next + " >" + key + "</li>")
            }
            $($quiz_id).fadeIn();
            $($question_id).fadeIn('slow', function () {
                $("#answers ul li").each(function (index) {
                    $(this).delay(400 * index).fadeIn(300);
                    console.log("Response - 3");
                });
            });

            $('span.tooltip').mouseover(function(event) {
                console.log("Term selected!");
                term = $(this).text();
                termElement = document.getElementById(term);
                termRect = termElement.getBoundingClientRect();
                //console.log(termRect);
                console.log(tooltipsArray[term]);
                //console.log(termRect.top, termRect.left);
                createTooltip(term, termRect);
            }).mouseout(function() {
                hideToolTip();
            });
        });

    }
    else {
        var response = responses[next];
        $($quiz_id).fadeOut('slow', function () {
            console.log(response);
            $($response_id).html("<h2 style='text-align: center; padding: 8px;'>" + response.title + "</h2>" + "<p style='font-size: 16px; padding: 8px;'>" + response.text  + "</p>");
            $($response_id).fadeIn('slow');
            $('#total_response').fadeIn('slow');
        });


    }

}
