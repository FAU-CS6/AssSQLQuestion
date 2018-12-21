/**
 * @file Javascript handlers tailored for outputQuestion page
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

/**
 * Class wrapping all functions needed for the outputQuestion page
 */
class handlerOutputQuestion extends handlerAbstract
{
  /**
   * Disables all input areas that are important for executing sql
   */
  static disableInputAreas()
  {
    // Disable the execute button
    document.getElementById("qpisql-execute-button").disabled = true;

    // Disable the code input area
    editor_sequence_b.setOption("readOnly", true);
  }

  /**
   * Enables all input areas that are important for executing sql
   */
  static enableInputAreas()
  {
    // Enable the execute button
    document.getElementById("qpisql-execute-button").disabled = false;

    // Enable the code input area
    editor_sequence_b.setOption("readOnly", false);
  }

  /**
   * Deletes old outputs displayed on the page
   */
  static deleteOldOutputs()
  {
    this.outputError(new sqlRunErrorNoExecution());
  }

  /**
   * Get the first sql sequence by reading the input areas of the page
   *
   * @return {string} The first sql sequence
   */
  static getSequenceA()
  {
    return document.getElementById("qpisql-sequence-a").value;
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
    return document.getElementById("qpisql-sequence-c").value;
  }

  /**
   * Get the boolean declaring whether the integrity check has to be executed or not
   *
   * @return {boolean} Boolean declaring whether the integrity check has to be executed or not
   */
  static getIntegrityCheck()
  {
    return document.getElementById('qpisql-integrity-check').value == "true";
  }

  /**
   * Output the running state (inform the user about the execution being started)
   */
  static outputRunning()
  {
    // Set the hidden fields
    document.getElementById('qpisql-error-bool').value="false";
    document.getElementById('qpisql-executed-bool').value="false";
    document.getElementById('qpisql-output-relation').value="";

    // Set the other two qpisql-inner-output-areas to be hidden and emtpy
    // In this case the error output area
    document.getElementById('qpisql-output-area-error').innerHTML = "";
    document.getElementById('qpisql-output-area-error').style.display = "none";
    // And the relation output area
    document.getElementById('qpisql-output-area-relation').innerHTML = "";
    document.getElementById('qpisql-output-area-relation').style.display = "none";

    // Set the execution running to be visable
    document.getElementById('qpisql-output-area-execution-running').style.display = "inherit";
  }

  /**
   * Output a error found while execution of sql sequences
   *
   * @param {sqlRunErrorAbstract} error The error object
   */
  static outputError(error)
  {
    // Set the hidden fields
    document.getElementById('qpisql-error-bool').value="true";
    document.getElementById('qpisql-executed-bool').value="false";
    document.getElementById('qpisql-output-relation').value="";

    // Set the other two qpisql-inner-output-areas to be hidden and emtpy
    // In this case the execution running output area
    document.getElementById('qpisql-output-area-execution-running').style.display = "none";
    // And the relation output area
    document.getElementById('qpisql-output-area-relation').innerHTML = "";
    document.getElementById('qpisql-output-area-relation').style.display = "none";

    // Set the error output area to be visable and output the error
    document.getElementById('qpisql-output-area-error').innerHTML = error.getOutputText();
    document.getElementById('qpisql-output-area-error').style.display = "inherit";
  }

  /**
   * Output the result of executing sql sequences
   *
   * @param {sqlResult} result The sqlResult of the running the sql sequences
   */
  static outputResult(result)
  {
    // Set the hidden fields
    document.getElementById('qpisql-error-bool').value="false";
    document.getElementById('qpisql-executed-bool').value="true";
    document.getElementById('qpisql-output-relation').value=result.toJSON();

    // Set the other two qpisql-inner-output-areas to be hidden and emtpy
    // In this case the execution running output area
    document.getElementById('qpisql-output-area-execution-running').style.display = "none";
    // And the error output area
    document.getElementById('qpisql-output-area-error').innerHTML = "";
    document.getElementById('qpisql-output-area-error').style.display = "none";

    // Set the error output area to be visable and output the error
    document.getElementById('qpisql-output-area-relation').innerHTML = result.toHTMLTable();
    document.getElementById('qpisql-output-area-relation').style.display = "inherit";
  }

}
