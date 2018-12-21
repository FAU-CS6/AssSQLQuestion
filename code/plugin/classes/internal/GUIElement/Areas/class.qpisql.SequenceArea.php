<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

require_once __DIR__.'/../interface.qpisql.GUIElement.php';
require_once __DIR__.'/../Elements/SequenceArea/class.qpisql.SequenceInfo.php';
require_once __DIR__.'/../Elements/SequenceArea/class.qpisql.SequenceX.php';
require_once __DIR__.'/../Elements/SequenceArea/class.qpisql.IntegrityCheck.php';
require_once __DIR__.'/../Elements/SequenceArea/class.qpisql.ExecuteButton.php';

/**
 * Represents the sequence area used in assSQLQuestionGUI
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class SequenceArea extends ilCustomInputGUI implements GUIElement
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
	 * @var SequenceInfo The info text subelement
	 */
	var $infoText = null;

	/**
	 * @var SequenceX The sequence a subelement
	 */
	var $sequenceA = null;

	/**
	 * @var SequenceX The sequence b subelement
	 */
	var $sequenceB = null;

	/**
	 * @var SequenceX The sequence c subelement
	 */
	var $sequenceC = null;

	/**
	 * @var IntegrityCheck The integrity check subelement
	 */
	var $integrityCheck = null;

	/**
	 * @var ExecuteButton The execute button subelement
	 */
	var $executeButton = null;

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
		$this->infoText = new SequenceInfo(
			$plugin // Plugin
		);

		// Sequence A
		$this->sequenceA = new SequenceX(
			$plugin, // Plugin
			$object, // Object
			$this->plugin->txt('sequence_a'), // Header
			'sequence_a', // Id
			'sequence_a' // Name
		);

		// Sequence B
		$this->sequenceB = new SequenceX(
			$plugin, // Plugin
			$object, // Object
			$this->plugin->txt('sequence_b'), // Header
			'sequence_b', // Id
			'sequence_b' // Name
		);

		// Sequence C
		$this->sequenceC =  new SequenceX(
			$plugin, // Plugin
			$object, // Object
			$this->plugin->txt('sequence_c'), // Header
			'sequence_c', // Id
			'sequence_c' // Name
		);

		// Integrity check
		$this->integrityCheck = new IntegrityCheck(
			$plugin, // Plugin
			$object // Object
		);

		// Execute Button
		$this->executeButton = new ExecuteButton(
			$plugin // Plugin
		);

		// Set Title, Information and Required
    $this->setTitle($this->plugin->txt('sequences_info'));
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
		$html .= $this->sequenceA->getEditOutput();
		$html .= $this->sequenceB->getEditOutput();
		$html .= $this->sequenceC->getEditOutput();
		$html .= $this->integrityCheck->getEditOutput();
		$html .= $this->executeButton->getEditOutput();

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
		$html .= $this->sequenceA->getQuestionOutput();
		$html .= $this->sequenceB->getQuestionOutput();
		$html .= $this->sequenceC->getQuestionOutput();
		$html .= $this->integrityCheck->getQuestionOutput();
		$html .= $this->executeButton->getQuestionOutput();

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
		$html .= $this->sequenceA->getSolutionOutput();
		$html .= $this->sequenceB->getSolutionOutput();
		$html .= $this->sequenceC->getSolutionOutput();
		$html .= $this->integrityCheck->getSolutionOutput();
		$html .= $this->executeButton->getSolutionOutput();

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
	 	 $this->sequenceA->writePostData();
	 	 $this->sequenceB->writePostData();
	 	 $this->sequenceC->writePostData();
	 	 $this->integrityCheck->writePostData();
	 	 $this->executeButton->writePostData();
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
