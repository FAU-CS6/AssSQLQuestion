/**
 * @file A class implementing an error occured at the creation of the database in sqlRun
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

 /**
  * A class implementing an error occured at the creation of the database in sqlRun
  */
class sqlRunErrorDBCreation extends sqlRunErrorAbstract
{
  /**
   * Constructor of a single sqlRunErrorDBCreation
   *
   * @param {string} errorMessage The message of the error
   */
  constructor(errorMessage)
  {
    super(errorMessage);
    this.errorType = "sqlRunErrorDBCreation";
  }

  /**
   * Get (beautified) output text (Error message in a form suitable for the error)
   *
   * @return {string} The (beautified) output text
   */
  getOutputText()
  {
    // Error_db_creation is a custom string describing a sqlRunErrorDBCreation
    return error_db_creation + " " + this.errorMessage;
  }
}
