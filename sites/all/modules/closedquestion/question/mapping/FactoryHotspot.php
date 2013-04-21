<?php

/**
 * @file
 * Contains the Factory that creates the different hotspot objects from a
 * hotspot XML node
 */

/**
 * Factory method for creating hotspot classes from XML DOMElements
 *
 * @param DOMElement $node
 *   The XML element representing the hotspot.
 * @param CqQuestionInterface $context
 *   The question or other object that the mapping can query for things like the
 *   current answer, draggables and hotspots.
 *
 * @return CqHotspot
 *   The correct hotspot object.
 */
function cq_Hotspot_from_xml($node, $context) {
  $type = '';
  if ($node) {
    $type = $node->getAttribute('shape');
    $identifier = $node->getAttribute('identifier');
    if (empty($identifier)) {
      $identifier = $node->getAttribute('id');
    }
    $coords = $node->getAttribute('coords');
    $description = cq_get_text_content($node, $context);

    $hotspot = FALSE;
    switch (drupal_strtoupper($type)) {
      case 'RECT':
        $numbers = explode(',', $coords);
        $x1 = (int) $numbers[0];
        $y1 = (int) $numbers[1];
        $x2 = (int) $numbers[2];
        $y2 = (int) $numbers[3];
        $hotspot = new CqHotspotRect($identifier, $x1, $y1, $x2, $y2);
        $hotspot->setDescription($description);
        break;

      case 'CIRCLE':
        $numbers = explode(',', $coords);
        $x1 = (int) $numbers[0];
        $y1 = (int) $numbers[1];
        $radius = (int) $numbers[2];
        $hotspot = new CqHotspotCircle($identifier, $x1, $y1, $radius);
        $hotspot->setDescription($description);
        break;

      case 'POLY':
        $numbers = explode(',', $coords);
        $hotspot = new CqHotspotPoly($identifier, $numbers);
        $hotspot->setDescription($description);
        break;

      default:
        drupal_set_message(t('Unknown hotspot type: %type', array('%type' => $type)));
        break;
    }
  }
  return $hotspot;
}
