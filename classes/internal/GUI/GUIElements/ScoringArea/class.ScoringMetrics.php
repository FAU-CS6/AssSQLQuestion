<?php
require_once __DIR__.'/../../class.GUIElement.php';

// Abstract ScoringMetric
require_once __DIR__.'/../../../Scoring/class.ScoringMetric.php';

// All ScoringMetrics to be shown
require_once __DIR__.'/../../../Scoring/ScoringMetrics/ResultLines/class.ResultLines.php';

/**
 * Represents the quantity of ScoringMetrics
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class ScoringMetrics extends GUIElement
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
    $html = "";

    // Add the html code of all ScoringMetrics
    $html .= ResultLines::getEditOutput($this->plugin, $this->object);

    return $html;
  }

  /**
   * Returns the html output of the GUI element tailored for the question output page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getQuestionOutput()
  {
    $html = "";

    // Add the html code of all ScoringMetrics
    $html .= ResultLines::getQuestionOutput($this->plugin, $this->object);

    return $html;
  }

  /**
   * Returns the html output of the GUI element tailored for the solution output page
   *
   * @return string The html code of the GUI element
   * @access public
   */
  public function getSolutionOutput()
  {
    $html = "";

    // Add the html code of all ScoringMetrics
    $html .= ResultLines::getSolutionOutput($this->plugin, $this->object);

    return $html;
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
     $html = "";

     // Add the html code of all ScoringMetrics
     $html .= ResultLines::writePostData($this->plugin, $this->object);

     return $html;
   }
}
?>
