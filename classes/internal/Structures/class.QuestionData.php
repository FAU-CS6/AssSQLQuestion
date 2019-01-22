<?php
/**
 * Internal structure to store the extra data used in assSQLQuestion. Includes functions to
 * save data to database and load it out of the database
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 */
class ResultLines
{
	/**
	 * @var string The first sql sequence of the question
	 */
	var $sequence_a = "";

	/**
	 * @var string The second sql sequence of the question
	 */
	var $sequence_b = "";

	/**
	 * @var string The third sql sequence of the question
	 */
	var $sequence_c = "";

	/**
	 * @var boolean A boolean indicating whether the question includes an integrity check (true) or not (false)
	 */
	var $integrity_check = false;

	/**
	 * @var boolean A boolean indicating whether the questions sequences contain errors (true) or not (false)
	 */
	var $error_bool = false;

	/**
	 * @var string A json string containing the error of the current sql sequences
	 */
	var $error = "";

	/**
	 * @var boolean A boolean indicating whether the current questions sequences have been executed (true) or not (false)
	 */
	var $executed_bool = false;

	/**
	 * @var string A json string containg the output relation of the current sql sequences
	 */
	var $output_relation = "";

	/**
	 * @var SolutionMetric[] An array containg all pattern solution SolutionMetrics used in this question
	 */
	var $solution_metrics = array();

  /**
	 * Getter and Setter
	 */

	 /**
 	 * Returns the requested sequence (Either sequence_a, sequence_b or sequence_c)
 	 *
	 * @param string $sequence_name The name of the requested sequence
 	 * @return string The first sql sequence
 	 */
 	function getSequence($sequence_name)
 	{
		switch($sequence_name)
		{
			case "sequence_a":
				return $this->sequence_a;
			case "sequence_b":
				return $this->sequence_b;
			case "sequence_c":
				return $this->sequence_c;
		}

		throw new Exception('Requested an unkown sequence');
 	}

 	/**
 	 * Sets a sequence (Either sequence_a, sequence_b or sequence_c)
 	 *
	 * @param string $sequence_name The name of the sequence
 	 * @param string $sequence The first sql sequence
 	 */
 	function setSequence($sequence_name, $sequence)
 	{
		switch($sequence_name)
		{
			case "sequence_a":
				$this->sequence_a = $sequence;
				return;
			case "sequence_b":
				$this->sequence_b = $sequence;
				return;
			case "sequence_c":
				$this->sequence_c = $sequence;
				return;
		}

		throw new Exception('Tried to set an unkown sequence');
 	}

	/**
	 * Returns the integrity_check boolean (true for a integrity check has to be done)
	 *
	 * @return boolean The integrity_check boolean
	 */
	function getIntegrityCheck()
	{
		return $this->integrity_check;
	}

	/**
	 * Sets the integrity_check boolean (true for a integrity check has to be done)
	 *
	 * @param boolean $integrity_check The integrity_check boolean
	 */
	function setIntegrityCheck($integrity_check)
	{
		$this->integrity_check = $integrity_check;
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
	 * Sets the error json of the execution (empty for no errors that have been found)
	 *
	 * @param string $error The error json
	 */
	function setError($error)
	{
		$this->error = $error;
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
	 * Get all SolutionMetrics
	 *
	 * @return SolutionMetric[] A array containing all SolutionMetrics
	 */
	function getAllSolutionMetrics()
	{
		return $this->solution_metrics;
	}

	/**
	 * Get maximum points by adding up all SolutionMetrics
	 *
	 * @return integer The maxium possible points
	 */
	function getMaximumPoints()
	{
		// Initialize with 0
		$maximum_points = 0;

		foreach($this->solution_metrics as $solution_metric)
		{
			$maximum_points += $solution_metric->getPoints();
		}

		return $maximum_points;
	}

	/**
	 * Sets all SolutionMetrics
	 *
	 * @param SolutionMetric[] $solution_metrics An array containg all SolutionMetrics to be set
	 */
	function setAllSolutionMetrics($solution_metrics)
	{
		$this->solution_metrics = $solution_metrics;
	}

	/**
	 * Get all SolutionMetrics with a specific type
	 *
	 * @param string $type The type of the searched SolutionMetric
	 * @return SolutionMetric[] A array containing all metrics with this type
	 */
	function getSolutionMetricsWithType($type)
	{
		$found_metrics = array();

		foreach($this->solution_metrics as $solution_metric)
		{
			if($solution_metric->getType() == $type)
			{
				array_push($found_metrics, $solution_metric);
			}
		}

		return $found_metrics;
	}

	/**
	 * Save a single SolutionMetric
   *
   * @param SolutionMetric $solution_metric The SolutionMetric to be set
	 */
	function setSingleSolutionMetric($solution_metric)
	{
		if(is_a($solution_metric, "SolutionMetric"))
		{
			// Remove existing SolutionMetric with the same type
			for($i = 0; $i < sizeof($this->solution_metrics); $i++)
			{
				if($solution_metric->getType() == $this->solution_metrics[$i]->getType())
				{
					array_splice($this->solution_metrics, $i, 1);
				}
			}

			// Push the new SolutionMetric
			array_push($this->solution_metrics, $solution_metric);
		}
		else
		{
			throw new Exception("Object is no SolutionMetric in setSingleSolutionMetric()");
		}
	}

	/**
	 * Database functions
	 */

	/**
	 * Save data to the database
	 * (See dpupdate.php for more informations on used tables)
   *
   * @param integer $question_id A unique key which defines the question in the database
	 */
	function saveToDb($question_id)
	{
		global $ilDB;

		// Update "il_qpl_qst_qpisql_qd"

		// Delete existing entries with current question id (to avoid double entries)
		$ilDB->manipulate("DELETE FROM il_qpl_qst_qpisql_qd
											 WHERE question_fi = '".$question_id."'");

	  // Insert the current question data
		$ilDB->manipulateF(
			"INSERT INTO il_qpl_qst_qpisql_qd (question_fi,
																				 sequence_a,
																				 sequence_b,
																				 sequence_c,
																				 integrity_check,
																				 error_bool,
																				 error,
																				 executed_bool,
																				 output_relation)
			VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
			array("integer", "text", "text",
						"text", "integer", "integer", "clob",
						"integer", "clob"),
			array($question_id, $this->getSequence('sequence_a'), $this->getSequence('sequence_b'),
						$this->getSequence('sequence_c'), $this->getIntegrityCheck(), $this->getErrorBool(),
						$this->getError(), $this->getExecutedBool(), $this->getOutputRelation())
    );


		// Update "il_qpl_qst_qpisql_qsm"

		// Delete existing entries with current question id (to avoid double entries)
		$ilDB->manipulate("DELETE FROM il_qpl_qst_qpisql_qsm
											 WHERE question_fi = '".$question_id."'");

	  // Insert all current SolutionMetrics
		foreach($this->solution_metrics as $solution_metric)
		{
			$ilDB->manipulateF(
				"INSERT INTO il_qpl_qst_qpisql_qsm (question_fi,
																					  type,
																					  points,
																					  value)
				VALUES (%s, %s, %s, %s)",
				array("integer", "text",
							"integer", "clob"),
				array($question_id, $solution_metric->getType(),
							$solution_metric->getPoints(), $solution_metric->getValue())
	    );
		}
	}

	/**
	 * Load data from the database
	 * (See dpupdate.php for more informations on used tables)
	 *
	 * @param integer $question_id A unique key which defines the question in the database
	 */
	function loadFromDb($question_id)
	{
		global $DIC;
		$ilDB = $DIC->database();

		// Set Sequences and other data from il_qpl_qst_qpisql_qd
		$result_qd = $ilDB->query("SELECT * FROM il_qpl_qst_qpisql_qd WHERE question_fi = "
				. $ilDB->quote($question_id, 'integer'));

		$data_qd = $ilDB->fetchAssoc($result_qd);

		$this->setSequence('sequence_a', $data_qd['sequence_a']);
		$this->setSequence('sequence_b', $data_qd['sequence_b']);
		$this->setSequence('sequence_c', $data_qd['sequence_c']);
		$this->setIntegrityCheck($data_qd['integrity_check']);
		$this->setErrorBool($data_qd['error_bool']);
		$this->setError($data_qd['error']);
		$this->setExecutedBool($data_qd['executed_bool']);
		$this->setOutputRelation($data_qd['output_relation']);

		// Set SolutionMetrics from il_qpl_qst_qpisql_qsm
		$result_qsm = $ilDB->query("SELECT * FROM il_qpl_qst_qpisql_qsm WHERE question_fi = "
				. $ilDB->quote($question_id, 'integer'));

		while($data_qsm = $ilDB->fetchAssoc($result_qsm))
		{
			$this->setSingleSolutionMetric(
				new SolutionMetric($data_qsm['type'],
													$data_qsm['points'],
													$data_qsm['value'])
			);
		}
	}
}
?>
