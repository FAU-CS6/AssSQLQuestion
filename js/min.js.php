<?php
require_once __DIR__.'/../lib/minify/vendor/autoload.php';

use MatthiasMullie\Minify;

/**
 * A internal helper to dynamicly minify the js files
 *
 * This lowers data traffic produced by the plugin and (even more important) is designed
 * to make it harder to reverse engineer the plugins functionality for test participants.
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class Minifier
{
  /**
   * Get a minfied js content
   *
   * It is important to mention that the minfied JS file only includes JS code written only for this project.
   * Used libaries are not included.
   *
   * @return string The content of the JS part
   * @access public
   */
  public static function getMinifiedJSPath()
  {
    // Initialize the minifier
    $minifier = new Minify\JS();

    // Add the JS files to be minified
    // ExecutionHandler
    $minifier->add(__DIR__.'/classes/class.ExecutionHandler.js');

    // ExecutionInputs
    $minifier->add(__DIR__.'/classes/ExecutionInputs/class.ExecutionInput.js');
    $minifier->add(__DIR__.'/classes/ExecutionInputs/class.SequenceTextareaInput.js');
    $minifier->add(__DIR__.'/classes/ExecutionInputs/class.IntegrityCheckCheckboxInput.js');
    $minifier->add(__DIR__.'/classes/ExecutionInputs/class.HiddenFieldInput.js');
    $minifier->add(__DIR__.'/classes/ExecutionInputs/class.HiddenTextareaInput.js');

    // ExecutionOutputs
    $minifier->add(__DIR__.'/classes/ExecutionOutputs/class.ExecutionOutput.js');
    $minifier->add(__DIR__.'/classes/ExecutionOutputs/class.SequenceTextareaOutput.js');
    $minifier->add(__DIR__.'/classes/ExecutionOutputs/class.ExecuteButtonOutput.js');
    $minifier->add(__DIR__.'/classes/ExecutionOutputs/class.IntegrityCheckCheckboxOutput.js');
    $minifier->add(__DIR__.'/classes/ExecutionOutputs/class.OutputAreaOutput.js');
    $minifier->add(__DIR__.'/classes/ExecutionOutputs/class.ScoringMetricEditQuestion.js');
    $minifier->add(__DIR__.'/classes/ExecutionOutputs/class.ScoringMetricOutputQuestion.js');

    // SQL
    $minifier->add(__DIR__.'/classes/SQL/class.SQLResult.js');
    $minifier->add(__DIR__.'/classes/SQL/class.SQLRun.js');
    $minifier->add(__DIR__.'/classes/SQL/SQLRunErrors/class.SQLRunErrorAbstract.js');
    $minifier->add(__DIR__.'/classes/SQL/SQLRunErrors/class.SQLRunErrorDBCreation.js');
    $minifier->add(__DIR__.'/classes/SQL/SQLRunErrors/class.SQLRunErrorIntegrityCheck.js');
    $minifier->add(__DIR__.'/classes/SQL/SQLRunErrors/class.SQLRunErrorNoExecution.js');
    $minifier->add(__DIR__.'/classes/SQL/SQLRunErrors/class.SQLRunErrorNoVisibleResult.js');
    $minifier->add(__DIR__.'/classes/SQL/SQLRunErrors/class.SQLRunErrorRunningSequence.js');

    return $minifier->minify();
  }
}

// Set the correct MIME TYPE
header('Content-Type: application/javascript');

// Display the minfied JS code
echo Minifier::getMinifiedJSPath();
?>
