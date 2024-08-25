<?php
$this->template->title->set('Wallet Pending');

$TBonus = 0;

$wallet_address = $this->usermodel->userWallet('withdrawal')->wallet_address;
$info_pending = $this->walletmodel->walletPending($wallet_address);
$start_date = date('Y-m-01');
$end_date = date('Y-m-t');

$this->db->select_sum('w_pending_amount');
$this->db->where('wallet_address', $wallet_address);
$this->db->join('tb_users_wallet', 'wallet_id = w_pending_wallet_id', 'left');
$this->db->where('w_pending_type', 'credit');
$this->db->where('w_pending_date_add BETWEEN "' . $start_date . '" AND "' . $end_date . '"');
$get             = $this->db->get('tb_wallet_pending');
$get_totalBONUS     = $get->row()->w_pending_amount;

if (!empty($get_totalBONUS)) {
    $TBonus     = $get_totalBONUS;
}
?>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="card bg-danger img-card box-danger-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font h6"><?php echo 'Rp. ' . number_format($info_pending, 0, ',', '.'); ?></h2>
                        <p class="text-white mb-0">Bonus Pending</p>
                        <small>Bonus Yang akan didapat Pada awal Bulan</small>
                    </div>
                    <div class="ms-auto"> <i class="ti-wallet text-white fs-50 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="card bg-success img-card box-info-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font h6"><?php echo 'Rp. ' . number_format($TBonus, 0, ',', '.'); ?></h2>
                        <p class="text-white mb-0">Total Bonus </p>
                        <small>Bonus Yang Sudah Diterima Bulan lalu</small>

                    </div>
                    <div class="ms-auto"> <i class="ti-wallet text-white fs-50 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Histori Wallet Pending</h3>
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

                    $this->db->order_by('w_pending_date_add', 'DESC');
                    $this->db->where('w_pending_wallet_id', $wallet->wallet_id);
                    $getdata = $this->db->get('tb_wallet_pending', $limit, $offset);


                    $this->db->where('w_pending_wallet_id', $wallet->wallet_id);
                    $Gettotal = $this->db->get('tb_wallet_pending')->num_rows();
                    foreach ($getdata->result() as $show) {
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td>Rp. <?php echo number_format($show->w_pending_amount, 0, ',', '.'); ?></td>
                            <td><?php echo $show->w_pending_desc ?></td>
                            <td>
                                <?php if ($show->w_pending_type == 'credit') { ?>
                                    <span class="badge bg-success" style="min-width: 50px;">Credit</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger" style="min-width: 50px;">Debit</span>
                                <?php } ?>
                            </td>
                            <td><?php echo $show->w_pending_date_add ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo $this->paginationmodel->paginate('wallet_pending', $Gettotal, $limit) ?>
        </div>
    </div>
</div>