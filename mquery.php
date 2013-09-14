<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class mquery extends CI_Model {

    public function random($query){
        if($this->db->query($query)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    function  create($table,$data){
        $this->db->insert($table, $data); 
    }
    function read($table,$where='',$limit='',$offset='',$column='*'){
        if(is_array($where)){
            $this->db->select($column.' from '.$table);
            $this->db->where($where);
        }else{
            $this->db->select($column.' from '.$table.' '.$where);
        }
        
        if($limit!='' && $offset!=''){
            $this->db->limit($limit, $offset);
        }elseif($limit!='' && $offset==''){
            $this->db->limit($limit);
        }
        return $this->db->get()->result();
    }
    function update($table,$id,$kode,$data){
        $this->db->where($id, $kode);
        if(is_array($data)){
         $this->db->update($table,$data); 
        }else{
            echo'error';
        }
    }
    function delete($table,$triger,$triger_value){
        if(is_array($triger_value)){
            $this->db->where_in($triger,$triger_value);
        }else{
            $this->db->where($triger,$triger_value);
        }
        $this->db->delete($table);  
    }
    function edit($table,$triger,$triger_value,$column='*',$type=''){
        $this->db->select($column);
        $this->db->where($triger,$triger_value);
        $query=$this->db->get($table);
        if($type==''){
            return $query->result();
        }elseif($type=='json'){
            $json=array();
            $data=$query->result();
            foreach($data[0] as $key => $value) {
                $json[$key]=$value;
            }
            return json_encode($json);
        }
    }
    function total_row($table,$where=''){
            if($where!=''){
                if(is_array($where)){
                    $this->db->where($where);
                }else{
                    $this->db->query($where);
                }
                return $this->db->count_all_results($table);
            }else{
                return $this->db->count_all($table);
            }
    }
    function get_column($table){
        $this->db->select('column_name from information_schema.columns');
        $this->db->where('table_name',$table);
        $this->db->order_by('ordinal_position');
        return $this->db->get();
    }
    function dropdown($table,$id,$val,$where='',$column='*'){
        $this->db->select($column.' from '.$table.' '.$where);
        $query=$this->db->get()->result();
        foreach ($query as $row){
            $dropdown[$row->$id]=$row->$val;
        }
        return $dropdown;
    }
    function dropdown_enum($table,$column){
        $type = $this->db->query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'" )->row( 0 )->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        foreach( explode(',', $matches[1]) as $value )
        {
            $val=trim( $value, "'" );
             $enum[$val] = $val;
        }
        return $enum;
    }
    public function table_exists($table_name){
        if($this->table_exists($table_name)){
            return FALSE;
        }else{
            return TRUE;
        }
    }

    
}
?>
