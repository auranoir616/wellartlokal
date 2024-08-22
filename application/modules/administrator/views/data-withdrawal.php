<?php
$this->template->title->set('Data Withdrawal');
$this->template->label->set('ADMIN');
$this->template->sublabel->set('Data Withdrawal');

?>
<div class="page-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Withdrawal</h3>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <td width="5%">#</td>
                            <td>Member</td>
                            <td>Data Bank</td>
                            <td>Total</td>
                            <td>SubTotal</td>
                            <td>Status</td>
                            <td>Tanggal</td>
                            <td width="25%">Aksi</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $limit       = 15;
                        $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                        $no          = $offset + 1;

                        $this->db->order_by('withdrawl_date', 'DESC');
                        $getdata = $this->db->get('tb_withdrawl', $limit, $offset);

                        $Gettotal = $this->db->get('tb_withdrawl')->num_rows();
                        foreach ($getdata->result() as $show) {
                            $userdata = userdata(['id' => $show->withdrawl_userid]);
                        ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td>
                                    <?php echo $userdata->user_fullname ?>
                                    <br>
                                    <small>@<?php echo $userdata->username ?></small>
                                </td>
                                <td>
                                    <?php echo $show->withdrawl_account ?>
                                    <br>
                                    <?php echo $show->withdrawl_bank_name ?> - <?php echo $show->withdrawl_bank_number ?>
                                </td>
                                <td><?php echo 'Rp.  ' . number_format($show->withdrawl_amount, 0, '.', '.') ?></td>
                                <td><b style="font-weight: 900;font-size: large;"><?php echo 'Rp.  ' . number_format($show->withdrawl_will_get, 0, '.', '.') ?></b></td>
                                <td>
                                    <?php if ($show->withdrawl_status == "Pending") { ?>
                                        <span class="badge bg-danger">Pending</span>
                                    <?php } elseif ($show->withdrawl_status == "Rejected") { ?>
                                        <span class="badge bg-warning text-white">Rejected</span>
                                    <?php } else { ?>
                                        <span class="badge bg-success">Success</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo $show->withdrawl_date ?></td>
                                <td>
                                    <?php if ($show->withdrawl_status == "Pending") { ?>
                                        <a href="javascript:void(0)" onclick="approvewd('<?php echo $show->withdrawl_trxid ?>')" class="btn btn-primary btn-sm" title="Konfirmasi" style="min-width:80px">Approve</a>
                                        <a href="javascript:void(0)" onclick="cancelwd('<?php echo $show->withdrawl_trxid ?>')" class="btn btn-danger  btn-sm" title="Tolak" style="min-width:80px">Reject</a>
                                    <?php } else { ?>
                                        -
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php echo $this->paginationmodel->paginate('data-withdrawal', $Gettotal, $limit) ?>
            </div>
        </div>
    </div>
</div>
<script>
    function approvewd(code) {
        Swal.fire({
            allowOutsideClick: false,
            title: 'Apakah Anda Yakin?',
            text: "Transaksi Withdrawal akan dikonfirmasi!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YA Konfirmasi',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/withdrawl/approvewd') ?>',
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


    function cancelwd(code) {
        Swal.fire({
            allowOutsideClick: false,
            title: 'Apakah Anda Yakin?',
            text: "Transaksi Withdrawal akan ditolak!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YA Tolak',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/withdrawl/rejectwd') ?>',
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