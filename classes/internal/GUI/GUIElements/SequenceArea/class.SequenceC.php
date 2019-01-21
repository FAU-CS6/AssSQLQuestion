<?php
require_once __DIR__.'/../../class.GUIElement.php';

/**
 * Represents the sequenceC GUIElement
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class SequenceC extends GUIElement
{
  /**
   * Returns the html output of the GUI element tailored for the edit page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getEditOutput()
  {
    // Get any default data
    $sequence_c = $this->object->getSequence('sequence_c');

    // If there is $_POST data use that
    if(isset($_POST["sequence_c"]))
    {
      $sequence_c = $_POST["sequence_c"];
    }

		$tpl = $this->plugin->getTemplate('SequenceArea/tpl.il_as_qpl_qpisql_sea_sequence_input.html');
    $tpl->setVariable("HEADER", $this->plugin->txt('ai_sea_eo_seq_c'));
    $tpl->setVariable("ID", 'sequence_c');
    $tpl->setVariable("NAME", 'sequence_c');
    $tpl->setVariable("CONTENT", $sequence_c);
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
    $tpl = $this->plugin->getTemplate('SequenceArea/tpl.il_as_qpl_qpisql_sea_hidden_field.html');
    $tpl->setVariable("ID", 'sequence_c');
    $tpl->setVariable("NAME", 'sequence_c');
    $tpl->setVariable("VALUE", $this->object->getSequence('sequence_c'));
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
		 $this->object->setSequence('sequence_c', (string) $_POST['sequence_c']);
   }
}
?>
