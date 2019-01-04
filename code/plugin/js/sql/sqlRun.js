/**
 * @file A class representing a single sql run through
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.2
 */

/**
 * A single database run through (based on sql.js)
 */
class sqlRun
{
  /**
   * Constructor of sqlRun
   * A run through consists of three sql sequences (A, B, C) that are executed successively
   *
   * @param {string} sequenceA The first sql sequence
   * @param {string} sequenceB The second sql sequence
   * @param {string} sequenceC The third sql sequence
   * @param {boolean} checkIntegrity If set to true, changes to the database are not allowed in b sequence - This is checked by hashing the database after a and before c and comparing their hash values
   * @param {ExecutionHandler} callback A handler implementing a callback function onResult (for results) and a callback function onError (for errors)
   */
  constructor(sequenceA, sequenceB, sequenceC, checkIntegrity, callback)
  {
    // Set the calling parameters as member variables
    this.sequenceA = sequenceA;
    this.sequenceB = sequenceB;
    this.sequenceC = sequenceC;
    this.checkIntegrity = checkIntegrity;
    this.callback = callback;

    // Initialize a worker as we do not want the SQL to block our page
    this.worker = new Worker(window.QPISQL_URL_PATH + '/lib/sql.js/worker.sql.js');

    // Initalize a member variable for the lastResult
    // This will be set to the result of the last SELECT query in the sequence
    this.lastResult = null;

    // Call the executeSequenceA
    this.executeSequenceA();
  }

  /**
   * Executes SequenceA and calls executeSequenceB() if no errors have been found.
   * Otherwise it calls the callbackError function
   *
   * @return {string} The json string
   */
  executeSequenceA()
  {
    // We have to save this object to a const as we need it in the callbacks
    const thisrun = this;

    // Callback in case there is an error
    this.worker.onerror = function(e, run = thisrun)
    {
      run.callback.onError(new sqlRunErrorRunningSequence(e, "A"));
    }

    // Callback in case there was no error
    this.worker.onmessage = function(e, run = thisrun)
    {
      if(e.data.results.length > 0)
      {
        run.lastResult = new sqlResult(e.data.results.pop());
      }

      // Go to the nextSequence
      run.executeSequenceB();
    }

    if(this.sequenceA.length > 0)
    {
      this.worker.postMessage({action:'exec', sql: this.sequenceA});
    }
    else
    {
      // Go to the nextSequence
      this.executeSequenceB();
    }

  }

  /**
   * Executes SequenceB and calls executeSequenceC() if no errors have been found.
   * Otherwise it calls the callbackError function.
   */
  executeSequenceB()
  {
    // We have to save this object to a const as we need it in the callbacks
    const thisrun = this;

    // Callback in case there is an error
    this.worker.onerror = function(e, run = thisrun)
    {
      run.callback.onError(new sqlRunErrorRunningSequence(e, "B"));
    }

    // Callback in case there was no error
    this.worker.onmessage = function(e, run = thisrun)
    {
      if(e.data.results.length > 0)
      {
        run.lastResult = new sqlResult(e.data.results.pop());
      }

      // Go to the nextSequence
      run.executeSequenceC();
    }

    if(this.sequenceB.length > 0)
    {
      this.worker.postMessage({action:'exec', sql: this.sequenceB});
    }
    else
    {
      // Go to the nextSequence
      this.executeSequenceC();
    }
  }

  /**
   * Executes SequenceC and calls the callbackResult function if no errors have been found.
   * Otherwise it calls the callbackError function.
   */
  executeSequenceC()
  {
    // We have to save this object to a const as we need it in the callbacks
    const thisrun = this;

    // Callback in case there is an error
    this.worker.onerror = function(e, run = thisrun)
    {
      run.callback.onError(new sqlRunErrorRunningSequence(e, "C"));
    }

    // Callback in case there was no error
    this.worker.onmessage = function(e, run = thisrun)
    {
      if(e.data.results.length > 0)
      {
        run.lastResult = new sqlResult(e.data.results.pop());
      }

      // Check if there is a last result
      if(run.lastResult == null)
      {
        run.callback.onError(new sqlRunErrorNoVisibleResult(""));
      }
      // If there is one call callbackResult
      else
      {
        run.callback.onResult(run.lastResult);
      }
    }

    if(this.sequenceC.length > 0)
    {
      this.worker.postMessage({action:'exec', sql: this.sequenceC});
    }
    else
    {
      if(this.lastResult == null)
      {
        this.callback.onError(new sqlRunErrorNoVisibleResult(""));
      }
      // If there is one call callbackResult
      else
      {
        this.callback.onResult(this.lastResult);
      }
    }
  }

}
