<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends CI_Model
{

	private static $data = [
		'status' 	=> true,
		'message' 	=> null,
	];

	public function __construct()
	{
		parent::__construct();
		Self::$data['csrf_data'] 	= $this->security->get_csrf_hash();
		$this->load->model('emailmodel');
	}

	function invoiceapprove()
	{
		$this->db->where('pembayaran_status', 'pending');
		$this->db->where('pembayaran_code', post('code'));
		$this->db->join('tb_users_invoice', 'invoice_id = pembayaran_invoice_id');
		$this->db->join('tb_users', 'invoice_user_id = id');
		$this->db->join('tb_packages', 'package_id = invoice_package_id');
		$cekdatainvoice = $this->db->get('tb_users_pembayaran');
		if ($cekdatainvoice->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "Invoice Data Not Found";
		}

		$this->form_validation->set_rules('code', 'Invoice Code', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$datainvoice 	= $cekdatainvoice->row();
			$trx_id 		= hash('SHA256', random_string('alnum', 16));

			/*============================================
            =            UPDATE STATUS INVOICE            =
            ============================================*/
			$this->db->update('tb_users_invoice', array('invoice_status' => 'success'), array('invoice_id' => $datainvoice->invoice_id));
			$this->db->update('tb_users_pembayaran', array('pembayaran_status' => 'approve'), array('pembayaran_invoice_id' => $datainvoice->invoice_id));

			/*============================================
            =       INPUT LANDING UNTUK USER AKTIF  	=
            ============================================*/
			$this->db->insert('tb_lending', [
				'lending_userid'            => $datainvoice->id,
				'lending_package_id'        => $datainvoice->package_id,
				'lending_package'           => $datainvoice->package_name,
				'lending_amount'            => $datainvoice->package_price,
				'lending_source'            => 'direct_transfer',
				'lending_datestart'         => sekarang(),
				'lending_dateend'           => date('Y-m-d 23:59:59', strtotime('+50 month', now())),
			]);

			/*============================================
            =		      INPUT POIN REWARD  		   =
            ============================================*/
			$this->db->insert(
				'tb_poinrw',
				[
					'poinrw_userid'		=> $datainvoice->referral_id,
					'poinrw_pktid'		=> $datainvoice->package_id,
					'poinrw_total'		=> (int)1,
					'poinrw_tipe'		=> 'credit',
					'poinrw_desc'		=> 'Sponsor Bonus From ' . $datainvoice->username . ' Registration',
					'poinrw_date'		=> sekarang(),
					'poinrw_code'		=> strtolower(random_string('alnum', 64)),
				]
			);

			/*============================================
            =		      INPUT BONUS SPONSOR  		   =
            ============================================*/
			$bonus_sponsor 	= ($datainvoice->package_sponsor / 100) * $datainvoice->package_price;
			$wallet     		= $this->usermodel->userWallet('withdrawal', $datainvoice->referral_id);

			$this->db->insert(
				'tb_wallet_balance',
				[
					'w_balance_wallet_id'       => $wallet->wallet_id,
					'w_balance_amount'          => $bonus_sponsor,
					'w_balance_type'            => 'credit',
					'w_balance_desc'            => 'Sponsor Bonus From ' . $datainvoice->username . ' Registration',
					'w_balance_date_add'        => sekarang(),
					'w_balance_txid'            => strtolower(random_string('alnum', 64)),
					'w_balance_ket'				=> 'sponsor',
				]
			);

			/*============================================
            =		      INPUT ROI AKTIF  		   		=
            ============================================*/
			$array_data = array();
			$this->db->where('package_id', (int)$datainvoice->package_id);
			$gettttt = $this->db->get('tb_packages');
			$array_bonus     = json_decode($gettttt->row()->package_roi);
			$setplus = $gettttt->row()->package_day;
			foreach ($array_bonus as $roi) {
				$roi_package	= $gettttt->row()->package_price;
				$roi_total		= $roi;
				array_push($array_data, date('Y-m-d', strtotime('+' . $setplus . ' day', now())));
				$setplus    += 10;
			}

			$arra_roi = [
				'paket'		=> $roi_package,
				'total'		=> $roi_total,
				'tanggal'	=> json_encode($array_data),
			];

			$this->db->insert(
				'tb_pktactive',
				[
					'pktactive_userid'		=> $datainvoice->id,
					'pktactive_datelist'	=> $arra_roi['tanggal'],
					'pktactive_pkgamount'	=> $arra_roi['paket'],
					'pktactive_amount'		=> $arra_roi['total'],
					'pktactive_date'		=> sekarang(),
					'pktactive_status'		=> 'active',
					'pktactive_code'		=> strtolower(random_string('alnum', 64)),
				]
			);


			Self::$data['heading'] 		= 'Berhasil';
			Self::$data['message'] 		= 'Transaksi Telah Dikonfirmasi!';
			Self::$data['type']	 		= 'success';
		} else {

			Self::$data['heading'] 		= 'Gagal';
			Self::$data['type']	 		= 'error';
		}

		return Self::$data;
	}

	function bonuslevel($user_id = null, $user_id_from = null, $getpaket = 1, $level = 1)
	{
		$result 		= array();
		$status 		= true;
		$paketid		= $getpaket;

		$datauser 		= userdata(['id' => $user_id]);
		$userdata 		= userdata(['id' => $user_id_from]);

		// GET PAKET
		$this->db->where('package_id', $paketid);
		$get_packages 		= $this->db->get('tb_packages')->row();

		$array_term_level 	= json_decode($get_packages->package_titik);
		if ($level > count($array_term_level)) {
			$status = false;
		}

		if ($userdata->upline_id == 1) {
			$status = false;
		}
		if ($userdata->upline_id == 0) {
			$status = false;
		}

		$uplinedata 	= userdata(['id' => $userdata->upline_id]);


		if ($status) {
			if ($uplinedata) {

				$this->db->where('package_id', $uplinedata->user_paketid);
				$getpaket 		= $this->db->get('tb_packages')->row();
				$array_bonus 	= json_decode($getpaket->package_titik);

				$wallet     		= $this->usermodel->userWallet('withdrawal', $uplinedata->id);

				$this->db->insert(
					'tb_wallet_balance',
					[
						'w_balance_wallet_id'       => $wallet->wallet_id,
						'w_balance_amount'          => $array_bonus[$level - 1],
						'w_balance_type'            => 'credit',
						'w_balance_desc'            => 'Bonus Royalty, Level ' . $level . ' dari Pendaftaran Username : ' . $datauser->username,
						'w_balance_date_add'        => sekarang(),
						'w_balance_txid'            => strtolower(random_string('alnum', 64)),
						'w_balance_ket'				=> 'level',
					]
				);

				$this->db->insert('tb_titiklevel', [
					'titiklevel_userid'             => $uplinedata->id,
					'titiklevel_downlineid'         => $datauser->id,
					'titiklevel_level'              => $level,
					'titiklevel_date'               => sekarang(),
				]);

				$this->bonuslevel($datauser->id, $uplinedata->id, $paketid, $level + 1);
			}
		}
		return $result;
	}


	function bonuslevel_ro($user_id = null, $user_id_from = null, $getpaket = 1, $gettot = 1, $level = 1)
	{
		$result 		= array();
		$status 		= true;
		$paketid		= $getpaket;
		$totro			= $gettot;

		$datauser 		= userdata(['id' => $user_id]);
		$userdata 		= userdata(['id' => $user_id_from]);

		// GET PAKET
		$this->db->where('package_id', $paketid);
		$get_packages 		= $this->db->get('tb_packages')->row();

		$array_term_level 	= json_decode($get_packages->package_titik);
		if ($level > count($array_term_level)) {
			$status = false;
		}

		if ($userdata->upline_id == 1) {
			$status = false;
		}
		if ($userdata->upline_id == 0) {
			$status = false;
		}

		$uplinedata 	= userdata(['id' => $userdata->id]);


		if ($status) {
			if ($uplinedata) {

				if ($userdata->id == $user_id) {
					$dekripsi = 'Cashback dari Transaksi Repeat Order';
				} else {
					$dekripsi = 'Bonus Level, Level Ke ' . $level . ' dari Repeat Order Username : ' . $datauser->username;
				}

				$wallet     		= $this->usermodel->userWallet('withdrawal', $uplinedata->id);

				$this->db->insert(
					'tb_wallet_balance',
					[
						'w_balance_wallet_id'       => $wallet->wallet_id,
						'w_balance_amount'          => (int)$array_term_level[$level - 1] * (int)$totro,
						'w_balance_type'            => 'credit',
						'w_balance_desc'            => $dekripsi,
						'w_balance_date_add'        => sekarang(),
						'w_balance_txid'            => strtolower(random_string('alnum', 64)),
						'w_balance_ket'				=> 'level',
					]
				);

				$this->bonuslevel_ro($datauser->id, $uplinedata->upline_id, $paketid, $totro, $level + 1);
			}
		}
		return $result;
	}

	function approveTabungan()
	{
		$this->db->where('invnabung_status', 'process');
		$this->db->where('paynabung_code', post('code'));
		$this->db->join('tb_invnabung', 'tb_invnabung.invnabung_id = tb_paynabung.paynabung_invid');
		$this->db->join('tb_users', 'tb_users.id = tb_paynabung.paynabung_userid');
		$cekPaynabungg = $this->db->get('tb_paynabung');
		if ($cekPaynabungg->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "Data Paynabung Tidak Ditemukan";
		}

		$this->form_validation->set_rules('code', 'Invoice Code', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$paynabung = $cekPaynabungg->row();
			$random_string = strtolower(random_string('alnum', 60));

			/*============================================
            =         UPDATE STATUS INVNABUNG            =
            ============================================*/
			$this->db->update('tb_invnabung', array('invnabung_status' => 'success'), array('invnabung_id' => $paynabung->invnabung_id));
			$this->db->update('tb_paynabung', array('paynabung_status' => 'Approve'), array('paynabung_id' => $paynabung->paynabung_id));

			$userdatas = userdata(['id' => $paynabung->paynabung_userid]);
			$referral_id = $userdatas->referral_id;

			$walletid     = $this->usermodel->userWallet('withdrawal', $referral_id);

			$persen = (int)2 / 100;
			$totalbonus = $paynabung->invnabung_amount * $persen;

			$this->db->insert(
				'tb_wallet_balance',
				[
					'w_balance_wallet_id'       => $walletid->wallet_id,
					'w_balance_amount'          => $totalbonus,
					'w_balance_type'            => 'credit',
					'w_balance_desc'            => 'Bonus Pelunasan Rp. ' . number_format($totalbonus, 0, '.', '.') . ' Dari Username ' . $userdatas->username,
					'w_balance_date_add'        => sekarang(),
					'w_balance_txid'            => strtolower(random_string('alnum', 64)),
					'w_balance_ket'				=> 'sponsor',
				]
			);

			/*============================================
            =          INSERT WALLET TABUNGAN           =
            ============================================*/
			$this->db->insert(
				'tb_walletnabung',
				[
					'walletnabung_userid'			=> $paynabung->invnabung_userid,
					'walletnabung_amount'			=> $paynabung->invnabung_amount,
					'walletnabung_type'				=> 'credit',
					'walletnabung_date'				=> sekarang(),
					'walletnabung_code'				=> $random_string,
				]
			);
			/*============================================
            =          INSERT HISTORI TABUNGAN           =
            ============================================*/
			$this->db->insert(
				'tb_historitabungan',
				[
					'historitabungan_userid'		=> $paynabung->invnabung_userid,
					'historitabungan_desc'			=> 'Credit Dana Pelunasan Sebesar Rp. ' . number_format($paynabung->invnabung_amount, 0, ',', '.'),
					'historitabungan_total'			=> $paynabung->invnabung_amount,
					'historitabungan_date'			=> sekarang(),
					'historitabungan_code'			=> $random_string,
				]
			);

			$totSaldo = $this->usermodel->userWalletNabung($paynabung->invnabung_userid);

			$nilaistoran = 1500000;
			$this->db->where('booking_userid', $paynabung->invnabung_userid);
			$cekstoran = $this->db->get('tb_booking');
			if ($cekstoran->num_rows() != 0) {
				$nilaistoran = 1500000;
			}

			$nowa     = $paynabung->user_phone;
			$pesan    = "Yth. " . $paynabung->user_fullname . " Setoran pelunasan sebesar Rp. " . number_format($paynabung->invnabung_amount, 0, '.', '.') . " telah berhasil. Total pembayaran biaya umroh anda menjadi: Rp. "  . number_format($totSaldo + $nilaistoran, 0, '.', '.') . " Terima kasih !! - PT. Sispenju Amanah Wisata (SISPENJU TOUR)";

			$this->notifWA($nowa, $pesan);

			Self::$data['heading'] 		= 'Berhasil';
			Self::$data['type']	 		= 'success';
			Self::$data['message'] 		= 'Transaksi Pelunasan Telah Dikonfirmasi!';
		} else {
			Self::$data['heading'] 		= 'Gagal';
			Self::$data['type']	 		= 'error';
		}
		return Self::$data;
	}

	function rejectTabungan()
	{
		$this->db->where('invnabung_status', 'process');
		$this->db->where('paynabung_code', post('code'));
		$this->db->join('tb_invnabung', 'tb_invnabung.invnabung_id = tb_paynabung.paynabung_invid');
		$cekkPay = $this->db->get('tb_paynabung');
		if ($cekkPay->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "Data Tidak Ditemukan atau Sudah Terkonfirmasi";
		}

		$this->form_validation->set_rules('code', 'Kode Invoice', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$pay = $cekkPay->row();

			// UPDATE INVOICE
			$this->db->update('tb_invnabung', array('invnabung_status' => 'pending'), array('invnabung_id' => $pay->invnabung_id));
			// DELETE PEMBAYARAN
			$this->db->delete('tb_paynabung', array('paynabung_invid' => $pay->paynabung_invid));

			Self::$data['heading'] 		= 'Berhasil';
			Self::$data['type']	 		= 'success';
			Self::$data['message'] 		= 'Transaksi Ditolak !';
		} else {
			Self::$data['heading'] 		= 'Gagal';
			Self::$data['type']	 		= 'error';
		}
		return Self::$data;
	}

	function approvesale()
	{
		$this->db->join('tb_users_invoice', 'pembayaran_invoice_id = invoice_id');
		$this->db->join('tb_packages', 'package_id = invoice_package_id');
		$this->db->where('pembayaran_touserid', userid());
		$this->db->where('pembayaran_status', 'pending');
		$this->db->where('pembayaran_code', $this->input->post('code'));
		$this->db->join('tb_users', 'invoice_userfromid = id');
		$CEKPAY = $this->db->get('tb_users_pembayaran');
		if ($CEKPAY->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']    = 'Transaction Data Not Found or Confirmed';
		}

		$this->form_validation->set_rules('code', 'Code', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$dataPAY = $CEKPAY->row();
			$userdata   = userdata();

			/*============================================
            =        UPDATE PEMBAYARAN DAN INVOICE        =
            ============================================*/
			$this->db->update('tb_users_invoice', array('invoice_status' => 'success'), array('invoice_id' => $dataPAY->invoice_id));
			$this->db->update('tb_users_pembayaran', array('pembayaran_status' => 'approve'), array('pembayaran_invoice_id' => $dataPAY->invoice_id));


			/*============================================
            =		       INSERT PIN KODE  			=
            ============================================*/
			for ($jumlah = 0; $jumlah < str_replace('.', '', $dataPAY->invoice_total); $jumlah++) {

				$kodeeee         = 'HBL' . strtoupper(random_string('numeric', 2) . random_string('alnum', 4) . random_string('alnum', 4) . random_string('alnum', 4) . random_string('alnum', 4) . $dataPAY->package_kode);
				$this->db->insert(
					'tb_users_pin',
					[
						'pin_package_id'        => $dataPAY->invoice_package_id,
						'pin_kode'              => $kodeeee,
						'pin_date_add'          => sekarang(),
						'pin_userid'            => $dataPAY->invoice_userfromid,
						'pin_code'              => strtolower(random_string('alnum', 64)),
					]
				);
			}

			// LAPORAN
			$this->db->insert(
				'tb_reportpin',
				[
					'reportpin_userid'      => $dataPAY->invoice_usertoid,
					'reportpin_desc'        => 'Send ' . $dataPAY->invoice_total . ' PIN Codes, Package: ' . $dataPAY->package_name . ' to Username: ' . $dataPAY->username,
					'reportpin_date'        => sekarang(),
					'reportpin_code'        => strtolower(random_string('alnum', 64)),
				]
			);

			// LAPORAN PENERIMA
			$this->db->insert(
				'tb_reportpin',
				[
					'reportpin_userid'      => $dataPAY->invoice_userfromid,
					'reportpin_desc'        => 'Receive ' . $dataPAY->invoice_total . ' PIN Codes, Package: ' . $dataPAY->package_name . ' from Username: ' . $userdata->username,
					'reportpin_date'        => sekarang(),
					'reportpin_code'        => strtolower(random_string('alnum', 64)),
				]
			);

			// REPORT ORDER UNTUK PEMBELI
			$this->db->insert(
				'tb_reportorder',
				[
					'reportorder_userid'	=> $dataPAY->invoice_userfromid,
					'reportorder_total'		=> $dataPAY->invoice_total,
					'reportorder_desc'		=> 'Orders ' . $dataPAY->invoice_total . ' PIN Codes, Package: ' . $dataPAY->package_name . ' from Username: ' . $userdata->username,
					'reportorder_date'		=> sekarang(),
					'reportorder_code'		=> strtolower(random_string('alnum', 64)),
				]
			);




			Self::$data['heading'] 		= 'Success';
			Self::$data['message'] 		= 'Billing has been confirmed and PIN has been sent!';
			Self::$data['type']	 		= 'success';
		} else {

			Self::$data['heading'] 		= 'Error';
			Self::$data['type']	 		= 'error';
		}

		return Self::$data;
	}

	function rejectsale()
	{

		$this->db->join('tb_users_invoice', 'pembayaran_invoice_id = invoice_id');
		$this->db->join('tb_packages', 'package_id = invoice_package_id');
		$this->db->where('pembayaran_touserid', userid());
		$this->db->where('pembayaran_status', 'pending');
		$this->db->where('pembayaran_code', $this->input->post('code'));
		$this->db->join('tb_users', 'invoice_userfromid = id');
		$CEKPAY = $this->db->get('tb_users_pembayaran');
		if ($CEKPAY->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']    = 'Transaction Data Not Found or Confirmed';
		}

		$this->form_validation->set_rules('code', 'Code', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$dataPAY = $CEKPAY->row();

			// REMOVE PEMBAYARAN
			$this->db->delete('tb_users_pembayaran', array('pembayaran_code' => $this->input->post('code')));

			// UPDATE INVOICE
			$this->db->update('tb_users_invoice', array('invoice_status' => 'pending'), array('invoice_id' => $dataPAY->invoice_id));


			Self::$data['heading'] 		= 'Success';
			Self::$data['message'] 		= 'Transaction Has Been Rejected!';
			Self::$data['type']	 		= 'success';
		} else {

			Self::$data['heading'] 		= 'Gagal';
			Self::$data['type']	 		= 'error';
		}

		return Self::$data;
	}

	function approvenewstokist()
	{
		$this->db->where('pembayaran_stokis_code', $this->input->post('code'));
		$this->db->join('tb_users_invoice_stokis', 'pembayaran_stokis_invoice_id = invoice_stokis_id');
		$this->db->join('tb_pktstokist', 'pktstokist_id = invoice_stokis_package_id');
		$this->db->join('tb_users', 'pembayaran_stokis_userid = id');
		$CEKPAY = $this->db->get('tb_users_pembayaran_stokis');
		if ($CEKPAY->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']    = 'Data Transaksi Tidak Ditemukan atau Sudah Terkonfirmasi';
		}

		$this->form_validation->set_rules('code', 'Code', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$dataPAY = $CEKPAY->row();

			/*============================================
            =        UPDATE PEMBAYARAN DAN INVOICE        =
            ============================================*/
			$this->db->update('tb_users_invoice_stokis', array('invoice_stokis_status' => 'success'), array('invoice_stokis_id' => $dataPAY->invoice_stokis_id));
			$this->db->update('tb_users_pembayaran_stokis', array('pembayaran_stokis_status' => 'approve'), array('pembayaran_stokis_invoice_id' => $dataPAY->invoice_stokis_id));

			/*============================================
            =		       INSERT PIN KODE  			=
            ============================================*/
			// for ($jumlah = 0; $jumlah < str_replace('.', '', $dataPAY->invoice_stokis_total); $jumlah++) {

			// 	$kodeeee         = 'ALB' . strtoupper(random_string('numeric', 2) . random_string('alnum', 4) . random_string('alnum', 4) . random_string('alnum', 4) . random_string('alnum', 4) . 'RE');
			// 	$this->db->insert(
			// 		'tb_users_pin',
			// 		[
			// 			'pin_package_id'        => (int)1,
			// 			'pin_kode'              => $kodeeee,
			// 			'pin_date_add'          => sekarang(),
			// 			'pin_userid'            => $dataPAY->invoice_stokis_userid,
			// 			'pin_code'              => strtolower(random_string('alnum', 64)),
			// 		]
			// 	);
			// }

			// $typeee = ($dataPAY->invoice_stokis_package_id == (int)1) ? 'stokis' : 'master';

			// $this->db->where('userstokis_userid', $dataPAY->invoice_stokis_userid);
			// $CEKKKKKK = $this->db->get('tb_users_stokis');
			// if ($CEKKKKKK->num_rows() == 0) {
			// 	// JIKA BELUM ADA STOKIS
			// 	$this->db->insert(
			// 		'tb_users_stokis',
			// 		[
			// 			'userstokis_userid'		=> $dataPAY->invoice_stokis_userid,
			// 			'userstokis_type'		=> $typeee,
			// 			'userstokis_provinsi'	=> $dataPAY->user_provinsi,
			// 			'userstokis_kota'		=> $dataPAY->user_kota,
			// 			'userstokis_kecamatan'	=> $dataPAY->user_kecamatan,
			// 			'userstokis_kelurahan'	=> $dataPAY->user_kelurahan,
			// 			'userstokis_alamat'		=> $dataPAY->user_alamat,
			// 			'userstokis_date'		=> sekarang(),
			// 			'userstokis_code'		=> strtolower(random_string('alnum', 64)),
			// 		]
			// 	);
			// } else {
			// 	$DATAAA = $CEKKKKKK->row();
			// 	// JIKA SUDAH ADA DATA STOKIS
			// 	$this->db->update(
			// 		'tb_users_stokis',
			// 		[
			// 			'userstokis_type'		=> $typeee,
			// 			'userstokis_provinsi'	=> $dataPAY->user_provinsi,
			// 			'userstokis_kota'		=> $dataPAY->user_kota,
			// 			'userstokis_kecamatan'	=> $dataPAY->user_kecamatan,
			// 			'userstokis_kelurahan'	=> $dataPAY->user_kelurahan,
			// 			'userstokis_alamat'		=> $dataPAY->user_alamat,
			// 			'userstokis_date'		=> sekarang(),
			// 		],
			// 		[
			// 			'userstokis_userid'		=> $dataPAY->invoice_stokis_userid,
			// 			'userstokis_code'		=> $DATAAA->userstokis_code,
			// 		]
			// 	);
			// }


			// LAPORAN
			// $this->db->insert(
			// 	'tb_reportpin',
			// 	[
			// 		'reportpin_userid'      => userid(),
			// 		'reportpin_desc'        => 'Kirim ' . $dataPAY->invoice_stokis_total . ' PIN Kode Paket Registrasi ke Username: ' . $dataPAY->username . ' yang mendaftar sebagai ' . $dataPAY->pktstokist_name,
			// 		'reportpin_date'        => sekarang(),
			// 		'reportpin_code'        => strtolower(random_string('alnum', 64)),
			// 	]
			// );

			// // LAPORAN PENERIMA
			// $this->db->insert(
			// 	'tb_reportpin',
			// 	[
			// 		'reportpin_userid'      => $dataPAY->invoice_stokis_userid,
			// 		'reportpin_desc'        => 'Terima ' . $dataPAY->invoice_stokis_total . ' PIN Kode Paket Registrasi ke Username: ' . $dataPAY->username . ' dari pendaftaran ' . $dataPAY->pktstokist_name,
			// 		'reportpin_date'        => sekarang(),
			// 		'reportpin_code'        => strtolower(random_string('alnum', 64)),
			// 	]
			// );

			// $this->db->insert(
			// 	'tb_totpin',
			// 	[
			// 		'totpin_amount'	=> (int)$dataPAY->invoice_stokis_total,
			// 		'totpin_desc'	=> 'Pendaftaran ' . $typeee . ' oleh Username : ' . $dataPAY->username,
			// 		'totpin_type'	=> 'debit',
			// 		'totpin_date'	=> sekarang(),
			// 	]
			// );

			Self::$data['heading'] 		= 'Berhasil';
			Self::$data['message'] 		= 'Transaksi Ini Berhasil Dikonfirmasi!';
			Self::$data['type']	 		= 'success';
		} else {

			Self::$data['heading'] 		= 'Gagal';
			Self::$data['type']	 		= 'error';
		}

		return Self::$data;
	}

	function rejectnewstokist()
	{
		$this->db->where('pembayaran_stokis_code', $this->input->post('code'));
		$this->db->join('tb_users_invoice_stokis', 'pembayaran_stokis_invoice_id = invoice_stokis_id');
		$this->db->join('tb_pktstokist', 'pktstokist_id = invoice_stokis_package_id');
		$this->db->join('tb_users', 'pembayaran_stokis_userid = id');
		$CEKPAY = $this->db->get('tb_users_pembayaran_stokis');
		if ($CEKPAY->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']    = 'Data Transaksi Tidak Ditemukan atau Sudah Terkonfirmasi';
		}

		$this->form_validation->set_rules('code', 'Code', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$dataPAY = $CEKPAY->row();

			/*============================================
            =        UPDATE PEMBAYARAN DAN INVOICE        =
            ============================================*/
			$this->db->update('tb_users_invoice_stokis', array('invoice_stokis_status' => 'pending'), array('invoice_stokis_id' => $dataPAY->invoice_stokis_id));
			$this->db->delete('tb_users_pembayaran_stokis', array('pembayaran_stokis_status' => 'pending'), array('pembayaran_stokis_invoice_id' => $dataPAY->invoice_stokis_id));

			/*============================================
            =		       INSERT PIN KODE  			=
            ============================================*/
			// for ($jumlah = 0; $jumlah < str_replace('.', '', $dataPAY->invoice_stokis_total); $jumlah++) {

			// 	$kodeeee         = 'ALB' . strtoupper(random_string('numeric', 2) . random_string('alnum', 4) . random_string('alnum', 4) . random_string('alnum', 4) . random_string('alnum', 4) . 'RE');
			// 	$this->db->insert(
			// 		'tb_users_pin',
			// 		[
			// 			'pin_package_id'        => (int)1,
			// 			'pin_kode'              => $kodeeee,
			// 			'pin_date_add'          => sekarang(),
			// 			'pin_userid'            => $dataPAY->invoice_stokis_userid,
			// 			'pin_code'              => strtolower(random_string('alnum', 64)),
			// 		]
			// 	);
			// }

			// $typeee = ($dataPAY->invoice_stokis_package_id == (int)1) ? 'stokis' : 'master';

			// $this->db->where('userstokis_userid', $dataPAY->invoice_stokis_userid);
			// $CEKKKKKK = $this->db->get('tb_users_stokis');
			// if ($CEKKKKKK->num_rows() == 0) {
			// 	// JIKA BELUM ADA STOKIS
			// 	$this->db->insert(
			// 		'tb_users_stokis',
			// 		[
			// 			'userstokis_userid'		=> $dataPAY->invoice_stokis_userid,
			// 			'userstokis_type'		=> $typeee,
			// 			'userstokis_provinsi'	=> $dataPAY->user_provinsi,
			// 			'userstokis_kota'		=> $dataPAY->user_kota,
			// 			'userstokis_kecamatan'	=> $dataPAY->user_kecamatan,
			// 			'userstokis_kelurahan'	=> $dataPAY->user_kelurahan,
			// 			'userstokis_alamat'		=> $dataPAY->user_alamat,
			// 			'userstokis_date'		=> sekarang(),
			// 			'userstokis_code'		=> strtolower(random_string('alnum', 64)),
			// 		]
			// 	);
			// } else {
			// 	$DATAAA = $CEKKKKKK->row();
			// 	// JIKA SUDAH ADA DATA STOKIS
			// 	$this->db->update(
			// 		'tb_users_stokis',
			// 		[
			// 			'userstokis_type'		=> $typeee,
			// 			'userstokis_provinsi'	=> $dataPAY->user_provinsi,
			// 			'userstokis_kota'		=> $dataPAY->user_kota,
			// 			'userstokis_kecamatan'	=> $dataPAY->user_kecamatan,
			// 			'userstokis_kelurahan'	=> $dataPAY->user_kelurahan,
			// 			'userstokis_alamat'		=> $dataPAY->user_alamat,
			// 			'userstokis_date'		=> sekarang(),
			// 		],
			// 		[
			// 			'userstokis_userid'		=> $dataPAY->invoice_stokis_userid,
			// 			'userstokis_code'		=> $DATAAA->userstokis_code,
			// 		]
			// 	);
			// }


			// LAPORAN
			// $this->db->insert(
			// 	'tb_reportpin',
			// 	[
			// 		'reportpin_userid'      => userid(),
			// 		'reportpin_desc'        => 'Kirim ' . $dataPAY->invoice_stokis_total . ' PIN Kode Paket Registrasi ke Username: ' . $dataPAY->username . ' yang mendaftar sebagai ' . $dataPAY->pktstokist_name,
			// 		'reportpin_date'        => sekarang(),
			// 		'reportpin_code'        => strtolower(random_string('alnum', 64)),
			// 	]
			// );

			// // LAPORAN PENERIMA
			// $this->db->insert(
			// 	'tb_reportpin',
			// 	[
			// 		'reportpin_userid'      => $dataPAY->invoice_stokis_userid,
			// 		'reportpin_desc'        => 'Terima ' . $dataPAY->invoice_stokis_total . ' PIN Kode Paket Registrasi ke Username: ' . $dataPAY->username . ' dari pendaftaran ' . $dataPAY->pktstokist_name,
			// 		'reportpin_date'        => sekarang(),
			// 		'reportpin_code'        => strtolower(random_string('alnum', 64)),
			// 	]
			// );

			// $this->db->insert(
			// 	'tb_totpin',
			// 	[
			// 		'totpin_amount'	=> (int)$dataPAY->invoice_stokis_total,
			// 		'totpin_desc'	=> 'Pendaftaran ' . $typeee . ' oleh Username : ' . $dataPAY->username,
			// 		'totpin_type'	=> 'debit',
			// 		'totpin_date'	=> sekarang(),
			// 	]
			// );

			Self::$data['heading'] 		= 'Berhasil';
			Self::$data['message'] 		= 'Pengajuan Stokis Berhasil di Reject';
			Self::$data['type']	 		= 'success';
		} else {

			Self::$data['heading'] 		= 'Gagal';
			Self::$data['type']	 		= 'error';
		}

		return Self::$data;
	}
}

/* End of file Invoice.php */
/* Location: ./application/models/Invoice.php */