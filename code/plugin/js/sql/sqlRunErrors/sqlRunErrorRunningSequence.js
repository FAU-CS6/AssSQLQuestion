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
    this.errorType = "sqlRunErrorRunningSequence";
    this.sequence = sequence;
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

  /**
   * Get (beautified) output text (Error message in a form suitable for the error)
   *
   * @return {string} The (beautified) output text
   */
  getOutputText()
  {
    return "(" + this.sequence + ") " + error_running_sequence + " <i>" + this.errorMessage + "</i>";
  }
}
