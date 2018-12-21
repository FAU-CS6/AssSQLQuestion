<?php
require_once __DIR__.'/../../interface.qpisql.GUIElement.php';

/**
 * Represents the sequence input GUIElement
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class SequenceX implements GUIElement
{
	/**
	 * @var ilassSQLQuestionPlugin The plugin object
	 */
	var $plugin = null;

	/**
	 * @var assSQLQuestion The question object
	 */
	var $object = null;

	/**
	 * @var string The header line of the Input field
	 */
	var $header = "";

	/**
	 * @var string The id of the textarea field
	 */
	var $id = "";

	/**
	 * @var string The name of the textarea field
	 */
	var $name = "";

  /**
  * Constructor
  *
  * @param ilassSQLQuestionPlugin $plugin The plugin object
  * @param assSQLQuestion $object The question object
	* @param string $header The header line of the Input field
	* @param string $id The id of the textarea field
	* @param string $name The name of the textarea field
  * @access public
  */
  public function __construct($plugin, $object, $header, $id, $name)
  {
    // Set plugin and object
    $this->plugin = $plugin;
    $this->object = $object;

		// Set the header, the id and the name
		$this->header = $header;
		$this->id = $id;
		$this->name = $name;
  }

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
		$tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_sequence_area_sequence_input.html');
    $tpl->setVariable("HEADER", $this->header);
    $tpl->setVariable("ID", $this->id);
    $tpl->setVariable("NAME", $this->name);
    $tpl->setVariable("CONTENT", $this->object->getSequence($this->name));
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
