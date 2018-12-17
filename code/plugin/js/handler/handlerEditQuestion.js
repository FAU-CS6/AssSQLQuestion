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
    document.getElementById("qpisql-execute-button").disabled = true;

    // Disable the code input areas
    editor_sequence_a.setOption("readOnly", true);
    editor_sequence_b.setOption("readOnly", true);
    editor_sequence_c.setOption("readOnly", true);

    // Disable the integrity_check checkbox
    document.getElementById('qpisql-integrity-check').disabled = true;
  }

  /**
   * Enables all input areas that are important for executing sql
   */
  static enableInputAreas()
  {
    // Enable the execute button
    document.getElementById("qpisql-execute-button").disabled = false;

    // Enable the code input areas
    editor_sequence_a.setOption("readOnly", false);
    editor_sequence_b.setOption("readOnly", false);
    editor_sequence_c.setOption("readOnly", false);

    // Enable the integrity_check checkbox
    document.getElementById('qpisql-integrity-check').disabled = false;
  }

  /**
   * Deletes old outputs displayed on the page
   */
  static deleteOldOutputs()
  {
    this.outputError(new sqlRunErrorNoExecution());

    this.setScoringAreas(false);
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
    return document.getElementById('qpisql-integrity-check').checked;
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

    // We have to set the scoring areas, too

    // Result lines metric
    document.getElementById('qpisql-scoring-metric-result-lines-output').innerHTML = result.getNumberOfRows();
    document.getElementById('qpisql-scoring-metric-result-lines-value').value = result.getNumberOfRows();

    this.setScoringAreas(true);
  }

  /**
   * Helper to set the scoring areas to executed/not executed
   *
   * @param {boolean} executed_bool Whether the scoring are should be set to executed (true) or not executed
   */
  static setScoringAreas(executed_bool)
  {
    // Executed divs in scoring area
    var executed_areas = document.getElementsByClassName('qpisql-scoring-metric-executed');

    for (var i = 0; i < executed_areas.length; i++)
    {
      if(executed_bool)
      {
        executed_areas[i].style.display = "inherit";
      }
      else
      {
        executed_areas[i].style.display = "none";
      }
    }

    // Not executed divs in scoring area
    var not_executed_areas = document.getElementsByClassName('qpisql-scoring-metric-not-executed');

    for (var i = 0; i < not_executed_areas.length; i++)
    {
      if(executed_bool)
      {
        not_executed_areas[i].style.display = "none";
      }
      else
      {
        not_executed_areas[i].style.display = "inherit";
      }
    }

    // If not executed delete all existing computed metric values in scoring areas
    if(!executed_bool)
    {
      var executed_areas_inner = document.getElementsByClassName('qpisql-scoring-metric-executed-inner');

      for (var i = 0; i < executed_areas_inner.length; i++)
      {
        executed_areas_inner[i].innerHTML = "";
      }
    }
  }
}
