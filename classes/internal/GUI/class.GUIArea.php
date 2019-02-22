<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

/**
 * Represents an abstract GUIArea implemented by the different GUIAreas of assSQLQuestionGUI.
 *
 * This class is based on the idea that the  edit, question and solution page are using
 * the all the same areas.
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
abstract class GUIArea extends ilCustomInputGUI
{
  /**
	 * @var ilassSQLQuestionPlugin The plugin object
	 */
	protected $plugin = null;

	/**
	 * @var assSQLQuestion The question object
	 */
	protected $object = null;

	/**
	 * @var GUIElement[] The subelements used in the GUIArea
	 */
	private $subelements = array();

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
   * Setter for the subelements
   */

   /**
    * Setter for adding a subelement
    *
    * @param GUIElement $subelement The subelement to be added
    * @access protected
    */
   protected function addSubElement($subelement)
   {
     array_push($this->subelements, $subelement);
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
  public function getEditOutput()
  {
		$html = "";

    foreach ($this->subelements as $subelement)
    {
      $html .= $subelement->getEditOutput();
    }

		return $html;
  }

  /**
   * Returns the html output of the GUI element tailored for the question output page
   *
	 * @param ParticipantInput $participant_input A ParticipantInput object containing the existing data
   * @return string The html code of the GUI element
   * @access public
   */
  public function getQuestionOutput($participant_input)
  {
		$html = "";

    foreach ($this->subelements as $subelement)
    {
      $html .= $subelement->getQuestionOutput($participant_input);
    }

		return $html;
  }

  /**
   * Returns the html output of the GUI element tailored for the solution output page
   *
	 * @param ParticipantInput $participant_input A ParticipantInput object containing the participant inputs
	 * @param string $id The ID postfix used to have unique ids for all divs
   * @return string The html code of the GUI element
   * @access public
   */
  public function getSolutionOutput($participant_input, $id)
  {
		$html = "";

    foreach ($this->subelements as $subelement)
    {
      $html .= $subelement->getSolutionOutput($participant_input, $id);
    }

		return $html;
  }

  /**
   * Functions to handle POST data
   */

   /**
    * Writes the POST data of the edit page into the $object
		*
		* @access public
    */
   public function writePostData()
   {
     foreach ($this->subelements as $subelement)
     {
       $subelement->writePostData();
     }
   }

	 /**
	  * Writes the POST data of a participants input into a ParticipantInput object
		*
		* @param ParticipantInput $participant_input The ParticipantInput object the POST data is written to
		*/
	 public function writeParticipantInput($participant_input)
	 {
		 foreach ($this->subelements as $subelement)
		 {
			 $subelement->writeParticipantInput($participant_input);
		 }
 	 }

	 /**
	 	* Functions originaly implemented in ilCustomInputGUI that need to be overwritten
    */

   /**
 		* Checks the input of the edit page
 		*
 		* (This is an override of the ilCustomInputGUI:checkInput() to be tailored
 		* for the sequences input area of editQuestion)
 		*
 		* @return boolean True if input is ok, False if it is not
    * @access public
 		*/
   public function checkInput()
	 {
		 throw new Exception("It is necessary to override checkInput in every GUIArea");

		 return false;
	 }

}
?>
