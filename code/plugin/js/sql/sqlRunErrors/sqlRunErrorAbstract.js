/**
 * @file A abstract parent class for the different error types that might occur in sqlRun
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

 /**
  * A abstract parent class for the different error types that might occur in sqlRun
  */
class sqlRunErrorAbstract
{
  /**
   * Constructor of a single sqlRunErrorAbstract
   *
   * @param {string} errorMessage The message of the error
   */
  constructor(errorMessage)
  {
    this.errorMessage = errorMessage;
  }

  /**
   * Get the saved error message
   *
   * @return {string} The error message
   */
  getErrorMessage()
  {
    return this.errorMessage;
  }

  /**
   * Get the type of the error
   *
   * @return {string} The error type (The class the error is of)
   */
  getErrorType()
  {
    return "sqlRunErrorAbstract";
  }
}
