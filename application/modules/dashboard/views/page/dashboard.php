<?php
$this->template->title->set('Dashboard');

// $this->db->where('package_kode', 'RO');
// echo $hrgperpin = $this->db->get('tb_packages')->row()->package_name;


$wallet = $this->usermodel->userWallet('withdrawal');
$wallet_withdrawal  = $this->usermodel->userWallet('withdrawal')->wallet_address;
$info_Wallet    = $this->walletmodel->walletAddressBalance($wallet_withdrawal);

$this->db->where('referral_id', userid());
$totsponsor = $this->db->get('tb_users')->num_rows();

print_r($this->rank->qualifSP(userid(), 2));

// $this->db->where('upline_id', userid());
// $totsponsor = $this->db->get('tb_users')->result();
// foreach ($totsponsor as $key) {
//     $totsponsors = $this->rank->qualifSP($key->id, 2);
//     print_r($totsponsors);
// }
// print_r($this->rank->ranking());

// print_r($this->rank->getmyrank(userid()));

// $this->db->where()

// foreach ($this->rank->qualifSP() as $item) {
//     $rankkk = $item['myrank'];
// }

$startday   = date('Y-m-d 00:00:00');
$endday     = date('Y-m-d 23:59:59');
$this->db->where('titiklevel_userid', userid());
$this->db->where('titiklevel_date BETWEEN "' . $startday . '" AND "' . $endday . '"');
$totalMember = $this->db->get('tb_titiklevel')->num_rows();


$toootalRO          = 0;
$startbulanini      = date('Y-m-01 00:00:00', now());
$endbulanini        = date('Y-m-t 23:59:59', now());


$this->db->select_sum('historiro_amount');
$this->db->where('historiro_date BETWEEN "' . $startbulanini . '" AND "' . $endbulanini . '"');
$this->db->where('historiro_userid', userid());
$get_RO    = $this->db->get('tb_historiro');

$get_TOTRO     = $get_RO->row()->historiro_amount;
if (!empty($get_TOTRO)) {
    $toootalRO     = $get_TOTRO;
}

$this->db->select_sum('omset_amount');
$this->db->where('omset_date BETWEEN "' . $startbulanini . '" AND "' . $endbulanini . '"');
$getbulanini    = $this->db->get('tb_omset');
$get_bulanini    = $getbulanini->row()->omset_amount;
if (!empty($get_bulanini)) {
    $saldo_bulanini     = $get_bulanini;
}
// $startbulanini       = date('Y-m-01 00:00:00', now());
// $endbulanini         = date('Y-m-t 23:59:59', now());

// $this->db->select_sum('omset_amount');
// $this->db->where('omset_date BETWEEN "' . $startbulanini . '" AND "' . $endbulanini . '"');
// $getbulanini    = $this->db->get('tb_omset');
// $get_bulanini    = $getbulanini->row()->omset_amount;
// if (!empty($get_bulanini)) {
//     $saldo_bulanini     = $get_bulanini;
// }



$ROPribadi = 0;
$this->db->select_sum('omset_amount');
$this->db->where('omset_userid', userid());
$this->db->where('omset_date BETWEEN "' . $startbulanini . '" AND "' . $endbulanini . '"');
$RO_Pribadi    = $this->db->get('tb_omset');
$get_ROPRIBADI  = $RO_Pribadi->row()->omset_amount;
if (!empty($get_ROPRIBADI)) {
    $ROPribadi     = $get_ROPRIBADI;
}
?>



<style>
    .page-header {
        margin-bottom: 0;
    }
</style>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 mt-sm-4">
        <div class="alert alert-primary" role="alert">
            <center>
                KODE REFERRAL DAN UPLINE ANDA
                <br>
                <span style="font-weight: bold;" class="h2" onclick="copyyyKODE(`<?php echo $userdata->user_referral_code; ?>`)"><?php echo $userdata->user_referral_code; ?></span>
                <hr>
                Gunakan Kode Di Atas Untuk Kolom Referral atau Upline Saat Mendaftarkan Member Baru
            </center>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 mt-sm-4">
        <div class="alert alert-info text-primary text-center" role="alert">
            MEMBER TERDAFTAR HARI INI
            <hr>
            <span style="font-weight: bold;" class="h2"><?php echo $totalMember ?></span>

        </div>
    </div>
</div>

<script>
    function copyyyKODE(text) {
        var copy = document.createElement("textarea");
        document.body.appendChild(copy);
        copy.value = text;
        copy.select();
        document.execCommand("copy");
        document.body.removeChild(copy);


        Swal.fire(
            "Berhasil",
            "User Kode <strong>" + text + "</strong> Dicopy",
            "success"
        )
    }
</script>
<div class="row mt-4">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card  bg-success img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-4 number-font h6">
                            <?php
                            $this->db->where('rank_id', userdata()->user_rankid);
                            $getrank = $this->db->get('tb_rank');
                            $myrank = isset($getrank->row()->rank_name) ? $getrank->row()->rank_name : 'Member';
                            ?>
                            <?php echo $myrank;  ?>
                        </h2>
                        <p class="text-white mb-0">Rank Status</p>
                    </div>
                    <div class="ms-auto"><i class="ti-crown text-white fs-50 me-2 mt-2"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card  bg-secondary img-card box-secondary-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-4 number-font h6"><?php echo 'Rp. ' . number_format($info_Wallet, 0, ',', '.'); ?></h2>
                        <p class="text-white mb-0">Wallet Balance</p>
                    </div>
                    <div class="ms-auto"> <i class="ti-wallet text-white fs-50 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card  bg-danger img-card box-danger-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-4 number-font h6"><?php echo number_format($get_bulanini, 0, ',', '.'); ?> BV</h2>
                        <p class="text-white mb-0">Omset Bulan Ini</p>
                    </div>
                    <div class="ms-auto"> <i class="ti-wallet text-white fs-50 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card bg-primary img-card box-primary-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-4 number-font h6"><?php echo $totsponsor ?> Users</h2>
                        <p class="text-white mb-0">Total Referrals </p>
                    </div>
                    <div class="ms-auto"> <i class="fe fe-users text-white fs-50 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="alert alert-success text-center" role="alert">
    <h4 style="color:#000">Total Repeat Order Pribadi pada Bulan <?php echo date('m-Y') ?> : <strong style="color:#000"><?php echo $ROPribadi ?>BV, (<?php echo $toootalRO ?>X Repeat Order)</strong></h4>
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
                        <td>Tanggal</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no          = 1;

                    $wallet      = $this->usermodel->userWallet('withdrawal');

                    $this->db->limit(10);
                    $this->db->order_by('w_balance_date_add', 'DESC');
                    $this->db->where('w_balance_wallet_id', $wallet->wallet_id);
                    $getdata = $this->db->get('tb_wallet_balance');
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
        </div>
    </div>
</div>