/**
 * @file Javascript handlers tailored for editQuestion page
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

/**
 * Class wrapping all functions needed for the editQuestion page
 */
class handlerEditQuestion extends handlerAbstract
{
  /**
   * Disables all input areas that are important for executing sql
   */
  static disableInputAreas()
  {
    // Disable the execute button
    document.getElementById("il_as_qpl_qpisql_execution_button").disabled = true;

    // Disable the code input areas
    editor_sequence_a.setOption("readOnly", true);
    editor_sequence_b.setOption("readOnly", true);
    editor_sequence_c.setOption("readOnly", true);

    // Disable the integrity_check checkbox
    document.getElementById('integrity_check').disabled = true;
  }

  /**
   * Enables all input areas that are important for executing sql
   */
  static enableInputAreas()
  {
    // Enable the execute button
    document.getElementById("il_as_qpl_qpisql_execution_button").disabled = false;

    // Enable the code input areas
    editor_sequence_a.setOption("readOnly", false);
    editor_sequence_b.setOption("readOnly", false);
    editor_sequence_c.setOption("readOnly", false);

    // Enable the integrity_check checkbox
    document.getElementById('integrity_check').disabled = false;
  }

  /**
   * Deletes old outputs displayed on the page
   */
  static deleteOldOutputs()
  {
    this.output('il_as_qpl_qpisql_output_area_no_execution',
                'il_as_qpl_qpisql_output_area_no_execution',
                document.getElementById('il_as_qpl_qpisql_output_area_no_execution').innerHTML,
                "",
                false,
                false);
  }

  /**
   * Get the first sql sequence by reading the input areas of the page
   *
   * @return {string} The first sql sequence
   */
  static getSequenceA()
  {
    return editor_sequence_a.getValue();
  }

  /**
   * Get the second sql sequence by reading the input areas of the page
   *
   * @return {string} The second sql sequence
   */
  static getSequenceB()
  {
    return editor_sequence_b.getValue();
  }

  /**
   * Get the third sql sequence by reading the input areas of the page
   *
   * @return {string} The third sql sequence
   */
  static getSequenceC()
  {
    return editor_sequence_c.getValue();
  }

  /**
   * Get the boolean declaring whether the integrity check has to be executed or not
   *
   * @return {boolean} Boolean declaring whether the integrity check has to be executed or not
   */
  static getIntegrityCheck()
  {
    return document.getElementById('integrity_check').checked;
  }

  /**
   * Output the running state (inform the user about the execution being started)
   */
  static outputRunning()
  {
    this.output('il_as_qpl_qpisql_output_area_execution_running',
                'il_as_qpl_qpisql_output_area_execution_running',
                document.getElementById('il_as_qpl_qpisql_output_area_execution_running').innerHTML,
                "",
                false,
                false);
  }

  /**
   * Output a error found while execution of sql sequences
   *
   * @param {sqlRunErrorAbstract} error The error object
   */
  static outputError(error)
  {
    // Switch through the different types of errors
    // This is necessary to get translated error messages for every kind of error
    switch(error.getErrorType())
    {
      case "sqlRunErrorDBCreation":
            this.output('il_as_qpl_qpisql_output_area_error_db_creation',
                        'il_as_qpl_qpisql_output_area_error_db_creation_message',
                        error.getErrorMessage(),
                        "",
                        true,
                        false);
            break;
      case "sqlRunErrorIntegrityCheck":
            this.output('il_as_qpl_qpisql_output_area_error_integrity_check',
                        'il_as_qpl_qpisql_output_area_error_integrity_check_message',
                        error.getErrorMessage(),
                        "",
                        true,
                        false);
            break;
      case "sqlRunErrorNoVisibleResult":
            this.output('il_as_qpl_qpisql_output_area_error_no_visible_result',
                        'il_as_qpl_qpisql_output_area_error_no_visible_result_message',
                        error.getErrorMessage(),
                        "",
                        true,
                        false);
            break;
      case "sqlRunErrorRunningSequence":
            this.output('il_as_qpl_qpisql_output_area_error_running_sequence',
                        'il_as_qpl_qpisql_output_area_error_running_sequence_message',
                        error.getErrorMessage(),
                        "",
                        true,
                        false);
           // In this special case we have to add the number of sequence, too
           document.getElementById('il_as_qpl_qpisql_output_area_error_running_sequence_sequence_name').innerHTML = error.getSequenceName();
           break;
    }
  }

  /**
   * Output the result of executing sql sequences
   *
   * @param {sqlResult} result The sqlResult of the running the sql sequences
   */
  static outputResult(result)
  {
    this.output('il_as_qpl_qpisql_output_area_relation',
                'il_as_qpl_qpisql_output_area_relation',
                result.toHTMLTable(),
                result.toJSON(),
                false,
                true);
  }

  /**
   * Helper to write output of each kind into editQuestion
   *
   * @param {string} output_display_div The id of the output div the content is part of
   * @param {string} output_div The id of the div the content should be written to
   * @param {string} output_content The content to be displayed
   * @param {string} output_relation A json string representing the output relation (may be empty if an error occured or output has to be reset)
   * @param {boolean} error_bool A boolean representing the error state of the last query
   * @param {boolean} executed_bool A boolean represeting the state of execution
   */
  static output(output_display_div, output_div, output_content, output_relation, error_bool, executed_bool)
  {
    // Make all output divs invisible
    var areas = document.getElementsByClassName('il_as_qpl_qpisql_output_areas');

    for (var i = 0; i < areas.length; i++)
    {
      areas[i].style.display = "none";
    }

    // Delete all currently shown errors
    var errors = document.getElementsByClassName('il_as_qpl_qpisql_output_area_error_message');

    for (var i = 0; i < errors.length; i++)
    {
      errors[i].innerHTML = "";
    }

    // Delete the currently shown sequence identifier in the suiting error
    document.getElementById('il_as_qpl_qpisql_output_area_error_running_sequence_sequence_name').innerHTML = "";

    // Delete the currently shown relation
    document.getElementById('il_as_qpl_qpisql_output_area_relation').innerHTML = "";

    // Make the output_display_div visable
    document.getElementById(output_display_div).style.display = "inherit";

    // Put the content into the right div
    document.getElementById(output_div).innerHTML = output_content;

    // Set the hidden fields
    document.getElementById('il_as_qpl_qpisql_output_relation').value = output_relation;
    document.getElementById('il_as_qpl_qpisql_error_bool').value = String(error_bool);
    document.getElementById('il_as_qpl_qpisql_executed_bool').value = String(executed_bool);
  }
}
