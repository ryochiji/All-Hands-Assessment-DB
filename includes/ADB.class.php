<?php 

class ADB{

    static function getDB(){
        static $db;

        if (!isset($db)){
            $db = new Mysqli('localhost','root','','assessments');
            if ($db->connect_error) throw new Exception("DB Connection failed.");
        }
        return $db;
    }

    static function normalizeAssessmentData(&$d){
        $fields = self::getFields();
        foreach($d as $k=>&$e){
            $def = $fields[$k];
            if ($def[0]=='d' && is_array($e)){
                $e = self::formatDateArray($e);
                if ($e=='--') unset($d[$k]);
            }
        }
        /*
        if (isset($d['rdy_date']) && is_array($d['rdy_date'])){
            $a = $d['rdy_date']; 
            $d['rdy_date'] = $a['y'].'-'.$a['m'].'-'.$a['d'];
            if ($d['rdy_date']=='--') unset($d['rdy_date']);
        }
        */
    }

    static function formatDateArray($a){
        return $a['y'].'-'.$a['m'].'-'.$a['d'];
    }

    static function create($data){
        self::normalizeAssessmentData($data);
        $data['indate'] = date('Y-m-d');

        $fields = self::getFields();
        $columns = array_keys($fields);
        $columns[] = 'indate';

        $db = self::getDB();

        $cols = array();
        $values = array();
        foreach($columns as $col){
            if (empty($data[$col])) continue;
            $cols[] = $col;
            $values[] = $db->real_escape_string($data[$col]);
        }

        $sql = 'INSERT INTO assessments ('.implode(',',$cols).')';
        $sql.= ' VALUES (\''.implode("','",$values).'\')';
        if (!$db->query($sql)){
            throw new Exception("DB Error:".$db->error);
        }
        return $db->insert_id; 
    }

    static function save($id,$data){
        self::normalizeAssessmentData($data);

        $fields = self::getFields();
        $columns = array_keys($fields);

        $db = self::getDB();
        $id = $db->real_escape_string($id);

        $values = array();
        foreach($columns as $col){
            $val = $db->real_escape_string($data[$col]);
            $values[] = $col."='$val'"; 
        }

        $sql = 'UPDATE assessments SET '.implode(',',$values);
        $sql.= " WHERE id='$id'";
        error_log($sql);
        if (!$db->query($sql)){
            throw new Exception("DB Error:".$db->error);
        }
        return true; 
    }

    static function get($id){
        if (!is_numeric($id)){
            throw new Exception('Invalid ID');
        }

        $db = self::getDB();
        $id = $db->real_escape_string($id);
        $sql = 'SELECT * FROM assessments WHERE id='.$id;
        $r = $db->query($sql);
        if (!$r->num_rows){
            throw new Exception('Unknown ID');
        }

        return $r->fetch_assoc();
    }

    static function fetch_all($r){
        $out = array();

        while($a=$r->fetch_assoc()){
            $out[] = $a;
        }
        return $out;
    }

    static function getList($sort=false){
        $db = self::getDB();
        $sql = 'SELECT id,indate,assmnt_date,proj_name,address,';
        $sql.= ' shortdesc,municipality,status,blocked,family_name';
        $sql.= ' FROM assessments'; 
        if (empty($sort)) $sort='status';
        if ($sort){
            $sql.= ' ORDER BY '.$db->real_escape_string($sort);
        }
        $r = $db->query($sql);
        if ($db->error){
            throw new Exception($db->error);
        }

        //$rows = $r->fetch_all(MYSQLI_ASSOC);
        $rows = self::fetch_all($r);
        $data = array();
        foreach($rows as $row){
            $row['contacts'] = array();
            $data[$row['id']] = $row;
        }

        $sql = 'SELECT * FROM phone_numbers';
        $r = $db->query($sql);
        //$numbers = $r->fetch_all(MYSQLI_ASSOC);
        $numbers = self::fetch_all($r);

        foreach($numbers as $num){
            $data[$num['assessment_id']]['contacts'][] = $num;
        }
        return $data;
    }

    static function getContacts($assid){
        $db = self::getDB();
        $assid = $db->real_escape_string($assid);
        $sql = "SELECT * FROM phone_numbers WHERE assessment_id='$assid' ORDER BY id"; 
        $r = $db->query($sql);
        return self::fetch_all($r); //$r->fetch_all(MYSQLI_ASSOC);
    }

    static function saveContact($aid,$name,$num,$notes){
        $db = self::getDB();
        $v = array('aid'=>$aid,'name'=>$name,'num'=>$num,'notes'=>$notes);
        self::escapeArray($v);
        extract($v);

        $sql = 'INSERT INTO phone_numbers (assessment_id,name,number,notes)';
        $sql.= " VALUES ('$aid','$name','$num','$notes')";
        $r = $db->query($sql);
        if ($db->error){
            throw new Exception($db->error);
        } 
        
    }

    static function getCallLog($aid){
        $db = self::getDB();
        $aid = $db->real_escape_string($aid);

        $sql = "SELECT * FROM calllog WHERE assessment_id='$aid'";
        $sql.= " ORDER BY ctime DESC";
        $r = $db->query($sql);
        return self::fetch_all($r); //$r->fetch_all(MYSQLI_ASSOC);
    }

    static function saveCallLog($aid,$who,$comment){
        $db = self::getDB();
        $aid = $db->real_escape_string($aid);
        $who = $db->real_escape_string($who);
        $comment = $db->real_escape_string($comment);

        $sql = 'INSERT INTO calllog (assessment_id,who,comment)';
        $sql.= " VALUES('$aid','$who','$comment')";
        $r = $db->query($sql);
        if ($db->error){
            throw new Exception($db->error);
        } 
        return $db->insert_id; 
    }

    static function getWorkLog($aid){
        $db = self::getDB();
        $aid = $db->real_escape_string($aid);

        $sql = "SELECT * FROM worklog WHERE assessment_id='$aid'";
        $sql.= " ORDER BY ctime DESC";
        $r = $db->query($sql);
        return self::fetch_all($r); //$r->fetch_all(MYSQLI_ASSOC);
    }

    static function saveWorkLog($data){
        $db = self::getDB();
        self::escapeArray($data);
        extract($data);

        $wdate = $year.'-'.$month.'-'.$day;

        $sql = 'INSERT INTO worklog (assessment_id,who,wdate,comment,volunteers)';
        $sql.= " VALUES ('$id','$who','$wdate','$comment','$volunteers')";
        $r = $db->query($sql);
        if ($db->error){
            throw new Exception($db->error);
        } 
        
    }

    static function deleteWorkLog($id){
        $db = self::getDB();
        $id = $db->real_escape_string($id);
        $sql = "DELETE FROM worklog WHERE id='$id'";
        $r = $db->query($sql);
        if ($db->error){
            throw new Exception($db->error);
        }
        return true;
    }

    static function updateWorkLog($data){
        $db = self::getDB();
        self::escapeArray($data);
        extract($data);

        $wdate = $year.'-'.$month.'-'.$day;

        $sql = 'UPDATE worklog SET ';
        $sql.= " who='$who',volunteers='$volunteers',";
        $sql.= " comment='$comment',wdate='$wdate' WHERE id='$id'";
        $r = $db->query($sql);

        if ($db->error){
            throw new Exception($db->error);
        }

        return 1;
    }

    static function getWorkLogEntry($id){
        $db = self::getDB();
        $id = $db->real_escape_string($id);
        $sql = 'SELECT * FROM worklog WHERE id='.$id;
        $r = $db->query($sql);
        return $r->fetch_assoc();
    }

    static function escapeArray(&$a){
        $db = self::getDB();
        foreach($a as &$v){
            $v = $db->real_escape_string($v);
        }
    }

    static function getStatuses(){
        return array(
            'req'  => 'Assessment Requested',
            'asc'  => 'Assessment Scheduled',
            'ass'  => 'Assessed',
            'wsc'  => 'Work Scheduled',
            'prog' => 'In Progress',
            'wadd' => 'More Work Requested',
            'rhab' => 'Rehab Candidate',
            'hold' => 'On Hold',
            'canc' => 'Cancelled',
            'done' => 'Done'
            );
    }

    static function getBlocked(){
        return array(
            0 => 'No',
            1 => 'Waiting on owner',
            2 => 'Need follow-up'
            );
    }

    static function getFields(){
        static $a;

        if (!isset($a)){
            $status = self::getStatuses();
            $blocked = self::getBlocked(); 
            $perms = array(0=>'No',1=>'Yes',2=>'Pending');
            $a = array();
            $a['proj_name'] = array(1,32,'l','Project Name');
            $a['status'] = array($status,null,'l','Status');
            $a['blocked'] = array($blocked,null,'r','Blocked');
            $a['assmnt_date'] = array('d',null,'r','Assessment Date');
            $a['translator'] = array(1, 32, 'r','Translator');
            $a['assessor'] = array(1, 32, 'l', 'Assessor');
            $a['family_name'] = array(1, 32, 'l','Name');
            $a['ref_by'] = array(1,32,'r','Referred By');
            $a['municipality'] = array(1, 32, 'l','Municipality');
            $a['latitude'] = array(1, 32, 'l','Latitude');
            $a['longitude'] = array(1, 32, 'l','Longitude');
            $a['address'] = array(3, 32, 'l','Address');
            $a['inhabitants'] = array(1,16, 'l', '# of Inhabitatns');
            $a['occupations'] = array(1,32,'r','Occupations');
            $a['employment'] = array(1,32,'r','Employment Status');
            $a['residence'] = array(2,32,'r','Current Residence');
            $a['plans'] = array(3,32,'l','Plans to Return Home');
            $a['elderly'] = array('c',null,'r','Elderly');
            $a['disabled'] = array('c',null,'r','Disabled');
            $a['small_children'] = array('c',null,'r','Small Children');
            $a['single_female'] = array('c',null,'r','Single Female Head of Household');
            $a['insurance'] = array('c',null,'r','Has Tsunami Insurance');
            $a['ins_details'] = array(1,48,'r','Insurance Deets');
            $a['electricity'] = array('c',null,'r','Has Electricity');
            $a['shortdesc'] = array(1,32,'l','Short Description');
            $a['description'] = array(5,32,'l','Description');
            $a['work'] = array(3,32,'l','Work Requested');
            $a['required'] = array(3,32,'l','Tools,Materials and Skills Required');
            $a['needs'] = array(3,32,'r','Needs to Move Home');
            $a['rehab_status'] = array('c',null,'r','Home Restoration');
            $a['contributions'] = array(2,32,'l','Contributions from Family');
            $a['work_plan'] = array(5,32,'l','Work Plan');
            $a['est_vols'] = array(1,6,'r','Estimated Volunteers');
            $a['est_days'] = array(1,6,'r','Estimated Days');
            $a['approved'] = array('c',null,'l','Approved');
            $a['app_deets'] = array(1,32,'l','Approval Details');
            $a['perm'] = array($perms,null,'r','Permission from Owner');
            $a['perm_deets'] = array(1,32,'r','Permission Notes');
            $a['rdy_date'] = array('d', null, 'r', 'Ready Date');
            $a['rdy_notes'] = array(2,32,'r','Ready Notes');
            $a['done_date'] = array('d',null,'r','Done Date');
        }

        return $a;
    }

}
