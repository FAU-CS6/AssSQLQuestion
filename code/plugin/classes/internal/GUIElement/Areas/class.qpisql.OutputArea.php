<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

require_once __DIR__.'/../interface.qpisql.GUIElement.php';
require_once __DIR__.'/../Elements/OutputArea/class.qpisql.OutputInfo.php';
require_once __DIR__.'/../Elements/OutputArea/class.qpisql.Output.php';

/**
 * Represents the output area used in assSQLQuestionGUI
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class OutputArea extends ilCustomInputGUI implements GUIElement
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
	 * @var OutputInfo The info text subelement
	 */
	var $infoText = null;

	/**
	 * @var Output The actual output area subelement
	 */
	var $outputArea = null;


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
    $this->$object = $object;

		// Set the subelements

		// Info area
		$this->infoText = new OutputInfo(
			$plugin // Plugin
		);

		// Output area
		$this->outputArea = new Output(
			$plugin, // Plugin
			$object // Object
		);

		// Set Title, Information and Required
    $this->setTitle($this->plugin->txt('output_info'));
    $this->setRequired(true);
    $this->setHtml($this->getEditOutput());
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
		$html = "";

		$html .= $this->infoText->getEditOutput();
		$html .= $this->outputArea->getEditOutput();

		return $html;
  }

  /**
   * Returns the html output of the GUI element tailored for the question output page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getQuestionOutput()
  {
		$html = "";

		$html .= $this->infoText->getQuestionOutput();
		$html .= $this->outputArea->getQuestionOutput();

		return $html;
  }

  /**
   * Returns the html output of the GUI element tailored for the solution output page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getSolutionOutput()
  {
		$html = "";

		$html .= $this->infoText->getSolutionOutput();
		$html .= $this->outputArea->getSolutionOutput();

		return $html;
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
		 $this->infoText->writePostData();
	 	 $this->outputArea->writePostData();
   }

	 /*
	 	* Functions originaly implemented in ilCustomInputGUI that need to be overwritten
    */

   /**
 		* Checks the input of the edit page
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
}
?>
