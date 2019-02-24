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
      html += "<td>" + this.columns[i] + "</td>";
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

  /**
   * Check whether a specific functional dependency exists in the result
   *
   * @param {Array} determinateAttributes The attributes - to be more specific the names of the columns as string - that are part of the (suspected) determinate
   * @param {Array} dependentAttributes The attributes - to be more specific the names of the columns as string - that are presumably dependent ob the determinate
   *
   * @return {boolean} True for the dependency being existent in the result - false for it being not exsistent
   */
   checkFunctionalDependency(determinateAttributes, dependentAttributes)
   {
     // At first we have to transform the column names to indices
     var determinatePositions = null;
     var dependentPositions = null;

     try
     {
       determinatePositions = this.transformColumnNamesToPositions(determinateAttributes);
       dependentPositions = this.transformColumnNamesToPositions(dependentAttributes);
     }
     catch(err)
     {
       console.warn("You tried to check a functional dependency with non exsistent column names");

       // A functional dependency with non valid column names of course does not exist
       return false;
     }

     // We sort the positions array to ensure the same ordering all the time
     determinatePositions.sort();
     dependentPositions.sort();

     // Initialize two empty arrays - one for the values of the determinateAttributes
     // and one for the values of the dependentAttributes
     var determinateValuesJSON = [];
     var dependentValuesJSON = [];

     // Now we iterate through the whole value array
     for(var i = 0; i < this.values.length; i++)
     {
       // Read the values
       determinateValue = new Array(determinatePositions.length);
       dependentValue = new Array(dependentPositions.length);

       for(var ii = 0; ii < determinatePositions.length; ii++)
       {
         determinateValue[ii] = this.values[i][determinatePositions[ii]];
       }

       for(var ii = 0; ii < dependentPositions.length; ii++)
       {
         dependentValue[ii] = this.values[i][dependentPositions[ii]];
       }

       // Transform the values into a JSON string
       var determinateValueJSON = JSON.stringify(determinateValue);
       var dependentValueJSON = JSON.stringify(dependentValue);

       // Check whether there is already a identical determinateValue in the determinateValuesJSON array
       var positionInArray = determinateValuesJSON.indexOf(determinateValueJSON);

       // If the value already exsists check whether the dependent value is the same like in this case
       if(positionInArray != -1)
       {
         if(determinateValueJSON == determinateValuesJSON[positionInArray] &&
            dependentValueJSON == dependentValueJSON[positionInArray])
          {
            // Do nothing ... this is fine
          }
          else
          {
            // If the same determinateValue has two different dependentValues linked to it the
            // functional dependency is not valid
            return false;
          }
       }
       else
       {
         // Add the combination of determinateValue and dependentValue to the JSON array
         determinateValuesJSON.push(determinateValueJSON);
         dependentValuesJSON.push(dependentValueJSON);
       }
     }

     // If none of the values leads to a contradiction in the values arrays the functional dependency is valid
     return true;
   }

   /**
    * Transform an array of column names into column positions
    *
    * @param {Array} columnNames An array containing column names
    *
    * @return {Array} An array containing the positions of the column names
    */
   transformColumnNamesToPositions(columnNames)
   {
     var columnPositions = new Array(columnNames.length);

     for (var i = 0; i < columnNames.length; i++)
     {
       var index = this.columns.indexOf(columnNames[i]);

       if(index == -1)
       {
         throw new Error("You tried to compute the position of a non exsistent column name");
       }
       // else
       columnPositions[i] = index;
     }

     return columnPositions;
   }

}
