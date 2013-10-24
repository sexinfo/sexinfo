<?php

/**
 * @file
 * Customize the navigation shown when editing or viewing submissions.
 *
 * Available variables:
 * - $node: The node object for this webform.
 * - $mode: Either "form" or "display". May be other modes provided by other
 *          modules, such as "print" or "pdf".
 * - $submission: The Webform submission array.
 * - $submission_content: The contents of the webform submission.
 * - $submission_navigation: The previous submission ID.
 * - $submission_information: The next submission ID.
 */
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
  function goPrevious() {
    if (document.getElementsByClassName("webform-submission-previous")[0].tagName!="A") return false; // There is no next to go to
    document.getElementsByClassName("webform-submission-previous")[0].click(); // Otherwise, click the link
    return true;
  }


  function goNext() {
    if (document.getElementsByClassName("webform-submission-next")[0].tagName!="A") return false; // There is no next to go to
    document.getElementsByClassName("webform-submission-next")[0].click(); // Otherwise click the link
    return true;
  }

  function doDelete() {
    // We have our own confirmation to bypass the system's confirmation
    if (!confirm("Are you sure you want to delete?")) return false;

    // Get all anchor elements ("links")
    var links = document.getElementsByTagName("a");

    // Itterate through every link
    for (i=0; i < links.length; i++)
      // Find the link that is labled "delete" and contains "submission" in the url and click it
      if (links[i].innerHTML==="Delete" && links[i].href.indexOf("/sexinfo/node/22/submission/") != -1) {

        // We will asynchornously submit a POST request to delete the item
        var url = links[i].href;

        // The post data requires some parsing
        $.get(url, function(data) {
          var postData = {
              'confirm': 1,
              'form_build_id': $(data).find("[name='form_build_id']").attr("value"),
              'form_token': $(data).find(name="[name='form_token']").attr("value"),
              'form_id': $(data).find(name="[name='form_id']").attr("value"),
              'op': "delete"};
          console.warn(postData);
          $.post(url, postData, function() {
            // This is the callback function that defines when the delete is successful, we go to the next question
            // If there is no next question, we go to the previous question
            if (!goNext()) goPrevious();
          });
        });
      }
  }

  $('body').keydown(function(e) {
    if (e.keyCode == 37) goPrevious();		// Left Arrow
    else if (e.keyCode == 39) goNext();		// Right Arrow
    else if (e.keyCode == 68) doDelete();	// 'd'
  });

</script>

<?php if ($mode == 'display' || $mode == 'form'): ?>
  <div class="clearfix">
    <?php print $submission_actions; ?>
    <?php print $submission_navigation; ?>
  </div>
<?php endif; ?>

<?php print $submission_information; ?>

<div class="webform-submission">
  <?php print render($submission_content); ?>
</div>

<?php if ($mode == 'display' || $mode == 'form'): ?>
  <div class="clearfix">
    <?php print $submission_navigation; ?>
  </div>
<?php endif; ?>
