<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {

			$this->session->set_flashdata(
				'auth_flash',
				alerts('Anda harus login terlebih dahulu untuk mengakses halaman ini !', 'danger')
			);

			redirect('login', 'refresh');
		}
		if (!userdata()) {
			redirect('logout', 'refresh');
		}
	}

	function view_page($filename = 'dashboard')
	{

		$data = array();
		if (!file_exists(APPPATH . 'modules/dashboard/views/page/' . $filename . '.php')) {
			show_404();
			exit;
		}
		$data['data_group']     = $this->ion_auth->get_users_groups()->row();
		$data['userdata']     	= userdata();

		$this->template->content->view('page/' . $filename, $data);
		$this->template->publish();
	}

	function view_gen($param = 1)
	{
		if ($param < 1 || $param > 10) {
			show_404();
			exit;
		}

		$filename = 'mygeneration';
		if (!file_exists(APPPATH . 'modules/dashboard/views/page/' . $filename . '.php')) {
			show_404();
			exit;
		}

		$this->db->where('titiklevel_level', $param);
		$this->db->where('titiklevel_userid', userid());
		$cektitik = $this->db->get('tb_titiklevel');
		if ($cektitik->num_rows() == 0) {
			show_404();
			exit;
		}

		$data['genke'] = $param;
		$data['titiklevel'] = $cektitik->row();
		$data['userdata']     	= userdata();

		$this->template->content->view('page/' . $filename, $data);
		$this->template->publish();
	}

	function test()
	{
		// $getttttt = $this->db->get('tb_omset');
		// foreach ($getttttt->result() as $ommmmset) {
		// 	$this->omsetgroup($ommmmset->omset_userid, $ommmmset->omset_userid, ((int)$ommmmset->omset_amount));
		// }
	}


	function omsetgroup($user_id = null, $user_id_from = null, $getbv = 0)
	{
		$result         = array();
		$status         = true;

		$datauser         = userdata(['id' => $user_id]);
		$userdata         = userdata(['id' => $user_id_from]);
		if ($userdata->upline_id == 0) {
			$status = false;
		}

		$uplinedata     = userdata(['id' => $userdata->upline_id]);

		if ($status) {

			if ($uplinedata) {

				// JIKA PENERIMA SUDAH MEMPUNYA 100BV MAKA OMSET BERTAMBAH
				// if ($this->rank->myBV($uplinedata->id) >= 100) {
				// OMSET LAMA + NILAI BARU
				$totalBV = $uplinedata->user_omset + $getbv;

				$this->db->update(
					'tb_users',
					[
						'user_omset'    => $totalBV,
					],
					[
						'id'            => $uplinedata->id,
					]
				);
				// }


				$this->omsetgroup($datauser->id, $uplinedata->id, $getbv);
			}
		}
		return $result;
	}
}

/* End of file Dashboard.php */
/* Location: ./application/modules/dashboard/controllers/Dashboard.php */