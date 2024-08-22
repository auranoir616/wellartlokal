<?php
$this->template->title->set('Bonus Royalty');
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
                    $this->db->where('w_balance_ket', 'royalty');
                    $this->db->where('w_balance_wallet_id', $wallet->wallet_id);
                    $getdata = $this->db->get('tb_wallet_balance', $limit, $offset);

                    $this->db->where('w_balance_ket', 'royalty');
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
            <?php echo $this->paginationmodel->paginate('royalty', $Gettotal, $limit) ?>
        </div>
    </div>
</div>