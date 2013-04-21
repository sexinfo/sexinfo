
/**
 *@file
 *Javascript functions for the Hotspot questions.
 */

/**
 * Attach the code that puts all draggables in the right spot, and loads
 * the dragArea to a Drupal behaviour.
 */
Drupal.behaviors.closedQuestionHS = {
  attach: function (context) {
    var settings = Drupal.settings.closedQuestion.hs;
    for (var questionId in settings) {
      /* init */
      var qsettings = settings[questionId];
      if (qsettings['initialised']) {
        continue;
      }
      qsettings['initialised'] = true;
    
      /* define ids of startList and answercontainer */
      var answerContainerId = questionId + "answerContainer";
      var answerContainer = jQuery("#" + answerContainerId);

      /* store question Id */
      answerContainer.attr('questionId', questionId);

      /* set background-image and answerContainer width/height */
      answerContainer.width(qsettings.ddImage.width);
      answerContainer.height(qsettings.ddImage.height);
      /* Once the image is loaded, double check the size */
      var image = new Image();
      image.fixClient = answerContainer;
      jQuery(image).load(function() {
        if (this.width > this.fixClient.width()) {
          this.fixClient.width(this.width);
        }
        if (this.height > this.fixClient.height()) {
          this.fixClient.height(this.height);
        }
      });
      image.src = qsettings.ddImage.url;

      /* turn all elements with class "draggable" in startlist into jquery draggables
      */
      jQuery(".cqDdDraggable").draggable({
        zIndex : 10000,
        containment : "parent"
      });
      jQuery("#" + answerContainerId + " .cqDdDraggable").attr("questionId", questionId);

      /* position all draggables as defined in ddQuestionsDraggableStartPos */
      var length = qsettings.ddDraggableStartPos.length;

      for (var i = 0; i < length; i++){
        var cqvalue = qsettings.ddDraggableStartPos[i].cqvalue;
        var draggable = jQuery("#" + answerContainerId + " .cqDdDraggable[cqvalue=" + cqvalue + "]");

        var x = qsettings.ddDraggableStartPos[i].x - draggable.width() / 2;  //substract width/2 because stored coordinated is center
        var y = qsettings.ddDraggableStartPos[i].y - draggable.height() / 2; //idem height/2

        //set the css values
        draggable.css("left", x);
        draggable.css("top", y);
      }

      /* turn answercontainer into jquery droppable */
      answerContainer.droppable({
        /* set drop event */
        drop: function(event, ui) {
          /* user dropped draggable on the answercontainer */
          /* get draggable value and coordinates */
          var cqvalue = ui.draggable.attr("cqvalue");
          var questionId = ui.draggable.attr("questionId");
          var coords = cqGetCenterCoordsHS(jQuery(this), ui.draggable);
          var qsettings = Drupal.settings.closedQuestion.hs[questionId];

          /* replace (or add) coordinates in ddQuestionsDraggableStartPos */
          var found  = false;
          var length = qsettings.ddDraggableStartPos.length;

          //replace: walk through ddQuestionsDraggableStartPos to find object with object.cqvalue = value
          for (var i = 0; i < length; i++){
            if (qsettings.ddDraggableStartPos[i].cqvalue == cqvalue) {
              //found! set new x and y
              qsettings.ddDraggableStartPos[i].x = coords.x;
              qsettings.ddDraggableStartPos[i].y = coords.y;
              found = true;
            }
          }

          //add: add new object when object with object.cqvalue=cqvalue not found in ddQuestionsDraggableStartPos
          if (!found) {
            qsettings.ddDraggableStartPos[length] = {
              "cqvalue" : cqvalue,
              "x" : coords.x,
              "y" : coords.y
            };
            found = false;
          }
          cqCheckAnswerHS(questionId);
        },
        /* define which elements to accept */
        accept: "[questionId='" + questionId + "']"
      });

      /* Create crosshairs when clicking on answer container */
      var createDraggableFunction = function(e) {
        //get current number of draggables */
        var questionId = jQuery(this).attr('questionId');
        var answerContainer;
        if ( this.nodeName == 'MAP' ) {
          answerContainer = jQuery('#' + questionId + 'answerContainer');
        }
        else {
          answerContainer = jQuery(this);
        }
      
        var qsettings = Drupal.settings.closedQuestion.hs[questionId];
        var numberOfRegisteredDraggables = qsettings.ddDraggableStartPos.length;
        var cqvalue, draggable, left, top, xPos, yPos;

        if (qsettings.maxChoices == undefined || numberOfRegisteredDraggables < qsettings.maxChoices) {
          /* create new draggable div */
          var newDrag = document.createElement('div');
          jQuery(newDrag).addClass("cqDdDraggable cqDdDraggable_reduced");
          answerContainer.append(newDrag);

          jQuery(newDrag).draggable({
            "zIndex" : "10000",
            containment : 'parent'
          });
          jQuery(newDrag).attr('questionId', questionId);
          jQuery(newDrag).attr('cqvalue', new Date().getTime());
          jQuery(newDrag).css({
            'width' : '50px',
            'height' : '50px'
          });

          jQuery(newDrag).html('<img src="' + Drupal.settings.closedQuestion.hsimage + '" />');

          /* add it to the answercontainer and set position */
          left = e.pageX - answerContainer.offset().left - jQuery(newDrag).width() / 2;
          top = e.pageY - answerContainer.offset().top - jQuery(newDrag).height() / 2;
          xPos = e.pageX - answerContainer.offset().left;
          yPos = e.pageY - answerContainer.offset().top;

          jQuery(newDrag).css({
            'left' : left+'px',
            'top' : top+'px'
          });

          /* register draggable in ddQuestionsDraggableStartPos array */
          cqvalue = 'd' + (numberOfRegisteredDraggables+1);

          jQuery(newDrag).attr('cqvalue', cqvalue);
          qsettings.ddDraggableStartPos[numberOfRegisteredDraggables] = {
            "x":xPos,
            "y":yPos,
            "cqvalue":cqvalue
          };
        }
        else {
          /* Move the last draggable */
          cqvalue = 'd' + (numberOfRegisteredDraggables);
          draggable = jQuery('.cqDdDraggable[cqvalue=' + cqvalue + ']', answerContainer);

          left = e.pageX - answerContainer.offset().left - jQuery(draggable).width() / 2;
          top = e.pageY - answerContainer.offset().top - jQuery(draggable).height() / 2;
          xPos = e.pageX - answerContainer.offset().left;
          yPos = e.pageY - answerContainer.offset().top;

          jQuery(draggable).css({
            'left': left + 'px',
            'top': top + 'px'
          });
          qsettings.ddDraggableStartPos[numberOfRegisteredDraggables - 1] = {
            "x" : xPos,
            "y" : yPos,
            "cqvalue" : cqvalue
          };
        }
        cqCheckAnswerHS(questionId);
        return;
      }


      /* Create crosshairs when clicking on answer container */
      answerContainer.click(createDraggableFunction);
      jQuery('map[name=' + questionId + 'map]').attr('questionId', questionId);
      jQuery('map[name=' + questionId + 'map]').click(createDraggableFunction);
    }
  }
};

/**
 * Returns the center coordinates of a draggable relative to a droppable.
 *
 * @param droppable object
 *   The Jquery droppable object
 * @param draggable object
 *   The Jquery draggable object
 *
 * @returns object
 *   The coordinates: object.x and object.y
 **/
function cqGetCenterCoordsHS(droppable, draggable){
  var xPos = (draggable.offset().left - droppable.offset().left + draggable.width() / 2);
  var yPos = (draggable.offset().top - droppable.offset().top + draggable.height() / 2);

  return {
    x: xPos,
    y: yPos
  };
}

/**
 * Generate the answer string from the draggables, for the question with the
 * given id and puts it in the answer form field for that question.
 *
 * @param questionId string
 *   The question id of the question to generate the answer string for.
 *
 * @return TRUE
 */
function cqCheckAnswerHS(questionId) {
  var returnString = "";
  var qsettings = Drupal.settings.closedQuestion.hs[questionId];
  var length = qsettings.ddDraggableStartPos.length;

  for (i = 0; i < length; i++){
    var draggableVar = qsettings.ddDraggableStartPos[i];
    returnString += "" + draggableVar.cqvalue + "," + draggableVar.x + ","+draggableVar.y + ";";
  }
  var answerElement = jQuery('[name="' + questionId + 'answer"]');
  answerElement.val(returnString);
  return true;
}
