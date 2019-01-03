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
		$tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_output_area_output.html');
		$tpl->setVariable("ERROR_BOOL", $this->object->getErrorBool());
    $tpl->setVariable("EXECUTED_BOOL", $this->object->getExecutedBool());
    $tpl->setVariable("OUTPUT_RELATION", $this->object->getOutputRelation());
    $tpl->setVariable("STATUS_EXECUTION_RUNNING", $this->plugin->txt('status_execution_running'));
    $tpl->setVariable("ERROR_NO_EXECUTION", $this->plugin->txt('error_no_execution'));
    $tpl->setVariable("ERROR_DB_CREATION", $this->plugin->txt('error_db_creation'));
    $tpl->setVariable("ERROR_INTEGRITY_CHECK", $this->plugin->txt('error_integrity_check'));
    $tpl->setVariable("ERROR_NO_VISIBLE_RESULT", $this->plugin->txt('error_no_visible_result'));
    $tpl->setVariable("ERROR_RUNNING_SEQUENCE", $this->plugin->txt('error_running_sequence'));
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
		 $this->object->setSequence($this->name, (string) $_POST[$this->name]);
   }
}
?>
