/**
 * @file A class implementing an error at the integrity check of sqlRun
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

 /**
  * A class implementing an error at the integrity check of sqlRun
  */
class sqlRunErrorIntegrityCheck extends sqlRunErrorAbstract
{
  /**
   * Constructor of a single sqlRunErrorDBCreation
   *
   * @param {string} errorMessage The message of the error
   */
  constructor(errorMessage)
  {
    super(errorMessage);
    this.errorType = "sqlRunErrorIntegrityCheck";
  }
}
