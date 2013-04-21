<?php

/**
 * @file
 * A polygon shaped hotspot formed by a series of points.
 */
class CqHotspotPoly implements CqHotspotInterface {

  /**
   * The identifier of the hotspot.
   *
   * @var string
   */
  private $identifier = 'NoName';
  /**
   * The array of x-coordinates of the points in the polygon.
   *
   * @var array of number
   */
  private $pointsX;
  /**
   * The array of y-coordinates of the points in the polygon.
   *
   * @var array of number
   */
  private $pointsY;
  /**
   * The coordinates of the polygon as a string, to be used in an html
   * image-map.
   *
   * @var string
   */
  private $pointsString = '';
  /**
   * The description for this hotspot.
   *
   * @var string
   */
  private $description;

  /**
   * Creates a new CqHotspotPoly
   *
   * @param string $identifier
   *   The identifier to use for this hotspot.
   * @param array $numbers
   *   An array of x and y coordinates in the form of x1,y1,x2,y2,x3,y3,
   */
  public function __construct($identifier, $numbers) {
    $this->identifier = $identifier;
    $count = count($numbers);
    $pointCount = 0;
    $this->pointsX = Array();
    $this->pointsY = Array();

    if (($count % 2) != 0) {
      drupal_set_message(t('HotSpotPoly: strange number count: @c', array('@c' => $count)));
      $count--;
    }
    $pointCount = $count / 2;
    $allPoints = array();
    for ($teller = 0; $teller < $pointCount; $teller++) {
      $x = $numbers[$teller * 2];
      $y = $numbers[$teller * 2 + 1];
      $this->pointsX[] = $x;
      $this->pointsY[] = $y;
      $allPoints[] = $x;
      $allPoints[] = $y;
    }
    $this->pointsString = implode(',', $allPoints);
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
    $inside = FALSE;
    $npol = count($this->pointsX);
    $j = $npol - 1;
    for ($i = 0; $i < $npol; $j = $i++) {
      if (( ($this->pointsY[$i] <= $y) && ($this->pointsY[$j] > $y) ) ||
          ( ($this->pointsY[$j] <= $y) && ($this->pointsY[$i] > $y) )) {
        // possible intercept
        $dx = $this->pointsX[$j] - $this->pointsX[$i];
        $dy = $this->pointsY[$j] - $this->pointsY[$i];
        $py = $this->pointsY[$j] - $y;
        $px = $dx * $py / $dy;
        $interceptX = $this->pointsX[$j] - $px;
        if ($x < $interceptX) {
          $inside = !$inside;
        }
      }
    }
    return $inside;
  }

  /**
   * Implements CqHotspotInterface::getMapHtml()
   */
  public function getMapHtml() {
    return 'shape="poly" coords="' . $this->pointsString . '"';
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
