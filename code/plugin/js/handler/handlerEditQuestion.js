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
    this.output("Start the execution to see some output.", "", false, false);
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
    this.output("The execution is running. Please wait some seconds.", "", false, false);
  }

  /**
   * Output a error found while execution of sql sequences
   *
   * @param {string} error The error message
   */
  static outputError(error)
  {
    this.output(error, "", true, true);
  }

  /**
   * Output the result of executing sql sequences
   *
   * @param {sqlResult} result The sqlResult of the running the sql sequences
   */
  static outputResult(result)
  {
    this.output(result.toHTMLTable(), result.toJSON(), false, true);
  }

  /**
   * Helper to write output of each kind into editQuestion
   *
   * @param {string} output_content The content to be displayed in output area
   * @param {string} output_relation A json string representing the output relation (may be empty if an error occured or output has to be reset)
   * @param {boolean} error_bool A boolean representing the error state of the last query
   * @param {boolean} executed_bool A boolean represeting the state of execution
   */
  static output(output_content, output_relation, error_bool, executed_bool)
  {
    document.getElementById('il_as_qpl_qpisql_output_area').innerHTML = output_content;
    document.getElementById('il_as_qpl_qpisql_output_relation').value = output_relation;
    document.getElementById('il_as_qpl_qpisql_error_bool').value = String(error_bool);
    document.getElementById('il_as_qpl_qpisql_executed_bool').value = String(executed_bool);
  }
}
