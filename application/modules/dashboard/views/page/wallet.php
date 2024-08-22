<?php
$this->template->title->set('Wallet');

$TBonus = 0;

$wallet = $this->usermodel->userWallet('withdrawal');
$wallet_withdrawal  = $this->usermodel->userWallet('withdrawal')->wallet_address;
$info_Wallet    = $this->walletmodel->walletAddressBalance($wallet_withdrawal);

$info_pending = $this->walletmodel->walletPending($wallet_withdrawal);

$this->db->select_sum('w_balance_amount');
$this->db->where('wallet_address', $wallet_withdrawal);
$this->db->join('tb_users_wallet', 'wallet_id = w_balance_wallet_id', 'left');
$this->db->where('w_balance_type', 'credit');
$get             = $this->db->get('tb_wallet_balance');
$get_totalBONUS     = $get->row()->w_balance_amount;

if (!empty($get_totalBONUS)) {
    $TBonus     = $get_totalBONUS;
}
?>
<div class="row">
    <div class="col-sm-12 col-md-4 col-lg-4">
        <div class="card bg-success img-card box-info-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font h6"><?php echo 'Rp. ' . number_format($TBonus, 0, ',', '.'); ?></h2>
                        <p class="text-white mb-0">Total Bonus</p>
                    </div>
                    <div class="ms-auto"> <i class="ti-wallet text-white fs-50 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4 col-lg-4">
        <div class="card bg-secondary img-card box-info-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font h6"><?php echo 'Rp. ' . number_format($info_Wallet, 0, ',', '.'); ?></h2>
                        <p class="text-white mb-0">Total Saldo</p>
                    </div>
                    <div class="ms-auto"> <i class="ti-wallet text-white fs-50 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4 col-lg-4">
        <div class="card bg-danger img-card box-danger-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font h6"><?php echo 'Rp. ' . number_format($info_pending, 0, ',', '.'); ?></h2>
                        <p class="text-white mb-0">Bonus Pending</p>
                    </div>
                    <div class="ms-auto"> <i class="ti-wallet text-white fs-50 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="alert alert-danger" role="alert">
    Anda Memiliki <?php echo 'Rp. ' . number_format($info_pending, 0, ',', '.'); ?> Bonus Unilevel <strong>PENDING</strong> lakukan Repeat Order Untuk Menerima Bonus Tersebut di Tanggal 01, Jika Sampai Pada Tanggal 01 Bulan Berikutnya Anda Tidak Melakukan Repeat Order Bonus Akan Hangus.
    <a href="<?php echo site_url('wallet-pending'); ?>" class="text-dark">CEK BONUS PENDING</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Histori Wallet</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                <thead>
                    <tr>
                        <td width="5%">#</td>
                        <td>Total</td>
                        <td>Deskripsi</td>
                        <td width="15%">Tipe</td>
                        <td>Tanggal</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit       = 20;
                    $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                    $no          = $offset + 1;

                    $wallet      = $this->usermodel->userWallet('withdrawal');

                    $this->db->order_by('w_balance_date_add', 'DESC');
                    $this->db->where('w_balance_wallet_id', $wallet->wallet_id);
                    $getdata = $this->db->get('tb_wallet_balance', $limit, $offset);


                    $this->db->where('w_balance_wallet_id', $wallet->wallet_id);
                    $Gettotal = $this->db->get('tb_wallet_balance')->num_rows();
                    foreach ($getdata->result() as $show) {
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td>Rp. <?php echo number_format($show->w_balance_amount, 0, ',', '.'); ?></td>
                            <td><?php echo $show->w_balance_desc ?></td>
                            <td>
                                <?php if ($show->w_balance_type == 'credit') { ?>
                                    <span class="badge bg-success" style="min-width: 50px;">Credit</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger" style="min-width: 50px;">Debit</span>
                                <?php } ?>
                            </td>
                            <td><?php echo $show->w_balance_date_add ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo $this->paginationmodel->paginate('wallet', $Gettotal, $limit) ?>
        </div>
    </div>
</div>