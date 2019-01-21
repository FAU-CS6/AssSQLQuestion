<?php
require_once __DIR__.'/../../class.GUIElement.php';

/**
 * Represents the info area GUIElement
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class SequenceInfo extends GUIElement
{
  /**
   * Returns the html output of the GUI element tailored for the edit page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getEditOutput()
  {
		$tpl = $this->plugin->getTemplate('Mixed/tpl.il_as_qpl_qpisql_m_info.html');
    $tpl->setVariable("INFO", $this->plugin->txt('ai_sea_eo_info'));
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
    // The sequence area is used to display the question/task text itself
    $tpl = $this->plugin->getTemplate('Mixed/tpl.il_as_qpl_qpisql_m_info.html');
    $tpl->setVariable("INFO", "<b>" . $this->plugin->txt('ai_sea_qo_task') . "</b><br />" . $this->object->getQuestion());
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
