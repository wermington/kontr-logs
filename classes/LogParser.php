<?php

require_once "Student.php";
require_once "Request.php";

class LogParser
{
	private $log_content;
	private $subject;
	private $task;
	private $student;
	private $tutor;
	private $tutors;

	private function get_name($line)
	{
		$parts = explode(" ", $line, 3);
		return $parts[1];
	}
	
	function __construct($log_content, Request $request)
	{
		$this->log_content = $log_content;
		$this->subject = $request->getProperty('subject');
		$this->task = $request->getProperty('task');
		$this->student = $request->getProperty('student');
		$this->tutor = $request->getProperty('tutor');
	}
	
	function parse_as_stud()
	{
		$students = array();
		$lines = explode("\n", $this->log_content);
		
		$sub = $this->subject." ".$this->task;
		
		if($this->student)
		{
			$stud_login = $this->student;
		}
		else
		{
			$stud_login = "";
		}
		
		$tutor_filter = false;
		if($this->tutor && $this->tutor != "")
		{
			$tutor_filter = true;
			$ret = File::get_student_info();
			$this->tutors = $ret['tutor'];
		}
		
		foreach($lines as $line)
		{
			if(strstr($line, $sub) && (!$this->student || strstr($line, $stud_login." "))) // added space to student filename to avoid nasty bug
			{
				$name = $this->get_name($line);
				
				if($tutor_filter)
				{
					if(!array_key_exists($name, $this->tutors))
					{
						continue;
					}
					else if($this->tutor!=".{$this->tutors[$name]}")
					{
						continue;
					}
				}

				if(array_key_exists($name, $students))
				{
					$students[$name]->add_sub($line);
				}
				else
				{
					$student = new Student($line);
					$students[$student->name] = $student;
				}
			}
		}
		
		return $students;
	}
	
	function parse_as_sub()
	{
		$subs = array();
	
		$lines = explode("\n", $this->log_content);
		
		$sub = $this->subject." ".$this->task;
		
		foreach($lines as $line)
		{
			if(strstr($line, $sub))
			{
				$subs[] = new Submission($line);
			}
		}
		
		return $subs;
	}
	
	function parse_as_tags()
	{
		$tags = array();
	
		$lines = explode("\n", $this->log_content);
		
		$sub = $this->subject." ".$this->task;
		
		foreach($lines as $line)
		{
			if(strstr($line, $sub))
			{
				$colon = strpos($line, ':');
				$semi = strpos($line, ';');
				$len = $semi - $colon;
				$tags_str = trim(substr($line, $colon+1, $len-1));
				$tags_array = explode(" ", $tags_str);
				$tags = array_merge($tags, $tags_array);
			}
		}
		
		return array_unique($tags);
	}
}

?>
