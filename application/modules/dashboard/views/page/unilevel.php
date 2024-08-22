<?php
$this->template->title->set('Bonus Unilevel');
$wallet_address = $this->usermodel->userWallet('withdrawal')->wallet_address;
$info_pending = $this->walletmodel->walletPending($wallet_address);
?>
<div class="alert alert-danger" role="alert">
    Anda Memiliki <?php echo 'Rp. ' . number_format($info_pending, 0, ',', '.'); ?> Bonus Unilevel <strong>PENDING</strong> lakukan Repeat Order Untuk Menerima Bonus Tersebut di Tanggal 01, Jika Sampai Pada Tanggal 01 Bulan Berikutnya Anda Tidak Melakukan Repeat Order Bonus Akan Hangus.
    <a href="<?php echo site_url('wallet-pending'); ?>" class="text-dark">CEK BONUS PENDING</a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bonus Unilevel</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                <thead>
                    <tr>
                        <td width="5%">#</td>
                        <td>Total</td>
                        <td>Deskripsi</td>
                        <td width="25%">Tanggal</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $wallet      = $this->usermodel->userWallet('withdrawal');
                    $limit       = 15;
                    $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                    $no          = $offset + 1;

                    $this->db->order_by('w_balance_date_add', 'DESC');
                    $this->db->where('w_balance_ket', 'unilevel');
                    $this->db->where('w_balance_wallet_id', $wallet->wallet_id);
                    $getdata = $this->db->get('tb_wallet_balance', $limit, $offset);

                    $this->db->where('w_balance_ket', 'unilevel');
                    $this->db->where('w_balance_wallet_id', $wallet->wallet_id);
                    $Gettotal = $this->db->get('tb_wallet_balance')->num_rows();
                    foreach ($getdata->result() as $show) {
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td>Rp. <?php echo number_format($show->w_balance_amount, 0, ',', '.'); ?></td>
                            <td><?php echo $show->w_balance_desc ?></td>
                            <td><?php echo $show->w_balance_date_add ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo $this->paginationmodel->paginate('unilevel', $Gettotal, $limit) ?>
        </div>
    </div>
</div>