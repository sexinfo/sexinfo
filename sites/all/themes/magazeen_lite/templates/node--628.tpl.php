<?php include_once 'utils/am-i-pregnant.php' ?>

<?php
  $data = getData();

  $question_id = $_GET['question-id'];
  if ($question_id == NULL) $question_id = 0;

  $element = $data[$question_id];
  $is_question = $element['type'] == 'question';

  $question = $is_question ? $element['question'] : "";
  $options = $is_question ? $element['answers'] : array();
  $response = $is_question ? "" : $element['message'];
?>

  <script type='text/javascript'>
    var data = jQuery.parseJSON(<?php echo json_encode(json_encode($data)) ?>);

    function nextElement(id) {
      return data[id];
    }

    function showElement(thingy) {
      if (thingy.type === 'question') {
        $('#question').show();
        $('#answer').hide();
        $('#question_title').html(thingy.question);
        console.log(thingy.question);
        var options = $('#options');
        options.empty();
        for (var key in thingy.answers) {
          var option = "<input type='radio' name='question-id' value=" + thingy.answers[key] + ">" + key + "</br>";
          options.append(option);
        };
      } else {
        $('#question').hide();
        $('#answer').show();
        $('#answer').html(thingy.message);
      }
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
    <div id='question' style='display: <?php print($is_question ? 'visible' : 'none'); ?>;'>
      <form onsubmit='return submitAnswer()'>
        <?php printf("<h3 id='question_title'>%s</h3>", $question) ?>
        <div id='options'>
        <?php foreach (array_keys($options) as $key) {
          printf("<input type='radio' name='question-id' value='%s'>%s</br>", $options[$key], $key);
        } ?>
        </div>
        <input type="submit" value="Submit">
      </form>
    </div>

    <div id='answer' style='display: <?php print($is_question ? 'none' : 'visible'); ?>;'>
      <?php print($response); ?>
    </div>
  </div>

</div><!-- .topics-container -->
