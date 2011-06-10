<?php

class HelloComponent extends Component{

    public static function index($ctx){
        return 'Hello New World';
    }

    public static function templates($ctx){
        $vars = array('var'=>'Me!');
        $out1 = Utils::applyTemplate($vars, 'hello3');
        $vars = array('var'=>$out1);
        return Utils::at($vars, 'hello3');
    }
    
    public static function test($ctx){
        echo ini_get('magic_quotes_gpc');
    }

}
