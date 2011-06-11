<?php

require_once('includes/ADB.class.php');

class WorklogComponent extends Component{

    public static function router($ctx, $id){
        $entry = ADB::getWorkLogEntry($id); 
        $entry['comment'] = htmlspecialchars($entry['comment']);
        if (empty($entry['wdate'])) $entry['wdate'] = date('Y-m-d');
        list($y,$m,$d) = explode('-',$entry['wdate']);
        if ($y<2011) $y = 2011;
        $entry['year'] = Utils::generateNumSelect('year',$y-1,$y+1,$y);
        $entry['month'] = Utils::selectMonth('month',$m);
        $entry['day'] = Utils::generateNumSelect('day',1,31,$d);
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

