/**
 * SQL execution helper tailored for the editQuestion page
 * (see assSQLQuestionGUI for more information)
 */
 function executeEditQuestion()
 {
   // At first disable the execute button
   document.getElementById("btn-exec").disabled = true;

   // Get the latest database preparation code
   const db_preparation_code = editor_db_preparation_code.getValue();

   // Get the latest pattern solution code
   const pattern_solution_code = editor_pattern_solution_code.getValue();

   // Execute the queries
   const output = executeCode(db_preparation_code, pattern_solution_code);

   // Create an outputJsonString for easier transport
   const outputJsonString = JSON.stringify(output);

   // Enable the execute button again
   document.getElementById("btn-exec").disabled = false;
 }


/**
 * Simple execution helper
 * e.g. used by executeEditQuestion()
 *
 * @var string db_preparation_code The initialization code of the database
 * @var string execution_code The code that has to be executed
 * @returns mixed An complex array containing the result of the query
 */
 function executeCode(db_preparation_code, execution_code)
 {
   try
   {
     // Setup the database (new database for every execution)
     // This is important as the db_prepation_code might have been changed between to executions
     const db = new sqlDatabase(db_preparation_code);

     // Execute the statement
    return db.executeStatement(execution_code);
   }
   catch(err)
   {
     console.log(err.message);
     throw err;
   }
 }
