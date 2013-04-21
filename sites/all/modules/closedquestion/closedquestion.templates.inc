<?php

/**
 * @file
 * Storage for our XML templates.
 */

/**
 * Defines the actual XML templates. Stored in a separate file to avoid being
 * processed on every page load.
 *
 * @see closedquestion_closedquestion_templates()
 */
function _closedquestion_closedquestion_templates() {
  return array(
    'cq_multiple_choice' => array(
      'name' => 'Multiple Choice',
      'xml' => '<question type="OPTION">
  <text>Put the question here</text>
  <hint mintries="1">A first hint.</hint>
  <hint mintries="2">A second hint.</hint>
  <hint mintries="3">A third hint.</hint>
  <option correct="0">
    <choice>Option 1</choice>
    <feedback>feedback for option 1</feedback>
  </option>
  <option correct="0">
    <choice>Option 2</choice>
    <feedback>feedback for option 2</feedback>
  </option>
  <option correct="1">
    <choice>Option 3</choice>
    <feedback>feedback for option 3</feedback>
  </option>
  <option correct="0">
    <choice>Option 4</choice>
    <feedback>feedback for option 4</feedback>
  </option>
</question>',
    ),

    'cq_multiple_answer' => array(
      'name' => 'Multiple Answer',
      'xml' => '<question type="CHECK">
  <text>Put the question here</text>
  <correct>Feedback if the student answers the question correct.</correct>
  <hint mintries="1">A first hint.</hint>
  <hint mintries="2">A second hint.</hint>
  <hint mintries="3">A third hint.</hint>
  <option>
    <choice>Option 1</choice>
    <feedback>feedback for option 1</feedback>
  </option>
  <option correct="1">
    <choice>Option 2</choice>
    <feedback>feedback for option 2</feedback>
  </option>
  <option correct="1">
    <choice>Option 3</choice>
    <feedback>feedback for option 3</feedback>
  </option>
  <option>
    <choice>Option 4</choice>
    <feedback>feedback for option 4</feedback>
  </option>
</question>'
    ),

    'cq_multiple_answer_advanced_mapping' => array(
      'name' => 'Multiple Answer, with advanced answer mapping',
      'xml' => '<question type="CHECK">
  <text>Put the question here</text>
  <hint mintries="1">A first hint.</hint>
  <hint mintries="2">A second hint.</hint>
  <hint mintries="3">A third hint.</hint>
  <option id="a">
    <choice>Option 1</choice>
  </option>
  <option id="b">
    <choice>Option 2</choice>
  </option>
  <option id="c">
    <choice>Option 3</choice>
  </option>
  <option id="d">
    <choice>Option 4</choice>
  </option>
  <mapping correct="1">
    <match pattern="a" />
    <match pattern="c" />
    <feedback>That is correct.</feedback>
  </mapping>
</question>',
    ),

    'cq_select_and_order' => array(
      'name' => 'Select & Order',
      'xml' => '<question type="selectorder" duplicates="0">
  <text>Select and order the needed steps. This example question contains only one target box.</text>
  <option id="a">
    <choice>Step A.</choice>
  </option>
  <option id="b">
    <choice>Step B.</choice>
  </option>
  <option id="c">
    <choice>Step C.</choice>
  </option>
  <option id="d">
    <choice>Step D.</choice>
  </option>
  <option id="e">
    <choice>Step E.</choice>
  </option>
  <option id="f">
    <choice>Step F.</choice>
  </option>
    <hint mintries="1">A first hint.</hint>
    <hint mintries="2">A second hint.</hint>
    <hint mintries="3">A third hint.</hint>
  <mapping correct="1">
    <match pattern="^bcd$" />
    <feedback>Correct. You only need steps B, C and D.</feedback>
  </mapping>
  <mapping correct="0">
    <match pattern="^[^b]*c.*$" />
    <feedback>You can not do step C unless you first do step B.</feedback>
  </mapping>
</question>',
    ),

    'cq_select_and_order_multiple_targets' => array(
      'name' => 'Select & Order, with multiple target boxes',
      'xml' => '<question type="selectorder">
  <text>Select and order the needed steps. This example question contains two target boxes.</text>
  <option id="a">
    <choice>Step A.</choice>
  </option>
  <option id="b">
    <choice>Step B.</choice>
  </option>
  <option id="c">
    <choice>Step C.</choice>
  </option>
  <option id="d">
    <choice>Step D.</choice>
  </option>
  <option id="e">
    <choice>Step E.</choice>
  </option>
  <option id="f">
    <choice>Step F.</choice>
  </option>
  <option id="1"><choice>Day 1</choice></option>
  <option id="2"><choice>Day 2</choice></option>
  <hint mintries="1">A first hint.</hint>
  <hint mintries="2">A second hint.</hint>
  <hint mintries="3">A third hint.</hint>
  <mapping correct="1">
    <match pattern="^1bc2d$" />
    <feedback>Correct. You need to do steps B and C on day 1 and step D on day 2.</feedback>
  </mapping>
  <mapping correct="0">
    <match pattern="^[^b]*c.*$" />
    <feedback>You can not do step C unless you first do step B.</feedback>
  </mapping>
  <mapping correct="0">
    <match pattern="([^d12]d)|(d[^d12])" />
    <feedback>Step D is an all-day event, you can not combine it with other
    steps on the same day.</feedback>
  </mapping>
</question>',
    ),

    'cq_hotspot' => array(
      'name' => 'Hotspot',
      'xml' => '<question type="hotspot">
  <text><p>Point out the correct two items.</p></text>
  <matchImg src="[attachurl:1]" maxChoices="2">
    <hotspot id="A" shape="circle" coords="275,68,68">Hotspot A.</hotspot>
    <hotspot id="B" shape="rect" coords="0,21,43,121"></hotspot>
    <hotspot id="C" shape="poly" coords="174,282,258,293,320,318,366,360,267,468,207,438,149,443,99,465,85,486,7,416,7,354,64,307">Hotspot C.</hotspot>
  </matchImg>
   <hint mintries="1">A first hint.</hint>
   <hint mintries="2">A second hint.</hint>
   <hint mintries="3">A third hint.</hint>
  <mapping correct="1">
    <match hotspot="A" />
    <match hotspot="B" />
    <feedback>Very Good, you pointed out the correct two items</feedback>
  </mapping>
  <mapping correct="0">
    <match hotspot="C" />
    <feedback>You have pointed out an incorrect item.</feedback>
  </mapping>
  <mapping correct="0">
    <not><match hotspot="A" /></not>
    <feedback>You have not pointed out the first correct item.</feedback>
  </mapping>
  <mapping correct="0">
    <not>
      <or>
        <match hotspot="A" />
        <match hotspot="B" />
        <match hotspot="C" />
      </or>
    </not>
    <feedback mintries="1">You have not selected any item.</feedback>
  </mapping>
</question>',
    ),

    'cq_drag_and_drop' => array(
      'name' => 'Drag & Drop',
      'xml' => '<question type="dragdrop">
  <text>Drag the items to the corresponding locations on the image.</text>
  <matchImg src="[attachurl:1]">
    <hotspot shape="rect" coords="2,91,119,124" identifier="h1" />
    <hotspot shape="circle" coords="6,196,123" identifier="h2" />
    <hotspot shape="poly" coords="6,268,6,301,153,301,123,208" identifier="h3" />
    <draggable identifier="d1">Draggable 1</draggable>
    <draggable identifier="d2">Draggable 2</draggable>
    <draggable identifier="d3">Draggable 3</draggable>
  </matchImg>
  <hint mintries="1">A first hint.</hint>
  <hint mintries="2">A second hint.</hint>
  <hint mintries="3">A third hint.</hint>
  <mapping correct="1">
    <combination hotspot="h1" draggable="d1" />
    <combination hotspot="h2" draggable="d2" />
    <combination hotspot="h3" draggable="d3" />
    <feedback>Correct. You have put the items at the right locations.</feedback>
  </mapping>
  <mapping correct="0">
    <combination hotspotpattern="h.*" draggablepattern="d[0-9]" />
    <feedback>You have at least one draggable on one hotspot.</feedback>
  </mapping>
  <mapping correct="0">
    <combination hotspot="h1" draggablepattern="d[0-9]" />
    <feedback>You have at least one draggable on hotspot 1.</feedback>
  </mapping>
  <mapping correct="0">
    <combination hotspotpattern="h.*" draggable="d1" />
    <feedback>You have draggable 1 on a hotspot.</feedback>
  </mapping>
</question>',
    ),

    'cq_fill_in_the_blanks_math' => array(
      'name' => 'Fill in the Blanks',
      'xml' => '<question type="fillblanks">
  <matheval expression="a=random()" store="1" />
  <matheval expression="b=random()" store="1" />
  <matheval expression="a=round(a*0.8+0.11,2)" />
  <matheval expression="b=round(b*5+2,0)" />
  <matheval expression="ans=a*b" />
  <matheval expression="min=ans-0.01" />
  <matheval expression="max=ans+0.01" />
  <text><p>You have measured a concentration of <mathresult expression="a" />mg/ml in a <mathresult expression="b" />-times diluted sample.<br/>
What was the concentration in the non-diluted solution?</p>
<p><b>The original concentration was: <inlineChoice identifier="c" freeform="1" />g/l</b></p></text>
  <hint mintries="1">A first hint.</hint>
  <hint mintries="2">A second hint.</hint>
  <hint mintries="3">A third hint.</hint>
  <mapping correct="1">
    <range inlineChoice="c" minval="min" maxval="max" />
    <feedback>Very good. The difference between your answer and the "real" answer is: <mathresult e="abs(ans-c)" /></feedback>
  </mapping>
  <mapping correct="0" stop="1">
    <not><match inlineChoice="c" pattern="^[+-]?[0-9]+([,.][0-9]+)?([eE][+-]?[0-9]+)?$" /></not>
    <feedback>Please give a number.</feedback>
  </mapping>
</question>',
    ),
  );
}
