<?php
/**
    Bare-bones component.  The index component handles
    requests to '/'.
    Since '/' has no subpaths, this component should only
    have a index() method.

    Try the tutorial for more information on components.
*/
require_once('includes/ADB.class.php');

class IndexComponent extends Component{
    
    public static function index($ctx){
        $vars = array();
        $sort = $ctx->getGet('sort');
        $projects = ADB::getList($sort);
        $vars['projects'] = self::renderList($projects);
        $vars['options'] = self::sortOptions($sort);
        $ctx->appendContent(Utils::at($vars,'index'));
        $ctx->appendContent('Assessment DB', 'title');
        return $ctx->wrapPage();
    }

    private static function sortOptions($cur){
        if (empty($cur)) $cur = 'status';

        $opts = array(
                    'assmnt_date' => 'Assessment Date',
                    'id'          => 'ID',
                    'proj_name'   => 'Project Name',
                    'family_name' => 'Family Name',
                    'status'      => 'Status',
                    'blocked'     => 'Blocked',
                    'work_scheduled' => 'Work Scheduled'
                );
        $out = array();
        foreach($opts as $k=>$v){
            $s = ($k == $cur ? 'selected' : '');
            $out[] = '<option value="'.$k.'" '.$s.'>'.$v.'</option>';
        }
        return implode("\n",$out);
    }

    private static function renderList($pa){
        $sts = ADB::getStatuses();
        $blk = ADB::getBlocked();
        $out = '';
        foreach($pa as $p){
            $row = '';
            if (count($p['contacts'])>0){
                $cts = array(); 
                foreach($p['contacts'] as $c){
                    $cts[] = $c['number'];
                }
                $p['contacts'] = implode(', ',$cts);
            }else{
                $p['contacts'] = '';
            } 
            $p['status'] = !empty($p['status'])?$sts[$p['status']]:'';
            $p['blocked'] = isset($p['blocked']) ? $blk[$p['blocked']] : '';
            $out.=Utils::at($p,'prow');
        }
        return $out;
    }

}
