<?php return <<<HTML
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{$title}</title>
    <style>
body{
    font-family:sans-serif;
    font-size:12px;
}
a{
    text-decoration:none;
    color:#33f;
}
#navbar{
    width:100%;
    background:#eee;
}
ul.nav{
    list-style-type:none;
    padding-left:5px;
}
ul.nav li{
    display:inline;
    padding-right:15px;
}
ul.nav li > a{
    color: #88b;
}
table,td{
    font-size:12px;
}
{$css}
    </style>
<body>
<div id="navbar">
<ul class="nav" >
<li><a href="/">Home</a></li>
<li><a href="/edit">New</a></li>
</ul>
</div>

{$main}
<script type="text/javascript">
{$js}
</script>
</body>
</html>
HTML;
