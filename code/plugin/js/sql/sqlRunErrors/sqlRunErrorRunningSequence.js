/**
 * @file A class implementing an error occured executing a sequence in sqlRun
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

 /**
  * A class implementing an error occured executing a sequence in sqlRun
  */
class sqlRunErrorRunningSequence extends sqlRunErrorAbstract
{
  /**
   * Constructor of a single sqlRunErrorDBCreation
   *
   * @param {string} errorMessage The message of the error
   * @param {string} sequence The name of the sequence the error occured in
   */
  constructor(errorMessage, sequence)
  {
    super(errorMessage);

    this.sequence = sequence;
  }

  /**
   * Get the type of the error
   *
   * @return {string} The error type (The class the error is of)
   */
  getErrorType()
  {
    return "sqlRunErrorRunningSequence";
  }

  /**
   * Get the name of the sequence the error occured in
   *
   * @return {string} The name of the sequence the error occured in
   */
  getSequenceName()
  {
    return this.sequence;
  }
}
