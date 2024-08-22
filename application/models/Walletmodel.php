<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Walletmodel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here 	
	}


	function getbonus($userid = null)
	{

		$return 		= 0;
		$saldo_masuk 	= $saldo_keluar = 0;

		$this->db->select_sum('bonus_amount');
		if ($userid != null) {
			$this->db->where('bonus_userid', $userid);
		} else {
			$this->db->where('bonus_userid', userid());
		}
		$this->db->where('bonus_type', 'credit');
		if (!$this->ion_auth->is_admin()) {
			$this->db->where('bonus_status', 'success');
		}
		$get 			= $this->db->get('tb_bonus');
		$get_saldo_masuk 	= $get->row()->bonus_amount;

		if (!empty($get_saldo_masuk)) {
			$saldo_masuk 	= $get_saldo_masuk;
		}


		//get saldo keluar
		$this->db->select_sum('bonus_amount');
		if ($userid != null) {
			$this->db->where('bonus_userid', $userid);
		} else {
			$this->db->where('bonus_userid', userid());
		}
		$this->db->where('bonus_type', 'debit');
		if (!$this->ion_auth->is_admin()) {
			$this->db->where('bonus_status', 'success');
		}
		$gett 			= $this->db->get('tb_bonus');
		$get_saldo_keluar 	= $gett->row()->bonus_amount;

		if (!empty($get_saldo_keluar)) {
			$saldo_keluar 	= $get_saldo_keluar;
		}

		$return 			= $saldo_masuk - $saldo_keluar;

		return $return;
	}



	function saldoro($userid = null)
	{

		$return 		= 0;
		$saldo_masuk 	= $saldo_keluar = 0;

		$this->db->select_sum('saldoro_amount');
		if ($userid != null) {
			$this->db->where('saldoro_userid', $userid);
		} else {
			$this->db->where('saldoro_userid', userid());
		}
		$this->db->where('saldoro_type', 'credit');
		$get 			= $this->db->get('tb_saldoro');
		$get_saldo_masuk 	= $get->row()->saldoro_amount;

		if (!empty($get_saldo_masuk)) {
			$saldo_masuk 	= $get_saldo_masuk;
		}


		//get saldo keluar
		$this->db->select_sum('saldoro_amount');
		if ($userid != null) {
			$this->db->where('saldoro_userid', $userid);
		} else {
			$this->db->where('saldoro_userid', userid());
		}
		$this->db->where('saldoro_type', 'debit');
		$gett 			= $this->db->get('tb_saldoro');
		$get_saldo_keluar 	= $gett->row()->saldoro_amount;

		if (!empty($get_saldo_keluar)) {
			$saldo_keluar 	= $get_saldo_keluar;
		}

		$return 			= $saldo_masuk - $saldo_keluar;

		return $return;
	}


	function walletAddressBalance($wallet_address = null, $date_start = null, $date_end = null)
	{

		$return 		= 0;
		$saldo_masuk 	= $saldo_keluar = 0;

		$this->db->select_sum('w_balance_amount');
		if (($date_start != null) && ($date_end != null)) {
			$this->db->where('w_balance_date_add BETWEEN "' . $date_start . '" AND "' . $date_end . '"');
		}
		$this->db->join('tb_users_wallet', 'wallet_id = w_balance_wallet_id', 'left');
		$this->db->where('wallet_address', $wallet_address);
		$this->db->where('w_balance_type', 'credit');
		$get 			= $this->db->get('tb_wallet_balance');
		$get_saldo_masuk 	= $get->row()->w_balance_amount;

		if (!empty($get_saldo_masuk)) {
			$saldo_masuk 	= $get_saldo_masuk;
		}


		//get saldo keluar
		$this->db->select_sum('w_balance_amount');
		if (($date_start != null) && ($date_end != null)) {
			$this->db->where('w_balance_date_add BETWEEN "' . $date_start . '" AND "' . $date_end . '"');
		}
		$this->db->join('tb_users_wallet', 'wallet_id = w_balance_wallet_id', 'left');
		$this->db->where('wallet_address', $wallet_address);
		$this->db->where('w_balance_type', 'debit');
		$get 			= $this->db->get('tb_wallet_balance');
		$get_saldo_keluar 	= $get->row()->w_balance_amount;

		if (!empty($get_saldo_keluar)) {
			$saldo_keluar 	= $get_saldo_keluar;
		}

		$return 			= $saldo_masuk - $saldo_keluar;

		return $return;
	}



	function walletPending($wallet_address = null)
	{

		$return 		= 0;
		$saldo_masuk 	= $saldo_keluar = 0;

		$this->db->select_sum('w_pending_amount');
		$this->db->join('tb_users_wallet', 'wallet_id = w_pending_wallet_id');
		$this->db->where('wallet_address', $wallet_address);
		$this->db->where('w_pending_type', 'credit');
		$get 			= $this->db->get('tb_wallet_pending');
		$get_saldo_masuk 	= $get->row()->w_pending_amount;

		if (!empty($get_saldo_masuk)) {
			$saldo_masuk 	= $get_saldo_masuk;
		}


		//get saldo keluar
		$this->db->select_sum('w_pending_amount');
		$this->db->join('tb_users_wallet', 'wallet_id = w_pending_wallet_id');
		$this->db->where('wallet_address', $wallet_address);
		$this->db->where('w_pending_type', 'debit');
		$get 			= $this->db->get('tb_wallet_pending');
		$get_saldo_keluar 	= $get->row()->w_pending_amount;

		if (!empty($get_saldo_keluar)) {
			$saldo_keluar 	= $get_saldo_keluar;
		}

		$return 			= $saldo_masuk - $saldo_keluar;

		return $return;
	}


	function walletTABUNGAN($user_id = null)
	{

		$userid = ($user_id == null) ? userid() : $user_id;

		$return 		= 0;
		$saldo_masuk 	= $saldo_keluar = 0;

		$this->db->select_sum('walletnabung_amount');
		$this->db->where('walletnabung_type', 'credit');
		$this->db->where('walletnabung_userid', $userid);
		$get 			= $this->db->get('tb_walletnabung');
		$get_saldo_masuk 	= $get->row()->walletnabung_amount;

		if (!empty($get_saldo_masuk)) {
			$saldo_masuk 	= $get_saldo_masuk;
		}

		//get saldo keluar
		$this->db->select_sum('walletnabung_amount');
		$this->db->where('walletnabung_type', 'debit');
		$this->db->where('walletnabung_userid', $userid);
		$get 			= $this->db->get('tb_walletnabung');
		$get_saldo_keluar 	= $get->row()->walletnabung_amount;

		if (!empty($get_saldo_keluar)) {
			$saldo_keluar 	= $get_saldo_keluar;
		}

		$return 			= $saldo_masuk - $saldo_keluar;

		return $return;
	}


	function totalcredit($wallet_address = null)
	{

		$return 		= 0;
		$saldo_masuk 	= 0;

		$this->db->select_sum('w_balance_amount');
		$this->db->join('tb_users_wallet', 'wallet_id = w_balance_wallet_id', 'left');
		$this->db->where('wallet_address', $wallet_address);
		$this->db->where('w_balance_type', 'credit');
		$get 			= $this->db->get('tb_wallet_balance');
		$get_saldo_masuk 	= $get->row()->w_balance_amount;

		if (!empty($get_saldo_masuk)) {
			$saldo_masuk 	= $get_saldo_masuk;
		}

		$return 			= $saldo_masuk;

		return $return;
	}


	function profitshare($userid = null)
	{

		$return 		= 0;
		$saldo_masuk 	= $saldo_keluar = 0;

		$this->db->select_sum('profitshare_total');
		if ($userid != null) {
			$this->db->where('profitshare_userid', $userid);
		} else {
			$this->db->where('profitshare_userid', userid());
		}
		$this->db->where('profitshare_type', 'credit');
		$gettttt 			= $this->db->get('tb_profitshare');
		$get_saldo_masuk 	= $gettttt->row()->profitshare_total;

		if (!empty($get_saldo_masuk)) {
			$saldo_masuk 	= $get_saldo_masuk;
		}


		//get saldo keluar
		$this->db->select_sum('profitshare_total');
		if ($userid != null) {
			$this->db->where('profitshare_userid', $userid);
		} else {
			$this->db->where('profitshare_userid', userid());
		}
		$this->db->where('profitshare_type', 'debit');
		$get 			= $this->db->get('tb_profitshare');
		$get_saldo_keluar 	= $get->row()->profitshare_total;

		if (!empty($get_saldo_keluar)) {
			$saldo_keluar 	= $get_saldo_keluar;
		}

		$return 			= $saldo_masuk - $saldo_keluar;

		return $return;
	}


	function poinreward($userid = null, $poin = 0)
	{

		$return 		= 0;
		$saldo_masuk 	= $saldo_keluar = 0;

		$this->db->select_sum('poinreward_amount');
		if ($userid != null) {
			$this->db->where('poinreward_userid', $userid);
		} else {
			$this->db->where('poinreward_userid', userid());
		}
		if ($poin != 0) {
			$this->db->where('poinreward_level', $poin);
		}
		$this->db->where('poinreward_type', 'credit');
		$gettttt 			= $this->db->get('tb_poinreward');
		$get_saldo_masuk 	= $gettttt->row()->poinreward_amount;

		if (!empty($get_saldo_masuk)) {
			$saldo_masuk 	= $get_saldo_masuk;
		}


		//get saldo keluar
		$this->db->select_sum('poinreward_amount');
		if ($userid != null) {
			$this->db->where('poinreward_userid', $userid);
		} else {
			$this->db->where('poinreward_userid', userid());
		}
		if ($poin != 0) {
			$this->db->where('poinreward_level', $poin);
		}
		$this->db->where('poinreward_type', 'debit');
		$get 			= $this->db->get('tb_poinreward');
		$get_saldo_keluar 	= $get->row()->poinreward_amount;

		if (!empty($get_saldo_keluar)) {
			$saldo_keluar 	= $get_saldo_keluar;
		}

		$return 			= $saldo_masuk - $saldo_keluar;

		return $return;
	}

	function mycashback($userid = null)
	{

		$return 		= 0;
		$saldo_masuk 	= 0;

		$this->db->select_sum('reportcashback_amount');
		if ($userid != null) {
			$this->db->where('reportcashback_userid', $userid);
		} else {
			$this->db->where('reportcashback_userid', userid());
		}
		$gettttt 			= $this->db->get('tb_reportcashback');
		$get_saldo_masuk 	= $gettttt->row()->reportcashback_amount;

		if (!empty($get_saldo_masuk)) {
			$saldo_masuk 	= $get_saldo_masuk;
		}

		$return 			= $saldo_masuk;

		return $return;
	}

	function cekgoldelite(){
		$query = "SELECT u.username, u.user_omset 
					FROM tb_users u
					WHERE u.user_omset >= 250 
					AND (
						SELECT COUNT(*)
						FROM tb_users r
						WHERE r.referral_id = u.id AND r.user_omset >= 250
					) >= 3;
				";
		return $this->db->query($query)->num_rows();
	}
}

/* End of file Walletmodel.php */
/* Location: ./application/models/Walletmodel.php */