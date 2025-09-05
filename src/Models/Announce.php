<?php

namespace App\Models;

use App\Models\DB;

class Announce {
    private $db;

    public function __construct() {
        $this->db = new DB('announcements');
    }

    public function getAnnouncesActive($limit = 9999){
        return $this->db->table('announcements')->limit($limit)->where("expires_at", ">", date('Y-m-d H:i:s'))->get();
    }

    public function getAllAnnounces(){
        return $this->db->table('announcements')->get();
    }

    public function addAnnouncement($data) {
        $save = $this->db->table('announcements')->insert($data);
        if($save) {
            return true;
        } else {
            return false;
        }
    }

    public function removeAnnouncement($id) {
        $this->db->table('announcements')->where('id', $id)->delete();
    }


    public function getAnnounceCount() {
        return $this->db->table('announcements')->count();
    }

    public function checkDate($id) {
        return $this->db->table('announcements')->where('id', $id)->where('expires_at', '<', date('Y-m-d H:i:s'))->exists();
    }
}
