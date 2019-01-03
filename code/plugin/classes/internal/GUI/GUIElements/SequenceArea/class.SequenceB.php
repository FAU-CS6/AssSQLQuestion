<?php
require_once __DIR__.'/../../class.GUIElement.php';

/**
 * Represents the sequenceB GUIElement
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class SequenceB extends GUIElement
{
  /**
   * Returns the html output of the GUI element tailored for the edit page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getEditOutput()
  {
		$tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_sequence_area_sequence_input.html');
    $tpl->setVariable("HEADER", $this->plugin->txt('sequence_b'));
    $tpl->setVariable("ID", 'sequence_b');
    $tpl->setVariable("NAME", 'sequence_b');
    $tpl->setVariable("CONTENT", $this->object->getSequence('sequence_b'));
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
		 $this->object->setSequence('sequence_b', (string) $_POST['sequence_b']);
   }
}
?>
