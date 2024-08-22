<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reward extends CI_Model
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

	function approve()
	{
		$this->db->where('userreward_code', $this->input->post('code'));
		$this->db->where('userreward_status', 'pending');
		$this->db->join('tb_reward', 'userreward_rewardid = reward_id');
		$cekreward = $this->db->get('tb_userreward');
		if ($cekreward->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "Tidak Valid atau Reward Terkonfirmasi";
		} else {
			$datareward = $cekreward->row();

			if ($this->usermodel->poinreward($datareward->userreward_userid) < $datareward->reward_point) {
				Self::$data['status']     = false;
				Self::$data['message']     = "Poin Member Tidak Cukup Untuk Klaim Reward Ini";
			}
		}

		$this->form_validation->set_rules('code', 'Code Transaksi', 'required');
		if ($this->form_validation->run() == FALSE) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors('', '<br/>');
		}

		if (Self::$data['status']) {
			$datareward = $cekreward->row();
			$userdata	= userdata(['id' => $datareward->userreward_userid]);

			$fee = (10 / 100) * $datareward->reward_amount;
			$total = $datareward->reward_amount - $fee;

			// UPDATE STATUS
			$this->db->update(
				'tb_userreward',
				[
					'userreward_status'		=> 'success',
				],
				[
					'userreward_code'		=> $this->input->post('code'),
				]
			);

			$this->db->insert(
				'tb_poinrw',
				[
					'poinrw_userid'             => $datareward->userreward_userid,
					'poinrw_paketid'    		=> $datareward->reward_id,
					'poinrw_total'              => $datareward->reward_point,
					'poinrw_tipe'               => 'debit',
					'poinrw_desc'               => "Klaim Reward " . $total,
					'poinrw_date'               => sekarang(),
					'poinrw_code'               => strtolower(random_string('alnum', 64)),
				]
			);

			$wallet             = $this->usermodel->userWallet('withdrawal', $datareward->userreward_userid);

			$this->db->insert(
				'tb_wallet_balance',
				[
					'w_balance_wallet_id'       => $wallet->wallet_id,
					'w_balance_amount'          => $total,
					'w_balance_type'            => 'credit',
					'w_balance_desc'            => "Klaim Reward " . $total,
					'w_balance_date_add'        => sekarang(),
					'w_balance_txid'            => strtolower(random_string('alnum', 64)),
					'w_balance_ket'             => 'reward',
				]
			);

			$nowa     = $userdata->user_phone;
			$pesan    = "Yth. " . $userdata->user_fullname . " Selamat Pencairan Reward Anda Sebesar Rp. " . number_format($total, 0, '.', '.') . " Sukses!! \r\n\r\nTetap Semangat dan Raih Prestasi Yang Lebih Tinggi Lagi https://sispenju.com";

			$this->notifWA($nowa, $pesan);

			Self::$data['heading'] 	= 'Berhasil';
			Self::$data['message'] 	= 'Transaksi Klaim Reward Dikonfirmasi';
			Self::$data['type'] 	= 'success';
		} else {

			Self::$data['heading'] 	= 'Gagal';
			Self::$data['type'] 	= 'error';
		}

		return Self::$data;
	}

	function notifWA($phone = NULL, $message = NULL)
	{
		$return = array();
		$userkey = 'b3f16549743b';
		$passkey = '59391aaa7eee4bb7f17cf4c1';
		$telepon = $phone;
		$message = str_replace('%20', ' ', $message);
		$url = 'https://console.zenziva.net/wareguler/api/sendWA/';
		$curlHandle = curl_init();
		curl_setopt($curlHandle, CURLOPT_URL, $url);
		curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
		curl_setopt($curlHandle, CURLOPT_POST, 1);
		curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
			'userkey' => $userkey,
			'passkey' => $passkey,
			'to' => $telepon,
			'message' => $message
		));
		json_decode(curl_exec($curlHandle), true);
		curl_close($curlHandle);

		return $return;
	}

	function reject()
	{
		$this->db->where('userreward_code', $this->input->post('code'));
		$this->db->where('userreward_status', 'pending');
		$this->db->join('tb_reward', 'userreward_rewardid = reward_id');
		$cekreward = $this->db->get('tb_userreward');
		if ($cekreward->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "Tidak Valid atau Reward Terkonfirmasi";
		} else {
			$datareward = $cekreward->row();

			if ($datareward->reward_point < $this->usermodel->poinreward($datareward->userreward_userid)) {
				Self::$data['status']     = false;
				Self::$data['message']     = "Poin Member Tidak Cukup Untuk Klaim Reward Ini";
			}
		}

		$this->form_validation->set_rules('code', 'Code Transaksi', 'required');
		if ($this->form_validation->run() == FALSE) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors('', '<br/>');
		}

		if (Self::$data['status']) {

			// UPDATE STATUS
			$this->db->update(
				'tb_userreward',
				[
					'userreward_status'		=> 'reject',
				],
				[
					'userreward_code'		=> $this->input->post('code'),
				]
			);


			Self::$data['heading'] 	= 'Berhasil';
			Self::$data['message'] 	= 'Transaksi Klaim Reward Ditolak';
			Self::$data['type'] 	= 'success';
		} else {

			Self::$data['heading'] 	= 'Gagal';
			Self::$data['type'] 	= 'error';
		}

		return Self::$data;
	}
}

/* End of file Withdrawl.php */
/* Location: ./application/models/Withdrawl.php */