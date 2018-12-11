<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

/**
 * Internal GUI class to wrap the scoring info on editQuestion page
 *
 * Is necessary to override checkInput
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class scoringInfoGUI extends ilCustomInputGUI
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
    $this->setTitle($this->plugin->txt('scoring_info'));
    $this->setInfo($this->plugin->txt('scoring_info_text'));
    $this->setRequired(true);
  }

  /**
   * Checks the input and sets an alert if it is not ok
   *
   * (This is an override of the ilCustomInputGUI:checkInput() to be tailored
   * for the scoring input area of editQuestion)
   *
   * @return boolean True if input is ok, False if it is not
   */
  function checkInput()
  {
    if(isset($_POST["points_result_lines"]) && ((integer) $_POST["points_result_lines"]) == 0)
    {
      $this->setAlert($this->plugin->txt('scoring_info_error'));
      return false;
    }

    return true;
  }
}
?>
