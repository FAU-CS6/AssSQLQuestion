<?php
require_once __DIR__.'/../../class.GUIElement.php';

/**
 * Represents the actual output element
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class Output extends GUIElement
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
		$tpl = $this->plugin->getTemplate('OutputArea/tpl.il_as_qpl_qpisql_oa_output.html');
		$tpl->setVariable("ERROR_BOOL", $this->object->getErrorBool());
    $tpl->setVariable("EXECUTED_BOOL", $this->object->getExecutedBool());
    $tpl->setVariable("OUTPUT_RELATION", $this->object->getOutputRelation());
    $tpl->setVariable("STATUS_EXECUTION_RUNNING", $this->plugin->txt('ai_oa_st_run'));
    $tpl->setVariable("ERROR_NO_EXECUTION", $this->plugin->txt('ai_oa_er_no_exec'));
    $tpl->setVariable("ERROR_DB_CREATION", $this->plugin->txt('ai_oa_er_db_crea'));
    $tpl->setVariable("ERROR_INTEGRITY_CHECK", $this->plugin->txt('ai_oa_er_int_che'));
    $tpl->setVariable("ERROR_NO_VISIBLE_RESULT", $this->plugin->txt('ai_oa_er_no_vis'));
    $tpl->setVariable("ERROR_RUNNING_SEQUENCE", $this->plugin->txt('ai_oa_er_run_seq'));
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
    $tpl = $this->plugin->getTemplate('OutputArea/tpl.il_as_qpl_qpisql_oa_output.html');
		$tpl->setVariable("ERROR_BOOL", "");
    $tpl->setVariable("EXECUTED_BOOL", "");
    $tpl->setVariable("OUTPUT_RELATION", "");
    $tpl->setVariable("STATUS_EXECUTION_RUNNING", $this->plugin->txt('ai_oa_st_run'));
    $tpl->setVariable("ERROR_NO_EXECUTION", $this->plugin->txt('ai_oa_er_no_exec'));
    $tpl->setVariable("ERROR_DB_CREATION", $this->plugin->txt('ai_oa_er_db_crea'));
    $tpl->setVariable("ERROR_INTEGRITY_CHECK", $this->plugin->txt('ai_oa_er_int_che'));
    $tpl->setVariable("ERROR_NO_VISIBLE_RESULT", $this->plugin->txt('ai_oa_er_no_vis'));
    $tpl->setVariable("ERROR_RUNNING_SEQUENCE", $this->plugin->txt('ai_oa_er_run_seq'));
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
     $this->object->setErrorBool($_POST["error_bool"] == "true" ? true : false);
     $this->object->setExecutedBool($_POST["executed_bool"] == "true" ? true : false);
     $this->object->setOutputRelation((string) $_POST["output_relation"]);
   }
}
?>
