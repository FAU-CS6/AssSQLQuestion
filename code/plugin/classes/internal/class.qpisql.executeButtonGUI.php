<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

/**
 * Internal GUI class to wrap the execute Button on editQuestion page
 *
 * Is used to get a cleaner assSQLQuestionGUI
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class executeButtonGUI extends ilCustomInputGUI
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
    $this->setHTML(
      '<input type="button" class="btn-default btn-sm btn" id="il_as_qpl_qpisql_execution_button" value="Execute" onclick="handlerEditQuestion.execute()">'
    );
  }
}
?>
