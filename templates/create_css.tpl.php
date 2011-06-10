<?php return <<<CSS

div {
}
#twocol-container{
    width:700px;
    overflow:auto;
}
#twocol-left{
    width:345px;
    float:left;
}
#twocol-right{
    width:345px;
    float:right;
}
#twocol-bottom{
    width:700px;
    padding-top:10px;
    padding-bottom:20px;
}
label {
    display:block;
    color:#aaa;
    margin-top:5px;
}
h2,h3{
    width:100%;
    color:#fff;
    background:#444;
    padding:2px;
}
h2 > a,h3 > a{
    font-weight:normal;
    font-size:11px;
    color: #f93;
}
label > span{
    font-size:0.9em;
}
ul.log{
    list-style-type:none;
    padding-left:0px;
}
ul.log > li > span{
    font-weight:bold;
    color:#333;
}
ul.log > li > div{
    padding-left:5px;
    color:#777;
}
CSS;

