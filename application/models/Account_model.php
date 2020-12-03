<?php

class Account_model extends MY_Model {


    //TODO:This function should not be used more.REFER NEW Notification_lib
    function getNotificationType($acsess_to, $entity_code) {
        $this->db->select("*");
        $this->db->from('cdac_categories');
        $this->db->where('category_type', 'NOT-TYP');
        $this->db->where('category_status', 'A');
        $queryData = $this->db->get();
        if ($queryData->num_rows() > 0) {
            $data = $queryData->result_array();
            foreach ($data as $key => $value) {
                if ($acsess_to == "ATC") {
                    $this->db->select("notification_count,notification_status,row_id,notification_code");
                    $this->db->from('entity_notifications');
                    $this->db->where('notification_code', $value['category_code']);
                    $this->db->where('entity_code', $entity_code);
                } else if ($acsess_to == "ARC") {
                    $this->db->select("notification_count,row_id,notification_status,notification_code");
                    $this->db->from('entity_notifications');
                    $this->db->where('notification_code', $value['category_code']);
                    $this->db->join('cdac_entities ce', 'ce.entity_code = entity_notifications.entity_code');
                    $this->db->where('ce.entity_parent_code', $entity_code);

                } else if ($acsess_to == "CDAC") {
                    $this->db->select("notification_count,row_id,notification_status,notification_code");
                    $this->db->from('entity_notifications');
                    $this->db->where('notification_code', $value['category_code']);
                }
                $countData = $this->db->get();;
                if ($countData->num_rows() > 0) {
                    $countD = $countData->row_array();
                    $count = $countD['notification_count'];
                } else {
                    $count = 0;
                }
                if (isset($countD['row_id'])) {
                    $data[$key]['notification_id'] = $countD['row_id'];
                    
                } else {
                    $data[$key]['notification_id'] = '';
                }
                 if ($countData->num_rows() > 0) {
                $data[$key]['notification_status'] = $countD['notification_status'];
                $data[$key]['notification_code'] = $countD['notification_code'];
                 }else{
                     $data[$key]['notification_status'] = 0;
                $data[$key]['notification_code'] = 0;
                     
                 }
                $data[$key]['count'] = $count;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function updateInfo($table, $where, $data) {
        $this->db->where($where);
        return $this->db->update($table, $data);
    }

}
