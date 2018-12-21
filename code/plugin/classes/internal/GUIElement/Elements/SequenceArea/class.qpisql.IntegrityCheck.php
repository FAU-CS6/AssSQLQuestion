<?php
require_once __DIR__.'/../../interface.qpisql.GUIElement.php';

/**
 * Represents the integrityCheck GUIElement
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class IntegrityCheck implements GUIElement
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
  * Constructor
  *
  * @param ilassSQLQuestionPlugin $plugin The plugin object
  * @param assSQLQuestion $object The question object
  * @access public
  */
  public function __construct($plugin, $object)
  {
    // Set plugin and object
    $this->plugin = $plugin;
    $this->object = $object;
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
		$tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_sequence_area_integrity_check_input.html');
    $tpl->setVariable("HEADER", $this->plugin->txt('integrity_check'));
    $tpl->setVariable("CHECKED", "");
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
		 $this->object->setIntegrityCheck(isset($_POST["integrity_check"]) && $_POST["integrity_check"] == "1");
   }
}
?>
