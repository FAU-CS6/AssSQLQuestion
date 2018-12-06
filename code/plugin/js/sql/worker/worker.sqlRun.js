/**
 * @file A worker wrapping sqlRun (seperated thread for any sqlRun)
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

 // As a worker is a separate thread we need to load all necessary js files again
 importScripts('../sqlRun.js', '../sqlResult.js', '../../../lib/sql.js/sql.js',
               '../sqlRunErrors/sqlRunErrorAbstract.js', '../sqlRunErrors/sqlRunErrorDBCreation.js',
               '../sqlRunErrors/sqlRunErrorIntegrityCheck.js', '../sqlRunErrors/sqlRunErrorNoVisibleResult.js',
               '../sqlRunErrors/sqlRunErrorRunningSequence.js');

onmessage = function(e) {
  // Extract the data
  const sequenceA = e.data["sequenceA"];
  const sequenceB = e.data["sequenceB"];
  const sequenceC = e.data["sequenceC"];
  const integrityCheck = e.data["integrityCheck"];

  // Initialize the run variable to be available outside of the try catch, too
  var run;

  // Create/Start the sqlRun
  try
  {
    run = new sqlRun(sequenceA, sequenceB, sequenceC, integrityCheck);
  }
  catch(err)
  {
    postMessage({"type": "error", "error": err});
    return;
  }

  // Send the result back
  postMessage({"type": "result", "result": run.getLastResult()});
}
