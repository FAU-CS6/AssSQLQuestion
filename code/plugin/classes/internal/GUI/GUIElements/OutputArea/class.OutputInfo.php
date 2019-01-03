<?php
require_once __DIR__.'/../../class.GUIElement.php';

/**
 * Represents the info area GUIElement
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class OutputInfo extends GUIElement
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
		$tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_output_area_info.html');
    $tpl->setVariable("INFO", $this->plugin->txt('output_info_text'));
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
		 $this->object->setErrorBool($_POST["error_bool"] == "true" ? true : false);
		 $this->object->setExecutedBool($_POST["executed_bool"] == "true" ? true : false);
		 $this->object->setOutputRelation((string) $_POST["output_relation"]);
   }
}
?>
