<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Model
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

	function updateprofile()
	{
		if (!$this->ion_auth->hash_password_db(userid(), post('user_pass'))) {
			Self::$data['status']       = false;
			Self::$data['message']      = 'Konfirmasi Password Tidak Sesuai!';
		}

		$this->form_validation->set_rules('user_fullname', 'Nama Lengkap', 'required');
		$this->form_validation->set_rules('user_phone', 'No WhatsApp', 'required');
		$this->form_validation->set_rules('user_pass', 'Konfirmasi Password', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {

			$update_data 		= [
				'user_fullname'	  		=> post('user_fullname'),
				'user_phone'	  		=> post('user_phone'),
			];
			$this->ion_auth->update(userid(), $update_data);

			Self::$data['heading'] 	= 'Berhasil';
			Self::$data['message'] 	= 'Data Profile Anda Telah Diperbarui!';
			Self::$data['type'] 	= 'success';
		} else {
			Self::$data['heading'] 	= 'Gagal';
			Self::$data['type'] 	= 'error';
		}
		return Self::$data;
	}

	public function changepass()
	{
		if (!$this->ion_auth->hash_password_db(userid(), post('current_password'))) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'Password lama tidak sesuai';
		}

		$this->form_validation->set_rules('current_password', 'Old password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('confirm_password', 'Repeat New Password', 'trim|required|matches[new_password]');
		if ($this->form_validation->run() == FALSE) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {

			$this->ion_auth->update(userid(), [
				'password'		=> $this->input->post('new_password')
			]);

			Self::$data['heading'] 	= 'Berhasil';
			Self::$data['message'] 	= 'Password berhasil diperbarui';
			Self::$data['type'] 	= 'success';
		} else {

			Self::$data['heading'] 	= 'Gagal';
			Self::$data['type'] 	= 'error';
		}

		return Self::$data;
	}

	function updatebank()
	{
		if (!$this->ion_auth->hash_password_db(userid(), post('bank_pass'))) {
			Self::$data['status']       = false;
			Self::$data['message']      = 'Konfirmasi Password Tidak Sesuai!';
		}

		$this->form_validation->set_rules('user_bank_account', 'Rekening Atas Nama', 'required');
		$this->form_validation->set_rules('user_bank_name', 'Nama Bank', 'required');
		$this->form_validation->set_rules('user_bank_number', 'Nomor Rekening', 'required');
		$this->form_validation->set_rules('bank_pass', 'Konfirmasi Password', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {

			$this->db->update(
				'tb_users',
				[
					'user_bank_account'		=> post('user_bank_account'),
					'user_bank_name'		=> post('user_bank_name'),
					'user_bank_number'		=> post('user_bank_number'),

				],
				[
					'id'					=> userid(),
				]
			);

			Self::$data['heading'] 	= 'Berhasil';
			Self::$data['message'] 	= 'Data Bank Anda Telah Diperbarui!';
			Self::$data['type'] 	= 'success';
		} else {
			Self::$data['heading'] 	= 'Gagal';
			Self::$data['type'] 	= 'error';
		}
		return Self::$data;
	}

	function klaimreward()
	{
		// VALIDASI REWARD APAKAH ADA
		$this->db->where('reward_code', post('code'));
		$cekreward = $this->db->get('tb_reward');
		if ($cekreward->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "Data Reward Tidak Valid";
		} else {
			$datareward = $cekreward->row();
			// VALIDASI POIN
			if ($this->usermodel->poinreward() < $datareward->reward_point) {
				Self::$data['status']     = false;
				Self::$data['message']     = "Poin Anda Tidak Cukup Untuk Klaim Reward Ini";
			}
		}

		// VALIDASI PASSWORD
		if (!$this->ion_auth->hash_password_db(userid(), post('konfirmasi_password'))) {
			Self::$data['status']       = false;
			Self::$data['message']      = 'Konfirmasi Password Tidak Cocok!';
		}

		// VALIDASI DATA
		$this->form_validation->set_rules('code', 'Kode Reward', 'required');
		$this->form_validation->set_rules('reward_bank_account', 'Rekening Atas Nama', 'required');
		$this->form_validation->set_rules('reward_bank_name', 'Nama Bank', 'required');
		$this->form_validation->set_rules('reward_bank_number', 'Nomor Rekening', 'required');
		$this->form_validation->set_rules('reward_phone', 'No WhatsApp', 'required');
		$this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		// CEK APAKAH ADA YANG PENDING
		$this->db->where('userreward_status', 'pending');
		$this->db->where('userreward_userid', userid());
		$cek_pending = $this->db->get('tb_userreward');
		if ($cek_pending->num_rows() != 0) {
			Self::$data['status']       = false;
			Self::$data['message']      = 'Anda Memiliki Transaksi Pending!';
		}


		if (Self::$data['status']) {
			$datareward = $cekreward->row();

			$this->db->insert(
				'tb_userreward',
				[
					'userreward_rewardid'		=> $datareward->reward_id,
					'userreward_userid'			=> userid(),
					'userreward_account'		=> $this->input->post('reward_bank_account'),
					'userreward_bank'			=> $this->input->post('reward_bank_name'),
					'userreward_number'			=> $this->input->post('reward_bank_number'),
					'userreward_contact'		=> $this->input->post('reward_phone'),
					'userreward_date'			=> sekarang(),
					'userreward_code'			=> strtolower(random_string('alnum', 64))
				]
			);

			Self::$data['message']      = 'Berhasil Claim Reward & Tenunggu Konfirmasi Dari Admin';
			Self::$data['heading']      = 'Berhasil';
			Self::$data['type']         = 'success';
		} else {
			Self::$data['heading']     	= 'Gagal';
			Self::$data['type']     	= 'error';
		}

		return Self::$data;
	}

	function klaim_reward()
	{
		// VALIDASI REWARD APAKAH ADA
		$this->db->where('reward_code', post('code'));
		$cekreward = $this->db->get('tb_reward');
		if ($cekreward->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "Data Reward Tidak Valid atau Tidak Ditemukan";
		} else {
			$this->db->where('referral_id', userid());
			$totsponsor = $this->db->get('tb_users')->num_rows();

			$datareward = $cekreward->row();
			// VALIDASI HANYA KLAIM 1X
			$this->db->where('userreward_rewardid', $datareward->reward_id);
			$this->db->where('userreward_userid', userid());
			$CEKKKKKKKK = $this->db->get('tb_userreward',);
			if ($CEKKKKKKKK->num_rows() != 0) {
				Self::$data['status']     = false;
				Self::$data['message']     = "Anda Telah Mengklaim Reward ini!";
			}

			// VALIDASI KUALIFIKASI
			if ($totsponsor < $datareward->reward_point) {
				Self::$data['status']     = false;
				Self::$data['message']     = "Anda Tidak Memenuhi Kualifikasi Untuk Klaim Reward Ini";
			}
		}

		// VALIDASI DATA
		$this->form_validation->set_rules('code', 'Kode Reward', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$datareward = $cekreward->row();

			$this->db->insert(
				'tb_userreward',
				[
					'userreward_rewardid'		=> $datareward->reward_id,
					'userreward_userid'			=> userid(),
					'userreward_date'			=> sekarang(),
					'userreward_code'			=> strtolower(random_string('alnum', 64))
				]
			);

			$wallet 	= $this->usermodel->userWallet('withdrawal', userid());
			$this->db->insert(
				'tb_wallet_balance',
				[
					'w_balance_wallet_id'       => $wallet->wallet_id,
					'w_balance_amount'          => $datareward->reward_amount,
					'w_balance_type'            => 'credit',
					'w_balance_desc'            => 'Transaksi Klaim Reward',
					'w_balance_date_add'        => sekarang(),
					'w_balance_ket'             => 'reward',
					'w_balance_txid'            => hash('SHA256', random_string('alnum', 16)),
				]
			);

			Self::$data['message']      = 'Reward Berhasil Diklaim, Periksa Wallet Anda!';
			Self::$data['heading']      = 'Berhasil';
			Self::$data['type']         = 'success';
		} else {
			Self::$data['heading']     	= 'Gagal';
			Self::$data['type']     	= 'error';
		}

		return Self::$data;
	}
}

/* End of file Profile.php */
/* Location: ./application/modules/postdata/models/user_post/Profile.php */