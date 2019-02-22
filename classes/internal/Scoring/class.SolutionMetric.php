<?php
/**
 * A internal helper class to define a solid structure for the pattern solution
 * of a ScoringMetric
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class SolutionMetric
{
  /**
   * @var string The type of the ScoringMetric (e.g. "functional_dependency")
   */
  var $type = "";

  /**
   * @var integer The maximum points that are given to a candidate if his solution meets the metric
   */
  var $points = 0;

  /**
   * @var string The pattern solution value of the ScoringMetric
   */
  var $value = "";

  /**
   * Constructor
   *
   * The construtor of a single SolutionMetric
   *
   * @param string $type The type of the ScoringMetric (e.g. "functional_dependency")
   * @param integer $points The maximum points that are given to a candidate if his solution meets the metric
   * @param string $value The pattern solution value of the ScoringMetric
   * @access public
   */
  function __construct($type, $points, $value)
  {
    $this->type = $type;
    $this->points = $points;
    $this->value = $value;
  }

  /**
   * Serialize a SolutionMetric object to a JSON string
   *
   * @return string The JSON string
   */
  function toJSON()
  {
    // To use json_encode we need an array containing the values of the object
    $arr = array('type' => $this->type, 'points' => $this->points, 'value' => $this->value);

    return json_encode($arr);
  }

  /**
   * Get the type of the SolutionMetric
   *
   * @return string The type of the ScoringMetric
   */
  function getType()
  {
    return $this->type;
  }

  /**
   * Get the points of the SolutionMetric
   *
   * @return integer The points of the ScoringMetric
   */
  function getPoints()
  {
    return $this->points;
  }

  /**
   * Get the value of the SolutionMetric
   *
   * @return string The value of the ScoringMetric
   */
  function getValue()
  {
    return $this->value;
  }
}
?>
