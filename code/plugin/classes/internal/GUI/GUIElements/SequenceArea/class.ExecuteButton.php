<?php
require_once __DIR__.'/../../class.GUIElement.php';

/**
 * Represents the execute button GUIElement
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class ExecuteButton extends GUIElement
{
  /**
   * Returns the html output of the GUI element tailored for the edit page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getEditOutput()
  {
		$tpl = $this->plugin->getTemplate('SequenceArea/tpl.il_as_qpl_qpisql_sea_execute_button.html');
		$tpl->setVariable("BUTTONTEXT", $this->plugin->txt('ai_sea_exec_text'));
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
    $tpl = $this->plugin->getTemplate('SequenceArea/tpl.il_as_qpl_qpisql_sea_execute_button.html');
		$tpl->setVariable("BUTTONTEXT", $this->plugin->txt('ai_sea_exec_text'));
    return $tpl->get();
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
