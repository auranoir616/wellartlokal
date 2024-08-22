<?php
$this->template->title->set('Bonus Gold Elite');

$saldo_bulanini     = 0;

$startbulanini       = date('Y-m-01 00:00:00', now());
$endbulanini         = date('Y-m-t 23:59:59', now());

$this->db->select_sum('omset_amount');
$this->db->where('omset_date BETWEEN "' . $startbulanini . '" AND "' . $endbulanini . '"');
$getbulanini    = $this->db->get('tb_omset');
$get_bulanini    = $getbulanini->row()->omset_amount;
if (!empty($get_bulanini)) {
    $saldo_bulanini     = $get_bulanini;
}


$bonusgoldelit = ($saldo_bulanini * 10 / 100) / $this->walletmodel->cekgoldelite()
?>
<div class="row justify-content-between">

    <div class="col-sm-4">
        <div class="card" style="background-color: #2980b9;color:#fff">
            <div class="card-body">
                <h6>Omset Bulan Ini</h6>
                <hr style="border-top: 1px solid #000;">
                <h3><?php echo number_format($saldo_bulanini, 0, ',', '.'); ?> BV</h3>
                <h5>Rp. <?php echo number_format(1000 * $saldo_bulanini, 0, ',', '.'); ?></h5>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card" style="background-color: #27ae60;color:#fff">
            <div class="card-body">
                <h6>Potensi Bonus</h6>
                <hr style="border-top: 1px solid #000;">
                <h3><?php echo $bonusgoldelit ; ?> BV</h3>
                <h5>Rp. <?php echo number_format(1000 * $bonusgoldelit, 0, ',', '.'); ?></h5>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h5>Histori</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="20%">Member</th>
                        <th>Deskripsi</th>
                        <th width="20%">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit       = 15;
                    $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                    $no          = $offset + 1;

                    $this->db->order_by('omset_date', 'DESC');
                    $getdata = $this->db->get('tb_omset', $limit, $offset);

                    $Gettotal = $this->db->get('tb_omset')->num_rows();
                    foreach ($getdata->result() as $show) {
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo number_format($show->omset_amount, 0, ',', '.'); ?> BV</td>
                            <td><?php echo $show->omset_desc ?></td>
                            <td><?php echo $show->omset_date ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo $this->paginationmodel->paginate('data-omset', $Gettotal, $limit) ?>
        </div>
    </div>
</div>