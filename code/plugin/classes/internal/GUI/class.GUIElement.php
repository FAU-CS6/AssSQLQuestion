<?php
/**
 * Represents an abstract GUIArea implemented by the different GUIElements of assSQLQuestionGUI.
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
abstract class GUIElement
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

  /**
   * Output functions
   */

  /**
   * Returns the html output of the GUI element tailored for the edit page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  abstract public function getEditOutput();

  /**
   * Returns the html output of the GUI element tailored for the question output page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  abstract public function getQuestionOutput();

  /**
   * Returns the html output of the GUI element tailored for the solution output page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  abstract public function getSolutionOutput();

	/**
   * Functions to handle POST data
   */

   /**
    * Writes the POST data of the edit page into the $object
		*
		* @access public
    */
   abstract public function writePostData();
}
?>
