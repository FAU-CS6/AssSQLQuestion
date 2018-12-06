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

    // Delete old outputs
    this.deleteOldOutputs();

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

    // If the Browser supports web workers we use them for better multithreading
    if(window.Worker)
    {
      // Start a worker
      var workerSqlRun = new Worker(window.URL_PATH + '/js/sql/worker/worker.sqlRun.js');

      // Send the data to the worker
      workerSqlRun.postMessage({"sequenceA": sequenceA,
                                "sequenceB": sequenceB,
                                "sequenceC": sequenceC,
                                "integrityCheck": integrityCheck});

      const handler = this;

      workerSqlRun.onmessage = function(e, callingHandler = handler) {
        if(e.data["type"] == "result")
        {
          // Create a new sqlResult out of the result value
          const result = new sqlResult({"columns": e.data["result"]["columns"],
                                        "values": e.data["result"]["values"]})

          // Output the result on page
          callingHandler.outputResult(result);

          // Enable the input again
          callingHandler.enableInputAreas();
        }
        else
        {
          var err;

          // We have to create sqlRunErrors out of the e.data["error"] again
          switch(e.data["error"]["errorType"])
          {
            case "sqlRunErrorAbstract":
              err = new sqlRunErrorAbstract(e.data["error"]["errorMessage"]);
              break;
            case "sqlRunErrorDBCreation":
              err = new sqlRunErrorDBCreation(e.data["error"]["errorMessage"]);
              break;
            case "sqlRunErrorIntegrityCheck":
              err = new sqlRunErrorIntegrityCheck(e.data["error"]["errorMessage"]);
              break;
            case "sqlRunErrorNoVisibleResult":
              err = new sqlRunErrorNoVisibleResult(e.data["error"]["errorMessage"]);
              break;
            case "sqlRunErrorRunningSequence":
              err = new sqlRunErrorRunningSequence(e.data["error"]["errorMessage"], e.data["error"]["sequence"]);
              break;
          }

          // Output received error on page
          callingHandler.outputError(err);

          // Enable the input again
          callingHandler.enableInputAreas();
        }

        this.terminate();
      }
    }
    // If Web Workers are not supported we have to used the old fashioned way
    else
    {
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
