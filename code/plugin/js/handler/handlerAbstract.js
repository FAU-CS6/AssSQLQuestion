/**
 * @file Implements all handlers needed for a page - this is the abstract parent that should be extended by other handlers
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

/**
 * Class implementing all handlers needed for a page
 * - this is the abstract parent that should be extended by other handlers
 */
class handlerAbstract
{
  /*
   * Functions that can be used without tailoring in page handlers
   */

  /**
   * Main handler of the "Execute" button
   * Disables input areas, executes the sql sequences and writes them to the page
   */
  static execute()
  {
    // Disable input areas
    this.disableInputAreas();

    // Inform the User about the execution
    this.outputRunning();

    // Get sequence a
    const sequenceA = this.getSequenceA();

    // Get sequence b
    const sequenceB = this.getSequenceB();

    // Get sequence c
    const sequenceC = this.getSequenceC();

    // Get the state of the integrityCheck checkbox
    const integrityCheck = this.getIntegrityCheck();

    // Initialize the run variable to be available outside of the try catch, too
    var run;

    // Execute the code
    try
    {
      run = new sqlRun(sequenceA, sequenceB, sequenceC, integrityCheck);
    }
    catch(err)
    {
      // Output received error on page
      this.outputError(err);

      // Enable the input again
      this.enableInputAreas();

      // Leave the function
      return;
    }

    // Output the result on page
    this.outputResult(run.getLastResult());

    // Enable the input again
    this.enableInputAreas();
  }

  /*
   * Functions that have to be tailored for use in page handlers
   */

   /**
    * Disables all input areas that are important for executing sql
    */
   static disableInputAreas()
   {
     // Do nothing as this has to be tailored for each page
   }

   /**
    * Enables all input areas that are important for executing sql
    */
   static enableInputAreas()
   {
     // Do nothing as this has to be tailored for each page
   }

   /**
    * Deletes old outputs displayed on the page
    */
   static deleteOldOutputs()
   {
     // Do nothing as this has to be tailored for each page
   }

   /**
    * Get the first sql sequence by reading the input areas of the page
    *
    * @return {string} The first sql sequence
    */
   static getSequenceA()
   {
     // Do nothing as this has to be tailored for each page
   }

   /**
    * Get the second sql sequence by reading the input areas of the page
    *
    * @return {string} The second sql sequence
    */
   static getSequenceB()
   {
     // Do nothing as this has to be tailored for each page
   }

   /**
    * Get the third sql sequence by reading the input areas of the page
    *
    * @return {string} The third sql sequence
    */
   static getSequenceC()
   {
     // Do nothing as this has to be tailored for each page
   }

   /**
    * Get the boolean declaring whether the integrity check has to be executed or not
    *
    * @return {boolean} Boolean declaring whether the integrity check has to be executed or not
    */
   static getIntegrityCheck()
   {
     // Do nothing as this has to be tailored for each page
   }

   /**
    * Output the running state (inform the user about the execution being started)
    */
   static outputRunning()
   {
     // Do nothing as this has to be tailored for each page
   }

   /**
    * Output a error found while execution of sql sequences
    *
    * @param {sqlRunErrorAbstract} error The error object
    */
   static outputError(error)
   {
     // Do nothing as this has to be tailored for each page
   }

   /**
    * Output the result of executing sql sequences
    *
    * @param {sqlResult} result The sqlResult of the running the sql sequences
    */
   static outputResult(result)
   {
     // Do nothing as this has to be tailored for each page
   }
}
