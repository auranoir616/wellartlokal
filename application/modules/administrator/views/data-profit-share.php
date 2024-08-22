<?php
$this->template->title->set('Data Profit Share');

$startblnlalu   = date('Y-m-01 00:00:00', strtotime('-1 month', now()));
$endlnlalu      = date('Y-m-t 23:59:59', strtotime('-1 month', now()));

$saldo_bulanlalu = 0;
$this->db->select_sum('omset_amount');
$this->db->where('omset_date BETWEEN "' . $startblnlalu . '" AND "' . $endlnlalu . '"');
$getbulanlalu     = $this->db->get('tb_omset');
$get_bulanlalu    = $getbulanlalu->row()->omset_amount;
if (!empty($get_bulanlalu)) {
    $saldo_bulanlalu     = $get_bulanlalu;
}

$this->db->where('usershare_status', 'royalty');
$userroyal = $this->db->get('tb_usershare');

$this->db->where('usershare_status', 'ranking');
$userranking = $this->db->get('tb_usershare');

$nilaiBV = 1000;
?>

<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h6>Omset Bulan Lalu</h6>
                <hr style="border-top: 1px solid #000;">
                <h3><?php echo number_format($saldo_bulanlalu, 0, ',', '.'); ?> BV</h3>
                <h5>Rp. <?php echo number_format($nilaiBV * $saldo_bulanlalu, 0, ',', '.'); ?></h5>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <?php
        $BVRank = (30 / 100) * $saldo_bulanlalu;
        ?>
        <div class="card">
            <div class="card-body">
                <h6>Peringkat (30%)</h6>
                <hr style="border-top: 1px solid #000;">
                <h3><?php echo number_format($BVRank, 1, ',', '.'); ?> BV</h3>
                <h5>Rp. <?php echo number_format($nilaiBV * $BVRank, 0, ',', '.'); ?></h5>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <?php
        $BVRoyal = (18 / 100) * $saldo_bulanlalu;
        ?>
        <div class="card">
            <div class="card-body">
                <h6>Royalty (18%)</h6>
                <hr style="border-top: 1px solid #000;">
                <h3><?php echo number_format($BVRoyal, 1, ',', '.'); ?> BV</h3>
                <h5>Rp. <?php echo number_format($nilaiBV * $BVRoyal, 0, ',', '.'); ?></h5>
            </div>
        </div>
    </div>
</div>
<div class="alert alert-danger" role="alert">
    Profit Share Otomatis Dihitung di Setiap Akhir Bulan, Pada Malam Harinya. Anda Dapat Menjalakan Manual Untuk Mengecek Yang Mendapatkan ShareProfit, Akan Tetapi Data Real Yang Dipakai Untuk ShareProfit Akan Di Hitung di Akhir Bulan (Agar Data Akurat).
</div>
<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6>Bonus Peringkat</h6>
                <hr style="border-top: 1px solid #000;">
                <h5><?php echo number_format($userranking->num_rows(), 0, ',', '.'); ?> USER</h5>
                <a href="javascript:void(0)" onclick="aksiRANK('ranking')" class="btn btn-sm btn-block btn-primary text-white" title="Remove">
                    GENERATE RANKING
                </a>
            </div>
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6>Bonus Royalti</h6>
                <hr style="border-top: 1px solid #000;">
                <h5><?php echo number_format($userroyal->num_rows(), 0, ',', '.'); ?> USER</h5>
                <a href="javascript:void(0)" onclick="aksiROYALTI('royalti')" class="btn btn-sm btn-block btn-primary text-white" title="Remove">
                    GENERATE ROYALTY
                </a>
            </div>
        </div>
    </div>
</div>
<div class="alert alert-info h5 text-center" role="alert">
    Untuk Memperbarui Data Dibawah Ini Harap Generate Ulang, Dan Jika <strong class="text-danger">Generate Lambat atau Lemot</strong> Itu Dikarenakan Jumlah Data User Banyak.
</div>
<div class="card">
    <div class="card-header">
        <h5>Data ShareProfit</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Username</th>
                        <th>Profit</th>
                        <th width="20%">Status</th>
                        <th width="20%">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit       = 15;
                    $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                    $no          = $offset + 1;

                    $this->db->order_by('usershare_date', 'DESC');
                    $this->db->join('tb_users', 'usershare_userid = id');
                    $this->db->join('tb_rank', 'rank_id = usershare_rankid');
                    $getdata = $this->db->get('tb_usershare', $limit, $offset);

                    $this->db->join('tb_users', 'usershare_userid = id');
                    $this->db->join('tb_rank', 'rank_id = usershare_rankid');
                    $Gettotal = $this->db->get('tb_usershare')->num_rows();
                    foreach ($getdata->result() as $show) {
                        #dari hasil bagian
                        // $omsetttt         = ($show->usershare_status == 'ranking') ? ($nilaiBV * $BVRank) : ($nilaiBV * $BVRoyal);

                        #dari omset global
                        $omsetttt         = ($show->usershare_status == 'ranking') ? ($nilaiBV * $saldo_bulanlalu) : ($nilaiBV * $saldo_bulanlalu);
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $show->username ?></td>
                            <td>Rp. <?php echo number_format($this->rank->bagian($omsetttt, $show->usershare_status, $show->usershare_rankid), 0, ',', '.'); ?></td>
                            <td>
                                <?php echo $show->rank_name ?> (<?php echo ($show->usershare_status == 'ranking') ? $show->rank_ranking : $show->rank_royalty ?>%)
                                <br>
                                <?php if ($show->usershare_status == 'ranking') { ?>
                                    <span class="badge bg-primary"><?php echo strtoupper($show->usershare_status) ?></span>
                                <?php } else { ?>
                                    <span class="badge bg-success"><?php echo strtoupper($show->usershare_status) ?></span>
                                <?php } ?>
                            </td>
                            <td><?php echo $show->usershare_date ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo $this->paginationmodel->paginate('data-profit-share', $Gettotal, $limit) ?>
        </div>
    </div>
</div>

<script>
    function aksiRANK(code) {
        Swal.fire({
            allowOutsideClick: false,
            title: 'Apakah Anda Yakin?',
            text: "Data Ranking Akan Digenerate, Jika Sudah Ada Data Sebelumnya Akan Diperbarui!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YA Generate',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/shareprofit/count_ranking') ?>',
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

    function aksiROYALTI(code) {
        Swal.fire({
            allowOutsideClick: false,
            title: 'Apakah Anda Yakin?',
            text: "Data Royalty Akan Digenerate, Jika Sudah Ada Data Sebelumnya Akan Diperbarui!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YA Generate',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/shareprofit/count_royalty') ?>',
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