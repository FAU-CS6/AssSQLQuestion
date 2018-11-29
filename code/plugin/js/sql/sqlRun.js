/**
 * @file A class representing a single sql run through
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

/*
 * Constants containing usual error messages - to facilitate easier changes by less redundancy
 */

/**
 * Error message for failed creation of the database
 * @type {string}
 */
const errorDBCreation = "Creation of the database failed";

/**
 * Error message for failed run of a sequence
 * @type {string}
 */
const errorRunningSequence = "Error while running sequence";

/**
 * Error message for no SELECT or visible result in the sequences
 * @type {string}
 */
const errorNoVisibleResult = "There is no visible result in the sequence (This might be due to a missing SELECT statement)";

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
      throw errorDBCreation;
    }

    // Execute sequence a
    try
    {
      this.executeStatementAndSaveToLastResult(sequenceA);
    }
    catch(err)
    {
      throw errorRunningSequence + " A \"" + err.message + "\"";
    }

    // If checkIntegrity is set to be true, we have to check the hash value of the database now for the first time
    // TODO

    // Execute sequence b
    try
    {
      this.executeStatementAndSaveToLastResult(sequenceB);
    }
    catch(err)
    {
      throw errorRunningSequence + " B \"" + err.message + "\"";
    }

    // If checkIntegrity is set to be true, we have to check the hash value of the database now for the second time
    // TODO

    // Execute sequence c
    try
    {
      this.executeStatementAndSaveToLastResult(sequenceC);
    }
    catch(err)
    {
      throw errorRunningSequence + " C \"" + err.message + "\"";
    }

    // If there haven't been any SELECT statements in A, B and C
    // or if these SELECT statements did not return anything we have to throw an error
    if(this.lastResult == null)
    {
      throw errorNoVisibleResult;
    }
  }

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
   * Computes a hash value of the current Database
   *
   * @return {string} The computed hash value
   */
  computeHashValueOfDatabase()
  {
    // Get all current tables by executing the suiting SQL query
    var currentTables = this.executeStatement("SELECT name FROM sqlite_master WHERE type = \"table\"");

    // TODO

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
