<?php 

require_once('includes/ADB.class.php');

class ViewComponent extends Component{

    public static function router($ctx,$id){
        //$r = ADB::get($id);
        //return print_r($r,1);
        try{
            $r = ADB::get($id);
            return print_r($r,1);
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

}
