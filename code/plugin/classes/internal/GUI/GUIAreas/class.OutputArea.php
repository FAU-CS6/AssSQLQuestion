<?php
require_once __DIR__.'/../class.GUIArea.php';

require_once __DIR__.'/../GUIElements/OutputArea/class.OutputInfo.php';
require_once __DIR__.'/../GUIElements/OutputArea/class.Output.php';

/**
 * Represents the output area used in assSQLQuestionGUI
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class OutputArea extends GUIArea
{
  /**
  * Constructor
  *
  * @param ilassSQLQuestionPlugin $plugin The plugin object
  * @param assSQLQuestion $object The question object
  * @access public
  */
  public function __construct($plugin, $object)
  {
    // Use the GUIArea constructor
		parent::__construct($plugin,
												$object);

		// Set the subelements

		// Info area
		$this->addSubElement(new OutputInfo(
			$plugin, // Plugin
			$object // Object
		));

		// Output area
		$this->addSubElement(new Output(
			$plugin, // Plugin
			$object // Object
		));

    // Set Title, Information and Required
    $this->setTitle($this->plugin->txt('sequences_info'));
    $this->setRequired(true);
    $this->setHtml($this->getEditOutput());
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
		* @access public
 		*/
   public function checkInput()
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
