/**
 * Multi-Use Javascript functions
 **/

/**
 * executeSolution() output array to JSON string
 *
 * @var mixed output The original output array of sqlDatabase
 * @returns string The JSON string
 */
function outputToJSON(output)
{
  return JSON.stringify(output);
}

/**
 * executeSolution() output array to a HTML table
 *
 * @var mixed output The original output array of sqlDatabase
 * @returns string The HTML code of the table
 */
function outputToHTMLTable(output)
{
  // Begin the table
  var html = "<table class='il_as_qpl_qpisql_output_table'>";

  // Insert attribute names as header row
  html += "<tr class='il_as_qpl_qpisql_output_table_header'>";

  for(var i = 0; i < output["columns"].length; i++)
  {
    // Insert a new column
    html += "<td><b>" + output["columns"][i] + "</b></td>";
  }

  html += "</tr>";

  // Insert the single tuples
  for(var i = 0; i < output["values"].length; i++)
  {
    html += "<tr class='il_as_qpl_qpisql_output_table_tuple'>";

    for(var ii = 0; ii < output["values"][i].length; ii++)
    {
      html += "<td>" + output["values"][i][ii] + "</td>";
    }

    html += "</tr>";
  }

  // End the table
  html += "</table>";

  return html;
}

/**
 * Execute a solution
 * Includes creating the database, running the preparation statements and executing the solution
 *
 * @var string db_preparation_code The preparation code for the database
 * @var string solution_code The solution code
 * @returns mixed The output array
 */
function executeSolution(db_preparation_code, solution_code)
{
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
    throw "Database could not be created";
  }

  try
  {
    // Run the preparation code
    db.runStatement(db_preparation_code);
  }
  catch(err)
  {
    throw "Error while executing the preparation code: \"" + err.message + "\"";
  }

  // Initialize the output variable to be available outside of the try catch block
  var output;

  try
  {
    // Execute the pattern solution code
    output = db.executeStatement(solution_code);
  }
  catch(err)
  {
    throw "Error while executing the solution or the completion code: \"" + err.message + "\"";
  }

  return output;
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
   document.getElementById("il_as_qpl_qpisql_execution_button").disabled = true;

   // Get the latest database preparation code
   const db_preparation_code = editor_db_preparation_code.getValue();

   // Get the latest pattern solution code
   const pattern_solution_code = editor_pattern_solution_code.getValue();

   // Get the latest completion_code
   const completion_code = editor_completion_code.getValue();

   // If no errors are found this will be false atherwise it will be true
   var error_bool = false;

   // Initialize the output variable to be available outside of the try catch block
   var output;

   // Execute the code
   try
   {
     output = executeSolution(db_preparation_code, pattern_solution_code + " " + completion_code);
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

     // Display the output
     outputDisplayEditQuestion(output);
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
  * @var string error_message The message to be displayed
  * @var string error_bool The error status - "true" for error found, "false" for no error found
  */
 function writeErrorsEditQuestion(error_message, error_bool)
 {
   document.getElementById('il_as_qpl_qpisql_error_log').innerHTML = error_message;
 }

 /**
  * Output display function for editQuestion page
  *
  * @var mixed output The Output array to be displayed
  */
 function outputDisplayEditQuestion(output)
 {
   document.getElementById('il_as_qpl_qpisql_output_div').innerHTML = outputToHTMLTable(output);
   document.getElementById('il_as_qpl_qpisql_statement_output').value = outputToJSON(output);
 }
