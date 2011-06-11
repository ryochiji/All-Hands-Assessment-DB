<?php return <<<HTML

<h3>Work Log
<a href="javascript:toggle('wloglink','workform');" id="wloglink">Add Entry</a>
</h3>

<div id="workform" style="display:none;">
<form method="POST" action="/edit/worklog">
<input type="hidden" name="id" value="{$id}"/>
<label>Date</label>
{$year}
 / {$month}
 / {$day}
<label>Team Leader</label>
<input type="text" name="who" value=""/>
<label>Number of Volunteers</label>
<input type="text" name="volunteers" size="3"/>
<label>Notes <span>(work done, work still left, new work requested)</span></label>
<textarea cols="80" rows="3" name="comment"></textarea>
<br>
<input type="submit" value="submit" name="submit"/>
</form>
</div>

<ul class="log">
{$log}
</ul>

HTML;


