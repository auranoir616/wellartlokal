<?php $this->template->title->set('Data Sale'); ?>
<div class="alert alert-primary h6" role="alert">
    Halaman Konfirmasi Orderan Yang Masuk Ke ADMIN
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
                        <th>Package</th>
                        <th>Payment</th>
                        <th>SubTotal</th>
                        <th>Date</th>
                        <th width="25%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit       = 10;
                    $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                    $no          = $offset + 1;


                    $this->db->order_by('pembayaran_date_add', 'DESC');
                    $this->db->join('tb_users_invoice', 'pembayaran_invoice_id = invoice_id');
                    $this->db->join('tb_packages', 'package_id = invoice_package_id');
                    $this->db->where('invoice_usertoid', userid());
                    $this->db->join('tb_users', 'invoice_userfromid = id');
                    $getdata = $this->db->get('tb_users_pembayaran', $limit, $offset);

                    $this->db->join('tb_users_invoice', 'pembayaran_invoice_id = invoice_id');
                    $this->db->join('tb_packages', 'package_id = invoice_package_id');
                    $this->db->where('invoice_usertoid', userid());
                    $this->db->join('tb_users', 'invoice_userfromid = id');
                    $Gettotal = $this->db->get('tb_users_pembayaran')->num_rows();
                    foreach ($getdata->result() as $show) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php echo $show->user_fullname; ?>
                                <br>
                                <small>@<?php echo $show->username; ?></small>
                            </td>
                            <td><?php echo $show->invoice_total; ?> (<?php echo $show->package_name ?>)</td>
                            <td>
                                <?php if ($show->pembayaran_payment == 'bank') { ?>
                                    BANK
                                <?php } else { ?>
                                    WALLET
                                <?php } ?>
                                <br>
                                <strong>
                                    <u>
                                        <a href="<?php echo site_url('assets/upload/' . $show->pembayaran_struk) ?>" target="_blank">Lihat Bukti Pembayaran</a>
                                    </u>
                                </strong>
                            </td>
                            <td>
                                <?php if ($show->pembayaran_status == 'pending') { ?>
                                    <span class="badge badge-warning bg-warning" style="color:#fff;min-width:60px">Pending</span>
                                <?php } else {  ?>
                                    <span class="badge badge-success bg-success" style="color:#fff;min-width:60px">Success</span>
                                <?php } ?>
                                <br>
                                Rp. <?php echo number_format($show->pembayaran_nominal, 0, ',', '.'); ?>
                            </td>
                            <td>
                                <?php echo date('H:i:s', strtotime($show->invoice_date_add)) ?>
                                <br>
                                <?php echo date('d-M-Y', strtotime($show->invoice_date_add)) ?>
                            </td>
                            <td>
                                <?php if ($show->pembayaran_status == 'pending') { ?>
                                    <a href="javascript:void(0)" onclick="actionapprove('<?php echo $show->pembayaran_code ?>')" class="btn btn-sm btn-primary text-white mb-1" title="Approve" style="min-width: 80px;">
                                        Approve
                                    </a>
                                    <a href="javascript:void(0)" onclick="actionreject('<?php echo $show->pembayaran_code ?>')" class="btn btn-sm btn-danger text-white mb-1" title="Reject" style="min-width: 80px;">
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
            <?php echo $this->paginationmodel->paginate('data-sale', $Gettotal, $limit) ?>
        </div>
    </div>
</div>

<script>
    function actionapprove(code) {
        Swal.fire({
            allowOutsideClick: false,
            title: 'Are you sure?',
            text: "Transactions will be confirmed and cannot be cancelled!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YES Approve',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/invoice/approvesale') ?>',
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
            title: 'Are you sure?',
            text: "This Transaction Will Be Rejected and Will Not Proceed?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YES Reject',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/invoice/rejectsale') ?>',
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