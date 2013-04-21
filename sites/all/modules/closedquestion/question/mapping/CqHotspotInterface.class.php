<?php

/**
 * @file
 * The interface CqHotspotInterface.
 * Hotspots are area's in an image that have a certain behaviour. They can be
 * part of the correct answer, or have specific feedback.
 */
interface CqHotspotInterface {

  /**
   * Getter for the identifier of the hotspot.
   *
   * @return string
   *   The identifier of the hotspot.
   */
  public function getIdentifier();

  /**
   * Check if the point given by $location is inside the hotspot
   *
   * @param array $location
   *   An array($x, $y) containing the x and y coordinates of the location to
   *   check.
   *
   * @return boolean
   *   TRUE if $location is inside the hotspot, FALSE otherwise.
   */
  public function doMatch($location);

  /**
   * Return the image-map html for this hotspot.
   *
   * @return string
   *   The html to use in an html image-map
   */
  public function getMapHtml();

  /**
   * Returns the description for this hotspot. Can for instance be shown as
   * a mouse-over for the hotspot.
   *
   * @return string
   *   The html description.
   */
  public function getDescription();

  /**
   * Sets the description for this hotspot. Can for instance be shown as
   * a mouse-over for the hotspot.
   *
   * @param string $description
   *   The description for this hotspot.
   */
  public function setDescription($description);
}
