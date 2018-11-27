/**
 * Multi-Use Javascript functions
 **/

/**
 * sqlDatabase output array to JSON string
 *
 * @var mixed output The original output array of sqlDatabase
 * @returns string The JSON string
 */
function outputToJSON(output)
{
  return JSON.stringify(output);
}


/**
 * Javascript functions tailored for editQuestion page
 * see ../classes/class.assSQLQuestionGUI.php for corresponding editQuestion function
 **/

/**
 * SQL execution helper tailored for the editQuestion page
 */
 function executeEditQuestion()
 {
   // At first disable the execute button
   document.getElementById("btn-exec").disabled = true;

   // Get the latest database preparation code
   const db_preparation_code = editor_db_preparation_code.getValue();

   // Get the latest pattern solution code
   const pattern_solution_code = editor_pattern_solution_code.getValue();

   // Initialize the db variable to be available outside of the try catch block
   var db;

   try
   {
     // Setup the database (new database for every execution)
     // This is important as the db_prepation_code might have been changed between to executions
     db = new sqlDatabase();
   }
   catch(err)
   {
     writeErrorsEditQuestion("Database could not be created", "true");

     // If this failed the execution has to be aborted
     // This includes enableing the execute button again to be ready for another try
     document.getElementById("btn-exec").disabled = false;
     return;
   }

   try
   {
     // Run the preparation code
     db.runStatement(db_preparation_code);
   }
   catch(err)
   {
     writeErrorsEditQuestion("Error while executing the preparation code: \"" + err.message + "\"", "true");

     // If this failed the execution has to be aborted
     // This includes enableing the execute button again to be ready for another try
     document.getElementById("btn-exec").disabled = false;
     return;
   }

   // Initialize the output variable to be available outside of the try catch block
   var output;

   try
   {
     // Execute the pattern solution code
     output = db.executeStatement(pattern_solution_code);
   }
   catch(err)
   {
     writeErrorsEditQuestion("Error while executing the solution code: \"" + err.message + "\"", "true");

     // If this failed the execution has to be aborted
     // This includes enableing the execute button again to be ready for another try
     document.getElementById("btn-exec").disabled = false;
     return;
   }

   console.log(output);

   // Create an outputJsonString for easier transport
   const outputJsonString = JSON.stringify(output);

   // Inform the user about no errors being found
   writeErrorsEditQuestion("No errors found", "false");

   // Set the executed bool to be true
   document.getElementById('executed_bool').value = "true";

   // Enable the execute button again
   document.getElementById("btn-exec").disabled = false;
 }

 /**
  * Error log function for editQuestion page
  * Error_Messages will be displayed directly on page
  *
  * @var string error_message The message to be displayed
  * @var string error_bool The error status - "true" for error found, "false" for no error found
  */
 function writeErrorsEditQuestion(error_message, error_bool)
 {
   document.getElementById('error_log').innerHTML = error_message;
 }

 /**
  * Output display function for editQuestion page
  *
  * @var mixed output The Output array to be displayed
  */
 function outputDisplayEditQuestion(output)
 {
   document.getElementById('error_log').innerHTML = error_message;
 }
