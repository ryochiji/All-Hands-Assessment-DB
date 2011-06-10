<?php return <<<HTML

<form method="GET" action="/index">
Sort By: 
<select name="sort">
{$options}
</select>
<input type="submit" name="submit" value="Sort"/>
</form>

<table id="projlist" border="1">
<tr>
    <td>ID</td>
    <td>Project Name</td>
    <td>In-Date</td>
    <td>Status</td>
    <td>Blocked</td>
    <td>Assessment Date</td>
    <td>Assessor</td>
    <td>Description</td>
    <td>Family Name</td>
    <td>Municipality</td>
    <td>Contacts</td>
</tr>
{$projects}
</table>

HTML;
