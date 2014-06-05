<?php include_once 'utils/am-i-pregnant.php' ?>

<?php
  $questions = getQuestions();
  $responses = getResponses();

?>

  <script type='text/javascript'>
    var questions = jQuery.parseJSON(<?php echo json_encode(json_encode($questions)) ?>);
    var responses = jQuery.parseJSON(<?php echo json_encode(json_encode($responses)) ?>);
    console.log(questions);

    function nextElement(id) {
      return data[id];
    }

    function showElement(thingy) {
      $("#question").hide("slow");
      $("#answer").hide("slow", function() {
        if (thingy.type === 'question') {
          $('#question').slideDown('slow');
          $('#question_title').html(thingy.question);
          console.log(thingy.question);
          var options = $('#options');
          options.empty();
          for (var key in thingy.answers) {
            var option = "<input type='radio' name='question-id' value=" + thingy.answers[key] + ">" + key + "</br>";
            options.append(option);
          };
        } else {
          $('#answer').slideDown('slow');
          $('#answer-content').html(thingy.message);
        }
      });
    }

    function submitAnswer() {
      var element_id = $('input[name="question-id"]:checked').val();
      if (element_id == null) return false;
      showElement(nextElement(element_id));
      history.pushState({id: element_id}, "", "?question-id=" + element_id);
      return false;
    }

    window.onpopstate = function(e) {
      var element_id = 0;
      if (e.state != null) element_id = e.state.id;
      showElement(nextElement(element_id));
    }
  </script>
  <div id='content'>

    <!-- This is the field that shows the question to the user -->
    <div id='question' class='parent-topic'>
      <form onsubmit='return submitAnswer()'>
        <h3 id='question_title'></h3>
        <div id='options'>
          <!-- Answers go here -->
        </div>
      </form>
    </div>

    <!-- This is the field that shows the response to the user -->
    <div id='answer' class='parent-topic'>
      <div id='answer-content'></div>
    </div>
  </div>

</div><!-- .topics-container -->
