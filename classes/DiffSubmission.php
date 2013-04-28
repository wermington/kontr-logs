<?php

require_once "File.php";
require_once "Request.php";

class DiffSubmission
{

public $sub1;
public $sub2;
private $dir_files;
public $req_files;
private $stage_path;

public $diff_return;
public $diff_content;

function __construct(Request $request)
{
	$subject = $request->getProperty('subject');
	$task = $request->getProperty('task');
	$stg = Config::get_setting("stage_dir");
	
	$subs = explode(" ", $request->getProperty('subs'));
	
	$this->sub1 = $subs[0];
	$this->sub2 = $subs[1];
	
	$this->stage_path = "{$stg}{$subject}/{$task}/";
	$this->dir_files = File::get_directory_files($this->stage_path.$subs[0]);
	$this->req_files = File::get_required_files($subject, $task);
	
	$this->do_diff();
}


private function do_diff()
{
	foreach($this->req_files as $key)
	{
		ob_start();
		$sub_path = $this->dir_files[$key];
		$file1 = "{$this->stage_path}{$this->sub1}/{$sub_path}";
		$file2 = "{$this->stage_path}{$this->sub2}/{$sub_path}";
		$ret;
		system("diff -U10000 -b -u -t {$file1} {$file2}", $ret);
		$this->diff_return[$key] = $ret;
		if($ret > 1)
		{
			$this->diff_content[$key] = "<strong>Error!</strong> You probably selected submissions of different types or resubmitted one.";
		}
		else
		{
			$this->diff_content[$key] = ob_get_contents();
		}
		ob_end_clean();
	}
}

}

?>
