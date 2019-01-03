<?php
require_once __DIR__.'/../../class.GUIElement.php';

/**
 * Represents the integrityCheck GUIElement
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class IntegrityCheck extends GUIElement
{
  /**
   * Returns the html output of the GUI element tailored for the edit page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getEditOutput()
  {
		$tpl = $this->plugin->getTemplate('SequenceArea/tpl.il_as_qpl_qpisql_sea_integrity_check_input.html');
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
