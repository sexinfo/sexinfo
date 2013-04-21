<?php

/**
 * @file
 * A rectangle-shaped hotspot formed by two opposite corner points.
 */
class CqHotspotRect implements CqHotspotInterface {

  /**
   * The identifier of the hotspot.
   *
   * @var string
   */
  private $identifier = 'NoName';
  /**
   * The x-coordinate of the first corner point.
   *
   * @var number
   */
  private $x1 = 0;
  /**
   * The y-coordinate of the first corner point.
   *
   * @var number
   */
  private $y1 = 0;
  /**
   * The x-coordinate of the second corner point.
   *
   * @var number
   */
  private $x2 = 0;
  /**
   * The y-coordinate of the second corner point.
   *
   * @var number
   */
  private $y2 = 0;
  /**
   * The description for this hotspot.
   *
   * @var string
   */
  private $description;

  /**
   * Creates a new CqHotspotRect
   *
   * @param string $identifier
   *   The identifier to use for this hotspot.
   * @param number $x1
   *   The x-coordinate for the first corner point.
   * @param number $y1
   *   The y-coordinate for the first corner point.
   * @param number $x2
   *   The x-coordinate for the second corner point.
   * @param number $y2
   *   The y-coordinate for the second corner point.
   */
  public function __construct($identifier, $x1, $y1, $x2, $y2) {
    $this->identifier = $identifier;
    if ($x1 > $x2) {
      $this->x1 = $x2;
      $this->x2 = $x1;
    }
    else {
      $this->x1 = $x1;
      $this->x2 = $x2;
    }
    if ($y1 > $y2) {
      $this->y1 = $y2;
      $this->y2 = $y1;
    }
    else {
      $this->y1 = $y1;
      $this->y2 = $y2;
    }
  }

  /**
   * Implements CqHotspotInterface::getIdentifier()
   */
  public function getIdentifier() {
    return $this->identifier;
  }

  /**
   * Implements CqHotspotInterface::doMatch()
   */
  public function doMatch($location) {
    $x = $location[0];
    $y = $location[1];
    return ($x >= $this->x1 && $x <= $this->x2 && $y >= $this->y1 && $y <= $this->y2);
  }

  /**
   * Implements CqHotspotInterface::getMapHtml()
   */
  public function getMapHtml() {
    return 'shape="rect" coords="' . $this->x1 . ',' . $this->y1 . ',' . $this->x2 . ',' . $this->y2 . '"';
  }

  /**
   * Implements CqHotspotInterface::getDescription()
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Implements CqHotspotInterface::setDescription()
   */
  public function setDescription($description) {
    $this->description = $description;
  }

}
