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
  }

  /**
   * Get the type of the error
   *
   * @return {string} The error type (The class the error is of)
   */
  getErrorType()
  {
    return "sqlRunErrorDBCreation";
  }
}
