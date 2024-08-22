<?php
$this->template->title->set('Data Klaim Reward');
$this->template->label->set('ADMIN');
$this->template->sublabel->set('Data Klaim Reward');

?>
<div class="page-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Klaim Reward</h3>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <td width="5%">#</td>
                            <td>Member</td>
                            <td>Data Bank</td>
                            <td>No WhatsApp</td>
                            <td>Total Reward</td>
                            <td>Tanggal</td>
                            <td width="25%">Aksi</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $limit       = 15;
                        $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                        $no          = $offset + 1;

                        $this->db->order_by('userreward_date', 'DESC');
                        $this->db->join('tb_reward', 'reward_id = userreward_rewardid');
                        $this->db->join('tb_users', 'id = userreward_userid');
                        $getdata = $this->db->get('tb_userreward', $limit, $offset);


                        $this->db->join('tb_reward', 'reward_id = userreward_rewardid');
                        $this->db->join('tb_users', 'id = userreward_userid');
                        $Gettotal = $this->db->get('tb_userreward')->num_rows();
                        foreach ($getdata->result() as $show) {
                            $fee = (10 / 100) * $show->reward_amount;

                            $total = $show->reward_amount - $fee;
                        ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td>
                                    <?php echo $show->user_fullname ?>
                                    <br>
                                    <small>@<?php echo $show->username ?></small>
                                </td>
                                <td>
                                    <?php echo $show->userreward_account ?>
                                    <br>
                                    <?php echo $show->userreward_bank ?> - <?php echo $show->userreward_number ?>
                                </td>
                                <td>
                                    <?php echo $show->userreward_contact ?>
                                </td>
                                <td>
                                    <p class="h4" style="font-weight: bold;">Rp. <?php echo number_format($total, 0, ',', '.') ?></p>
                                </td>
                                <td>
                                    <?php if ($show->userreward_status == 'pending') {  ?>
                                        <span class="badge rounded-pill bg-warning text-dark">Pending</span>
                                    <?php } elseif ($show->userreward_status == 'reject') { ?>
                                        <span class="badge rounded-pill bg-danger text-white">Reject</span>
                                    <?php } else { ?>
                                        <span class="badge rounded-pill bg-success text-white">Success</span>
                                    <?php } ?>
                                    <br>
                                    <?php echo $show->userreward_date ?>
                                </td>
                                <td>
                                    <?php if ($show->userreward_status == 'pending') {  ?>
                                        <a href="javascript:void(0)" onclick="approve('<?php echo $show->userreward_code ?>')" class="btn btn-primary btn-sm" title="Approve" style="min-width:80px">Approve</a>
                                        <a href="javascript:void(0)" onclick="reject('<?php echo $show->userreward_code ?>')" class="btn btn-danger  btn-sm" title="Reject" style="min-width:80px">Reject</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php echo $this->paginationmodel->paginate('data-reward', $Gettotal, $limit) ?>
            </div>
        </div>
    </div>
</div>
<script>
    function approve(code) {
        Swal.fire({
            allowOutsideClick: false,
            title: 'Apakah Anda Yakin?',
            text: "Klaim Reward Akan Dikonfirmasi & Tidak Dapat Dibatalkan!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YA Konfirmasi',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/reward/approve') ?>',
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
            text: "Klaim Reward Akan Ditolak atau Direject!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YA Tolak',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/reward/reject') ?>',
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