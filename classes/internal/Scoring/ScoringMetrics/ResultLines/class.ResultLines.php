<?php
require_once __DIR__.'/../../class.ScoringMetric.php';

/**
 * Represents the ResultLines ScoringMetric
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class ResultLines extends ScoringMetric
{
  /**
   * @var string The type identifier of the scoring metric (e.g. "functional_dependency")
   */
  protected static $type = "result_lines";

  /**
	 * @var string The Javascript funtion to get the value of the sm out of a result
	 */
	protected static $getter = "function(result) { return result.getNumberOfRows(); }";

  /**
   * Get the info text of for the edit page
   *
   * @return string The info text shown at the edit page
   * @access protected
   */
  protected static function getEditPageInfo($plugin)
  {
    return $plugin->txt('ai_sca_eo_sm_rl_info');
  }

  /**
   * Get the info text of for the solution page
   *
   * @return string The info text shown at the solution page
   * @access protected
   */
  protected static function getSolutionPageInfo($plugin)
  {
    return $plugin->txt('ai_sca_so_sm_rl_info');
  }

  /**
   * Calculate the reached points out of a metric
   *
   * @param SolutionMetric[] $solution_metrics The suiting solution metric array (with the pattern solution values)
   * @param ParticipantMetric[] $participant_metrics The participant metric array to be evaluated
   * @return float The reached points
   * @access protected
   */
  public static function calculateReachedPoints($solution_metrics, $participant_metrics)
  {
    // Get the suiting solution and participant metric
    $solution_metric = static::getSolutionMetric($solution_metrics);
    $participant_metric = static::getParticipantMetric($participant_metrics);

    // In this Scoring metric the participant metrics value has to be exactly the value of the solution
    // metric for the participant to receive any points
    if($solution_metric->getValue() == $participant_metric->getValue())
    {
      return $solution_metric->getPoints();
    }

    return 0;
  }
}
?>
