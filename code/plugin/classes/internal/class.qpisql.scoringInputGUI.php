<?php
require_once "./Services/Form/classes/class.ilCustomInputGUI.php";

/**
 * Internal GUI class to wrap the scoring input area on editQuestion page
 *
 * Is used to get a cleaner assSQLQuestionGUI
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class scoringInputGUI extends ilCustomInputGUI
{
  /**
	 * @var ilassSQLQuestionPlugin The plugin object
	 */
  var $plugin;

  /**
	* Constructor
	*
	* @param ilassSQLQuestionPlugin $plugin The plugin object
	* @access public
	*/
  function __construct($plugin)
  {
    // Set plugin
    $this->plugin = $plugin;

    // Initialize html buffer
    $html = "";

    // Add the scoring metrics

    // Result lines
    $html .= $this->createScoringInfo($this->plugin->txt('scoring_metric_result_lines_info'));
    $html .= $this->createScoringArea('points_result_lines',
                                      $this->plugin->txt('scoring_metric_result_lines_output_text'),
                                      'il_as_qpl_qpisql_scoring_metric_result_lines_output',
                                      'value_result_lines',
                                      'il_as_qpl_qpisql_scoring_metric_result_lines_output_hidden_field_id');

    // Set the html
    $this->setHTML($html);
  }

  /**
   * Helper function to generate a single info area
   *
   * @param string $text The text of the info area
   * @access private
   */
  private function createScoringInfo($text)
  {
    return "<div class='help-block'>".$text."</div>";
  }

  /**
   * Helper function to generate a single scoring area
   *
   * @param string $point_field_name The name of the form field holding the selected points
   * @param string $output_text A custom text to describe the computed output
   * @param string $output_field_id The id of the div to write a output
   * @param string $hidden_field_name The name of the hidden form element to save computed the metric value
   * @param string $hidden_field_id The id of the hidden form element to save computed the metric value
   * @access private
   */
  private function createScoringArea($point_field_name, $output_text,
                                      $output_field_id, $hidden_field_name,
                                      $hidden_field_id)
  {
    $tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_scoring_area.html');
    $tpl->setVariable("POINT_FIELD_NAME", $point_field_name);
    $tpl->setVariable("POINTS_TEXT", $this->plugin->txt('points_text'));
    $tpl->setVariable("EXECUTE_FIRST_WARNING", $this->plugin->txt('scoring_area_execute_first'));
    $tpl->setVariable("OUTPUT_TEXT", $output_text);
    $tpl->setVariable("OUTPUT_FIELD_ID", $output_field_id);
    $tpl->setVariable("HIDDEN_FIELD_NAME", $hidden_field_name);
    $tpl->setVariable("HIDDEN_FIELD_ID", $hidden_field_id);
    $item = new ilCustomInputGUI('');

    return $tpl->get();
  }
}
?>
