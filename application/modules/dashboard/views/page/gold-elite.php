<?php
$this->template->title->set('Bonus Gold Elite');


?>


<div class="alert alert-primary" role="alert" style="color:#000">
    Bonus Royalty masuk dalam <strong>Kategori Profit Share</strong>, yang akan dibagikan setiap Tanggal 01, Penerima Bonus ini harus memenuhi Syarat Omset Pada Bulan Sebelumnya. <u><a href="javascript:" data-bs-toggle="modal" data-bs-target="#syarat" title="Lihat Syarat Bonus" style="color:#000">Lihat Syarat Bonus</a></u>
</div>
<div class="modal fade" id="syarat" tabindex="-1" aria-labelledby="syaratLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="syaratLabel">Syarat Bonus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <center class="mb-3 text-danger">Anda Wajib Memiliki 3 Kaki Sesuai Omset Berikut Ini</center>
                <img src="<?= base_url('assets/upload/goldelite.jpg') ?>" alt="">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card bg-warning img-card box-warning-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font h6">Rp. <?php echo isset($this->walletmodel->cekgoldelite()['bonus']) ? number_format(1000 * $this->walletmodel->cekgoldelite()['bonus'], 0, ',', '.') : 0; ?>
                        </h2>
                        <p class="text-white mb-0">Potensi Bonus Gold Elite</p>
                    </div>
                    <div class="ms-auto"> <i class="ti-wallet text-white fs-50 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bonus Gold Elite</h3>
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
                    $this->db->where('w_balance_ket', 'goldelite');
                    $this->db->where('w_balance_wallet_id', $wallet->wallet_id);
                    $getdata = $this->db->get('tb_wallet_balance', $limit, $offset);

                    $this->db->where('w_balance_ket', 'goldelite');
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
            <?php echo $this->paginationmodel->paginate('gold-elite', $Gettotal, $limit) ?>
        </div>
    </div>
</div>