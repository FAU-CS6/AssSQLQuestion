<?php
require_once __DIR__.'/../../class.GUIElement.php';

/**
 * Represents the info area GUIElement
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class ScoringInfo extends GUIElement
{
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
		$tpl = $this->plugin->getTemplate('Mixed/tpl.il_as_qpl_qpisql_m_info.html');
    $tpl->setVariable("INFO", $this->plugin->txt('ai_sca_eo_info'));
    return $tpl->get();
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
    return "";
  }

  /**
   * Returns the html output of the GUI element tailored for the solution output page
   *
   * @param ParticipantInput $participant_input A ParticipantInput object containing the participant inputs
   * @param string $id The ID prefix used to have unique ids for all divs
   * @return string The html code of the GUI element
   * @access public
   */
  public function getSolutionOutput($participant_input, $id)
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
		 // Do nothing
   }

   /**
	  * Writes the POST data of a participants input into a ParticipantInput object
		*
		* @param ParticipantInput $participant_input The ParticipantInput object the POST data is written to
    * @access public
		*/
	 public function writeParticipantInput($participant_input)
	 {
     // Do nothing
 	 }
}
?>
