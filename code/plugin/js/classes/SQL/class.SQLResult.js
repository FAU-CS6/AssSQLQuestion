/**
 * @file A class representing a single SQL result set
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

/**
 * A single SQL result set
 * Used to be prepared for changes of sql framework - abstracts the results and offers functions on them
 */
class SQLResult
{
  /**
   * Constructor of SQLResult
   * Creates an SQLResult out of the complex result array sql.js returns
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
   * Return the SQLResult in the form of json
   *
   * @return {string} The json string
   */
  toJSON()
  {
    return JSON.stringify({"columns": this.columns,"values": this.values});
  }

  /**
   * Return the SQLResult in the form of a html table
   *
   * @return {string} The HTML code of the table
   */
  toHTMLTable()
  {
    // Begin the table
    var html = "<table class='qpisql-output-table'>";

    // Insert attribute names as header row
    html += "<tr class='qpisql-output-table-header'>";

    for(var i = 0; i < this.columns.length; i++)
    {
      // Insert a new column
      html += "<td><b>" + this.columns[i] + "</b></td>";
    }

    html += "</tr>";

    // Insert the single tuples
    for(var i = 0; i < this.values.length; i++)
    {
      html += "<tr class='qpisql-output-table-tuple'>";

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
   * Get the number of tuples in the sqlResult
   *
   * @return {number} The number of tuples
   */
  getNumberOfRows()
  {
    return this.values.length;
  }

}
