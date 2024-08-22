<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
	}

	function repeat_order()
	{
		if (!$this->ion_auth->hash_password_db(userid(), post('ro_password'))) {
			Self::$data['status']       = false;
			Self::$data['message']      = 'Konfirmasi Password Anda Tidak Sesuai!';
		}

		$this->db->where('package_code', $this->input->post('ro_paket'));
		$gettttt = $this->db->get('tb_packages');
		if ($gettttt->num_rows() == 0) {
			Self::$data['status']       = false;
			Self::$data['message']      = 'Paket Repeat Order Tidak Valid!';
		}

		$this->form_validation->set_rules('ro_paket', 'Paket RO', 'required');
		$this->form_validation->set_rules('ro_total', 'Total RO', 'required');
		$this->form_validation->set_rules('ro_password', 'Password', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		$this->db->where('invoice_user_id', userid());
		$this->db->where('invoice_type', 'ro');
		$this->db->where('invoice_status !=', 'success');
		$cekinvoic = $this->db->get('tb_users_invoice');
		if ($cekinvoic->num_rows() != 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "Anda Memiliki Transaksi Aktif, Mohon Menunggu Selesai Transaksi Sebelumnya";
		}

		if (preg_replace('/[^0-9.]+/', '', preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $this->input->post('ro_total'))) < 1) {
			Self::$data['status']     = false;
			Self::$data['message']     = "Minimal Transaksi Repeat Order 1";
		}

		if (Self::$data['status']) {
			$datapaket = $gettttt->row();
			$total 		= (int)preg_replace('/[^0-9.]+/', '', preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $this->input->post('ro_total')));
			$rannnnnnn 	= rand(300, 999);

			$this->db->insert(
				'tb_users_invoice',
				[
					'invoice_package_id'		=> $datapaket->package_id,
					'invoice_type'				=> 'ro',
					'invoice_user_id'			=> userid(),
					'invoice_total'				=> $total,
					'invoice_amount'			=> ((int)$datapaket->package_price * $total),
					'invoice_subamount'			=> ((int)$datapaket->package_price * $total) + $rannnnnnn,
					'invoice_kodeinv'			=> date('Y') . date('m') . date('d') . $rannnnnnn,
					'invoice_kode_unik'			=> $rannnnnnn,
					'invoice_date_add'			=> sekarang(),
					'invoice_code'				=> strtolower(random_string('alnum', 64)),
				]
			);


			Self::$data['message']      = 'Invoice Repeat Order Anda Telah Dibuat, Harap Segara Konfirmasi';
			Self::$data['heading']      = 'Berhasil';
			Self::$data['type']         = 'success';
		} else {
			Self::$data['heading']     	= 'Error';
			Self::$data['type']     	= 'error';
		}

		return Self::$data;
	}


	function konfirmasipembayaran()
	{
		$this->db->where('bankadmin_code', $this->input->post('confirm_pembayaran'));
		$cekkbank = $this->db->get('tb_bankadmin');
		if ($cekkbank->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "Invalid Payment Method";
		} else {
			$config['upload_path']          = './assets/upload/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = '99999999';
			$config['max_width']            = '99999999';
			$config['max_height']           = '99999999';
			$config['remove_spaces']        = TRUE;
			$config['encrypt_name']         = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('confirm_fileimg')) {
				Self::$data['status']     = false;
				Self::$data['message']     = $this->upload->display_errors();
			}
		}

		$this->db->where('invoice_status', 'pending');
		$this->db->where('invoice_user_id', userid());
		$this->db->where('invoice_code', $this->input->post('code'));
		$cekinvoice = $this->db->get('tb_users_invoice');
		if ($cekinvoice->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "You Have No Transaction To Confirm";
		}

		$this->form_validation->set_rules('code', 'Transaction Code', 'required');
		$this->form_validation->set_rules('confirm_pembayaran', 'Payment Method', 'required');
		$this->form_validation->set_rules('confirm_account', 'Account in the Name', 'required');
		$this->form_validation->set_rules('confirm_bank', 'Bank Name', 'required');
		$this->form_validation->set_rules('confirm_number', 'Account Number', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$datainvoice 	= $cekinvoice->row();
			$databank 		= $cekkbank->row();
			$uploaded		= $this->upload->data();

			$this->db->insert('tb_users_pembayaran', [
				'pembayaran_invoice_id'				=> $datainvoice->invoice_id,
				'pembayaran_userid'					=> userid(),
				'pembayaran_adbankname'				=> $databank->bankadmin_bankname,
				'pembayaran_adbankaccount'			=> $databank->bankadmin_bankaccount,
				'pembayaran_adbanknumber'			=> $databank->bankadmin_banknumber,
				'pembayaran_bankname'				=> $this->input->post('confirm_bank'),
				'pembayaran_bankaccount'			=> $this->input->post('confirm_account'),
				'pembayaran_banknumber'				=> $this->input->post('confirm_number'),
				'pembayaran_struk'					=> $uploaded['file_name'],
				'pembayaran_date_add'				=> sekarang(),
				'pembayaran_nominal'				=> $datainvoice->invoice_amount,
				'pembayaran_code'					=> strtolower(random_string('alnum', 64)),
			]);


			$this->db->update(
				'tb_users_invoice',
				[
					'invoice_status'				=> 'process',
				],
				[
					'invoice_code'					=> $this->input->post('code')
				]
			);


			Self::$data['message']      = 'Invoice Confirmed, Waiting for Admin Confirmation';
			Self::$data['heading']      = 'Success';
			Self::$data['type']         = 'success';
		} else {
			Self::$data['heading']     	= 'Error';
			Self::$data['type']     	= 'error';
		}

		return Self::$data;
	}

	function neworder()
	{
		/*============================================
		=				VALIDASI PENJUAL       		=
		============================================*/
		$this->db->where('user_code', $this->input->post('order_penjualcode'));
		$CEKStokis = $this->db->get('tb_users');
		if ($CEKStokis->num_rows() == 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "Stockists Not Found";
		} else {
			$DataStokis = $CEKStokis->row();
			/*============================================
			=				VALIDASI PACKAGE       		=
			============================================*/
			$this->db->where('package_code', $this->input->post('order_package'));
			$CEKPaket = $this->db->get('tb_packages');
			if ($CEKPaket->num_rows() == 0) {
				Self::$data['status'] 	= false;
				Self::$data['message'] 	= "Invalid or Not Found Package";
			} elseif ($DataStokis->id != (int)1) {
				$DataPaket = $CEKPaket->row();
				/*============================================
				=				GET TOTAL PIN       		=
				============================================*/
				$this->db->where('pin_userid', $DataStokis->id);
				$this->db->where('pin_package_id', $DataPaket->package_id);
				$this->db->join('tb_packages', 'pin_package_id = package_id');
				$CEKPIN = $this->db->get('tb_users_pin');
				$TOTAL     = preg_replace('/[^0-9.]+/', '', preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $this->input->post('order_total')));
				/*============================================
				=				VALIDASI TOTAL       		=
				============================================*/
				if ($CEKPIN->num_rows() < (int)$TOTAL) {
					Self::$data['status'] 	= false;
					Self::$data['message'] 	= "Insufficient Seller Stock";
				}
			}
		}

		/*============================================
		=			VALIDASI PASSWORD       		=
		============================================*/
		if (!$this->ion_auth->hash_password_db(userid(), $this->input->post('order_password'))) {
			Self::$data['status']       = false;
			Self::$data['message']      = 'Confirm Password Incorrect!';
		}


		/*============================================
		=				VALIDASI INPUT       		=
		============================================*/
		// $this->form_validation->set_rules('order_provinsi', 'Province', 'required');
		// $this->form_validation->set_rules('order_kota', 'Regency / City', 'required');
		$this->form_validation->set_rules('order_penjualcode', 'Stokis', 'required');
		$this->form_validation->set_rules('order_package', 'Packages', 'required');
		$this->form_validation->set_rules('order_price', 'Packages Price', 'required');
		$this->form_validation->set_rules('order_total', 'Total Orders', 'required');
		$this->form_validation->set_rules('order_password', 'Confirm Password', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= validation_errors(' ', '<br/>');
		}

		/*=========================================================================
		=	VALIDASI JIKA SUDAH PUNYA INVOICE TIDAK SUKSES, DIA TIDAK BISA ORDER   =
		*==========================================================================*/
		$this->db->where('invoice_userfromid', userid());
		$this->db->where('invoice_status !=', 'success');
		$CEKINV = $this->db->get('tb_users_invoice');
		if ($CEKINV->num_rows() != 0) {
			Self::$data['status'] 	= false;
			Self::$data['message'] 	= "Previous Transaction Not Completed, <br>Please wait for the success status on the previous transaction";
		}

		if (Self::$data['status']) {
			$TOTAL 		= preg_replace('/[^0-9.]+/', '', preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $this->input->post('order_total')));
			$DataStokis = $CEKStokis->row();
			$DataPaket 	= $CEKPaket->row();
			$kode_uniq	= rand(300, 999);

			$this->db->insert(
				'tb_users_invoice',
				[
					'invoice_usertoid'		=> $DataStokis->id,
					'invoice_userfromid'	=> userid(),
					'invoice_package_id'	=> $DataPaket->package_id,
					'invoice_total'			=> $TOTAL,
					'invoice_amount'		=> $this->input->post('order_price'),
					'invoice_kode_unik'		=> $kode_uniq,
					'invoice_date_add'		=> sekarang(),
					'invoice_date_expired'	=> sekarang(),
					'invoice_status'		=> 'pending',
					'invoice_code'			=> strtolower(random_string('alnum', 60)),
				]
			);

			Self::$data['heading'] 	= 'Success';
			Self::$data['message'] 	= 'Invoice has been created. Please confirm immediately!';
			Self::$data['type'] 	= 'success';
		} else {
			Self::$data['heading'] 	= 'Error';
			Self::$data['type'] 	= 'error';
		}
		return Self::$data;
	}

	function confirmation()
	{
		$this->db->where('invoice_status', 'pending');
		$this->db->where('invoice_userfromid', userid());
		$this->db->where('invoice_code', $this->input->post('code'));
		$this->db->join('tb_users', 'id = invoice_usertoid');
		$cekinvoice = $this->db->get('tb_users_invoice');
		if ($cekinvoice->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "Invoice Not Found or Confirmed";
		} else {
			$config['upload_path']          = './assets/upload/';
			$config['allowed_types']        = 'jpg|png|jpeg';
			$config['max_size']             = '99999999';
			$config['max_width']            = '99999999';
			$config['max_height']           = '99999999';
			$config['remove_spaces']        = TRUE;
			$config['encrypt_name']         = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('confirm_fileimg')) {
				Self::$data['status']     = false;
				Self::$data['message']     = $this->upload->display_errors();
			}
		}

		if ($this->input->post('confirm_payment') == 'bank') {
			$this->form_validation->set_rules('confirm_account', 'Account in Name', 'required');
			$this->form_validation->set_rules('confirm_bank', 'Bank Name', 'required');
			$this->form_validation->set_rules('confirm_number', 'Account Number', 'required');
			if (!$this->form_validation->run()) {
				Self::$data['status']     = false;
				Self::$data['message']     = validation_errors(' ', '<br/>');
			}
		} elseif ($this->input->post('confirm_payment') == 'wallet') {
			$this->form_validation->set_rules('confirm_txid', 'TX ID or TX Hash', 'required');
			if (!$this->form_validation->run()) {
				Self::$data['status']     = false;
				Self::$data['message']     = validation_errors(' ', '<br/>');
			}
		}


		$this->form_validation->set_rules('code', 'Code', 'required');
		$this->form_validation->set_rules('confirm_payment', 'Payment Method', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$datainvoice 	= $cekinvoice->row();
			$uploaded		= $this->upload->data();

			if ($this->input->post('confirm_payment') == 'bank') {

				$this->db->insert('tb_users_pembayaran', [
					'pembayaran_invoice_id'				=> $datainvoice->invoice_id,
					'pembayaran_touserid'				=> $datainvoice->invoice_usertoid,
					'pembayaran_fromuserid'				=> userid(),
					'pembayaran_tobankname'				=> $datainvoice->user_bank_name,
					'pembayaran_tobankaccount'			=> $datainvoice->user_bank_account,
					'pembayaran_tobanknumber'			=> $datainvoice->user_bank_number,
					'pembayaran_frombankname'			=> $this->input->post('confirm_bank'),
					'pembayaran_frombankaccount'		=> $this->input->post('confirm_account'),
					'pembayaran_frombanknumber'			=> $this->input->post('confirm_number'),
					'pembayaran_payment'				=> $this->input->post('confirm_payment'),
					'pembayaran_struk'					=> $uploaded['file_name'],
					'pembayaran_date_add'				=> sekarang(),
					'pembayaran_nominal'				=> $datainvoice->invoice_total * $datainvoice->invoice_amount,
					'pembayaran_code'					=> strtolower(random_string('alnum', 64)),
				]);


				$this->db->update(
					'tb_users_invoice',
					[
						'invoice_status'				=> 'process',
					],
					[
						'invoice_code'					=> $this->input->post('code')
					]
				);
			} elseif ($this->input->post('confirm_payment') == 'wallet') {
				$this->db->insert('tb_users_pembayaran', [
					'pembayaran_invoice_id'				=> $datainvoice->invoice_id,
					'pembayaran_touserid'				=> $datainvoice->invoice_usertoid,
					'pembayaran_fromuserid'				=> userid(),
					'pembayaran_totxid'					=> $datainvoice->user_wallet,
					'pembayaran_fromtxid'				=> $this->input->post('confirm_txid'),
					'pembayaran_struk'					=> $uploaded['file_name'],
					'pembayaran_payment'				=> $this->input->post('confirm_payment'),
					'pembayaran_date_add'				=> sekarang(),
					'pembayaran_nominal'				=> $datainvoice->invoice_total * $datainvoice->invoice_amount,
					'pembayaran_code'					=> strtolower(random_string('alnum', 64)),
				]);


				$this->db->update(
					'tb_users_invoice',
					[
						'invoice_status'				=> 'process',
					],
					[
						'invoice_code'					=> $this->input->post('code')
					]
				);
			}


			Self::$data['message']      = 'Bill Confirmed, Awaiting Stockist Confirmation';
			Self::$data['heading']      = 'Success';
			Self::$data['type']         = 'success';
		} else {
			Self::$data['heading']     	= 'Error';
			Self::$data['type']     	= 'error';
		}

		return Self::$data;
	}

	function request_newstokist()
	{
		if (!$this->ion_auth->hash_password_db(userid(), $this->input->post('stokis_password'))) {
			Self::$data['status']       = false;
			Self::$data['message']      = 'Konfirmasi Password Tidak Sesuai!';
		}

		// $this->db->where('pktstokist_code', $this->input->post('stokis_paket'));
		$kodenya = (post('totalpin') >= 125) ? 'master' : 'stokis';
		$this->db->where('pktstokist_kode', $kodenya);
		$CEKPAKET = $this->db->get('tb_pktstokist');
		if ($CEKPAKET->num_rows() == 0) {
			Self::$data['status']   = false;
			Self::$data['message']  = 'Paket Stokist Tidak Valid!';
		}

		$this->db->where('bankadmin_code', $this->input->post('stokis_bankadmin'));
		$CEKBANK = $this->db->get('tb_bankadmin');
		if ($CEKBANK->num_rows() == 0) {
			Self::$data['status']   = false;
			Self::$data['message']  = 'Bank Admin Tidak Valid!';
		}

		$this->form_validation->set_rules('totalpin', 'Jumlah PIN Kurang', 'required|numeric|greater_than_equal_to[25]', ['greater_than_equal_to' => '{field}, Minimal 25.']);
		$this->form_validation->set_rules('user_provinsi', 'Provinsi User', 'required');
		$this->form_validation->set_rules('user_kota', 'Kota User', 'required');
		$this->form_validation->set_rules('user_kecamatan', 'Kecamatan User', 'required');
		$this->form_validation->set_rules('user_kelurahan', 'Kelurahan User', 'required');
		$this->form_validation->set_rules('user_alamat', 'Alamat User', 'required');
		$this->form_validation->set_rules('subtotal', 'Subtotal', 'required');
		$this->form_validation->set_rules('stokis_bankadmin', 'Bank Admin', 'required');
		$this->form_validation->set_rules('stokis_password', 'Konfirmasi Password', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$DATABANK 	= $CEKBANK->row();
			$DATAPAKET 	= $CEKPAKET->row();
			$harga		= $this->input->post('subtotal');
			$potongan	= ($DATAPAKET->pktstokist_disc / 100) * $this->input->post('subtotal');
			$this->db->insert(
				'tb_users_invoice_stokis',
				[
					'invoice_stokis_userid'			=> userid(),
					'invoice_stokis_package_id'		=> $DATAPAKET->pktstokist_id,
					// 'invoice_stokis_total'			=> $DATAPAKET->pktstokist_total,
					'invoice_stokis_total'			=> $harga,
					'invoice_stokis_adbankname'		=> $DATABANK->bankadmin_bankname,
					'invoice_stokis_adbankaccount'	=> $DATABANK->bankadmin_bankaccount,
					'invoice_stokis_adbanknumber'	=> $DATABANK->bankadmin_banknumber,
					// 'invoice_stokis_amount'			=> ($DATAPAKET->pktstokist_price - $DATAPAKET->pktstokist_disc) * $DATAPAKET->pktstokist_total,
					'invoice_stokis_amount'			=> $harga - $potongan,
					'invoice_stokis_kode_unik'		=> rand(100, 999),
					'invoice_stokis_date_add'		=> sekarang(),
					'invoice_stokis_code'			=> strtolower(random_string('alnum', 64)),
				]
			);

			$this->db->insert(
				'tb_users_stokis',
				[
					'userstokis_userid'   			=> userid(),
					'userstokis_pktstokisid'		=> $DATAPAKET->pktstokist_id,
					'userstokis_type'				=> $DATAPAKET->pktstokist_kode,
					'userstokis_provinsi'			=> $this->input->post('user_provinsi'),
					'userstokis_kota'				=> $this->input->post('user_kota'),
					'userstokis_kecamatan'			=> $this->input->post('user_kecamatan'),
					'userstokis_kelurahan'			=> $this->input->post('user_kelurahan'),
					'userstokis_alamat'				=> $this->input->post('user_alamat'),
					'userstokis_date'				=> sekarang(),
					'userstokis_code'				=> strtolower(random_string('alnum', 64)),
				]
			);

			Self::$data['heading'] 	= 'Berhasil';
			Self::$data['message'] 	= 'Invoice Telah Dibuat Harap Segera Konfirmasi!';
			Self::$data['type'] 	= 'success';
		} else {
			Self::$data['heading']  = 'Gagal';
			Self::$data['type']     = 'error';
		}

		return Self::$data;
	}

	function confirmnewstokist()
	{
		$this->db->where('invoice_stokis_code', $this->input->post('code'));
		$this->db->where('invoice_stokis_status', 'pending');
		$this->db->where('invoice_stokis_userid', userid());
		$CEKINV = $this->db->get('tb_users_invoice_stokis');
		if ($CEKINV->num_rows() == 0) {
			Self::$data['status']     = false;
			Self::$data['message']     = "Data Invoice Tidak Ditemukan atau Terkonfirmasi";
		} else {
			$config['upload_path']          = './assets/upload/';
			$config['allowed_types']        = 'jpg|png|jpeg';
			$config['max_size']             = '9999';
			$config['max_width']            = '9999';
			$config['max_height']           = '9999';
			$config['remove_spaces']        = TRUE;
			$config['encrypt_name']         = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('confirm_fileimg')) {
				Self::$data['status']     = false;
				Self::$data['message']     = $this->upload->display_errors();
			}
		}

		$this->form_validation->set_rules('code', 'Code', 'required');
		$this->form_validation->set_rules('confirm_account', 'Rekening Atasnama', 'required');
		$this->form_validation->set_rules('confirm_bank', 'Nama Bank', 'required');
		$this->form_validation->set_rules('confirm_number', 'Nomor Rekening', 'required');
		if (!$this->form_validation->run()) {
			Self::$data['status']     = false;
			Self::$data['message']     = validation_errors(' ', '<br/>');
		}

		if (Self::$data['status']) {
			$DATAINV 	= $CEKINV->row();
			$uploaded		= $this->upload->data();

			$this->db->insert('tb_users_pembayaran_stokis', [
				'pembayaran_stokis_invoice_id'			=> $DATAINV->invoice_stokis_id,
				'pembayaran_stokis_userid'				=> userid(),
				'pembayaran_stokis_tobankname'			=> $DATAINV->invoice_stokis_adbankname,
				'pembayaran_stokis_tobankaccount'		=> $DATAINV->invoice_stokis_adbankaccount,
				'pembayaran_stokis_tobanknumber'		=> $DATAINV->invoice_stokis_adbanknumber,
				'pembayaran_stokis_frombankname'		=> $this->input->post('confirm_bank'),
				'pembayaran_stokis_frombankaccount'		=> $this->input->post('confirm_account'),
				'pembayaran_stokis_frombanknumber'		=> $this->input->post('confirm_number'),
				'pembayaran_stokis_struk'				=> $uploaded['file_name'],
				'pembayaran_stokis_date_add'			=> sekarang(),
				'pembayaran_stokis_nominal'				=> $DATAINV->invoice_stokis_total,
				'pembayaran_stokis_code'				=> strtolower(random_string('alnum', 64)),
			]);


			$this->db->update(
				'tb_users_invoice_stokis',
				[
					'invoice_stokis_status'			=> 'process',
				],
				[
					'invoice_stokis_code'			=> $this->input->post('code')
				]
			);


			Self::$data['message']      = 'Tagihan Anda Telah Dikonfirmasi, Menunggu Konfirmasi Admin';
			Self::$data['heading']      = 'Berhasil';
			Self::$data['type']         = 'success';
		} else {
			Self::$data['heading']     	= 'Gagal';
			Self::$data['type']     	= 'error';
		}

		return Self::$data;
	}
}

/* End of file Invoice.php */
/* Location: ./application/modules/Postdata/models/user_post/Invoice.php */