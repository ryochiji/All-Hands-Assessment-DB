<?php return <<<HTML

<h3>Call Log
<a href="javascript:toggle('cloglink','callform');" id="cloglink">Add</a>
</h3>

<div id="callform" style="display:none;">
<form method="POST" action="/edit/comment">
<input type="hidden" name="id" value="{$id}"/>
<label>Who <span>(vonlunteer/staff)</span></label>
<input type="text" name="who" value=""/>
<label>Notes <span>(who you talked to, what was discussed)</span></label>
<textarea cols="80" rows="3" name="comment"></textarea>
<br>
<input type="submit" value="submit" name="submit"/>
</form>
</div>

<ul class="log">
{$log}
</ul>

HTML;


