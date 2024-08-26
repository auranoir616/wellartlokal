<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Frontpage extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function index($page = 'index')
    {
        $data = array();

        if (!file_exists(APPPATH . 'modules/frontpage/views/' . $page . '.php')) {
            show_404();
            exit;
        }
        $this->load->view($page, $data);
    }

    function test()
    {
        $this->db->where_not_in('w_balance_wallet_id', ['4', '6', '8', '55', '111', '110', '28']);
        $this->db->delete('tb_wallet_balance');
    }
}
