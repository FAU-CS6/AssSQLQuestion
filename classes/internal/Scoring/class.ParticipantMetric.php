<?php
/**
 * A internal helper class to define a solid structure for the participants solution
 * of a ScoringMetric
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class ParticipantMetric
{
  /**
   * @var string The type of the ScoringMetric (e.g. "functional_dependency")
   */
  var $type = "";

  /**
   * @var string The participants value of the ScoringMetric
   */
  var $value = "";

  /**
   * Constructor
   *
   * The construtor of a single ParticipantMetric
   *
   * @param string $type The type of the ScoringMetric (e.g. "functional_dependency")
   * @param string $value The pattern solution value of the ScoringMetric
   * @access public
   */
  function __construct($type, $value)
  {
    $this->type = $type;
    $this->value = $value;
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
