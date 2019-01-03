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
		$tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_scoring_area_info.html');
    $tpl->setVariable("INFO", $this->plugin->txt('scoring_info_text'));
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
		 // Do nothing
   }
}
?>
