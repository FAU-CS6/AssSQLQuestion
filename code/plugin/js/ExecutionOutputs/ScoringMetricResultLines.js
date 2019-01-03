/**
 * @file ExecutionOutput for the result lines scoring metric
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

 /**
  * ExecutionOutput for the result lines scoring metric
  */
 class ScoringMetricResultLines extends ExecutionOutput
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
     document.getElementById('qpisql-scoring-metric-not-executed-result-lines').style.display = "inherit";
     document.getElementById('qpisql-scoring-metric-executed-result-lines').style.display = "none";
     document.getElementById('qpisql-scoring-metric-output-result-lines').innerHTML = "";
     document.getElementById('qpisql-scoring-metric-value-result-lines').value = "false";
   }

   /**
    * Event handler that is called at the moment an execution is started
    */
   onExecution()
   {
     document.getElementById('qpisql-scoring-metric-not-executed-result-lines').style.display = "inherit";
     document.getElementById('qpisql-scoring-metric-executed-result-lines').style.display = "none";
     document.getElementById('qpisql-scoring-metric-output-result-lines').innerHTML = "";
     document.getElementById('qpisql-scoring-metric-value-result-lines').value = "false";
   }

   /**
    * Event handler that is called if a execution ends with an error
    *
    * @param {sqlRunErrorAbstract} error The error object
    */
   onError(error)
   {
     document.getElementById('qpisql-scoring-metric-not-executed-result-lines').style.display = "inherit";
     document.getElementById('qpisql-scoring-metric-executed-result-lines').style.display = "none";
     document.getElementById('qpisql-scoring-metric-output-result-lines').innerHTML = "";
     document.getElementById('qpisql-scoring-metric-value-result-lines').value = "false";
   }

   /**
    * Event handler that is called if a execution ends with a result
    *
    * @param {sqlResult} result The result object
    */
   onResult(result)
   {
     document.getElementById('qpisql-scoring-metric-not-executed-result-lines').style.display = "none";
     document.getElementById('qpisql-scoring-metric-executed-result-lines').style.display = "inherit";
     document.getElementById('qpisql-scoring-metric-output-result-lines').innerHTML = result.getNumberOfRows();
     document.getElementById('qpisql-scoring-metric-value-result-lines').value = result.getNumberOfRows();
   }

 }
