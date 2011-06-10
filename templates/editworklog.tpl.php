<?php return <<<HTML

<h3>Edit Work Log
</h3>

<a href="/view/{$assessment_id}">&lt;&lt; Back to project page</a>

<form method="POST" action="/worklog/update">
<input type="hidden" name="id" value="{$id}"/>
<input type="hidden" name="assid" value="{$assessment_id}"/>
<label>Date</label>
{$ctime}
<label>Team Leader</label>
<input type="text" name="who" value="{$who}"/>
<label>Number of Volunteers</label>
<input type="text" name="volunteers" size="3" value="{$volunteers}"/>
<label>Notes <span>(work done, work still left, new work requested)</span></label>
<textarea cols="80" rows="3" name="comment">{$comment}</textarea>
<br>
<input type="submit" value="Save" name="submit"/>
<span style="padding-left:50px;">
<a href="/worklog/delete?id={$id}&assid={$assessment_id}">
delete entry</a>
</form>


HTML;


