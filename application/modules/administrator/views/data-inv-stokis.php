<?php $this->template->title->set('Data INV Stokist'); ?>
<div class="alert alert-primary h4 text-center" role="alert">
    Halaman Konfirmasi, Member Yang Daftar Stokis
</div>
<div class="card">
    <div class="card-header">
        <h3>Data Sale</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Userdata</th>
                        <th>Paket</th>
                        <th>Pembayaran</th>
                        <th>SubTotal</th>
                        <th>Tanggal</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit       = 10;
                    $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                    $no          = $offset + 1;


                    $this->db->order_by('pembayaran_stokis_date_add', 'DESC');
                    $this->db->join('tb_users_invoice_stokis', 'pembayaran_stokis_invoice_id = invoice_stokis_id');
                    $this->db->join('tb_pktstokist', 'pktstokist_id = invoice_stokis_package_id');
                    $this->db->join('tb_users', 'pembayaran_stokis_userid = id');
                    $getdata = $this->db->get('tb_users_pembayaran_stokis', $limit, $offset);

                    $this->db->join('tb_users_invoice_stokis', 'pembayaran_stokis_invoice_id = invoice_stokis_id');
                    $this->db->join('tb_pktstokist', 'pktstokist_id = invoice_stokis_package_id');
                    $this->db->join('tb_users', 'pembayaran_stokis_userid = id');
                    $Gettotal = $this->db->get('tb_users_pembayaran_stokis')->num_rows();
                    foreach ($getdata->result() as $show) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php echo $show->user_fullname; ?>
                                <br>
                                <small>@<?php echo $show->username; ?></small>
                            </td>
                            <td><?php echo $show->pktstokist_name ?></td>
                            <td>
                                <?php echo $show->pembayaran_stokis_frombankaccount ?>
                                <br>
                                <?php echo $show->pembayaran_stokis_frombankname ?> - <?php echo $show->pembayaran_stokis_frombanknumber ?>
                            </td>
                            <td>
                                <?php if ($show->pembayaran_stokis_status == 'pending') { ?>
                                    <span class="badge bg-warning" style="color:#fff;min-width:60px">Pending</span>
                                <?php } else {  ?>
                                    <span class="badge bg-success" style="color:#fff;min-width:60px">Success</span>
                                <?php } ?>
                                <br>
                                Rp. <?php echo number_format($show->pembayaran_stokis_nominal, 0, ',', '.'); ?>
                            </td>
                            <td>
                                <?php echo date('H:i:s', strtotime($show->pembayaran_stokis_date_add)) ?>
                                <br>
                                <?php echo date('d-M-Y', strtotime($show->pembayaran_stokis_date_add)) ?>
                            </td>
                            <td>
                                <?php if ($show->pembayaran_stokis_status == 'pending') { ?>
                                    <a href="javascript:void(0)" onclick="actionapprove('<?php echo $show->pembayaran_stokis_code ?>')" class="btn btn-sm btn-primary text-white mb-1" title="Approve" style="min-width: 80px;">
                                        Approve
                                    </a>
                                    <a href="javascript:void(0)" onclick="actionreject('<?php echo $show->pembayaran_stokis_code ?>')" class="btn btn-sm btn-danger text-white mb-1" title="Reject" style="min-width: 80px;">
                                        Reject
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="p-2">
            <?php echo $this->paginationmodel->paginate('data-inv-order', $Gettotal, $limit) ?>
        </div>
    </div>
</div>

<script>
    function actionapprove(code) {
        Swal.fire({
            allowOutsideClick: false,
            title: 'Apakah Anda Yakin?',
            text: "Transaksi Akan Dikonfirmasi dan Tidak Dapat Dibatalkan!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YA Konfirmasi',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/invoice/approvenewstokist') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            code: code,
                            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                        }
                    })

                    .done(function(data) {

                        updateCSRF(data.csrf_data);
                        Swal.fire(
                            data.heading,
                            data.message,
                            data.type
                        ).then(function() {
                            if (data.status) {
                                location.reload();
                            }
                        });

                    })
            }
        });
    }

    function actionreject(code) {
        Swal.fire({
            allowOutsideClick: false,
            title: 'Apakah Anda Yakin?',
            text: "Transaksi ini akan ditolak dan tidak dapat dilanjutkan?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YA Tolak',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/invoice/rejectnewstokist') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            code: code,
                            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                        }
                    })

                    .done(function(data) {

                        updateCSRF(data.csrf_data);
                        Swal.fire(
                            data.heading,
                            data.message,
                            data.type
                        ).then(function() {
                            if (data.status) {
                                location.reload();
                            }
                        });

                    })
            }
        });
    }
</script>