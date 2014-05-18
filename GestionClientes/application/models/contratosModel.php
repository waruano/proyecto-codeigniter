<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of contratosModel
 *
 * @author waruano
 */
class contratosModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_persona($identificador) {
        $this->db->where('ID', $identificador);
        $this->db->limit(1);
        $result = $this->db->get('persona');
        if ($result->num_rows() > 0)
            return $result->row();
        else
            return null;
    }

    public function add_persona($data) {
        $this->db->insert('persona', $data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function add_titular($data) {
        $this->db->insert('titular', $data);
        $id = $this->db->insert_id();
        return $id;
    }
    public function add_beneficiario($data) {
        $this->db->insert('beneficiario', $data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function update_persona($data, $primaryKey) {
        return $this->db->update('persona', $data, array('ID' => $primaryKey));
    }

    public function update_titular($data, $primaryKey) {
        return $this->db->update('titular', $data, array('ID' => $primaryKey));
    }
    public function update_beneficiario($data, $primaryKey) {
        return $this->db->update('beneficiario', $data, array('ID' => $primaryKey));
    }
    public function delete_persona($primaryKey){
        echo'<script>alert("borrando persona"'.$primaryKey.');</script>';
        $this->db->where('ID',$prymaryKey);
        return $this->db->delete('persona');
    }
    public function delete_titular($primaryKey){
        echo'<script>alert("borrando titular"'.$primaryKey.');</script>';
        $this->db->where('ID',$prymaryKey);
        return $this->db->delete('titular');
    }
}

?>
