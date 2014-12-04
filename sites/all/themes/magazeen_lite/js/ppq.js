$quiz = $('.quiz-body')
$question = $('#question');
$answers = $('#answers ul');
$response = $('.response');
var questions;
var responses;

$(document).ready(function () {

    questions = loadJSON("questions.json");
    responses = loadJSON("responses.json");
    console.log(questions);
    console.log(responses);

    var startKey = questions['start'];
    var currentQuestion = questions[startKey];

    $('#TermsOfService').click(function () {
        if ($(this).is(':checked') == true) {
            $('#start-quiz').attr('disabled', true);
        } else
        {
            $('#start-quiz').attr('disabled', false);
        }
    });
    
    $('#start-quiz').click(function () {
        $question.html(currentQuestion.message);
        $(this).fadeOut('slow', function () {
            for (var key in currentQuestion.options) {
                $answers.append("<li class='question' hidden onclick='nextQuestion(this)' data-type=" + currentQuestion.options[key].type + " data-next=" + currentQuestion.options[key].next + " >" + key + "</li>")
            }
            $('.quiz-body').fadeIn('slow', function () {
                $("#answers ul li").each(function (index) {
                    $(this).delay(400 * index).fadeIn(300);
                });
            });

        });
    });

});


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

function nextQuestion(sender) {
    var type = $(sender).data('type');
    var next = $(sender).data('next');
    if (type == "question") {
        var question = questions[next];
        $(sender).siblings().each(function () {
            $(this).fadeOut('slow');
        });
        $quiz.delay(1000).fadeOut('slow', function () {
            $question.html(question.message);
            $answers.html("");
            for (var key in question.options) {
                $answers.append("<li class='question' hidden onclick='nextQuestion(this)' data-type=" + question.options[key].type + " data-next=" + question.options[key].next + " >" + key + "</li>")
            }
            $quiz.fadeIn();
            $question.fadeIn('slow', function () {
                $("#answers ul li").each(function (index) {
                    $(this).delay(400 * index).fadeIn(300);
                });
            });
        });
        
    }
    else {
        var response = responses[next];
        $quiz.fadeOut('slow', function () {
            console.log(response);
            $response.html("<h2 style='text-align: center'>" + response.title + "</h2>" + "<p style='font-size: 20px;'>" + response.text + "</p>");
            $response.fadeIn('slow');
        });


    }

}