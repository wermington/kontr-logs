<?php
/**
 * IndexBuilder class
 * @package
 */

require_once "classes/PageBuilder.php";
require_once "classes/File.php";
require_once "classes/Auth.php";

/**
 * Class implements methods for generating dynamic index content in html
 */
class IndexBuilder extends PageBuilder
{

        /**
         * Method gets and generate list of tasks for given subject
         */
	function option_tasks()
	{
		$subject = $this->get_request()->getProperty("subject");
		$selected_task = $this->get_request()->getProperty("task");
		$subjects = File::get_subjects();
		if (!$subject)
		{
			$subject=$subjects[0];
		}
		$tasks = File::get_tasks($subject);
		$tasks;
		$selected = "selected='selected'";
		
		foreach($tasks as $task)
		{
			echo "<option ";
			if($selected_task && $task==$selected_task)
			{
				echo $selected;
			}
			echo " value='{$task}'>{$task}</option>";
		}
	}
	
        /**
         * Method gets and generates list of subjects
         */
	function option_subjects()
	{
		$selected_subject = $this->get_request()->getProperty("subject");
		$subjects = File::get_subjects();
		if (!$selected_subject)
		{
			$subject=$subjects[0];
		}
		$selected = "selected='selected'";
		
		foreach($subjects as $sub)
		{
			echo "<option ";
			if($selected_subject && $sub==$selected_subject)
			{
				echo $selected;
			}
			echo " value='{$sub}'>{$sub}</option>";
		}
	}
	
        /**
         * Method gets and generates list of tutors
         */
	function option_tutors()
	{
		$ret = File::get_student_info();
		$tutors = array_unique(array_values($ret['tutor']));
		
		foreach($tutors as $tutor)
		{
			if($tutor=="")
			{
				echo "<option value='.notutor'>No tutor</option>";
			}
			else
			{
				echo "<option value='.{$tutor}'>{$tutor}</option>";
			}
		}
	}
	
        /**
         * Method displays manual submission tool button in case admin is logged
         */
	function manual_submission()
	{
		$admins = Config::get_array_setting('admins');
		$login = Auth::get_username();
		if($admins !== false && in_array($login, $admins))
		{
			echo "<span class='cp open-mansub-dialog' />[Submit]</span>";
		}
	}	

}

?>
