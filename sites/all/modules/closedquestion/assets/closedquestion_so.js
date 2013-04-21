
/**
 *@file
 *Javascript functions for the Select&Order questions.
 */

/**
 * Generate the answer string from the draggables, for the question with the
 * given id and puts it in the answer form field for that question.
 *
 * @param elementName string
 *   The element name of the question to generate the answer string for.
 *
 * @return TRUE
 */
function cqCheckAnswerSO(elementName) {
  var ulItems = document.getElementById(elementName + "targets").getElementsByTagName("ul");
  var answer = "";
  for (i = 0; i < ulItems.length; i++){
    var ulItem = ulItems[i];
    answer += jQuery(ulItem).attr("cqvalue");
    var liItems = ulItem.getElementsByTagName("li");
    for (j=0; j<liItems.length; j++) {
      answer += jQuery(liItems[j]).attr("cqvalue");
    }
  }
  var answerElement = jQuery('[name="' + elementName + 'selected"]');
  answerElement.val(answer);
}

/**
 * Removes an item from the selection.
 *
 * @param item draggable
 *   The item to remove.
 */
function cqRemoveItem(item) {
  var liItem = item.parentNode;
  var ulItem = liItem.parentNode;
  ulItem.removeChild(liItem);
  var listItem = ulItem.parentNode.parentNode;
  var longid = listItem.id;
  var id = longid.substring(0, longid.length-7);
  cqCheckAnswerSO(id);
}

/**
 * Attach the code that makes the items sortable to a Drupal behaviour.
 */
Drupal.behaviors.closedQuestionSO = {
  attach: function (context) {

    //    if (jQuery.browser.msie){
    //      /* ie has trouble rendering the draggables in the dropablelist when they have no fixed width / height */
    //      jQuery(".cqSoHorizontal .cqDraggable").each(function(index, Element){
    //        jQuery(this).css('width', jQuery(this).width() + 12);                            //+12 because of 'x' <div>
    //        jQuery(this).css('height', jQuery(this).height());
    //      }) 
    //    }
    jQuery('.cqDropableList:not(.cqSort-processed)', context).addClass('cqSort-processed').sortable({
      connectWith : ".cqDropableList",
      update : function(event, ui) {
        var listItem = ui.item[0].parentNode.parentNode.parentNode;
        var longid = listItem.id;
        var id = longid.substring(0, longid.length - 7);
        cqCheckAnswerSO(id);
      },
      remove : function(event, ui) {
        var listItem = ui.item[0].parentNode.parentNode.parentNode;
        var longid = listItem.id;
        var id = longid.substring(0, longid.length - 7);
        cqCheckAnswerSO(id);
      }
    });

    jQuery('.cqCopyList .cqDraggable:not(.cqDrag-processed)', context).addClass('cqDrag-processed').draggable({
      connectToSortable : "ul.cqDropableList",
      helper : "clone",
      zIndex : 1000
    });

    jQuery(".cqDDList").css("list-style-type", "none");
    jQuery(".cqDraggable").css("background-image", "none");

    jQuery('.cqDraggable:not(.cqX-processed)', context).addClass('cqX-processed').prepend("<a class='remove' onclick='cqRemoveItem(this)'>X</a>");

    jQuery('.cqDropableList:not(.cqSelect-processed)', context).addClass('cqSelect-processed').disableSelection();
  }
};
