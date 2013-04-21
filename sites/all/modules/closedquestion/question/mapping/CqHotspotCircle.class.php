<?php


/**
 * @file
 * Implements a circle-shaped hotspot formed by a centre point and a radius.
 */
class CqHotspotCircle implements CqHotspotInterface {

  /**
   * The identifier of the hotspot.
   *
   * @var string
   */
  private $identifier = 'NoName';
  /**
   * The x-coordinate of the center of the hotspot.
   *
   * @var number
   */
  private $x = 0;
  /**
   * The y-coordinate of the center of the hotspot.
   *
   * @var number
   */
  private $y = 0;
  /**
   * The radius of the hotspot.
   *
   * @var number
   */
  private $radius = 0;
  /**
   * The square of the radius, so we don't need to re-calculate this or the root
   * when calculating a distance from the center.
   *
   * @var number
   */
  private $radius2 = 0;
  /**
   * The description for this hotspot.
   *
   * @var string
   */
  private $description;

  /**
   * Creates a new CqHotspotCircle
   *
   * @param string $identifier
   *   The identifier to use for this hotspot.
   * @param number $x
   *   The x-coordinate of the center of the hotspot.
   * @param number $y
   *   The y-coordinate of the center of the hotspot.
   * @param number $radius
   *   The radius of the hotpot.
   */
  public function __construct($identifier, $x, $y, $radius) {
    $this->identifier = $identifier;
    $this->x = $x;
    $this->y = $y;
    $this->radius = $radius;
    $this->radius2 = $radius * $radius;
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
    $dist2 = pow($this->x - $x, 2) + pow($this->y - $y, 2);
    return ($dist2 < $this->radius2);
  }

  /**
   * Implements CqHotspotInterface::getMapHtml()
   */
  public function getMapHtml() {
    return 'shape="circle" coords="' . $this->x . ',' . $this->y . ',' . $this->radius . '"';
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

