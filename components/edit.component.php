<?php
require_once('includes/ADB.class.php');

class EditComponent extends Component{

    public static function router($ctx,$id){
        $vars = array();

        if ($id=='index'){
            $vals = array();
            $id='';
            $vars['title'] = 'New Project';
            $vars['button'] = 'Create';
        }else{
            try{
                $vals = ADB::get($id);
            }catch(Exception $e){
                return $e->getMessage();
            }
            $vars['title'] = 'Edit Project';
            $vars['button'] = 'Save';
        }

        $out = self::renderFields(ADB::getFields(),$vals);
        $vars['id'] = $id;
        $vars['left'] = $out['l']; 
        $vars['right'] = $out['r']; 
        $vars['bottom'] = "Bottom";
        $body = Utils::at($vars, 'create');
        $ctx->appendContent(Utils::at(null,'create_css'),'css');
        $ctx->appendContent($body);
        return $ctx->wrapPage();
    }

    public static function contact($ctx){
        $aid = $ctx->getPost('assessment_id');
        $name = $ctx->getPost('name');
        $num = $ctx->getPost('number');
        $notes = $ctx->getPost('notes');
        try{
            ADB::saveContact($aid,$name,$num,$notes);
            $ctx->redirect('/view/'.$aid);
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public static function comment($ctx){
        $aid = $ctx->getPost('id');
        $who = $ctx->getPost('who');
        $comment = $ctx->getPost('comment');
        try{
            ADB::saveCallLog($aid,$who,$comment);
            $ctx->redirect('/view/'.$aid);
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public static function worklog($ctx){
        $data = $ctx->getPost();
        try{
            ADB::saveWorkLog($data);
            $ctx->redirect('/view/'.$data['id']);
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
 
    public static function save($ctx){
        $sub = $ctx->getPost('submit');
        if (empty($sub)) $ctx->redirect('/edit/');

        $data = $ctx->getPost();

        if (empty($data['id'])){
            $id = ADB::create($data);
        }else{
            $id = $data['id'];
            unset($data['id']);
            ADB::save($id,$data);
        }
        $ctx->redirect('/view/'.$id);
    }

    public static function test($ctx){
        return print_r(self::renderFields(ADB::getFields()));
    }

    private static function renderFields($fields,$vals){
        $out = array('r'=>'','l'=>'');
        foreach($fields as $field=>$a){
            $lr = $a[2];
            $label = $a[3];
            if (isset($vals[$field])){
                $val = htmlspecialchars($vals[$field]);
            }else{
                $val = '';
            }
            if ($a[0]=='c'){
                $out[$lr].=self::renderCheckbox($field,$label,$val); 
            }else if ($a[0]=='d'){
                $out[$lr].=self::renderDate($field,$label,$val);
            }else if (is_array($a[0])){
                $out[$lr].=self::renderMenu($field,$label,$a[0],$val);
            }else if (is_integer($a[0])){
                $out[$lr].=self::renderTextbox($field,$label,$a[0],$a[1],$val);
            }
        } 
        return $out;
    }
                
    private static function renderMenu($field,$label,$opts,$val){
        $out = '<label>'.$label.'</label>';
        $out.= '<select name="'.$field.'">';
        $out.="<option value=\"\">--</option>\n";
        foreach($opts as $k=>$v){
            $s = ($k==$val ? 'SELECTED' : '');
            $out.="<option value=\"$k\" $s >$v</option>\n";
        }
        $out.='</select>';
        return $out;
    }

    private static function renderDate($field,$label,$val){
        if ($val){
            list($y,$m,$d) = explode('-',$val);
        }else{
            $y = $m = $d = 0;
        }
        $out = '';
        $out.='<label>'.$label.'</label>';
        $out.='<input type="text" name="'.$field.'[m]" size="2" value="'.$m.'"/>';
        $out.='<input type="text" name="'.$field.'[d]" size="2" value="'.$d.'"/>';
        $out.='<input type="text" name="'.$field.'[y]" size="4" value="'.$y.'"/>';
        $out.='<br/>';
        return $out;
    }

    private static function renderCheckbox($field,$label,$val){
        return implode(array(
                '<label>',
                '<input type="checkbox" name="'.$field.'" value="1"/>'.$label,
                '</label>'
                ));
    }

    private static function renderTextbox($field,$label,$rows,$cols,$val){
        $out = '';
        $out.= '<label>'.$label.'</label>';
        if ($rows==1){
            $out.='<input type="text" name="'.$field.'" ';
            $out.='value="'.$val.'" size="'.$cols.'"/>';
        }else{
            $out.='<textarea name="'.$field.'" rows="'.$rows.'" cols="'.$cols.'">';
            $out.=$val.'</textarea>';
        }
        $out.='<br/>';
        return $out;
    }

}
