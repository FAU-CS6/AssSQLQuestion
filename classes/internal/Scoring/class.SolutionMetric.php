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
    public $type = "";

    /**
     * @var integer The maximum points that are given to a candidate if his solution meets the metric
     */
    public $points = 0;

    /**
     * @var string The pattern solution value of the ScoringMetric
     */
    public $value = "";

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
    public function __construct($type, $points, $value)
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
    public function toJSON()
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the points of the SolutionMetric
     *
     * @return integer The points of the ScoringMetric
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Get the value of the SolutionMetric
     *
     * @return string The value of the ScoringMetric
     */
    public function getValue()
    {
        return $this->value;
    }
}
