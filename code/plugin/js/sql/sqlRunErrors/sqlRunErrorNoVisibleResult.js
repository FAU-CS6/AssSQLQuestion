/**
 * @file A class implementing an error for no SELECT or visible result in the sequences of sqlRun
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

 /**
  * A class implementing an error for no SELECT or visible result in the sequences of sqlRun
  */
class sqlRunErrorNoVisibleResult extends sqlRunErrorAbstract
{
  /**
   * Constructor of a single sqlRunErrorDBCreation
   *
   * @param {string} errorMessage The message of the error
   */
  constructor(errorMessage)
  {
    super(errorMessage);
    this.errorType = "sqlRunErrorNoVisibleResult";
  }
}
