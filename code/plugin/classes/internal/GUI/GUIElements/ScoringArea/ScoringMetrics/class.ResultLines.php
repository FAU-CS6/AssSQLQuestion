<?php
require_once __DIR__.'/../../../../class.ScoringMetric.php';
require_once __DIR__.'/../../../class.GUIElement.php';

/**
 * Represents the actual output element
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class ResultLines extends GUIElement
{
  /*
   * Functions used to get the html code for edit, question and solution output
   */

  /**
   * Returns the html output of the GUI element tailored for the edit page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getEditOutput()
  {
    $tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_scoring_area_scoring_metric_result_lines.html');
    $tpl->setVariable("INFO_TEXT", $this->plugin->txt('scoring_info_text'));
    $tpl->setVariable("POINTS", $this->plugin->txt('points'));
    $tpl->setVariable("WARNING_NO_EXECUTION", $this->plugin->txt('warning_no_execution'));
    $tpl->setVariable("OUTPUT_TEXT", $this->plugin->txt('scoring_metric_output_text'));
    $tpl->setVariable("INFO_TEXT_RESULT_LINES", $this->plugin->txt('scoring_metric_result_lines_info'));
    return $tpl->get();
  }

  /**
   * Returns the html output of the GUI element tailored for the question output page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getQuestionOutput()
  {
    return "";
  }

  /**
   * Returns the html output of the GUI element tailored for the solution output page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getSolutionOutput()
  {
    return "";
  }

  /*
   * Functions used to write POST data to the $object
   */

   /**
    * Writes the POST data of the edit page into the $object
		*
		* @access public
    */
   public function writePostData()
   {
     $this->object->setSingleScoringMetric(
       new ScoringMetric("result_lines", // id
                         "result_lines", // type
                         (integer) $_POST["points_result_lines"], // points
                         (string) $_POST["value_result_lines"]) //value
     );
   }
}
?>
