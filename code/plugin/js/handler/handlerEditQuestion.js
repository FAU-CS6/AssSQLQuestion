/**
 * @file Javascript functions tailored for editQuestion page
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
    // TODO
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
   * Output a error found while execution of sql sequences
   *
   * @param {string} error The error message
   */
  static outputError(error)
  {
    document.getElementById('il_as_qpl_qpisql_error_log').innerHTML = error;
    document.getElementById('il_as_qpl_qpisql_error_bool').value = "true";
  }

  /**
   * Output the result of executing sql sequences
   *
   * @param {sqlResult} result The sqlResult of the running the sql sequences
   */
  static outputResult(result)
  {
    document.getElementById('il_as_qpl_qpisql_output_div').innerHTML = result.toHTMLTable();
    document.getElementById('il_as_qpl_qpisql_statement_output').value = result.toJSON();
  }
}
