/**
 * @file Javascript functions tailored for editQuestion page
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

/**
 * SQL execution function triggered by execute button on editQuestion page
 */
 function executeEditQuestion()
 {
   // At first disable the execute button
   document.getElementById("il_as_qpl_qpisql_execution_button").disabled = true;

   // Get sequence a
   const sequenceA = editor_sequence_a.getValue();

   // Get sequence b
   const sequenceB = editor_sequence_b.getValue();

   // Get sequence c
   const sequenceC = editor_sequence_c.getValue();

   // If no errors are found this will be false atherwise it will be true
   var error_bool = false;

   // Initialize the run variable to be available outside of the try catch, too
   var run;

   // Execute the code
   try
   {
     run = new sqlRun(sequence_a, sequence_b, sequence_c, false);
   }
   catch(err)
   {
     error_bool = true;
     writeErrorsEditQuestion(err, "true");
   } 

   // Inform the user about no errors being found if error_bool is false
   if(!error_bool)
   {
     writeErrorsEditQuestion("No errors found", "false");

     // Display the result
     displayResultEditQuestion(run.getLastResult());
   }

   // Set the executed bool to be true
   document.getElementById('il_as_qpl_qpisql_executed_bool').value = "true";

   // Enable the execute button again
   document.getElementById("il_as_qpl_qpisql_execution_button").disabled = false;
 }

 /**
  * Error log function for editQuestion page
  * Error_Messages will be displayed directly on page
  *
  * @param {string} error_message The message to be displayed
  * @param {string} error_bool The error status - "true" for error found, "false" for no error found
  */
 function writeErrorsEditQuestion(error_message, error_bool)
 {
   document.getElementById('il_as_qpl_qpisql_error_log').innerHTML = error_message;
   document.getElementById('il_as_qpl_qpisql_error_bool').value = error_bool;
 }

 /**
  * Display a result at editQuestion page
  *
  * @param {sqlResult} result The result to be displayed
  */
 function displayResultEditQuestion(result)
 {
   document.getElementById('il_as_qpl_qpisql_output_div').innerHTML = result.toHTMLTable();
   document.getElementById('il_as_qpl_qpisql_statement_output').value = result.toJSON();
 }
