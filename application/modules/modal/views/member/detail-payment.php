<?php
$code = $this->input->get('code');

$this->db->where('pembayaran_code', $code);
$this->db->where('pembayaran_touserid', userid());
$CekPayment = $this->db->get('tb_users_pembayaran');

if ($CekPayment->num_rows() == 0) {
?>
    <center>Invoice Data Not Found</center>
<?php
} else {
    $datapay = $CekPayment->row();

?>
    <center>
        <h5>Payment method</h5>
    </center>
    <?php if ($datapay->pembayaran_payment == 'bank') { ?>
        <div class="form-group">
            <label for="">Account in Name</label>
            <input type="text" class="form-control" placeholder="Account in Name" value="<?php echo $datapay->pembayaran_tobankname ?>" disabled>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label for="">Bank Name</label>
                    <input type="text" class="form-control" placeholder="Bank Name" value="<?php echo $datapay->pembayaran_tobankaccount ?>" disabled>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label for="">Account Number</label>
                    <input type="text" class="form-control" placeholder="Account Number" value="<?php echo $datapay->pembayaran_tobanknumber ?>" disabled>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="form-group">
            <label for="">Wallet Address</label>
            <textarea class="form-control" cols="4" rows="4" style="resize: none;" disabled><?php echo $datapay->pembayaran_totxid ?></textarea>
            <small class="text-danger">USDT (TRC20)</small>
        </div>
    <?php } ?>
    <hr>
    <center>
        <h5>Payment confirmation</h5>
    </center>
    <?php if ($datapay->pembayaran_payment == 'bank') { ?>
        <div class="form-group">
            <label for="">Account in Name</label>
            <input type="text" class="form-control" placeholder="Account in Name" value="<?php echo $datapay->pembayaran_frombankname ?>" disabled>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label for="">Bank Name</label>
                    <input type="text" class="form-control" placeholder="Bank Name" value="<?php echo $datapay->pembayaran_frombankaccount ?>" disabled>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label for="">Account Number</label>
                    <input type="text" class="form-control" placeholder="Account Number" value="<?php echo $datapay->pembayaran_frombanknumber ?>" disabled>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="form-group">
            <label for="">TX ID or TX Hash</label>
            <textarea disabled placeholder="TX ID or TX Hash" class="form-control" cols="4" rows="4" style="resize: none;"><?php echo $datapay->pembayaran_fromtxid; ?></textarea>
        </div>
    <?php } ?>
    <hr>
    <center>
        <h6>Total payment</h6>
        <h5 style="color: red;font-weight:bold">Rp. <?php echo number_format($datapay->pembayaran_nominal, 0, ',', '.'); ?></h5>
        <h6>
            <u><b><a href="<?php echo site_url('assets/upload/' . $datapay->pembayaran_struk) ?>" target="_blank">View Proof of Payment</a></b></u>
        </h6>
    </center>
<?php } ?>