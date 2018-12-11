<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

/**
 * Internal GUI class to wrap a single sequence input on editQuestion page
 *
 * Is used to get a cleaner assSQLQuestionGUI
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class sequencesInputGUI extends ilCustomInputGUI
{
  /**
	 * @var ilassSQLQuestionPlugin The plugin object
	 */
  var $plugin;

  /**
	* Constructor
	*
  * @param string $id The id of the sequence (e.g. "sequence_a")
  * @param string $content The content of the form (to prefill it)
	* @param ilassSQLQuestionPlugin $plugin The plugin object
	* @access public
	*/
  function __construct($id, $content, $plugin)
  {
    // Set plugin
    $this->plugin = $plugin;

    // Set Title and Html
    $this->setTitle($this->plugin->txt($id));
    $this->setHTML($this->createCodeEditor($id, $content));
  }

  /**
   * Helper function to generate a single code editor element
   *
   * @param string $name The name of the code editor element
   * @param string $value The default value of the field
   * @access private
   */
  private function createCodeEditor($name, $value)
  {
    $tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_edit_code.html');
    $tpl->setVariable("CONTENT", ilUtil::prepareFormOutput($value));
    $tpl->setVariable("NAME", $name);
    $tpl->setVariable("PAGEHANDLER", "handlerEditQuestion");
    $item = new ilCustomInputGUI('');

    return $tpl->get();
  }
}
?>
