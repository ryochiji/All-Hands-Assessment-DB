<?php
require_once('includes/ADB.class.php');

class ViewComponent extends Component{

    public static function router($ctx,$id){
        $vars = array();

        try{
            $vals = ADB::get($id);
            $proj_name = $vals['proj_name'];
            unset($vals['proj_name']);
        }catch(Exception $e){
            return $e->getMessage();
        }

        $data = ADB::getFields();
        unset($data['proj_name']);

        $out = self::renderFields($data,$vals);
        
        $vars['id'] = $id;
        $vars['pname'] = $proj_name; 
        $vars['left'] = $out['l']; 
        $vars['right'] = $out['r']; 
        $vars['bottom'] = $out['b']; 
        $vars['contacts'] = self::renderContacts($id);
        $vars['calllog'] = self::renderCallLog($id);
        $vars['worklog'] = self::renderWorkLog($id);
        $body = Utils::at($vars, 'view');
        $ctx->appendContent(Utils::at(null,'create_css'),'css');
        $ctx->appendContent($body);
        return $ctx->wrapPage();
    }

    public static function test($ctx){
        return print_r(self::renderFields(ADB::getFields()));
    }


    public static function renderWorkLog($id){
        $vars = array();
        $vars['id'] = $id;
        $vars['log'] = '';
        date_default_timezone_set('Asia/Tokyo');
        $y = date('Y');
        $vars['year'] = Utils::generateNumSelect('year',$y-1,$y+1,$y);
        $vars['month'] = Utils::selectMonth('month',date('m'));
        $vars['day'] = Utils::generateNumSelect('day',1,31,date('d'));
        $a = ADB::getWorkLog($id);
        foreach($a as $e){
            if ($e['wdate']=='' || $e['wdate']=='0000-00-00') $e['wdate'] = $e['ctime'];
            $i = '<li>';
            $i.= '<span>'.$e['wdate'].' -- Team Leader: '.$e['who'];
            $i.= ' -- Volunteers: '.$e['volunteers'];
            $i.= ' - <a href="/worklog/'.$e['id'].'">edit</a>';
            $i.= '</span>';
            $i.= '<div>'.nl2br($e['comment']).'</div>';
            $i.= "</li>\n";
            $vars['log'].=$i; 
        }
        return Utils::at($vars, 'worklog');
    }

    public static function renderCallLog($id){
        $vars = array();
        $vars['id'] = $id;
        $vars['log'] = ''; 
        $a = ADB::getCallLog($id);
        foreach($a as $e){
            $i = '<li>';
            $i.= '<span>'.$e['ctime'].' - '.$e['who'].'</span>';
            $i.= '<div>'.nl2br($e['comment']).'</div>';
            $i.= "</li>\n";
            $vars['log'].=$i; 
        }
        return Utils::at($vars,'calllog');
    }

    public static function renderContacts($id){
        $cts = ADB::getContacts($id);

        $vars = array();
        $vars['id'] = $id;
        $vars['list'] = '';
        foreach($cts as $c){
            $i = '<li>';
            $i.= $c['name'].' - '.$c['number'];
            $i.= ' ('.$c['notes'].')';
            $i.= '</li>';
            $vars['list'].= $i;
        }

        return Utils::at($vars,'contacts');

    }

    private static function renderFields($fields,$vals){
        $out = array('r'=>'','l'=>'','b'=>'');
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
                $out[$lr].=self::renderTextbox($field,$label,$val);
            }
        } 
        $out['b'].='<label>Entered: '.$vals['indate'].'</label>';
        $out['b'].='<label>Last Updated: '.$vals['mtime'];
        return $out;
    }

    private static function renderMenu($field,$label,$opts,$val){
        $out = '<label>'.$label.'</label>';
        $str = isset($opts[$val]) ? $opts[$val] : '--';
        $out.= '<div>'.$str.'</div>';
        return $out;
    }

    private static function renderDate($field,$label,$val){
        $out = '';
        $out.='<label>'.$label.'</label>';
        $out.='<div>'.$val.'</div>';
        return $out;
    }

    private static function renderCheckbox($field,$label,$val){
        $out = '<label>'.$label.'</label>';
        $out.= '<div>'.($val?'Yes':'No').'</div>';
        return $out;
    }

    private static function renderTextbox($field,$label,$val){
        if (empty($val)) $val = '--';
        $out = '';
        $out.= '<label>'.$label.'</label>';
        $out.= '<div>'.nl2br($val).'</div>';
        return $out;
    }

}
