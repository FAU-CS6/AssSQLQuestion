/**
 * A single database instance (based on sql.js)
 */
class sqlDatabase
{
  /**
   * Constructor of the database instance
   *
   * @var string db_preparation_code The preparation code of the database instance
   */
  constructor(db_preparation_code)
  {
    try
    {
      // Create a new database instance
      this.db = new SQL.Database();
    }
    catch(err)
    {
      console.log("Error creating a new database instance");
      throw err;
    }

    try
    {
      // Run the preparation code
      this.db.run(db_preparation_code);
    }
    catch(err)
    {
      console.log("Error running the preparation code of the database");
      throw err;
    }

  }

  /**
   * Run (without a return value) a single SQl statement
   *
   * @var string statement The statement to be run
   */
  runStatement(statement)
  {
    try
    {
      this.db.run(statement);
    }
    catch(err)
    {
      console.log("Error running a statement");
      throw err;
    }
  }

  /**
   * Execute (with a return value) a single SQl statement
   *
   * @var string statement The statement to be executed
   * @returns mixed An complex array containing the result of the query
   */
  executeStatement(statement)
  {
    try
    {
      return this.db.exec(statement);
    }
    catch(err)
    {
      console.log("Error executing a statement");
      throw err;
    }
  }


}
