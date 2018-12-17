<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

/**
 * Internal GUI class to wrap the output area on editQuestion page
 *
 * Is necessary to override checkInput
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class editQuestionOutputArea extends ilCustomInputGUI
{
  /**
	 * @var ilassSQLQuestionPlugin The plugin object
	 */
  var $plugin;

  /**
	* Constructor
	*
	* @param ilassSQLQuestionPlugin $plugin The plugin object
	* @access public
	*/
  function __construct($plugin)
  {
    // Set plugin
    $this->plugin = $plugin;

    // Set Title, Information and Required
    $this->setTitle($this->plugin->txt('output_info'));
    $this->setRequired(true);
    $this->setHTML($this->createHTMLCode());
  }

  /**
   * Checks the input and sets an alert if it is not ok
   *
   * (This is an override of the ilCustomInputGUI:checkInput() to be tailored
   * for the output area of editQuestion)
   *
   * @return boolean True if input is ok, False if it is not
   */
  function checkInput()
  {
    if($_POST["executed_bool"] == "false" || $_POST["error_bool"] == "true")
    {
      // $this->setAlert($this->plugin->txt('output_info_error'));
      return false;
    }

    return true;
  }

  /**
   * Helper function to generate the html code out of the corresponding template
   *
   * @access private
   */
  private function createHTMLCode()
  {
    $tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_edit_question_output_area.html');
    $tpl->setVariable("INFO_TEXT", $this->plugin->txt('output_info_text'));
    $tpl->setVariable("ERROR_BOOL", "");
    $tpl->setVariable("EXECUTED_BOOL", "");
    $tpl->setVariable("OUTPUT_RELATION", "");
    $tpl->setVariable("STATUS_EXECUTION_RUNNING", $this->plugin->txt('status_execution_running'));
    $tpl->setVariable("ERROR_NO_EXECUTION", $this->plugin->txt('error_no_execution'));
    $tpl->setVariable("ERROR_DB_CREATION", $this->plugin->txt('error_db_creation'));
    $tpl->setVariable("ERROR_INTEGRITY_CHECK", $this->plugin->txt('error_integrity_check'));
    $tpl->setVariable("ERROR_NO_VISIBLE_RESULT", $this->plugin->txt('error_no_visible_result'));
    $tpl->setVariable("ERROR_RUNNING_SEQUENCE", $this->plugin->txt('error_running_sequence'));
    $tpl->setVariable("PAGEHANDLER", "handlerEditQuestion");
    return $tpl->get();
  }
}
?>
