<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

/**
 * Internal GUI class to wrap the integrity check input on editQuestion page
 *
 * Is used to get a cleaner assSQLQuestionGUI
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class integrityCheckGUI extends ilCustomInputGUI
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
    $this->setTitle($this->plugin->txt('integrity_check'));
    $this->setHTML(
      '<input type="checkbox" id="integrity_check" name="integrity_check" value="1" onclick="handlerEditQuestion.deleteOldOutputs()">'
    );
  }
}
?>
