
/**
 *@file
 *Javascript functions for the Drag&Drop questions.
 */

/**
 * Attach the code that initializes the closed question
 */
Drupal.behaviors.closedQuestion = {
  attach: function (context) {
    /* turn autocomplete of for inputs */
    jQuery('#closedquestion-get-form-for input').attr('autocomplete', 'off');

    /* put in-line feedback in the right spot */
    cqMoveFeedback(context);
  }
};
 
/**
 * Function that moves feedback from the general feedback area to the correct
 * place in the question.
 *
 * It clears out all existing feedback first, so only the new feedback is
 * visible.
 */
function cqMoveFeedback(context) {
  var feedback = jQuery('[id^="cq-feedback-wrapper"]', context);
  if (feedback.length > 0) {
    jQuery(".cqFbBlock").empty();
    jQuery(".cqFbItem", context).filter("[class*=block-]").each(function(index, Element) {
      var fbItem = jQuery(this);
      var parent = fbItem.parent();
      var fieldSet = parent.parent();
      var classString = fbItem.attr("class");
      var pos1 = classString.indexOf("block-");
      var pos2 = classString.indexOf(" ", pos1 + 6);
      if (pos2 < 0) {
        pos2 = classString.length;
      }
      var targetId = classString.substr(pos1 + 6, pos2 - pos1);
      var targetBlock = jQuery(".cqFbBlock.cqFb-" + targetId + "");
      targetBlock.append(fbItem);
      parent.remove();
      var fieldSetChildren = fieldSet.children();
      // If there are no more children in the fieldset, we can hide it.
      if (fieldSetChildren.length == 0) {
        fieldSet.parent().hide();
      }
    })
  }
}