<?php return <<<HTML

<h2>Project &quot;{$pname}&quot; <a href="/edit/{$id}">Edit</a></h2>

<div id="twocol-container">
<div id="twocol-left">
{$left}
</div>

<div id="twocol-right">
{$right}
</div>
</div>

<div id="twocol-bottom">
{$bottom}
</div>

{$contacts}
{$calllog}
{$worklog}

HTML;
