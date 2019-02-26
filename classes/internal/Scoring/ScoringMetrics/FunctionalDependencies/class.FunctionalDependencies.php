<?php
require_once __DIR__.'/../../class.ScoringMetric.php';

/**
 * Represents the FunctionalDependencies ScoringMetric
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class FunctionalDependencies extends ScoringMetric
{
    /**
     * @var string The type identifier of the scoring metric (e.g. "functional_dependency")
     */
    protected static $type = "functional_dependencies";

    /**
     * @var string The Javascript funtion to get the value of the sm out of a result
     */
    protected static $getter = "function(result) { return result.getAllMinimalFunctionalDependenciesAsJSON(); }";

    /**
     * Get the info text of for the edit page
     *
     * @return string The info text shown at the edit page
     * @access protected
     */
    protected static function getEditPageInfo($plugin)
    {
        return $plugin->txt('ai_sca_eo_sm_fd_info');
    }

    /**
     * Get the info text of for the solution page
     *
     * @return string The info text shown at the solution page
     * @access protected
     */
    protected static function getSolutionPageInfo($plugin)
    {
        return $plugin->txt('ai_sca_so_sm_fd_info');
    }

    /**
     * Calculate the reached points out of a metric
     *
     * @param SolutionMetric[] $solution_metrics The suiting solution metric array (with the pattern solution values)
     * @param ParticipantMetric[] $participant_metrics The participant metric array to be evaluated
     *
     * @return float The reached points
     *
     * @access public
     */
    public static function calculateReachedPoints($solution_metrics, $participant_metrics)
    {
        // Get the suiting solution and participant metric
        $solution_metric = static::getSolutionMetric($solution_metrics);
        $participant_metric = static::getParticipantMetric($participant_metrics);

        // Decode the JSONs
        $solution_metric_decoded = json_decode($solution_metric->getValue(), TRUE);
        $participant_metric_decoded = json_decode($participant_metric->getValue(), TRUE);

        // Compute the INTERSECT of both arrays
        $intersect = array_uintersect($solution_metric_decoded,$participant_metric_decoded,
                                      [__CLASS__, 'compareFunctionalDependencies']);

        // Compute the DIFF of both arrays as INTERSECT + DIFF = UNION
        $diff1 = array_udiff($solution_metric_decoded,$participant_metric_decoded,
                             [__CLASS__, 'compareFunctionalDependencies']);
        $diff2 = array_udiff($participant_metric_decoded,$solution_metric_decoded,
                             [__CLASS__, 'compareFunctionalDependencies']);
        $union = array_merge($intersect, $diff1, $diff2);

        // throw new Exception("SM ".(string)sizeof($participant_metric_decoded)." Union ".(string)sizeof($union)." Diff1 ".(string)sizeof($diff1)." Diff2 ".(string)sizeof($diff2)." Intersect ".(string)sizeof($intersect));

        // Compute 1 - Jaccard distance
        return (1 - ((sizeof($union) - sizeof($intersect))/sizeof($union))) * $solution_metric->getPoints();
    }

    /**
     * Compare function for functional dependencies - has to be 0 if both functional dependencies are equal
     *
     * @param array $a_json The first functional dependency as JSON string - should look like "{"determinateAttributes":[],"dependentAttributes":[]}"
     * @param array $b_json The second functional dependency as JSON string - should look like "{"determinateAttributes":[],"dependentAttributes":[]}"
     *
     * @return int 0 for both dependencies being the same - -1 for them being different
     *
     * @access public
     */
    public static function compareFunctionalDependencies($a_json, $b_json)
    {
      $a = json_decode($a_json, TRUE);
      $b = json_decode($b_json, TRUE);

      Log::warning("Test");

      // At first check whether both have the keys determinateAttributes and dependentAttributes
      if(!array_key_exists("determinateAttributes", $a) || !array_key_exists("dependentAttributes", $a) ||
         !array_key_exists("determinateAttributes", $b) || !array_key_exists("dependentAttributes", $b))
      {
        throw new Exception('Tried to compare non functional dependencies in the compareFunctionalDependencies() function');
      }

      // Compare the length of both the determinateAtrributes and the dependentAttributes
      if(sizeof($a["determinateAttributes"]) != sizeof($b["determinateAttributes"]) ||
         sizeof($a["dependentAttributes"]) != sizeof($b["dependentAttributes"]))
      {
        return -1;
      }

      // Save the values in seperate arrays for easier handling
      $a_determinate = $a["determinateAttributes"];
      $a_dependent = $a["dependentAttributes"];
      $b_determinate = $b["determinateAttributes"];
      $b_dependent = $b["dependentAttributes"];

      // Sort the arrays
      sort($a_determinate);
      sort($a_dependent);
      sort($b_determinate);
      sort($b_dependent);

      // Compare the determinate attributes
      for($i = 0; $i < sizeof($a_determinate); $i++)
      {
        if($a_determinate[$i] != $b_determinate[$i])
        {
          return -1;
        }
      }

      // Compare the determinate attributes
      for($i = 0; $i < sizeof($a_dependent); $i++)
      {
        if($a_dependent[$i] != $b_dependent[$i])
        {
          return -1;
        }
      }

      // Both functional dependencies are equal
      return 0;
    }
}
