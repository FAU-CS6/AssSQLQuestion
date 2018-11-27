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
  constructor()
  {
    // Create a new database instance
    this.db = new SQL.Database();
  }

  /**
   * Run (without a return value) a single SQl statement
   *
   * @var string statement The statement to be run
   */
  runStatement(statement)
  {
    this.db.run(statement);
  }

  /**
   * Execute (with a return value) a single SQl statement
   *
   * @var string statement The statement to be executed
   * @returns mixed An complex array containing the result of the last query
   */
  executeStatement(statement)
  {
    const output = this.db.exec(statement);

    // We only want the result of the last query - so we have to shorten this
    return output[output.length - 1];
  }


}
