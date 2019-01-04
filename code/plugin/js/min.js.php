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
    $minifier->add(__DIR__.'/../js/sql/sqlResult.js');
    $minifier->add(__DIR__.'/../js/sql/sqlRun.js');
    $minifier->add(__DIR__.'/../js/sql/sqlRunErrors/sqlRunErrorAbstract.js');
    $minifier->add(__DIR__.'/../js/sql/sqlRunErrors/sqlRunErrorDBCreation.js');
    $minifier->add(__DIR__.'/../js/sql/sqlRunErrors/sqlRunErrorIntegrityCheck.js');
    $minifier->add(__DIR__.'/../js/sql/sqlRunErrors/sqlRunErrorNoExecution.js');
    $minifier->add(__DIR__.'/../js/sql/sqlRunErrors/sqlRunErrorNoVisibleResult.js');
    $minifier->add(__DIR__.'/../js/sql/sqlRunErrors/sqlRunErrorRunningSequence.js');

    $minifier->add(__DIR__.'/../js/ExecutionHandler.js');

    $minifier->add(__DIR__.'/../js/ExecutionInputs/ExecutionInput.js');
    $minifier->add(__DIR__.'/../js/ExecutionInputs/SequenceTextareaInput.js');
    $minifier->add(__DIR__.'/../js/ExecutionInputs/IntegrityCheckCheckboxInput.js');

    $minifier->add(__DIR__.'/../js/ExecutionOutputs/ExecutionOutput.js');
    $minifier->add(__DIR__.'/../js/ExecutionOutputs/SequenceTextareaOutput.js');
    $minifier->add(__DIR__.'/../js/ExecutionOutputs/ExecuteButtonOutput.js');
    $minifier->add(__DIR__.'/../js/ExecutionOutputs/IntegrityCheckCheckboxOutput.js');
    $minifier->add(__DIR__.'/../js/ExecutionOutputs/OutputAreaOutput.js');
    $minifier->add(__DIR__.'/../js/ExecutionOutputs/ScoringMetricResultLines.js');

    return $minifier->minify();;
  }
}

// Set the correct MIME TYPE
header('Content-Type: application/javascript');

// Display the minfied JS code
echo Minifier::getMinifiedJSPath();
?>
