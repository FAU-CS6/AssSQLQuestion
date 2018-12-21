/**
 * @file Implements all functions to handle an execution
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

/**
 * Class implementing all functions to handle an execution
 */
class ExecutionHandler
{

  /**
   * Constructor
   */
  constructor()
  {
    // Initialize the ExecutionInput array
    this.inputs = [];

    // Initialize the ExecutionOutput array
    this.outputs = [];
  }

  /**
   * Execution handler
   */

  /**
   * Main handler of the "Execute" button - Starts a execution
   */
  execute()
  {
    // Call the onExecution event handlers
    this.onExecution();

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
      var workerSqlRun = new Worker(window.QPISQL_URL_PATH + '/js/sql/worker/worker.sqlRun.js');

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

          // Call the onResult event handlers
          callingHandler.onResult(result);
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

          // Call the onError event handlers
          callingHandler.onError(err);
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
      catch(error)
      {
        // Call the onError event handlers
        this.onError(error);

        // Leave the function
        return;
      }

      // Call the onResult event handlers
      this.onResult(run.getLastResult());
    }
  }

  /**
   * Setter for registering the ExecutionInputs and the ExecutionOutputs
   */

   /**
    * Register a ExecutionInput
    *
    * @param {ExecutionInput} input The ExecutionInput to be registered
    */
   registerInput(input)
   {
     this.inputs.push(input);
   }

   /**
    * Register a ExecutionOutput
    *
    * @param {ExecutionOutput} output The ExecutionOutput to be registered
    */
   registerOutput(output)
   {
     this.outputs.push(output);
   }

  /**
   * Getter for the execution inputs
   */

   /**
    * Get the first sql sequence by reading the input areas of the page
    *
    * @return {string} The first sql sequence
    */
   getSequenceA()
   {
     for(var i = 0; i <= this.inputs.length; i++)
     {
       if(this.inputs[i].getName() == "sequence_a")
       {
         return this.inputs[i].getValue();
       }
     }

     throw new Error("There was no sequence A found");
   }

   /**
    * Get the second sql sequence by reading the input areas of the page
    *
    * @return {string} The second sql sequence
    */
   getSequenceB()
   {
     for(var i = 0; i <= this.inputs.length; i++)
     {
       if(this.inputs[i].getName() == "sequence_b")
       {
         return this.inputs[i].getValue();
       }
     }

     throw new Error("There was no sequence B found");
   }

   /**
    * Get the third sql sequence by reading the input areas of the page
    *
    * @return {string} The third sql sequence
    */
   getSequenceC()
   {
     for(var i = 0; i <= this.inputs.length; i++)
     {
       if(this.inputs[i].getName() == "sequence_c")
       {
         return this.inputs[i].getValue();
       }
     }

     throw new Error("There was no sequence C found");
   }

   /**
    * Get the boolean declaring whether the integrity check has to be executed or not
    *
    * @return {boolean} Boolean declaring whether the integrity check has to be executed or not
    */
   getIntegrityCheck()
   {
     for(var i = 0; i <= this.inputs.length; i++)
     {
       if(this.inputs[i].getName() == "integrity_check")
       {
         return this.inputs[i].getValue();
       }
     }

     throw new Error("There was no integrity check value found");
   }

   /**
    * Call the event handlers
    */

    /**
     * Call the onChange handlers
     */
    onChange()
    {
      for(var i = 0; i < this.outputs.length; i++)
      {
        this.outputs[i].onChange();
      }
    }

   /**
    * Call the onExecution handlers
    */
   onExecution()
   {
     for(var i = 0; i < this.outputs.length; i++)
     {
       this.outputs[i].onExecution();
     }
   }

   /**
    * Call the onError handlers
    *
    * @param {sqlRunErrorAbstract} error The error object
    */
   onError(error)
   {
     for(var i = 0; i < this.outputs.length; i++)
     {
       this.outputs[i].onError(error);
     }
   }

   /**
    * Call the onResult handlers
    *
    * @param {sqlResult} result The sqlResult
    */
   onResult(result)
   {
     for(var i = 0; i < this.outputs.length; i++)
     {
       this.outputs[i].onResult(result);
     }
   }
}

// Construct a basic EXECUTE HANDLER that can be used at every page
window.EXECUTE_HANDLER = new ExecutionHandler();
