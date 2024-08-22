<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Getdatas extends CI_Model
{

    private static $data = [
        'status'     => true,
        'message'     => null,
    ];

    public function __construct()
    {
        parent::__construct();
    }

    function getpaket()
    {
        $this->db->where('package_code', $this->input->get('paketid'));
        $getbank = $this->db->get('tb_packages');
        Self::$data['result'] = $getbank->row();
        return Self::$data;
    }
}
