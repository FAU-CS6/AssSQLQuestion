<?php
/**
 * A internal helper class to define a solid structure for the participants input
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class ParticipantInput
{
	/**
	 * @var string The sql sequence the participant entered
	 */
	var $sequence = "";

	/**
	 * @var boolean A boolean indicating whether the participants sequence(s) contain errors (true) or not (false)
	 */
	var $error_bool = false;

	/**
	 * @var string A json string containing the error of the current sql sequences
	 */
	var $error = "";

	/**
	 * @var boolean A boolean indicating whether the participants sequence(s) have been executed (true) or not (false)
	 */
	var $executed_bool = false;

	/**
	 * @var string A json string containg the output relation of the participants sequence(s)
	 */
	var $output_relation = "";

	/**
	 * @var ParticipantMetric[] An array containg all pattern solution ParticipantMetrics used in this question
	 */
	var $participant_metrics = array();

  /**
   * Constructor
   *
   * The construtor of a single ParticipantInput
   *
   * @access public
   */
  function __construct()
  {
    // Do nothing
  }

  /**
   * Serialize a ParticipantInput object to a JSON string
   *
   * @return string The JSON string
   */
  function toJSON()
  {
    // To use json_encode we need an array containing the values of the object
    $arr = array('sequence' => $this->sequence,
                 'error_bool' => $this->error_bool,
                 'error' => $this->error,
                 'executed_bool' => $this->executed_bool,
                 'output_relation' => $this->output_relation);

   // Additionally we of course need the ParticipantMetrics
   $participant_metrics_json = array();

   foreach ($this->participant_metrics as $participant_metric)
   {
     array_push($participant_metrics_json, $participant_metric->toJSON());
   }

   $arr['participant_metrics'] = $participant_metrics_json;

   return json_encode($arr);
  }

  /**
   * Unserialize a JSON string into a ParticipantInput
   *
   * @param string $json The json string that should be transformed into a ParticipantInput
   * @return ParticipantInput The resulting ParticipantInput
   */
  static function fromJSON($json)
  {
    // At first we decode the JSON
    $decoded_json = null;

    // This might fail if the JSON is not valid at all
    try
    {
      $decoded_json = json_decode($json, true);
    }
    catch (Exception $e)
    {
      throw new Exception("We need a valid JSON Structure to decode it into a ParticipantInput");
    }

    // Now we build the ParticipantInput by setting all values
    $participant_input = new ParticipantInput();

    // The sequence
    if(array_key_exists('sequence', $decoded_json))
    {
      $participant_input->setSequence((string) $decoded_json['sequence']);
    }
    else
    {
      throw new Exception("We need a sequence to decode a JSON into a ParticipantInput");
    }

    // The error_bool
    if(array_key_exists('error_bool', $decoded_json))
    {
      $participant_input->setErrorBool($decoded_json['error_bool'] == "true" ? true : false);
    }
    else
    {
      throw new Exception("We need an error_bool to decode a JSON into a ParticipantInput");
    }

		// The error
    if(array_key_exists('error', $decoded_json))
    {
      $participant_input->setError($decoded_json['error']);
    }
    else
    {
      throw new Exception("We need an error to decode a JSON into a ParticipantInput");
    }

		// The executed_bool
    if(array_key_exists('executed_bool', $decoded_json))
    {
      $participant_input->setExecutedBool($decoded_json['executed_bool'] == "true" ? true : false);
    }
    else
    {
      throw new Exception("We need an executed_bool to decode a JSON into a ParticipantInput");
    }

		// The output_relation
    if(array_key_exists('output_relation', $decoded_json))
    {
      $participant_input->setOutputRelation($decoded_json['output_relation']);
    }
    else
    {
      throw new Exception("We need an output_relation to decode a JSON into a ParticipantInput");
    }

		// The participant_metrics
		if(array_key_exists('participant_metrics', $decoded_json))
    {
			foreach($decoded_json['participant_metrics'] as $participant_metric)
			{
				// Decode the inner JSON as well
				$participant_metric_decoded = json_decode($participant_metric, true);

				$participant_input->setSingleParticipantMetric(new ParticipantMetric($participant_metric_decoded['type'],
																																						 $participant_metric_decoded['value']));
			}
    }
    else
    {
      throw new Exception("We need the participant_metrics to decode a JSON into a ParticipantInput");
    }

		return $participant_input;
  }

  /**
   * Returns the sql sequence
   *
   * @return string The sql sequence
   */
  function getSequence()
  {
    return $this->sequence;
  }

 /**
  * Sets the sql sequence
  *
  * @param string $sequence The sql sequence
  */
 function setSequence($sequence)
 {
    $this->sequence = $sequence;
 }

 /**
  * Returns the error state of the execution (true for errors have been found)
  *
  * @return boolean The error state
  */
 function getErrorBool()
 {
   return $this->error_bool;
 }

 /**
  * Sets the error state of the execution (true for errors have been found)
  *
  * @param boolean $error_bool The error state of the execution
  */
 function setErrorBool($error_bool)
 {
   $this->error_bool = $error_bool;
 }

 /**
  * Returns the error json of the execution (empty for no errors that have been found)
  *
  * @return string The error json
  */
 function getError()
 {
   return $this->error;
 }

 /**
  * Sets the error json of the execution (empty for no errors that have been found)
  *
  * @param string $error The error json
  */
 function setError($error)
 {
   $this->error = $error;
 }

 /**
  * Returns the execution state (true for code was executed)
  *
  * @return boolean The execution state
  */
 function getExecutedBool()
 {
   return $this->executed_bool;
 }

 /**
  * Sets the execution state (true for code was executed)
  *
  * @param boolean $executed_bool The execution state
  */
 function setExecutedBool($executed_bool)
 {
   $this->executed_bool = $executed_bool;
 }

 /**
  * Returns the output relation
  *
  * @return string The output relation
  */
 function getOutputRelation()
 {
   return $this->output_relation;
 }

 /**
  * Sets the output relation
  *
  * @param string $output_relation The output relation
  */
 function setOutputRelation($output_relation)
 {
   $this->output_relation = $output_relation;
 }

 /**
  * Get all ParticipantMetrics
  *
  * @return ParticipantMetric[] A array containing all ParticipantMetrics
  */
 function getAllParticipantMetrics()
 {
   return $this->participant_metrics;
 }

 /**
  * Sets all ParticipantMetrics
  *
  * @param ParticipantMetric[] $participant_metrics An array containg all ParticipantMetrics to be set
  */
 function setAllPartcipantMetrics($participant_metrics)
 {
   $this->participant_metrics = $participant_metrics;
 }

 /**
  * Get all ParticipantMetrics with a specific type
  *
  * @param string $type The type of the searched ParticipantMetric
  * @return ParticipantMetric[] A array containing all metrics with this type
  */
 function getParticipantMetricsWithType($type)
 {
   $found_metrics = array();

   foreach($this->participant_metrics as $participant_metric)
   {
     if($participant_metric->getType() == $type)
     {
       array_push($found_metrics, $participant_metric);
     }
   }

   return $found_metrics;
 }

 /**
  * Save a single ParticipantMetric
  *
  * @param ParticipantMetric $participant_metric The ParticipantMetric to be set
  */
 function setSingleParticipantMetric($participant_metric)
 {
   if(is_a($participant_metric, "ParticipantMetric"))
   {
     // Remove existing SolutionMetric with the same type
     for($i = 0; $i < sizeof($this->participant_metrics); $i++)
     {
       if($participant_metric->getType() == $this->participant_metrics[$i]->getType())
       {
         array_splice($this->participant_metrics, $i, 1);
       }
     }

     // Push the new SolutionMetric
     array_push($this->participant_metrics, $participant_metric);
   }
   else
   {
     throw new Exception("Object is no ParticipantMetric in setSingleParticipantMetric()");
   }
 }

}
?>
