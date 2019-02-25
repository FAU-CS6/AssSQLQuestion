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
   * Get the number of tuples in the SQLResult
   *
   * @return {number} The number of tuples
   */
  getNumberOfRows()
  {
    return this.values.length;
  }

  /**
   * Get all (minimal) functional dependencies in the result
   *
   * @return {Array} All (minimal) functional dependencies ({determinate: [Array], dependent: [Array]}) in the result
   */
  getAllFunctionalDependencies()
  {
    // As recursion is needed for this we pass this to the recursiv helper function
    return this.findFunctionalDependencies([],this.columns.slice());
  }

  /**
   * Recursive helper for getAllFunctionalDependencies()
   *
   * @param {Array} currentDeterminate The attributes that are part of the determinate
   * @param {Array} undecidedAttributes The attributes that might become part of the determinate OR be dependent
   *
   * @return {Array} A array of all (minimal) functional dependencies ({determinate: [Array], dependent: [Array]}) found in this branch of the recursion
   */
  findFunctionalDependencies(currentDeterminate, undecidedAttributes)
  {
    // If there are no undecidedAttributes the recursion is finished
    if(undecidedAttributes.length < 1)
    {
      return [];
    }

    // Create a return array
    var returnArray = [];

    // If there are undecidedAttributes
    for(var i = 0; i < undecidedAttributes.length; i++)
    {
      // Make a local copy of currentDeterminates
      var newDeterminate = currentDeterminate.slice();

      // Add the [i] element of undecidedAttributes to it
      newDeterminate.push(undecidedAttributes[i]);

      // Make two slices of undecidedAttributes
      // This is an exact copy of undecidedAttributes with the [i] attribute removed
      var newUndecidedAttributes = undecidedAttributes.slice();
      newUndecidedAttributes.splice(i, 1);

      // We need to save the positions of the found functional dependencies as we might need them later on
      var positions = [];

      // Now check whether the (new) determinate if a real determinate for
      for(var ii = 0; ii < newUndecidedAttributes.length; ii++)
      {
        // Check whether undecidedAttributes[i] is dependent on the currentDeterminates
        if(this.checkFunctionalDependency(newDeterminate, [newUndecidedAttributes[ii]]))
        {
          // Add the new functional dependency to the return array
          returnArray.push({determinate: newDeterminate, dependent: [newUndecidedAttributes[ii]]});

          // As this undecidedAttribute is dependent on the currentDeterminate
          // we will not get any new minimal functional dependencies by adding it to the determinate
          // (would be a transitive functional dependency) - we can remove it from the newUndecidedAttributes array
          positions.push(ii);
        }
      }
      console.log("NewDeterminate:");
      console.log(newDeterminate);
      console.log("Positions:");
      console.log(positions);
      console.log("newUndecidedAttributes - before slice:")
      console.log(newUndecidedAttributes);

      // We are only interested in undecidedAttributes with an index higher than i for this recursion
      // By this we can double functional dependencies
      // newUndecidedAttributes = newUndecidedAttributes.slice(i);

      console.log("newUndecidedAttributes - after slice:")
      console.log(newUndecidedAttributes);

      var offset = 0;

      // Now we need to remove the positions that are still part of newUndecidedAttributes
      for(var ii = 0; ii < positions.length; ii++)
      {
        console.log("newUndecidedAttributes - while slice "+ii+" (1):");
        console.log(newUndecidedAttributes);
        console.log("Positions - Offset:");
        console.log(positions[ii] + " - " + offset);
        if(positions[ii] >= i)
        {
            newUndecidedAttributes.splice(positions[ii]-offset, 1);

            offset++;
        }
        console.log("newUndecidedAttributes - while slice "+ii+" (2):");
        console.log(newUndecidedAttributes);
      }

      console.log("newUndecidedAttributes - after splice:")
      console.log(newUndecidedAttributes);

      // Go deeper into the recursion
      returnArray.concat(this.findFunctionalDependencies(newDeterminate, newUndecidedAttributes));
    }

    return returnArray;
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
     // If at least one of the two attributes lists is empty it is no valid functional dependency
     if(determinateAttributes.length < 1 || dependentAttributes.length < 1)
     {
       return false;
     }

     // After that check we have to transform the column names to indices
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
       var determinateValue = new Array(determinatePositions.length);
       var dependentValue = new Array(dependentPositions.length);

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
            dependentValueJSON == dependentValuesJSON[positionInArray])
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
