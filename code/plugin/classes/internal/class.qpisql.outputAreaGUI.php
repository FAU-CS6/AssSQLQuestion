<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

/**
 * Internal GUI class to wrap the output area on editQuestion page
 *
 * Is used to get a cleaner assSQLQuestionGUI
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class outputAreaGUI extends ilCustomInputGUI
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

    // Set Title and Html
    $this->setTitle("");
    $this->setHTML($this->createOutputArea());
  }

  /**
   * Helper function to generate the output area html code
   *
   * @access private
   */
  private function createOutputArea()
  {
    $tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_output_area.html');
    $tpl->setVariable("NO_EXECUTION", $this->plugin->txt('no_execution'));
    $tpl->setVariable("EXECUTION_RUNNING", $this->plugin->txt('execution_running'));
    $tpl->setVariable("ERROR_DB_CREATION", $this->plugin->txt('error_db_creation'));
    $tpl->setVariable("ERROR_INTEGRITY_CHECK", $this->plugin->txt('error_integrity_check'));
    $tpl->setVariable("ERROR_NO_VISIBLE_RESULT", $this->plugin->txt('error_no_visible_result'));
    $tpl->setVariable("ERROR_RUNNING_SEQUENCE", $this->plugin->txt('error_running_sequence'));
    $item = new ilCustomInputGUI('');

    return $tpl->get();
  }
}
?>
