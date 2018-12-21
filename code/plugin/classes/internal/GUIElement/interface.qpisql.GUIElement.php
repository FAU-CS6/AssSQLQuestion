<?php
/**
 * Represents the GUIElement interface implemented by several GUI elements
 * used in assSQLQuestionGUI.
 *
 * This interface is based on the idea that every GUI element is used in edit,
 * question and solution output but in different ways.
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
interface GUIElement
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
  public function getEditOutput();

  /**
   * Returns the html output of the GUI element tailored for the question output page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getQuestionOutput();

  /**
   * Returns the html output of the GUI element tailored for the solution output page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getSolutionOutput();

  /*
   * Functions used to write POST data to the $object
   */

   /**
    * Writes the POST data of the edit page into the $object
    *
    * @access public
    */
   public function writePostData();

}
?>
