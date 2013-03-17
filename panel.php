<?php
require_once "classes/IndexBuilder.php";

$i = PageBuilder::instance();
$login = Auth::get_username();

?>
<div id="panel_enabler" onmouseover='panel_enabler_over();'></div>
<div id="head">
	(<?php echo $login; ?>)
	<div class='opt' style="border: 0">
		<form id="vyber" action="index.php" method="GET">
		<span>Choose task and subject:</span>

		<select name="task" onchange="poslat()">
		<?php $i->option_tasks(); ?>
		</select>

		<select name="subject" onchange="poslat()">
		<?php $i->option_subjects(); ?>
		</select>

		<input type="submit" value="GO">

		<a class="vpravo" href="#bottom">[bottom]</a><a class="vpravo" href="#top">[top]</a>
		</form>
	</div>


	<div class='opt'>
		<form>
		Show students with:
		<select id="studfilter" onchange="filter()">
		  <option  value="all">all</option>
		  <option  value="nanecisto">nanecisto only submissions</option>
		  <option  value="naostro">naostro submissions points &lt; 6</option>
		  <option  value="naostro6b">naostro submissions points=6</option>
		</select>
		Seminar tutor:
		<select id="tutorfilter" onchange="filter()">
		<option  value="all">all</option>
		<?php echo $i->option_tutors(); ?>
		</select>
		</form>
	</div>
	<div class='opt'>
		<form>
		Show submissions:
		<select onchange="filter_sub(this.value)">
		  <option  value="all">all</option>
		  <option  value="nanecisto">nanecisto only</option>
		  <option  value="naostro">naostro only</option>
		</select>
		</form>
	</div>
	<div class='opt'>
		Options:
		<span class="cp" onclick="enable_notif(this)"/>[Enable notifications]</span>
		<span class="cp" onclick="show_all(this)"/>[Expand all]</span>
		<span class="cp" onclick="showdiff('', '')">[diff selected submissions]</span>
		<span class="cp" onclick="sort_users()"/>[Sort alphabetically]</span>
	</div>
	<div></div>
	<div class='opt'>
		Legend:
		<span class='green'>nanecisto</span>
		<span class='yellow'>naostro</span>
		<span class='blue'>ok</span>
		<span class='red'>errors</span>
		<span class='purple'>bonus error only</span>
	</div>
</div>
<div id="space_filler" style=" height: 191px; display: none"></div> 	
