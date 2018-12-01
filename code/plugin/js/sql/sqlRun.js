/**
 * @file A class representing a single sql run through
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
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
   */
  constructor(sequenceA, sequenceB, sequenceC, checkIntegrity)
  {
    // Initalize a member variable for the lastResult
    // This will be set to the result of the last SELECT query in the sequence
    this.lastResult = null;

    // Create a new database instance
    try
    {
      this.db = new SQL.Database();
    }
    catch(err)
    {
      this.db.close();
      throw new sqlRunErrorDBCreation(err.message);
    }

    // Execute sequence a
    try
    {
      this.executeStatementAndSaveToLastResult(sequenceA);
    }
    catch(err)
    {
      this.db.close();
      throw new sqlRunErrorRunningSequence(err.message, "A");
    }

    // If checkIntegrity is set to be true, we have to check the hash value of the database now for the first time
    var hashValueAfterA;

    if(checkIntegrity)
    {
      hashValueAfterA = this.computeHashValueOfDatabase();
    }

    // Execute sequence b
    try
    {
      this.executeStatementAndSaveToLastResult(sequenceB);
    }
    catch(err)
    {
      this.db.close();
      throw new sqlRunErrorRunningSequence(err.message, "B");
    }

    // If checkIntegrity is set to be true, we have to check the hash value of the database now for the second time
    var hashValueBeforeC;

    if(checkIntegrity)
    {
      hashValueBeforeC = this.computeHashValueOfDatabase();

      if(hashValueAfterA != hashValueBeforeC)
      {
        this.db.close();
        throw new sqlRunErrorIntegrityCheck("");
      }
    }

    // Execute sequence c
    try
    {
      this.executeStatementAndSaveToLastResult(sequenceC);
    }
    catch(err)
    {
      this.db.close();
      throw new sqlRunErrorRunningSequence(err.message, "C");
    }

    // If there haven't been any SELECT statements in A, B and C
    // or if these SELECT statements did not return anything we have to throw an error
    if(this.lastResult == null)
    {
      this.db.close();
      throw new sqlRunErrorNoVisibleResult("");
    }

    this.db.close();
  }

  /**
   *
   */

  /**
   * Run (without a return value) a bunch of SQl statements
   *
   * @param {string} statement The statement(s) to be run
   */
  runStatement(statement)
  {
    this.db.run(statement);
  }

  /**
   * Execute (with a return value) a bunch of SQl statements
   *
   * @param {string} statement The statement(s) to be executed
   * @return {Array} An complex array containing the results of all SELECT queries
   */
  executeStatement(statement)
  {
    return this.db.exec(statement);
  }

  /**
   * Execute a bunch of SQL statements and save the last result to lastResult
   * This is a wrapper of executeStatement returning nothing but saving the lastResult in the object
   *
   * @param {string} statement The statement(s) to be executed
   */
  executeStatementAndSaveToLastResult(statement)
  {
    // Execute the Statement
    var results = this.executeStatement(statement);

    // If there is at least one visable result save the last one of them into lastResult
    if(results.length > 0)
    {
      this.lastResult = new sqlResult(results[results.length - 1]);
    }
  }

  /**
   * Computes a simple hash value of the current database
   *
   * @return {number} The computed hash value
   */
  computeHashValueOfDatabase()
  {
    // Initialize hash
    var hash = 0;

    // Get all current tables by executing the suiting SQL query
    var currentTables = this.executeStatement("SELECT name FROM sqlite_master WHERE type = \"table\"");

    // Check if there was a result (no result if there are no tables until now)
    if(currentTables.length < 1)
    {
      // Return hash as there simply is an empty database
      return hash;
    }

    // Iterate through the tables
    for(var i = 0; i < currentTables[0]["values"].length; i++)
    {
      // Add it to the hash value
      hash = (hash + this.computeHashValueOfTable(currentTables[0]["values"][i][0])) % Number.MAX_SAFE_INTEGER;
    }

    return hash;
  }

  /**
   * Computes a simple hash value of a single table in the current database
   * (This is a helper function for computeHashValueOfDatabase())
   *
   * @param {string} tableName The Name of the table that should be checked
   * @return {number} The computed hash value
   */
   computeHashValueOfTable(tableName)
   {
     // Initialize hash
     var hash = 0;

     // Get the content of the table
     var currentContent = this.executeStatement("SELECT * FROM " + tableName);

     // Check if there was a result (no result if table is empty)
     if(currentContent.length < 1)
     {
       // Return hash as there simply is an empty table
       return hash;
     }

     // We only use the table values for computation of the hash - the names of the columns are not checked
     // First iterate through the tuples
     for(var i = 0; i < currentContent[0]["values"].length; i++)
     {
       // Now iterate through the single values
       for(var ii = 0; ii < currentContent[0]["values"][i].length; ii++)
       {
         hash = (hash + this.computeHashValueOfString(String(currentContent[0]["values"][i][ii]))) % Number.MAX_SAFE_INTEGER;
       }
     }

     return hash;
   }

   /**
    * Computes a simple hash value of a single string
    * (This is a helper function for computeHashValueOfDatabase() and computeHashValueOfTable())
    *
    * @param {string} string The string that should be checked
    * @return {number} The computed hash value
    */
   computeHashValueOfString(string)
   {
     // Initialize hash
     var hash = 0;

     // Check if the string is longer than 0
     if(string.length < 1)
     {
       // Return hash as there simply none existent string
       return hash;
     }

     for(var i = 0; i < string.length; i++)
     {
       hash = (hash + string.charCodeAt(i)) % Number.MAX_SAFE_INTEGER;
     }

     return hash;
   }


  /**
   * Getter for the last result
   *
   * @return {sqlResult} The last result
   */
  getLastResult()
  {
    return this.lastResult;
  }
}
