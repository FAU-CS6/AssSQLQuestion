<?php

require_once "./Modules/TestQuestionPool/classes/class.assQuestion.php";
require_once "internal/class.qpisql.scoringMetric.php";

/**
 * Main defintion of the SQLQuestion plugin
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @ingroup ModulesTestQuestionPool
 */
class assSQLQuestion extends assQuestion
{
	/**
	 * Member variables that have to be part of every assQuestion
	 */

	/**
	 * @var ilassSQLQuestionPlugin The plugin object
	 */
	protected $plugin = null;

	/**
	 * Custom member variables/constants for an assSQLQuestion
	 */

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
	 * @var boolean A boolean indicating whether the current questions sequences have been executed (true) or not (false)
	 */
	var $executed_bool = false;

	/**
	 * @var string A json string containg the output relation of the current sql sequences
	 */
	var $output_relation = "";

	/**
	 * @var scoringMetric[] An array containg all computed scoring metrics used in this question
	 */
	var $scoring_metrics = array();

	/**
	 * Member functions that have to be part of every assQuestion
	 */

	/**
	 * Constructor
	 *
	 * The constructor takes possible arguments and creates an instance of the question object.
	 *
	 * @param string $title A title string to describe the question
	 * @param string $comment A comment string to describe the question
	 * @param string $author A string containing the name of the questions author
	 * @param integer $owner A numerical ID to identify the owner/creator
	 * @param string $question Question text
	 * @access public
	 *
	 * @see assQuestion:assQuestion()
	 */
	function __construct(
		$title = '',
		$comment = '',
		$author = '',
		$owner = -1,
		$question = ''
	)
	{
		// needed for excel export
		$this->getPlugin()->loadLanguageModule();

		parent::__construct($title, $comment, $author, $owner, $question);
	}

	/**
	 * Returns the question type of the question
	 *
	 * @return string The question type of the question
	 */
	public function getQuestionType()
	{
		return 'assSQLQuestion';
	}

	/**
	 * Returns the names of the used additional question data tables
	 *
	 * @return array The names of the additional tables
	 */
	public function getAdditionalTableName()
	{
		return array(
			'il_qpl_qst_qpisql_qd',
			'il_qpl_qst_qpisql_qsm'
		);
	}

	/**
	 * Collects all texts in the question which could contain media objects
	 * which were created with the Rich Text Editor
	 */
	protected function getRTETextWithMediaObjects()
	{
		$text = parent::getRTETextWithMediaObjects();

		return $text;
	}

	/**
	 * Get the plugin object
	 *
	 * @return object The plugin object
	 */
	public function getPlugin()
	{
		if ($this->plugin == null)
		{
			$this->plugin = ilPlugin::getPluginObject(IL_COMP_MODULE, 'TestQuestionPool', 'qst', 'assSQLQuestion');

		}
		return $this->plugin;
	}

	/**
	 * Returns true, if the question is complete
	 *
	 * @return boolean True, if the question is complete for use, otherwise false
	 */
	public function isComplete()
	{
		// Check whether the question is complete
		if(!empty($this->title) &&
			 !empty($this->author) &&
			 !empty($this->question) &&
			 $this->getSequenceB() != "" &&
			 $this->getExecutedBool() == "true" &&
	     $this->getErrorBool() == "false" &&
			 $this->getMaximumPoints() > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Saves a question object to a database
	 *
	 * @param	string $original_id The original id
	 * @access public
	 * @see assQuestion::saveToDb()
	 */
	function saveToDb($original_id = '')
	{
		// Save the basic data (implemented in assQuestion)
		$this->saveQuestionDataToDb($original_id);

		// Save the assSQLQuestion specific data to the database
		$this->saveSpecificQuestionDataToDb();

		// update the question time stamp and completion status
		parent::saveToDb();
	}

	/**
	 * Loads a question object from a database
	 *
	 * @param integer $question_id A unique key which defines the question in the database
	 * @see assQuestion::loadFromDb()
	 */
	public function loadFromDb($question_id)
	{
		// Load the basic data
		$this->loadQuestionDataFromDb($question_id);

		// Load the assSQLQuestion specific data
		$this->loadSpecificQuestionDataFromDb($question_id);

		try
		{
			$this->setAdditionalContentEditingMode($data['add_cont_edit_mode']);
		}
		catch(ilTestQuestionPoolException $e)
		{
		}

		// loads additional stuff like suggested solutions
		parent::loadFromDb($question_id);
	}

	/**
	 * Helper function for loadFromDb().
	 * Loads the basic data from the database
	 *
	 * @param integer $question_id A unique key which defines the question in the database
	 * @access private
	 */
	private function loadQuestionDataFromDb($question_id)
	{
		global $DIC;
		$ilDB = $DIC->database();

		$result = $ilDB->query("SELECT qpl_questions.* FROM qpl_questions WHERE question_id = "
				. $ilDB->quote($question_id, 'integer'));

		$data = $ilDB->fetchAssoc($result);
		$this->setId($question_id);
		$this->setObjId($data['obj_fi']);
		$this->setOriginalId($data['original_id']);
		$this->setOwner($data['owner']);
		$this->setTitle($data['title']);
		$this->setAuthor($data['author']);
		$this->setPoints($data['points']);
		$this->setComment($data['description']);
		$this->setSuggestedSolution($data['solution_hint']);

		$this->setQuestion(ilRTE::_replaceMediaObjectImageSrc($data['question_text'], 1));
		$this->setEstimatedWorkingTime(substr($data['working_time'], 0, 2), substr($data['working_time'], 3, 2), substr($data['working_time'], 6, 2));
	}

	/**
	 * Duplicates a question
	 * This is used for copying a question to a test
	 *
	 * @param bool   		$for_test
	 * @param string 		$title
	 * @param string 		$author
	 * @param string 		$owner
	 * @param integer|null	$testObjId
	 *
	 * @return void|integer Id of the clone or nothing.
	 */
	public function duplicate($for_test = true, $title = '', $author = '', $owner = '', $testObjId = null)
	{
		if ($this->getId() <= 0)
		{
			// The question has not been saved. It cannot be duplicated
			return;
		}

		// make a real clone to keep the actual object unchanged
		$clone = clone $this;

		$original_id = assQuestion::_getOriginalId($this->getId());
		$clone->setId(-1);

		if( (int) $testObjId > 0 )
		{
			$clone->setObjId($testObjId);
		}

		if (!empty($title))
		{
			$clone->setTitle($title);
		}
		if (!empty($author))
		{
			$clone->setAuthor($author);
		}
		if (!empty($owner))
		{
			$clone->setOwner($owner);
		}

		if ($for_test)
		{
			$clone->saveToDb($original_id);
		}
		else
		{
			$clone->saveToDb();
		}

		// copy question page content
		$clone->copyPageOfQuestion($this->getId());
		// copy XHTML media objects
		$clone->copyXHTMLMediaObjectsOfQuestion($this->getId());

		// call the event handler for duplication
		$clone->onDuplicate($this->getObjId(), $this->getId(), $clone->getObjId(), $clone->getId());

		return $clone->getId();
	}

	/**
	 * Copies a question
	 * This is used when a question is copied on a question pool
	 *
	 * @param integer	$target_questionpool_id
	 * @param string	$title
	 *
	 * @return void|integer Id of the clone or nothing.
	 */
	function copyObject($target_questionpool_id, $title = '')
	{
		if ($this->getId() <= 0)
		{
			// The question has not been saved. It cannot be duplicated
			return;
		}

		// make a real clone to keep the object unchanged
		$clone = clone $this;

		$original_id = assQuestion::_getOriginalId($this->getId());
		$source_questionpool_id = $this->getObjId();
		$clone->setId(-1);
		$clone->setObjId($target_questionpool_id);
		if (!empty($title))
		{
			$clone->setTitle($title);
		}

		// save the clone data
		$clone->saveToDb();

		// copy question page content
		$clone->copyPageOfQuestion($original_id);
		// copy XHTML media objects
		$clone->copyXHTMLMediaObjectsOfQuestion($original_id);

		// call the event handler for copy
		$clone->onCopy($source_questionpool_id, $original_id, $clone->getObjId(), $clone->getId());

		return $clone->getId();
	}

	/**
	 * Create a new original question in a question pool for a test question
	 * @param int $targetParentId			id of the target question pool
	 * @param string $targetQuestionTitle
	 * @return int|void
	 */
	public function createNewOriginalFromThisDuplicate($targetParentId, $targetQuestionTitle = '')
	{
		if ($this->id <= 0)
		{
			// The question has not been saved. It cannot be duplicated
			return;
		}

		$sourceQuestionId = $this->id;
		$sourceParentId = $this->getObjId();

		// make a real clone to keep the object unchanged
		$clone = clone $this;
		$clone->setId(-1);

		$clone->setObjId($targetParentId);

		if (!empty($targetQuestionTitle))
		{
			$clone->setTitle($targetQuestionTitle);
		}

		$clone->saveToDb();
		// copy question page content
		$clone->copyPageOfQuestion($sourceQuestionId);
		// copy XHTML media objects
		$clone->copyXHTMLMediaObjectsOfQuestion($sourceQuestionId);

		$clone->onCopy($sourceParentId, $sourceQuestionId, $clone->getObjId(), $clone->getId());

		return $clone->getId();
	}

	/**
	 * Synchronize a question with its original
	 * You need to extend this function if a question has additional data that needs to be synchronized
	 *
	 * @access public
	 */
	function syncWithOriginal()
	{
		parent::syncWithOriginal();
	}


	/**
	 * Get a submitted solution array from $_POST
	 *
	 * In general this may return any type that can be stored in a php session
	 * The return value is used by:
	 * 		savePreviewData()
	 * 		saveWorkingData()
	 * 		calculateReachedPointsForSolution()
	 *
	 * @return	array	('value1' => string|null, 'value2' => float|null)
	 */
	protected function getSolutionSubmit()
	{
		$value1 = trim(ilUtil::stripSlashes($_POST['question'.$this->getId().'value1']));
		$value2 = trim(ilUtil::stripSlashes($_POST['question'.$this->getId().'value2']));

		return array(
			'value1' => empty($value1)? null : (string) $value1,
			'value2' => empty($value2)? null : (float) $value2
		);
	}

	/**
	 * Get a stored solution for a user and test pass
	 * This is a wrapper to provide the same structure as getSolutionSubmit()
	 *
	 * @param int 	$active_id		active_id of hte user
	 * @param int	$pass			number of the test pass
	 * @param bool	$authorized		get the authorized solution
	 *
	 * @return	array	('value1' => string|null, 'value2' => float|null)
	 */
	public function getSolutionStored($active_id, $pass, $authorized = null)
	{
		// This provides an array with records from tst_solution
		// The example question should only store one record per answer
		// Other question types may use multiple records with value1/value2 in a key/value style
		if (isset($authorized))
		{
			// this provides either the authorized or intermediate solution
			$solutions = $this->getSolutionValues($active_id, $pass, $authorized);
		}
		else
		{
			// this provides the solution preferring the intermediate
			// or the solution from the previous pass
			$solutions = $this->getTestOutputSolutions($active_id, $pass);
		}


		if (empty($solutions))
		{
			// no solution stored yet
			$value1 = null;
			$value2 = null;
		}
		else
		{
			// If the process locker isn't activated in the Test and Assessment administration
			// then we may have multiple records due to race conditions
			// In this case the last saved record wins
			$solution = end($solutions);

			$value1 = $solution['value1'];
			$value2 = $solution['value2'];
		}

		return array(
			'value1' => empty($value1)? null : (string) $value1,
			'value2' => empty($value2)? null : (float) $value2
		);
	}


	/**
	 * Calculate the reached points from a solution array
	 *
	 * @param	array	('value1' => string, 'value2' => float)
	 * @return  float	reached points
	 */
	protected function calculateReachedPointsForSolution($solution)
	{
		// in our example we take the points entered by the student
		// and adjust them to be in the allowed range
		$points = (float) $solution['value2'];
		if ($points <= 0 || $points > $this->getMaximumPoints())
		{
			$points = 0;
		}

		// return the raw points given to the answer
		// these points will afterwards be adjusted by the scoring options of a test
		return $points;
	}


	/**
	 * Returns the points, a learner has reached answering the question
	 * The points are calculated from the given answers.
	 *
	 * @param int $active_id
	 * @param integer $pass The Id of the test pass
	 * @param bool $authorizedSolution
	 * @param boolean $returndetails (deprecated !!)
	 * @return int
	 *
	 * @throws ilTestException
	 */
	public function calculateReachedPoints($active_id, $pass = NULL, $authorizedSolution = true, $returndetails = false)
	{
		if( $returndetails )
		{
			throw new ilTestException('return details not implemented for '.__METHOD__);
		}

		if(is_null($pass))
		{
			$pass = $this->getSolutionMaxPass($active_id);
		}

		// get the answers of the learner from the tst_solution table
		// the data is saved by saveWorkingData() in this class
		$solution = $this->getSolutionStored($active_id, $pass, $authorizedSolution);

		return $this->calculateReachedPointsForSolution($solution);
	}


	/**
	 * Saves the learners input of the question to the database.
	 *
	 * @param integer $active_id 	Active id of the user
	 * @param integer $pass 		Test pass
	 * @param boolean $authorized	The solution is authorized
	 *
	 * @return boolean $status
	 */
	function saveWorkingData($active_id, $pass = NULL, $authorized = true)
	{
		if (is_null($pass))
		{
			$pass = ilObjTest::_getPass($active_id);
		}

		// get the submitted solution
		$solution = $this->getSolutionSubmit();

		$entered_values = 0;

		// save the submitted values avoiding race conditions
		$this->getProcessLocker()->executeUserSolutionUpdateLockOperation(function() use (&$entered_values, $solution, $active_id, $pass, $authorized) {


			$entered_values = isset($solution['value1']) || isset($solution['value2']);

			if ($authorized)
			{
				// a new authorized solution will delete the old one and the intermediate
				$this->removeExistingSolutions($active_id, $pass);
			}
			elseif ($entered_values)
			{
				// an new intermediate solution will only delete a previous one
				$this->removeIntermediateSolution($active_id, $pass);
			}

			if ($entered_values)
			{
				$this->saveCurrentSolution($active_id, $pass, $solution['value1'],  $solution['value2'], $authorized);
			}
		});


		// Log whether the user entered values
		if (ilObjAssessmentFolder::_enabledAssessmentLogging())
		{
			assQuestion::logAction($this->lng->txtlng(
				'assessment',
				$entered_values ? 'log_user_entered_values' : 'log_user_not_entered_values',
				ilObjAssessmentFolder::_getLogLanguage()
			),
				$active_id,
				$this->getId()
			);
		}

		// submitted solution is valid
		return true;
	}


	/**
	 * Reworks the already saved working data if neccessary
	 * @param integer $active_id
	 * @param integer $pass
	 * @param boolean $obligationsAnswered
	 * @param boolean $authorized
	 */
	protected function reworkWorkingData($active_id, $pass, $obligationsAnswered, $authorized)
	{
		// normally nothing needs to be reworked
	}


	/**
	 * Creates an Excel worksheet for the detailed cumulated results of this question
	 *
	 * @param object $worksheet    Reference to the parent excel worksheet
	 * @param int $startrow     Startrow of the output in the excel worksheet
	 * @param int $active_id    Active id of the participant
	 * @param int $pass         Test pass
	 *
	 * @return int
	 */
	public function setExportDetailsXLS($worksheet, $startrow, $active_id, $pass)
	{
		$worksheet->setFormattedExcelTitle($worksheet->getColumnCoord(0) . $startrow, $this->getPlugin()->txt('assSQLQuestion'));
		$worksheet->setFormattedExcelTitle($worksheet->getColumnCoord(1) . $startrow, $this->getTitle());

		$solution = $this->getSolutionStored($active_id, $pass, true);
		$value1 = isset($solution['value1']) ? $solution['value1'] : '';
		$value2 = isset($solution['value2']) ? $solution['value2'] : '';

		$row = $startrow + 1;

		$worksheet->setCell($row, 0, $this->plugin->txt('label_value1'));
		$worksheet->setBold($worksheet->getColumnCoord(0) . $row);
		$worksheet->setCell($row, 1, $value1);
		$row++;

		$worksheet->setCell($row, 0, $this->plugin->txt('label_value2'));
		$worksheet->setBold($worksheet->getColumnCoord(0) . $row);
		$worksheet->setCell($row, 1, $value2);
		$row++;

		return $row + 1;
	}

	/**
	 * Creates a question from a QTI file
	 *
	 * Receives parameters from a QTI parser and creates a valid ILIAS question object
	 *
	 * @param object $item The QTI item object
	 * @param integer $questionpool_id The id of the parent questionpool
	 * @param integer $tst_id The id of the parent test if the question is part of a test
	 * @param object $tst_object A reference to the parent test object
	 * @param integer $question_counter A reference to a question counter to count the questions of an imported question pool
	 * @param array $import_mapping An array containing references to included ILIAS objects
	 * @access public
	 */
	function fromXML(&$item, &$questionpool_id, &$tst_id, &$tst_object, &$question_counter, &$import_mapping)
	{
		$this->getPlugin()->includeClass("import/qti12/class.assSQLQuestionImport.php");
		$import = new assSQLQuestionImport($this);
		$import->fromXML($item, $questionpool_id, $tst_id, $tst_object, $question_counter, $import_mapping);
	}

	/**
	 * Returns a QTI xml representation of the question and sets the internal
	 * domxml variable with the DOM XML representation of the QTI xml representation
	 *
	 * @return string The QTI xml representation of the question
	 * @access public
	 */
	function toXML($a_include_header = true, $a_include_binary = true, $a_shuffle = false, $test_output = false, $force_image_references = false)
	{
		$this->getPlugin()->includeClass("export/qti12/class.assSQLQuestionExport.php");
		$export = new assSQLQuestionExport($this);
		return $export->toXML($a_include_header, $a_include_binary, $a_shuffle, $test_output, $force_image_references);
	}

	/**
	 * Custom member functions only needed in an assSQLQuestion
	 */

	/**
	 * Getter and Setter for all $additional_data
	 */

	/**
	 * Returns the first sql sequence (sequence_a)
	 *
	 * @return string The first sql sequence
	 */
	function getSequenceA()
	{
		return $this->sequence_a;
	}

	/**
	 * Sets the first sql sequence
	 *
	 * @param string $sequence_a The first sql sequence
	 */
	function setSequenceA($sequence_a)
	{
		$this->sequence_a = $sequence_a;
	}

	/**
	 * Returns the second sql sequence (sequence_b)
	 *
	 * @return string The second sql sequence
	 */
	function getSequenceB()
	{
		return $this->sequence_b;
	}

	/**
	 * Sets the second sql sequence
	 *
	 * @param string $sequence_b The second sql sequence
	 */
	function setSequenceB($sequence_b)
	{
		$this->sequence_b = $sequence_b;
	}

	/**
	 * Returns the third sql sequence (sequence_c)
	 *
	 * @return string The third sql sequence
	 */
	function getSequenceC()
	{
		return $this->sequence_c;
	}

	/**
	 * Sets the third sql sequence
	 *
	 * @param string $sequence_c The third sql sequence
	 */
	function setSequenceC($sequence_c)
	{
		$this->sequence_c = $sequence_c;
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
	 * Get all scoring metrics
	 *
	 * @return scoringMetric[] A array containing all scoring metrics
	 */
	function getAllScoringMetrics()
	{
		return $this->scoring_metrics;
	}

	/**
	 * Get maximum points by add up all scoring metrics
	 *
	 * @return integer The maxium possible points
	 */
	function getMaximumPoints()
	{
		// Initialize with 0
		$maximum_points = 0;

		foreach($this->scoring_metrics as $scoring_metric)
		{
			$maximum_points += $scoring_metric->getPoints();
		}

		return $maximum_point;
	}

	/**
	 * Sets all scoring metrics
	 *
	 * @param scoringMetric[] $scoring_metrics An array containg all scoring metrics to be set
	 */
	function setAllScoringMetrics($scoring_metrics)
	{
		$this->scoring_metrics = $scoring_metrics;
	}

	/**
	 * Get all scoring metrics with a specific type
	 *
	 * @param string $type The type of the searched scoring metrics
	 * @return scoringMetric[] A array containing all metrics with this type
	 */
	function getScoringMetricsWithType($type)
	{
		$found_metrics = array();

		foreach($this->scoring_metrics as $scoring_metric)
		{
			if($scoring_metric.getType() == $type)
			{
				array_push($found_metrics, $scoring_metric);
			}
		}

		return $found_metrics;
	}

	/**
	 * Save a single scoring metric
	 *
	 * @param scoringMetric The scoring metric that has to be saved
	 */
	function setSingleScoringMetric($scoring_metric)
	{
		array_push($this->scoring_metrics, $scoring_metric);
	}

	/**
	 * Database functions
	 */

	/**
	 * Save all data that is specific to assSQLQuestion into the database
	 * (See dpupdate.php for more informations on used tables)
	 */
	function saveSpecificQuestionDataToDb()
	{
		global $ilDB;

		// Update "il_qpl_qst_qpisql_qd"

		// Delete existing entries with current question id (to avoid double entries)
		$ilDB->manipulate("DELETE FROM il_qpl_qst_qpisql_qd
											 WHERE question_fi = '".$this->getId()."'");

	  // Insert the current question data
		$ilDB->manipulateF(
			"INSERT INTO il_qpl_qst_qpisql_qd (question_fi,
																				 sequence_a,
																				 sequence_b,
																				 sequence_c,
																				 integrity_check,
																				 error_bool,
																				 executed_bool,
																				 output_relation)
			VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
			array("integer", "text", "text",
						"text", "integer", "integer",
						"integer", "clob"),
			array($this->getId(), $this->getSequenceA(), $this->getSequenceB(),
						$this->getSequenceC(), $this->getIntegrityCheck(), $this->getErrorBool(),
						$this->getExecutedBool(), $this->getOutputRelation())
    );


		// Update "il_qpl_qst_qpisql_qsm"

		// Delete existing entries with current question id (to avoid double entries)
		$ilDB->manipulate("DELETE FROM il_qpl_qst_qpisql_qsm
											 WHERE question_fi = '".$this->getId()."'");

	  // Insert all current scoring metrics
		foreach($this->scoring_metrics as $scoring_metric)
		{
			$ilDB->manipulateF(
				"INSERT INTO il_qpl_qst_qpisql_qsm (question_fi,
																					  id,
																					  type,
																					  points,
																					  value)
				VALUES (%s, %s, %s, %s, %s)",
				array("integer", "text",
							"text", "integer",
							"clob"),
				array($this->getId(), $scoring_metric->getId(),
							$scoring_metric->getType(), $scoring_metric->getPoints(),
							$scoring_metric->getValue())
	    );
		}
	}

	/**
	 * Load all data that is specific to assSQLQuestion from the database
	 * (See dpupdate.php for more informations on used tables)
	 *
	 * @param integer $question_id A unique key which defines the question in the database
	 */
	function loadSpecificQuestionDataFromDb($question_id)
	{
		global $DIC;
		$ilDB = $DIC->database();

		// Set Sequences and other data from il_qpl_qst_qpisql_qd
		$result = $ilDB->query("SELECT * FROM il_qpl_qst_qpisql_qd WHERE question_fi = "
				. $ilDB->quote($question_id, 'integer'));

		$data = $ilDB->fetchAssoc($result);

		$this->getSequenceA($data['sequence_a']);
		$this->getSequenceB($data['sequence_b']);
		$this->getSequenceC($data['sequence_c']);
		$this->getIntegrityCheck($data['integrity_check']);
		$this->getErrorBool($data['error_bool']);
		$this->getExecutedBool($data['executed_bool']);
		$this->getOutputRelation($data['output_relation']);

		// Set scoring metric from il_qpl_qst_qpisql_qsm
		$result = $ilDB->query("SELECT * FROM il_qpl_qst_qpisql_qsm WHERE question_fi = "
				. $ilDB->quote($question_id, 'integer'));

		while($ilDB->fetchAssoc($result))
		{
			$this->setSingleScoringMetric(
				new scoringMetric($data['id'],
													$data['type'],
													$data['points'],
													$data['value'])
			);
		}
	}
}

?>
