<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

/**
 * Internal GUI class to wrap the sequence area of the editQuestion page
 *
 * Is necessary to override checkInput
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class editQuestionSequenceArea extends ilCustomInputGUI
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
    $this->setTitle($this->plugin->txt('sequences_info'));
    $this->setRequired(true);
    $this->setHtml($this->createHTMLCode());
  }

  /**
   * Checks the input and sets an alert if it is not ok
   *
   * (This is an override of the ilCustomInputGUI:checkInput() to be tailored
   * for the sequences input area of editQuestion)
   *
   * @return boolean True if input is ok, False if it is not
   */
  function checkInput()
  {
    if(isset($_POST["sequence_b"]) && $_POST["sequence_b"] == "")
    {
      // $this->setAlert($this->plugin->txt('sequences_info_error'));
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
    $tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_edit_question_sequence_area.html');
    $tpl->setVariable("INFO_TEXT", $this->plugin->txt('sequences_info_text'));
    $tpl->setVariable("SEQUENCE_A", $this->plugin->txt('sequence_a'));
    $tpl->setVariable("CONTENT_SEQUENCE_A", "");
    $tpl->setVariable("SEQUENCE_B", $this->plugin->txt('sequence_b'));
    $tpl->setVariable("CONTENT_SEQUENCE_B", "");
    $tpl->setVariable("SEQUENCE_C", $this->plugin->txt('sequence_c'));
    $tpl->setVariable("CONTENT_SEQUENCE_C", "");
    $tpl->setVariable("INTEGRITY_CHECK", $this->plugin->txt('integrity_check'));
    $tpl->setVariable("PAGEHANDLER", "handlerEditQuestion");
    return $tpl->get();
  }
}
?>
