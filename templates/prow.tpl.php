<?php return <<<HTML
<tr>
<td><a href="/edit/{$id}">Edit {$id}</a></td>
<td><a href="/view/{$id}"><b>{$proj_name}</b></a></td>
<td>{$status}</td>
<td>{$blocked}</td>
<td>{$assmnt_date}</td>
<td>{$work_scheduled}</td>
<td>{$shortdesc}</td>
<td>{$family_name}</td>
<td>{$municipality}</td>
<td>{$address}</td>
<td>{$contacts}</td>
</tr>

HTML;
