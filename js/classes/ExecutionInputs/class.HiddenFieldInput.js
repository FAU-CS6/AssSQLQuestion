/**
 * @file ExecutionInput for a hidden field input area
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

 /**
  * ExecutionInput for a single hidden field input area
  */
 class HiddenFieldInput extends ExecutionInput
 {
   /**
    * Constructor
    *
    * @param {string} name The name of the field
    * @param {string} id The id of the field
    */
   constructor(name, id)
   {
     super();

     // Set the name
     this.name = name;

     // Set the id
     this.id = id;
   }

   /**
    * Getter for the value of the input
    *
    * @return {string} The input value
    */
   getValue()
   {
     return document.getElementById(this.id).value;
   }
}
