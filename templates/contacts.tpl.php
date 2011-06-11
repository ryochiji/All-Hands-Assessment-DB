<?php return <<<HTML
<script>
function toggle(link,div){
    var l = document.getElementById(link);
    var d = document.getElementById(div);
    d.style.display = "";
    //l.style.display = "none";
}
</script>

<div id="contacts">
<h3>Contact Info
<a href="javascript:toggle('addlink','addform');" id="addlink">Add Contact</a>
</h3>

<div id="addform" style="display:none;">
<form method="POST" action="/edit/contact">
<input type="hidden" name="assessment_id" value="{$id}"/>
Name: <input type="text" name="name" size="32"/>
Number:<input type="text" name="number" size="14"/>
Notes:<input type="text" name="notes" size="32"/>
<input type="submit" name="submit" value="Add"/>
</form>
</div>

<ul style="list-style-type:none;padding-left:0px;">
{$list}
</ul>

</div>

HTML;

