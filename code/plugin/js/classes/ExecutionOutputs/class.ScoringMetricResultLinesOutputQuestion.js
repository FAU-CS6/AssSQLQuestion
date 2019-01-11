/**
 * @file ExecutionOutput for the result lines scoring metric on question output page
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

 /**
  * ExecutionOutput for the result lines scoring metric on question output page
  */
 class ScoringMetricResultLinesOutputQuestion extends ExecutionOutput
 {

   /**
    * Constructor
    */
   constructor()
   {
     super();
   }

   /**
    * Event handler that is called at the moment any input is changed
    */
   onChange()
   {
     document.getElementById('qpisql-scoring-metric-value-result-lines').value = "false";
   }

   /**
    * Event handler that is called at the moment an execution is started
    */
   onExecution()
   {
     document.getElementById('qpisql-scoring-metric-value-result-lines').value = "false";
   }

   /**
    * Event handler that is called if a execution ends with an error
    *
    * @param {SQLRunErrorAbstract} error The error object
    */
   onError(error)
   {
     document.getElementById('qpisql-scoring-metric-value-result-lines').value = "false";
   }

   /**
    * Event handler that is called if a execution ends with a result
    *
    * @param {SQLResult} result The result object
    */
   onResult(result)
   {
     document.getElementById('qpisql-scoring-metric-value-result-lines').value = result.getNumberOfRows();
   }

 }
