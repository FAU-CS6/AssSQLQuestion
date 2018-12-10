<?php
/**
 * A internal helper class to define a solid structure for scoring metrics
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class scoringMetric
{
  /**
   * @var string The id of the scoring metric (e.g. "functional_dependency_a->b") - max length is 256
   */
  var $id = "";

  /**
   * @var string The type of the scoring metric (e.g. "functional_dependency")
   */
  var $type = "";

  /**
   * @var integer The points that are given to a candidate if his solution meets the metric
   */
  var $points = 0;

  /**
   * @var string The value a candidates solution is compared to
   */
  var $value = "";

  /**
   * Constructor
   *
   * The construtor of a single scoringMetric
   *
   * @param string $id The id of the scoring metric (e.g. "functional_dependency_a->b") - max length is 256
   * @param string $type The type of the scoring metric (e.g. "functional_dependency")
   * @param integer $points The points that are given to a candidate if his solution meets the metric
   * @param string $value The value a candidates solution is compared to
   * @access public
   */
  function __construct($id, $type, $points, $value)
  {
    if(strlen($id) > 256)
    {
      throw new LengthException('$id is not allowed to be longer than 256 in scoringMetric');
    }

    $this->id = $id;
    $this->type = $type;
    $this->points = $points;
    $this->value = $value;
  }

  /**
   * Get the id of the scoring metric
   *
   * @return string The id of the scoring metric
   */
  function getId()
  {
    return $this->id;
  }

  /**
   * Get the type of the scoring metric
   *
   * @return string The type of the scoring metric
   */
  function getType()
  {
    return $this->type;
  }

  /**
   * Get the points of the scoring metric
   *
   * @return integer The points of the scoring metric
   */
  function getPoints()
  {
    return $this->points;
  }

  /**
   * Get the value of the scoring metric
   *
   * @return string The value of the scoring metric
   */
  function getValue()
  {
    return $this->value;
  }
}
?>
