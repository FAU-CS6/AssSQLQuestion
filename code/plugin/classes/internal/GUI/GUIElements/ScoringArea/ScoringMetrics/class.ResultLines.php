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
    $tpl = $this->plugin->getTemplate('ScoringArea/ScoringMetrics/ResultLines/tpl.il_as_qpl_qpisql_sca_sm_rl_points_input.html');
    $tpl->setVariable("INFO", $this->plugin->txt('ai_sca_eo_sm_rl_info'));
    $tpl->setVariable("POINTS", $this->plugin->txt('ai_sca_points'));
    $tpl->setVariable("WARNING_NO_EXECUTION", $this->plugin->txt('ai_sca_eo_no_exec'));
    $tpl->setVariable("OUTPUT_TEXT", $this->plugin->txt('ai_sca_eo_out_text'));
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
    $tpl = $this->plugin->getTemplate('ScoringArea/ScoringMetrics/ResultLines/tpl.il_as_qpl_qpisql_sca_sm_rl_hidden.html');
    $tpl->setVariable("VALUE", "false");
    return $tpl->get();
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
