<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Withdrawal extends CI_Model
{

	private static $data = [
		'status' 	=> true,
		'message' 	=> null,
	];

	public function __construct()
	{
		parent::__construct();
		Self::$data['csrf_data'] 		= $this->security->get_csrf_hash();
	}

	function reqest_new()
	{
		$wallet_withdrawal              = $this->usermodel->userWallet('withdrawal')->wallet_address;
		$info_walletwd                  = $this->walletmodel->walletAddressBalance($wallet_withdrawal);
		if ($info_walletwd < $this->input->post('wd_total')) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'Saldo Dompet Tidak Cukup!';
		}

		if (!$this->ion_auth->hash_password_db(userid(), post('wd_password'))) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'Konfirmasi Password Tidak Cocok!';
		}

		$this->form_validation->set_rules('wd_total', 'Total Withdrawals', 'required');
		$this->form_validation->set_rules('wd_password', 'Password', 'required');
		if ($this->form_validation->run() == FALSE) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors('', '<br/>');
		}


		$this->db->where('withdrawl_userid', userid());
		$this->db->where('withdrawl_status', 'Pending');
		$cekwdddd = $this->db->get('tb_withdrawl');
		if ($cekwdddd->num_rows() > 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'Anda Memiliki Transaksi Penarikan Yang Tertunda!';
		}
		if ($this->input->post('wd_total') < 50000) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'Minimum Transaksi Withdrawal Rp. 50,000 !';
		}

		$userdata = userdata();
		if ($userdata->user_bank_account == null) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= 'Harap Mengatur Data Bank Terlebih Dahulu';
		}


		// $hari = array("Sat", "Sun");
		// if (in_array(date('D'), $hari)) {
		// 	Self::$data['status'] 	= false;
		// 	Self::$data['message'] 	= 'Withdrawals On Weekdays, Saturdays & Holidays!';
		// }


		if (Self::$data['status']) {
			$userdata 	= userdata();
			$admin 		= 10000;
			$pph 		= (2.5 / 100) * $this->input->post('wd_total');
			$potongan  	= $admin + $pph;

			$this->db->insert('tb_withdrawl', [
				'withdrawl_userid'  		=> userid(),
				'withdrawl_amount'  		=> str_replace(' ', '', $this->input->post('wd_total')),
				'withdrawl_account'  		=> $userdata->user_bank_account,
				'withdrawl_bank_name'  		=> $userdata->user_bank_name,
				'withdrawl_bank_number'  	=> $userdata->user_bank_number,
				'withdrawl_will_get'  		=> str_replace(' ', '', $this->input->post('wd_total')) - $potongan,
				'withdrawl_potongan'		=> $potongan,
				'withdrawl_pph'				=> $pph,
				'withdrawl_admin'			=> $admin,
				'withdrawl_trxid' 			=> hash('SHA256', random_string('alnum', 16)),
				'withdrawl_date'  			=> sekarang(),
			]);

			Self::$data['heading'] 	= 'Berhasil';
			Self::$data['message'] 	= 'Permintaan Penarikan Telah Ditambahkan Dalam Antrian';
			Self::$data['type'] 	= 'success';
		} else {

			Self::$data['heading'] 	= 'Gagal';
			Self::$data['type'] 	= 'error';
		}

		return Self::$data;
	}
}

/* End of file Withdrawal.php */
/* Location: ./application/modules/postdata/models/user_post/Withdrawal.php */