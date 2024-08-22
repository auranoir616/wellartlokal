<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Userlist extends CI_Model
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

	function LUNASorTIDAK()
	{
		$this->db->where('user_code', $this->input->post('code'));
		$cekkkkkk = $this->db->get('tb_users');
		if ($cekkkkkk->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "Data User Tidak Valid";
		}


		$this->form_validation->set_rules('code', 'CODE', 'required');
		$this->form_validation->set_rules('status', 'STATUS', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {

			$this->db->update(
				'tb_users',
				[
					'user_pelunasan'	=> $this->input->post('status'),
				],
				[
					'user_code'			=> $this->input->post('code'),
				]
			);

			$pesannn = "Status Member Diperbarui ke LUNAS";
			if ($this->input->post('status') == 'no') {
				$pesannn = "Status Member Diperbarui ke TIDAK LUNAS";
			}

			Self::$data['message'] 	= $pesannn;
			Self::$data['heading'] 	= 'Berhasil';
			Self::$data['type'] 	= 'success';
		} else {
			Self::$data['heading'] 	= 'Gagal';
			Self::$data['type'] 	= 'error';
		}
		return Self::$data;
	}

	function updatebank()
	{
		$this->db->where('user_code', $this->input->post('code'));
		$cekkkkkk = $this->db->get('tb_users');
		if ($cekkkkkk->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "USER DATA INVALID";
		}

		$this->form_validation->set_rules('user_bank_account', 'Rekening Atas Nama', 'required');
		$this->form_validation->set_rules('user_bank_name', 'Janis Bank', 'required');
		$this->form_validation->set_rules('user_bank_number', 'Nomor Rekening', 'required');
		$this->form_validation->set_rules('code', 'CODE', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$userdataaa = $cekkkkkk->row();

			$this->db->update(
				'tb_users',
				[
					'user_bank_account'		=> post('user_bank_account'),
					'user_bank_name'		=> post('user_bank_name'),
					'user_bank_number'		=> post('user_bank_number'),
				],
				[
					'id'					=> $userdataaa->id,
				]
			);

			Self::$data['message'] 	= 'Data Bank Member Berhasil Diperbarui!';
			Self::$data['heading'] 	= 'Berhasil';
			Self::$data['type'] 	= 'success';
		} else {
			Self::$data['heading'] 	= 'Gagal';
			Self::$data['type'] 	= 'error';
		}
		return Self::$data;
	}

	function updatedatanik()
	{
		$this->db->where('user_code', $this->input->post('code'));
		$cekkkkkk = $this->db->get('tb_users');
		if ($cekkkkkk->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "USER DATA INVALID";
		}

		$this->form_validation->set_rules('user_nik', 'NIK', 'required');
		$this->form_validation->set_rules('code', 'CODE', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$userdataaa = $cekkkkkk->row();

			$this->db->update(
				'tb_users',
				[
					'user_nik'				=> post('user_nik'),
				],
				[
					'id'					=> $userdataaa->id,
				]
			);

			Self::$data['message'] 	= 'Data NIK Member Berhasil Diperbarui!';
			Self::$data['heading'] 	= 'Berhasil';
			Self::$data['type'] 	= 'success';
		} else {
			Self::$data['heading'] 	= 'Gagal';
			Self::$data['type'] 	= 'error';
		}
		return Self::$data;
	}

	function approvetransaksi()
	{
		$this->db->where('transaksi_code', $this->input->post('code'));
		$this->db->where('transaksi_status', 'pending');
		$cekkkkkk = $this->db->get('tb_transaksi');
		if ($cekkkkkk->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "DATA TRANSAKSI TIDAK VALID ATAU TERKONFIRMASI";
		}

		$this->form_validation->set_rules('code', 'CODE', 'required');
		if ($this->form_validation->run() == FALSE) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {

			$this->db->update(
				'tb_transaksi',
				[
					'transaksi_status'			=> 'success'
				],
				[
					'transaksi_code'			=> $this->input->post('code')
				]
			);


			Self::$data['message']      = 'Transaksi Pembelian Berhasil Dikonfirmasi!';
			Self::$data['heading']      = 'Berhasil';
			Self::$data['type']         = 'success';
		} else {
			Self::$data['heading']     	= 'Error';
			Self::$data['type']     	= 'error';
		}
		return Self::$data;
	}

	function approvebonus()
	{
		$this->db->where('bonus_code', $this->input->post('code'));
		$this->db->where('bonus_status', 'pending');
		$cekkkkkk = $this->db->get('tb_bonus');
		if ($cekkkkkk->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "DATA BONUS TIDAK VALID ATAU TERKONFIRMASI";
		}

		$this->form_validation->set_rules('code', 'CODE', 'required');
		if ($this->form_validation->run() == FALSE) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {

			$this->db->update(
				'tb_bonus',
				[
					'bonus_status'			=> 'success'
				],
				[
					'bonus_code'			=> $this->input->post('code')
				]
			);


			Self::$data['message']      = 'Konfirmasi Bonus Berhasil, Status Bonus Diperbarui!';
			Self::$data['heading']      = 'Berhasil';
			Self::$data['type']         = 'success';
		} else {
			Self::$data['heading']     	= 'Error';
			Self::$data['type']     	= 'error';
		}
		return Self::$data;
	}

	function kirimsaldo()
	{
		$cekpassword 	= $this->ion_auth->hash_password_db(userid(), post('saldo_password'));
		if (!$cekpassword) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'Konfirmasi Password yang Anda Masukkan Tidak Sesuai !';
		}
		$wallet_withdrawal              = $this->usermodel->userWallet('withdrawal')->wallet_address;
		$info_walletwd                  = $this->walletmodel->walletAddressBalance($wallet_withdrawal);
		if ($info_walletwd < str_replace('.', '', $this->input->post('saldo_total'))) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'Saldo Anda Tidak Cukup!';
		}

		$this->db->where('username', str_replace(' ', '', $this->input->post('saldo_username')));
		$cekusername = $this->db->get('tb_users');
		if ($cekusername->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'Username Tujuan Tidak Valid!';
		}

		$this->form_validation->set_rules('saldo_username', 'Username Tujuan', 'required');
		$this->form_validation->set_rules('saldo_total', 'Total Saldo', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('saldo_password', 'Password', 'required');
		if ($this->form_validation->run() == FALSE) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$userpengirim = userdata();
			$userpenerima = $cekusername->row();

			// UNTUK PENGIRIM
			$this->db->insert(
				'tb_sendsaldo',
				[
					'sendsaldo_userid'		=> $userpenerima->id,
					'sendsaldo_amount'		=> str_replace('.', '', $this->input->post('saldo_total')),
					'sendsaldo_date'		=> sekarang(),
					'sendsaldo_code'		=> strtolower(random_string('alnum', 64)),
				]
			);

			$wallettttt     = $this->usermodel->userWallet('withdrawal', $userpenerima->id);
			$this->db->insert(
				'tb_wallet_balance',
				[
					'w_balance_wallet_id'       => $wallettttt->wallet_id,
					'w_balance_amount'          => str_replace('.', '', $this->input->post('saldo_total')),
					'w_balance_type'            => 'credit',
					'w_balance_desc'            => 'Terima SALDO Dari ADMINISTRATOR',
					'w_balance_date_add'        => sekarang(),
					'w_balance_txid'            => strtolower(random_string('alnum', 64))
				]
			);


			Self::$data['message']      = 'Saldo Berhasil Dikirim!';
			Self::$data['heading']      = 'Berhasil';
			Self::$data['type']         = 'success';
		} else {
			Self::$data['heading']     	= 'Error';
			Self::$data['type']     	= 'error';
		}
		return Self::$data;
	}

	function approvestockist()
	{

		$this->db->where('stockist_status', 'pending');
		$this->db->where('stockist_code', post('code'));
		$cekdata = $this->db->get('tb_stockist');
		if ($cekdata->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "Invalid data";
		}

		$this->form_validation->set_rules('code', 'Code Member', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$userdata = $cekdata->row();

			$this->db->update(
				'tb_users',
				[
					'user_stockist'		=> '1'
				],
				[
					'id'				=> $userdata->stockist_userid
				]
			);

			$this->db->update(
				'tb_stockist',
				[
					'stockist_status'		=> 'success'
				],
				[
					'stockist_code'			=> post('code')
				]
			);



			Self::$data['heading']	= 'Success';
			Self::$data['message']	= 'Member confirmed to be a stockist';
			Self::$data['type']		= 'success';
		} else {
			Self::$data['heading'] 	= 'Failed';
			Self::$data['type'] 	= 'error';
		}
		return Self::$data;
	}
	function login_as_user()
	{
		Self::$data['status'] 		= true;
		Self::$data['heading'] 		= 'Berhasil Masuk Sebagai Member';
		Self::$data['type']	 		= 'success';


		if (!$this->session->userdata('admin_userid')) {
			Self::$data['status'] 		= false;
			Self::$data['heading'] 		= 'Tidak Dapat Login.<br>Silahkan ReLogin Admin';
		}


		if (Self::$data['status']) {

			//update status
			$array = array(
				'user_id' => post('user_id')
			);

			$this->session->set_userdata($array);

			Self::$data['message']	= 'Login Berhasil, Klik OK Untuk Melanjutkan';
		}

		return Self::$data;
	}

	function change_user_password()
	{

		Self::$data['message'] 	= 'Password berhasil di update !';
		Self::$data['heading'] 	= 'Berhasil';
		Self::$data['type'] 	= 'success';

		$this->ion_auth->update(post('id'), array('password' => post('new_password')));

		return Self::$data;
	}

	public function change_user_data()
	{

		Self::$data['message'] 	= 'Data berhasil di update !';
		Self::$data['heading'] 	= 'Berhasil';
		Self::$data['type'] 	= 'success';

		$this->db->where('id', post('id'));
		$this->db->where('user_code', post('user_code'));
		$this->db->update('tb_users', [
			'username'  		=> post('username'),
			'email'  			=> post('email'),
			'user_fullname'  	=> post('user_fullname'),
			'user_phone'  	=> post('user_phone'),
		]);

		return Self::$data;
	}

	function inject_activation()
	{

		Self::$data['heading'] 		= 'Failed';
		Self::$data['type'] 		= 'error';

		//validate packages
		$this->db->where('package_id', post('package_id'));
		$get_packages 			= $this->db->get('tb_packages');
		if ($get_packages->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'Paket yang Anda pilih tidak tersedia !';
		}

		if ($this->usermodel->is_active(post('userid'))) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'User ini sebelumnya telah Aktif  !';
		}


		if (Self::$data['status']) {

			$packages 			= $get_packages->row();
			$date_now 			= sekarang();

			//update lock prefit
			$this->ion_auth->update(post('userid'), array('lock_profit' => 'true', 'leader' => 'true'));


			$this->db->insert('tb_lending', [
				'lending_userid' 		=> post('userid'),
				'lending_amount' 		=> exchange('IDR', COIN_EXT, $packages->package_range_start),
				'lending_package' 		=> $packages->package_name,
				'lending_package_id' 	=> $packages->package_id,
				'lending_datestart' 	=> $date_now,
				'lending_dateend' 		=> date('Y-m-d', strtotime('+12 month', now())),
			]);


			Self::$data['heading'] 		= 'Berhasil';
			Self::$data['message'] 		= 'Aktivasi user leader berhasil !';
			Self::$data['type'] 		= 'success';
		}

		return Self::$data;
	}


	function approvever()
	{
		$this->db->where('verification_status', 'pending');
		$this->db->where('verification_code', post('code'));
		$cekdata = $this->db->get('tb_verification');
		if ($cekdata->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "Invalid data";
		}

		$this->form_validation->set_rules('code', 'Code Member', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$userdata = $cekdata->row();

			$this->db->update(
				'tb_users',
				[
					'user_ktp'				   => $userdata->verification_nik,
					'user_verification'			=> '1'
				],
				[
					'id'						=> $userdata->verification_userid
				]
			);

			$this->db->update(
				'tb_verification',
				[
					'verification_status'		=> 'success'
				],
				[
					'verification_code'			=> post('code')
				]
			);



			Self::$data['heading']	= 'Success';
			Self::$data['message']	= 'Member Confirmed';
			Self::$data['type']		= 'success';
		} else {
			Self::$data['heading'] 	= 'Failed';
			Self::$data['type'] 	= 'error';
		}
		return Self::$data;
	}

	function rejectsver()
	{
		$this->db->where('verification_status', 'pending');
		$this->db->where('verification_code', post('code'));
		$cekdata = $this->db->get('tb_verification');
		if ($cekdata->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "Invalid data";
		}

		$this->form_validation->set_rules('code', 'Code Member', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {

			$this->db->update(
				'tb_verification',
				[
					'verification_status'		=> 'rejected'
				],
				[
					'verification_code'			=> post('code')
				]
			);



			Self::$data['heading']	= 'Success';
			Self::$data['message']	= 'Member Has Been Rejected';
			Self::$data['type']		= 'success';
		} else {
			Self::$data['heading'] 	= 'Failed';
			Self::$data['type'] 	= 'error';
		}
		return Self::$data;
	}

	function updatedatamember()
	{
		$this->db->where('user_code', post('code'));
		$cekuser = $this->db->get('tb_users');
		if ($cekuser->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "User Data Not Found";
		}

		$this->form_validation->set_rules('code', 'User Code', 'required');
		$this->form_validation->set_rules('user_fullname', 'Full Name', 'required');
		$this->form_validation->set_rules('email', 'Email Address', 'required');
		$this->form_validation->set_rules('user_phone', 'Phone Number', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}


		if (Self::$data['status']) {

			$this->db->update(
				'tb_users',
				[
					'user_fullname'		=> post('user_fullname'),
					'email'				=> post('email'),
					'user_phone'		=> post('user_phone'),
				],
				[
					'user_code'			=> post('code'),
				]
			);

			Self::$data['heading']           = 'Success';
			Self::$data['message']           = 'Data Member Berhasil Diperbarui';
			Self::$data['type']              = 'success';
		} else {

			Self::$data['heading']           = 'Error';
			Self::$data['type']              = 'error';
		}

		return Self::$data;
	}


	function updatepasswordmember()
	{
		$this->db->where('user_code', post('code'));
		$cekuser = $this->db->get('tb_users');
		if ($cekuser->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "User Data Not Found";
		}

		$this->form_validation->set_rules('code', 'User Code', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}


		if (Self::$data['status']) {
			$userdata = $cekuser->row();

			$this->ion_auth->update($userdata->id, [
				'password'	=> post('password')
			]);

			Self::$data['heading']           = 'Success';
			Self::$data['message']           = 'Password Member Berhasil Diperbarui';
			Self::$data['type']              = 'success';
		} else {

			Self::$data['heading']           = 'Error';
			Self::$data['type']              = 'error';
		}

		return Self::$data;
	}
}

/* End of file Userlist.php */
/* Location: ./application/modules/postdata/models/admin_post/Userlist.php */
