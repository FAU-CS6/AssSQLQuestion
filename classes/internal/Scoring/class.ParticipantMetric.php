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
   * Serialize a ParticipantMetric object to a JSON string
   *
   * @return string The JSON string
   */
  function toJSON()
  {
    // To use json_encode we need an array containing the values of the object
    $arr = array('type' => $this->type, 'value' => $this->value);

    return json_encode($arr);
  }

  /**
   * Get the type of the ParticipantMetric
   *
   * @return string The type of the ScoringMetric
   */
  function getType()
  {
    return $this->type;
  }

  /**
   * Get the value of the ParticipantMetric
   *
   * @return string The value of the ScoringMetric
   */
  function getValue()
  {
    return $this->value;
  }
}
?>
