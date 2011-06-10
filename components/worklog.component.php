<?php

require_once('includes/ADB.class.php');

class WorklogComponent extends Component{

    public static function router($ctx, $id){
        $entry = ADB::getWorkLogEntry($id); 
        $entry['comment'] = htmlspecialchars($entry['comment']);
        $form = Utils::at($entry, 'editworklog');
        $ctx->appendContent($form);
        $ctx->appendContent(Utils::at(null,'create_css'),'css');
        return $ctx->wrapPage();
    } 

    public static function update($ctx){
        $data = $ctx->getPost();    
        ADB::updateWorkLog($data);
        $ctx->redirect('/view/'.$data['assid']);
    }

    public static function delete($ctx){
        $id = $ctx->getGet('id');
        $assid = $ctx->getGet('assid');
        ADB::deleteworklog($id);
        $ctx->redirect('/view/'.$assid);
    }

}

