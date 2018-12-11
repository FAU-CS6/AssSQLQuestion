<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

/**
 * Internal GUI class to wrap the sequences info on editQuestion page
 * 
 * Is necessary to override checkInput
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class sequencesInfoGUI extends ilCustomInputGUI
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
    $this->setInfo($this->plugin->txt('sequences_info_text'));
    $this->setRequired(true);
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
      $this->setAlert($this->plugin->txt('sequences_info_error'));
      return false;
    }

    return true;
  }
}
?>
