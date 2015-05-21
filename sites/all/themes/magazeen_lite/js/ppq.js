$quiz_id = '.quiz-body'
$question_id = '#ppq-question';
$answers_id = '#answers ul';
$response_id = '.response';
$tooltip_id = '.tooltip';
var questions;
var responses;
var term;
var termRect;

$(document).ready(function () {

    questions = loadJSON("questions.json");
    responses = loadJSON("responses.json");

    console.log(questions);
    console.log(responses);

    var startKey = questions['start'];
    var currentQuestion = questions[startKey];
    var tooltipsArray = currentQuestion.tooltips;
    console.log(tooltipsArray);

    $('#start-quiz').click(function () {
        console.log();
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
                });
            });
        });

        console.log("Question processing complete!");

        $('span.tooltip').mouseover(function(event) {
            console.log("Term selected!");
            term = document.getElementById($(this).text());
            console.log(term);
            //console.log(termRect.top, termRect.left);
            createTooltip(term);
        }).mouseout(function() {
            hideToolTip();
        });
    });
});

function processQuestion(question) {
    questions = loadJSON("questions.json");
    var startKey = questions['start'];
    var currentQuestion = questions[startKey];
    var tooltipsArray = currentQuestion.tooltips;
    var words = [];
    var i = 0;
    for (var key in tooltipsArray) {
    	words[i] = key;
    	i++;
    }
    console.log(words);

    words.forEach(function (word) {
        // 'g' is global flag
        question = question.replace(new RegExp(word, 'g'), "<span id="+word+" class='tooltip'>"+word+"</span>");
    });
    return question;
}

function createTooltip(termElement) {
    console.log("Tooltip creator invoked!"); 
    var $tooltip = $('<div class = "tooltip">The quick brown fox jumps over the lazy dog.</div>');
    $('.tooltip').after($tooltip);
    positionTooltip(termElement);
};

function hideToolTip() {
    console.log("Mouse exited.");
    $('div.tooltip').hide();
}

function positionTooltip(termElement) {
    console.log("Positioning tooltip...");
    termRect = termElement.getBoundingClientRect();
    console.log(termRect);
    var tPosX = termRect.left;
    var tPosY = termRect.bottom + 5;
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
        var question = questions[next];
        $(sender).siblings().each(function () {
            $(this).fadeOut('slow');
        });
        $($quiz_id).delay(1000).fadeOut('slow', function () {
            $($question_id).html(question.message);
            $($answers_id).html("");
            for (var key in question.options) {
                $($answers_id).append("<li class='question' hidden onclick='nextQuestion(this)' data-type=" + question.options[key].type + " data-next=" + question.options[key].next + " >" + key + "</li>")
            }
            $($quiz_id).fadeIn();
            $($question_id).fadeIn('slow', function () {
                $("#answers ul li").each(function (index) {
                    $(this).delay(400 * index).fadeIn(300);
                });
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
