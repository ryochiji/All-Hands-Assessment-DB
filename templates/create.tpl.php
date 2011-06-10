<?php return <<<HTML

<h2>{$title} <a href="/" style="font-size:12px;font-weight:normal;">cancel</a></h2>

<form method="POST" action="/edit/save">
<input type="hidden" value="{$id}" name="id"/>
<div id="twocol-container">
<div id="twocol-left">
{$left}
</div>

<div id="twocol-right">
{$right}
</div>
</div>

<div id="twocol-bottom">
<input type="submit" name="submit" value="{$button}"/>
</div>

</form>
HTML;
