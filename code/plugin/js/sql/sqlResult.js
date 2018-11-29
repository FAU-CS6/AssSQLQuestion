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

}
