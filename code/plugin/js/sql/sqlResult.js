/**
 * @file A class representing a single sql result set
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

/**
 * A single sql result set
 * Used to be prepared for changes of sql framework - abstracts the results and offers functions on them
 */
class sqlResult
{
  /**
   * Constructor of sqlResult
   * Creates an sqlResult out of the complex result array sql.js returns
   *
   * @param {Array} resultArray The array of a single sql.js result
   */
  constructor(resultArray)
  {
    // Set the columns
    this.columns = resultArray["columns"];

    // Set the values
    this.values = resultArray["values"];
  }

  /**
   * Return the sqlResult in the form of json
   *
   * @return {string} The json string
   */
  toJSON()
  {
    return JSON.stringify({"columns": this.columns,"values": this.values});
  }

  /**
   * Return the sqlResult in the form of a html table
   *
   * @return {string} The HTML code of the table
   */
  toHTMLTable()
  {
    // Begin the table
    var html = "<table class='il_as_qpl_qpisql_output_table'>";

    // Insert attribute names as header row
    html += "<tr class='il_as_qpl_qpisql_output_table_header'>";

    for(var i = 0; i < this.columns.length; i++)
    {
      // Insert a new column
      html += "<td><b>" + this.columns[i] + "</b></td>";
    }

    html += "</tr>";

    // Insert the single tuples
    for(var i = 0; i < this.values.length; i++)
    {
      html += "<tr class='il_as_qpl_qpisql_output_table_tuple'>";

      for(var ii = 0; ii < this.values[i].length; ii++)
      {
        html += "<td>" + this.values[i][ii] + "</td>";
      }

      html += "</tr>";
    }

    // End the table
    html += "</table>";

    return html;
  }

  /**
   * Get an array containing all functional dependencies of the result
   * This uses a second database internally (just at the moment - as it is the easiest implemantation)
   *
   * @return {Array} A array of functional dependencies
   */
  getFunctionalDependencies()
  {
    // Create a database and write all data into it
    var db = new SQL.Database();

    // Create a new table featuring the results columns
    db.run("CREATE TABLE result (" + this.columns.join(",") + ")");

    // Insert every value
    for(var i = 0; i < this.values.length; i++)
    {
      db.run("INSERT INTO result VALUES (\"" + this.values[i].join("\",\"") + "\")");
    }

    /**
     * Internal helper function checking a attribute combination for being a functional dependency
     *
     * @param {SQL.Database} db The database we created with the result data
     * @param {string} determinant A comma seperated list of attributes being checked for being the determinant
     * @param {string} dependentAttribute The potentially dependent attribute
     * @return {boolean} A boolean being true if it is a valid functional dependency and false if not
     */
    function isFunctionalDependency(db, determinant, dependentAttribute)
    {
      if(db.exec("SELECT " + determinant + " FROM result GROUP BY " + determinant + " HAVING COUNT (DISTINCT " + dependentAttribute + ") > 1;").length > 0)
      {
        // If there are results the combination is no functional dependency
        return false;
      }

      return true;
    }

    /**
     * Internal helper function checking all attribute combinations recursivly for being a functional dependency
     *
     * @param {SQL.Database} db The database we created with the result data
     * @param {Array} currentDeterminateAttributes All attributes that are already part of the determinat
     * @param {Array} currentRestAttributes All over attributes
     * @return {Array} All valid functional dependencies
     */
    function checkAllAttributeCombinations(db, currentDeterminateAttributes, currentRestAttributes)
    {
      // End of recursion
      if(currentRestAttributes.length == 0)
      {
        return [];
      }

      // Intitialize the return array
      var returnArray = [];

      // Iterate over all the left over attributes and check them for being a
      // dependent attribute first and than adding them to the determinateAttributes
      for(var i = 0; i < currentRestAttributes.length; i++)
      {
        // Check them for being a dependentAttribute of the currentDeterminateAttributes
        if(currentDeterminateAttributes.length > 0 && isFunctionalDependency(db, currentDeterminateAttributes, currentRestAttributes[i]))
        {
          returnArray.push({determinant: currentDeterminateAttributes, dependentAttribute: currentRestAttributes[i]});
        }

        // Copy the currentDeterminateAttributes and currentRestAtributes array
        var cloneCurrentDeterminateAttributes = currentDeterminateAttributes.slice(0);
        var cloneCurrentRestAtributes = currentRestAttributes.slice(0);

        // Add the checked attribute to cloneCurrentDeterminateAttributes
        cloneCurrentDeterminateAttributes.push(currentRestAttributes[i]);

        // Remove the current checked attribute of currentRestAttributes
        cloneCurrentRestAtributes.splice(i, 1);

        // Go further in recursion
        returnArray = returnArray.concat(checkAllAttributeCombinations(db, cloneCurrentDeterminateAttributes, cloneCurrentRestAtributes));

      }

      return returnArray;
    }

    return checkAllAttributeCombinations(db, [], this.columns);

  }

}
