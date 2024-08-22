<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Model
{

	private static $data = [
		'status' 	=> true,
		'message' 	=> null,
	];

	public function __construct()
	{
		parent::__construct();
		Self::$data['csrf_data'] 	= $this->security->get_csrf_hash();
	}

	function do_login()
	{
		$do_login 					= $this->ion_auth->login(post('authentication_id'), post('authentication_password'), true);
		if (!$do_login) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= $this->ion_auth->errors();
		}

		$this->form_validation->set_rules('authentication_id', 'USERNAME', 'required');
		$this->form_validation->set_rules('authentication_password', 'PASSWORD', 'required');
		if ($this->form_validation->run() == false) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (!$this->input->post()) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'Method not allowed';
		}

		if (Self::$data['status']) {

			// login success create session if admin
			$user_group 	= $this->ion_auth->get_users_groups()->row();
			if ($user_group->name == 'admin') {
				$array = array(
					'admin_userid' => userid()
				);
				$this->session->set_userdata($array);
			}


			Self::$data['message'] 	= 'Anda telah berhasil login. Klik OK untuk melanjutkan';
			Self::$data['heading'] 	= 'Sukses';
			Self::$data['type'] 	= 'success';
		} else {

			Self::$data['heading'] 	= 'Gagal';
			Self::$data['type'] 	= 'error';
		}

		return Self::$data;
	}
	function login_back_admin()
	{

		Self::$data['heading'] 		= 'Login Admin Berhasil';
		Self::$data['type']	 		= 'success';

		if (!$this->session->userdata('admin_userid')) {
			Self::$data['status'] 		= false;
			Self::$data['message'] 		= 'Not allowed';
		}

		if (Self::$data['status']) {

			//update status
			$array = array(
				'user_id' => $this->session->userdata('admin_userid')
			);
			$this->session->set_userdata($array);
			Self::$data['message']	= 'Berhasil login kembali menjadi menjadi Admin';
		} else {

			Self::$data['heading'] 		= 'Failed';
			Self::$data['type']	 		= 'error';
		}

		return Self::$data;
	}

	public function do_register()
	{
		$referral_id 			= 1; //ADMIN ID

		/*============================================
		= VALIDASI REFERRAL KODE YANG DI INPUT MANUAL =
		============================================*/
		if ($this->input->post('user_referral')) {

			$user_referral 		= userdata(['user_referral_code' => post('user_referral')]);
			if (!$user_referral) {
				Self::$data['status'] 	= false;
				Self::$data['message'] 	= 'Kode Referral Tidak Valid atau Tidak Tersedia!';
			} else {
				$referral_id  =	$user_referral->id;
			}
		}

		/*============================================
		=  JIKA ADA SESSION REFERRAL DARI LINK REF   =
		============================================*/
		if ($this->session->userdata('referralID')) {
			$referral_id 		= userdata(['user_referral_code' => $this->session->userdata('referralID')])->id;
		}
		/*============================================
		=            PENGECEKAN UPLINE               =
		============================================*/

		$upline_data     = userdata(array('user_referral_code' => $this->input->post('user_upline')));
		if (!$upline_data) {
			Self::$data['status']     = false;
			Self::$data['message']     = 'Upline Kode Tidak Valid ';
		} else {
			$upline_id      = $upline_data->id;
			$admin_id       = $upline_data->admin_id;
		}

		/*============================================
		=	           VALIDASI PAKET	           =
		============================================*/
		$this->db->where('pin_package_id', (int)1);
		$this->db->where('pin_kode', str_replace(' ', '', $this->input->post('user_pinkode')));
		$this->db->join('tb_packages', 'package_id = pin_package_id');
		$cekpinnn = $this->db->get('tb_users_pin');
		if ($cekpinnn->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'PIN Kode Tidak Valid atau Sudah Digunakan!';
		}

		/*============================================
		=     VALIDASI INPUT AGAR TIDAK KOSONG       =
		============================================*/
		$this->form_validation->set_rules('user_pinkode', 'PIN Kode', 'required');
		$this->form_validation->set_rules('user_fullname', 'Nama Lengkap', 'required');
		$this->form_validation->set_rules('user_username', 'Username', 'trim|required|min_length[4]|is_unique[tb_users.username]', array(
			'is_unique'    => 'Username Sudah Digunakan, Gunakan Username Lain.'
		));
		$this->form_validation->set_rules('user_email', 'Alamat Email', 'trim|required');
		$this->form_validation->set_rules('user_phone', 'Nomor WhatsApp', 'required');
		$this->form_validation->set_rules('user_referral', 'Referral Kode', 'required');
		$this->form_validation->set_rules('user_upline', 'Upline Kode', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'trim|required|min_length[6]');
		if (!$this->form_validation->run()) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}
		/*============================================
		=           JIKA STATUS TRUE / BENAR         =
		============================================*/
		if (Self::$data['status']) {
			$random_string 		= strtolower(random_string('alnum', 64));
			$paket				= $cekpinnn->row();
			/*============================================
			=            INPUT DATA PENDAFTAR            =
			============================================*/
			$additional_data 	= array(
				'referral_id' 			=> $referral_id,
				'upline_id' 			=> $upline_id,
				'user_fullname'			=> $this->input->post('user_fullname'),
				'user_phone'			=> $this->input->post('user_phone'),
				'user_referral_code'	=> random_string('alnum', 6),
				'user_code'				=> $random_string,
			);

			$this->ion_auth->register(str_replace(' ', '', $this->input->post('user_username')), $this->input->post('user_password'), str_replace(' ', '', $this->input->post('user_email')), $additional_data, array(2));
			$last_user 		= userdata(array('user_code' => $random_string));

			/*============================================
			=              MEMBUAT WALLET               =
			============================================*/
			$this->db->insert(
				'tb_users_wallet',
				[
					'wallet_user_id'  	=> $last_user->id,
					'wallet_address'  	=> generateWallet(),
					'wallet_type'  		=> 'withdrawal',
					'wallet_date_added' => sekarang()
				]
			);

			/*============================================
            =	            BONUS SPONSOR            	=
            ============================================*/
			$wallet_reff     = $this->usermodel->userWallet('withdrawal', $last_user->referral_id);
			$this->db->insert(
				'tb_wallet_balance',
				[
					'w_balance_wallet_id'       => $wallet_reff->wallet_id,
					'w_balance_amount'          => $paket->package_sponsor,
					'w_balance_type'            => 'credit',
					'w_balance_desc'            => 'Bonus Sponsor, Pendaftaran Username : ' . str_replace(' ', '', $this->input->post('user_username')),
					'w_balance_date_add'        => sekarang(),
					'w_balance_ket'             => 'sponsor',
					'w_balance_txid'            => hash('SHA256', random_string('alnum', 16)),
				]
			);

			/*============================================
            =	            BONUS LEVEL            		=
            ============================================*/
			// $this->bonuslevel($last_user->id, $last_user->id, 1);


			/*============================================
            =	            TITIK LEVEL            		=
            ============================================*/
			$this->titiklevel($last_user->id, $last_user->id, 1);


			/*============================================
            =	            REPORT PIN            		=
            ============================================*/
			$this->db->insert(
				'tb_histori_userpin',
				[
					'histori_userid'			=> $paket->pin_userid,
					'histori_userpindesc'		=> 'Pendaftaran Username : ' . str_replace(' ', '', $this->input->post('user_username')) . ' dengan PIN Kode : ' . str_replace(' ', '', $this->input->post('user_pinkode')),
					'histori_userpindate'		=> sekarang(),
					'histori_code'				=> strtolower(random_string('alnum', 64)),
				]
			);


			/*============================================
			=              HAPUS DATA PIN             =
			============================================*/
			$this->db->delete(
				'tb_users_pin',
				[
					'pin_kode'		=> str_replace(' ', '', $this->input->post('user_pinkode')),
				]
			);
			/*============================================
			=           HAPUS SESION REFERRAL           =
			============================================*/
			$this->session->unset_userdata([
				'referralID'
			]);


			Self::$data['message'] 	= 'Pendaftaran Akun Baru Berhasil';
			Self::$data['heading'] 	= 'Berhasil';
			Self::$data['type'] 	= 'success';
		} else {

			Self::$data['heading'] 	= 'Gagal';
			Self::$data['type'] 	= 'error';
		}

		return Self::$data;
	}

	function titiklevel($user_id = null, $user_id_from = null, $level = 1)
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
				$this->db->insert('tb_titiklevel', [
					'titiklevel_userid'             => $uplinedata->id,
					'titiklevel_downlineid'         => $datauser->id,
					'titiklevel_level'              => $level,
					'titiklevel_date'               => sekarang(),
				]);

				$this->titiklevel($datauser->id, $uplinedata->id, $level + 1);
			}
		}
		return $result;
	}


	function bonuslevel($user_id = null, $user_id_from = null, $level = 1)
	{
		$result 		= array();
		$status 		= true;

		$datauser 		= userdata(['id' => $user_id]);
		$userdata 		= userdata(['id' => $user_id_from]);

		// GET PAKET
		$this->db->where('package_id', (int)1);
		$get_packages 		= $this->db->get('tb_packages')->row();

		$array_term_level 	= json_decode($get_packages->package_level);
		if ($level > count($array_term_level)) {
			$status = false;
		}

		if ($userdata->upline_id == 0) {
			$status = false;
		}

		$uplinedata 	= userdata(['id' => $userdata->upline_id]);


		if ($status) {
			if ($uplinedata) {
				$wallet     		= $this->usermodel->userWallet('withdrawal', $uplinedata->id);

				$this->db->insert(
					'tb_wallet_balance',
					[
						'w_balance_wallet_id'       => $wallet->wallet_id,
						'w_balance_amount'          => ($array_term_level[$level - 1] / 100) * $get_packages->package_price,
						'w_balance_type'            => 'credit',
						'w_balance_desc'            => 'Bonus Unilevel Registrasi, Level Ke ' . $level . ' dari Username : ' . $datauser->username,
						'w_balance_date_add'        => sekarang(),
						'w_balance_txid'            => strtolower(random_string('alnum', 64)),
						'w_balance_ket'				=> 'unilevel',
					]
				);

				$this->bonuslevel($datauser->id, $uplinedata->id, $level + 1);
			}
		}
		return $result;
	}
}
