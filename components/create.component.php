<?php
require_once('includes/ADB.class.php');

class CreateComponent extends Component{

    public function index($ctx){

        $vars = array();
        $out = self::renderFields(ADB::getFields());
        $vars['left'] = $out['l']; 
        $vars['right'] = $out['r']; 
        $vars['bottom'] = "Bottom";
        $body = Utils::at($vars, 'create');
        $ctx->appendContent(Utils::at(null,'create_css'),'css');
        $ctx->appendContent($body);
        return $ctx->wrapPage();
    }

    public function save($ctx){
        $sub = $ctx->getPost('submit');
        if (empty($sub)) $ctx->redirect('/create');

        $data = $ctx->getPost();
        $id = ADB::create($data);
        echo $id;
    }

    public function test($ctx){
        return print_r(self::renderFields(ADB::getFields()));
    }

    private static function renderFields($fields){
        $out = array('r'=>'','l'=>'');
        foreach($fields as $field=>$a){
            $lr = $a[2];
            $label = $a[3];
            if ($a[0]=='c'){
                $out[$lr].=self::renderCheckbox($field,$label); 
            }else if ($a[0]=='d'){
                $out[$lr].=self::renderDate($field,$label);
            }else if (is_integer($a[0])){
                $out[$lr].=self::renderTextbox($field,$label,$a[0],$a[1]);
            }
        } 
        return $out;
    }

    private static function renderDate($field,$label){
        $out = '';
        $out.='<label>'.$label.'</label>';
        $out.='<input type="text" name="'.$field.'[m]" size="2"/>';
        $out.='<input type="text" name="'.$field.'[d]" size="2"/>';
        $out.='<input type="text" name="'.$field.'[y]" size="4"/>';
        $out.='<br/>';
        return $out;
    }

    private static function renderCheckbox($field,$label){
        return implode(array(
                '<label>',
                '<input type="checkbox" name="'.$field.'" value="1"/>'.$label,
                '</label>'
                ));
    }

    private static function renderTextbox($field,$label,$rows,$cols){
        $out = '';
        $out.= '<label>'.$label.'</label>';
        if ($rows==1){
            $out.='<input type="text" name="'.$field.'" value="" size="'.$cols.'"/>';
        }else{
            $out.='<textarea rows="'.$rows.'" cols="'.$cols.'"></textarea>';
        }
        $out.='<br/>';
        return $out;
    }

}
