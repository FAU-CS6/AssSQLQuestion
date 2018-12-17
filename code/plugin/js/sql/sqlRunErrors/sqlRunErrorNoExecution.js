/**
 * @file A class implementing an error occured at the creation of the database in sqlRun
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

 /**
  * A class implementing an error due to no execution being started
  */
class sqlRunErrorNoExecution extends sqlRunErrorAbstract
{
  /**
   * Constructor of a single sqlRunErrorDBCreation
   */
  constructor()
  {
    super("");
    this.errorType = "sqlRunErrorNoExecution";
  }

  /**
   * Get (beautified) output text (Error message in a form suitable for the error)
   *
   * @return {string} The (beautified) output text
   */
  getOutputText()
  {
    return error_no_execution + " " + this.errorMessage;
  }
}
