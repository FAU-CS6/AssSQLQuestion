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
}
?>
