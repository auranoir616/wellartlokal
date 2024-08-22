<?php
$code = $this->input->get('code');

$this->db->where('invoice_code', $code);
$this->db->where('invoice_userfromid', userid());
$this->db->join('tb_users', 'id = invoice_usertoid');
$CEKINV = $this->db->get('tb_users_invoice');
if ($CEKINV->num_rows() == 0) {
?>
    <center>Data not found</center>
<?php } else {
    $DATAINV = $CEKINV->row();
?>
    <div class="form-group">
        <label for="">Payment Method</label>
        <select name="confirm_payment" class="form-control" onchange="setshow(this)">
            <option disabled selected>Select Payment</option>
            <?php
            $arrrrrrrey = array(
                array(
                    'label' => 'Local Bank',
                    'value' => 'bank',
                ),
                array(
                    'label' => 'Wallet USDT',
                    'value' => 'wallet',
                ),
            );
            foreach ($arrrrrrrey as $tfffff) {
                $selecttttt = '';
                if ($tfffff['value'] == 'bank' && $DATAINV->user_bank_number == null) {
                    $selecttttt = 'disabled';
                } elseif ($tfffff['value'] == 'wallet' && $DATAINV->user_wallet == null) {
                    $selecttttt = 'disabled';
                }
            ?>
                <option value="<?php echo $tfffff['value']; ?>" <?php echo $selecttttt; ?>><?php echo $tfffff['label']; ?></option>
            <?php } ?>
        </select>
    </div>
    <div id="showbank" style="display: none;">
        <?php echo form_open_multipart('', 'id="formBANKconfirm"'); ?>
        <input type="hidden" name="code" value="<?php echo $code; ?>">
        <input type="hidden" name="confirm_payment" value="bank">
        <div class="form-group">
            <label for="">Account in Name</label>
            <input type="text" class="form-control" placeholder="Account in Name" value="<?php echo $DATAINV->user_bank_name ?>" disabled>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label for="">Bank Name</label>
                    <input type="text" class="form-control" placeholder="Bank Name" value="<?php echo $DATAINV->user_bank_account ?>" disabled>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label for="">Account Number</label>
                    <input type="text" class="form-control" placeholder="Account Number" value="<?php echo $DATAINV->user_bank_number ?>" disabled>
                </div>
            </div>
        </div>
        <center class="mb-3">
            <p class="h5">Payment Confirmation</p>
        </center>
        <div class="form-group">
            <label for="">Account in Name</label>
            <input type="text" class="form-control" placeholder="Account in Name" name="confirm_account" autocomplete="off">
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Bank Name</label>
                    <input type="text" class="form-control" placeholder="Bank Name" name="confirm_bank" autocomplete="off">
                    <small>Example: BNI, BRI, BCA, BTN</small>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Account Number</label>
                    <input type="text" class="form-control" placeholder="Account Number" name="confirm_number" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="">Total Payment</label>
            <input type="text" class="form-control" placeholder="Total Payment" value="Rp. <?php echo number_format($DATAINV->invoice_total * $DATAINV->invoice_amount, 0, ',', '.'); ?>" disabled style="font-weight: 900;font-size: large;color:red">
        </div>
        <div class="form-group">
            <div class="file-upload">
                <div class="file-select">
                    <div class="file-select-button" id="fileName">Choose File</div>
                    <div class="file-select-name" id="noFile">No file chosen...</div>
                    <input type="file" name="confirm_fileimg" id="chooseFile" id="formFile" onchange="readURL(this)">
                </div>
            </div>
            <img id="imgggggg" style="max-width:150px; max-height:150px;margin-top: 10px;border: 1px solid #ddd">
            <script type="text/javascript">
                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('#imgggggg')
                                .attr('src', e.target.result);
                        };

                        reader.readAsDataURL(input.files[0]);
                    }
                }
            </script>
            <style>
                .file-upload {
                    display: block;
                    text-align: center;
                    font-family: Helvetica, Arial, sans-serif;
                    font-size: 12px;
                }

                .file-upload .file-select {
                    display: block;
                    border: 2px solid #dce4ec;
                    color: #34495e;
                    cursor: pointer;
                    height: 40px;
                    line-height: 40px;
                    text-align: left;
                    background: #ffffff;
                    overflow: hidden;
                    position: relative;
                }

                .file-upload .file-select .file-select-button {
                    background: #dce4ec;
                    padding: 0 10px;
                    display: inline-block;
                    height: 40px;
                    line-height: 40px;
                }

                .file-upload .file-select .file-select-name {
                    line-height: 40px;
                    display: inline-block;
                    padding: 0 10px;
                }

                .file-upload .file-select:hover {
                    border-color: #34495e;
                    transition: all 0.2s ease-in-out;
                    -moz-transition: all 0.2s ease-in-out;
                    -webkit-transition: all 0.2s ease-in-out;
                    -o-transition: all 0.2s ease-in-out;
                }

                .file-upload .file-select:hover .file-select-button {
                    background: #34495e;
                    color: #ffffff;
                    transition: all 0.2s ease-in-out;
                    -moz-transition: all 0.2s ease-in-out;
                    -webkit-transition: all 0.2s ease-in-out;
                    -o-transition: all 0.2s ease-in-out;
                }

                .file-upload.active .file-select {
                    border-color: #3fa46a;
                    transition: all 0.2s ease-in-out;
                    -moz-transition: all 0.2s ease-in-out;
                    -webkit-transition: all 0.2s ease-in-out;
                    -o-transition: all 0.2s ease-in-out;
                }

                .file-upload.active .file-select .file-select-button {
                    background: #3fa46a;
                    color: #ffffff;
                    transition: all 0.2s ease-in-out;
                    -moz-transition: all 0.2s ease-in-out;
                    -webkit-transition: all 0.2s ease-in-out;
                    -o-transition: all 0.2s ease-in-out;
                }

                .file-upload .file-select input[type="file"] {
                    z-index: 100;
                    cursor: pointer;
                    position: absolute;
                    height: 100%;
                    width: 100%;
                    top: 0;
                    left: 0;
                    opacity: 0;
                    filter: alpha(opacity=0);
                }

                .file-upload .file-select.file-select-disabled {
                    opacity: 0.65;
                }

                .file-upload .file-select.file-select-disabled:hover {
                    cursor: default;
                    display: block;
                    border: 2px solid #dce4ec;
                    color: #34495e;
                    cursor: pointer;
                    height: 40px;
                    line-height: 40px;
                    margin-top: 5px;
                    text-align: left;
                    background: #ffffff;
                    overflow: hidden;
                    position: relative;
                }

                .file-upload .file-select.file-select-disabled:hover .file-select-button {
                    background: #dce4ec;
                    color: #666666;
                    padding: 0 10px;
                    display: inline-block;
                    height: 40px;
                    line-height: 40px;
                }

                .file-upload .file-select.file-select-disabled:hover .file-select-name {
                    line-height: 40px;
                    display: inline-block;
                    padding: 0 10px;
                }
            </style>
        </div>
        <div class="form-group">
            <button id="btn01confirmB" type="submit" class="btn btn-primary btn-block font-weight-bold text-white">PAYMENT CONFIRMATION</button>
            <button id="btn02confirmB" type="button" class="btn btn-primary btn-block font-weight-bold text-white" disabled>PROCESSING</button>
        </div>
        <?php echo form_close(); ?>
        <script>
            jQuery(document).ready(function($) {
                $('#btn02confirmB').hide();
                $('#formBANKconfirm').submit(function(event) {
                    event.preventDefault();
                    $('#btn01confirmB').hide();
                    $('#btn02confirmB').show();

                    $.ajax({
                            url: '<?php echo site_url('postdata/user_post/invoice/confirmation') ?>',
                            type: 'post',
                            dataType: 'json',
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                        })
                        .done(function(data) {

                            // CSRF IS MAGIC
                            updateCSRF(data.csrf_data);
                            Swal.fire(
                                data.heading,
                                data.message,
                                data.type
                            ).then(function() {
                                if (data.status) {
                                    location.reload();
                                }
                                $('#btn01confirmB').show();
                                $('#btn02confirmB').hide();
                            });
                        })

                });
            });
        </script>

    </div>
    <div id="showwallet" style="display: none;">
        <?php echo form_open_multipart('', 'id="formWALLETconfirm"'); ?>
        <input type="hidden" name="code" value="<?php echo $code; ?>">
        <input type="hidden" name="confirm_payment" value="wallet">
        <div class="form-group">
            <label for="">Wallet Address</label>
            <textarea class="form-control" cols="4" rows="4" style="resize: none;" disabled><?php echo $DATAINV->user_wallet ?></textarea>
            <small class="text-danger">USDT (TRC20)</small>
        </div>

        <center class="mb-3">
            <p class="h5">Payment Confirmation</p>
        </center>
        <div class="form-group">
            <label for="">TX ID or TX Hash</label>
            <textarea name="confirm_txid" placeholder="TX ID or TX Hash" class="form-control" cols="4" rows="4" style="resize: none;"></textarea>
        </div>
        <div class="form-group">
            <label for="">Total Payment</label>
            <input type="text" class="form-control" placeholder="Total Payment" value="Rp. <?php echo number_format($DATAINV->invoice_total * $DATAINV->invoice_amount, 0, ',', '.'); ?>" disabled style="font-weight: 900;font-size: large;color:red">
        </div>
        <div class="form-group">
            <div class="file-upload">
                <div class="file-select">
                    <div class="file-select-button" id="fileName">Choose File</div>
                    <div class="file-select-name" id="noFile">No file chosen...</div>
                    <input type="file" name="confirm_fileimg" id="chooseFile" id="formFile" onchange="reeadURL(this)">
                </div>
            </div>
            <img id="imgeeeeg" style="max-width:150px; max-height:150px;margin-top: 10px;border: 1px solid #ddd">
            <script type="text/javascript">
                function reeadURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('#imgeeeeg')
                                .attr('src', e.target.result);
                        };

                        reader.readAsDataURL(input.files[0]);
                    }
                }
            </script>
            <style>
                .file-upload {
                    display: block;
                    text-align: center;
                    font-family: Helvetica, Arial, sans-serif;
                    font-size: 12px;
                }

                .file-upload .file-select {
                    display: block;
                    border: 2px solid #dce4ec;
                    color: #34495e;
                    cursor: pointer;
                    height: 40px;
                    line-height: 40px;
                    text-align: left;
                    background: #ffffff;
                    overflow: hidden;
                    position: relative;
                }

                .file-upload .file-select .file-select-button {
                    background: #dce4ec;
                    padding: 0 10px;
                    display: inline-block;
                    height: 40px;
                    line-height: 40px;
                }

                .file-upload .file-select .file-select-name {
                    line-height: 40px;
                    display: inline-block;
                    padding: 0 10px;
                }

                .file-upload .file-select:hover {
                    border-color: #34495e;
                    transition: all 0.2s ease-in-out;
                    -moz-transition: all 0.2s ease-in-out;
                    -webkit-transition: all 0.2s ease-in-out;
                    -o-transition: all 0.2s ease-in-out;
                }

                .file-upload .file-select:hover .file-select-button {
                    background: #34495e;
                    color: #ffffff;
                    transition: all 0.2s ease-in-out;
                    -moz-transition: all 0.2s ease-in-out;
                    -webkit-transition: all 0.2s ease-in-out;
                    -o-transition: all 0.2s ease-in-out;
                }

                .file-upload.active .file-select {
                    border-color: #3fa46a;
                    transition: all 0.2s ease-in-out;
                    -moz-transition: all 0.2s ease-in-out;
                    -webkit-transition: all 0.2s ease-in-out;
                    -o-transition: all 0.2s ease-in-out;
                }

                .file-upload.active .file-select .file-select-button {
                    background: #3fa46a;
                    color: #ffffff;
                    transition: all 0.2s ease-in-out;
                    -moz-transition: all 0.2s ease-in-out;
                    -webkit-transition: all 0.2s ease-in-out;
                    -o-transition: all 0.2s ease-in-out;
                }

                .file-upload .file-select input[type="file"] {
                    z-index: 100;
                    cursor: pointer;
                    position: absolute;
                    height: 100%;
                    width: 100%;
                    top: 0;
                    left: 0;
                    opacity: 0;
                    filter: alpha(opacity=0);
                }

                .file-upload .file-select.file-select-disabled {
                    opacity: 0.65;
                }

                .file-upload .file-select.file-select-disabled:hover {
                    cursor: default;
                    display: block;
                    border: 2px solid #dce4ec;
                    color: #34495e;
                    cursor: pointer;
                    height: 40px;
                    line-height: 40px;
                    margin-top: 5px;
                    text-align: left;
                    background: #ffffff;
                    overflow: hidden;
                    position: relative;
                }

                .file-upload .file-select.file-select-disabled:hover .file-select-button {
                    background: #dce4ec;
                    color: #666666;
                    padding: 0 10px;
                    display: inline-block;
                    height: 40px;
                    line-height: 40px;
                }

                .file-upload .file-select.file-select-disabled:hover .file-select-name {
                    line-height: 40px;
                    display: inline-block;
                    padding: 0 10px;
                }
            </style>
        </div>
        <div class="form-group">
            <button id="btn01confirmW" type="submit" class="btn btn-primary btn-block font-weight-bold text-white">PAYMENT CONFIRMATION</button>
            <button id="btn02confirmW" type="button" class="btn btn-primary btn-block font-weight-bold text-white" disabled>PROCESSING</button>
        </div>
        <?php echo form_close(); ?>
        <script>
            jQuery(document).ready(function($) {
                $('#btn02confirmW').hide();
                $('#formWALLETconfirm').submit(function(event) {
                    event.preventDefault();
                    $('#btn01confirmW').hide();
                    $('#btn02confirmW').show();

                    $.ajax({
                            url: '<?php echo site_url('postdata/user_post/invoice/confirmation') ?>',
                            type: 'post',
                            dataType: 'json',
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                        })
                        .done(function(data) {

                            // CSRF IS MAGIC
                            updateCSRF(data.csrf_data);
                            Swal.fire(
                                data.heading,
                                data.message,
                                data.type
                            ).then(function() {
                                if (data.status) {
                                    location.reload();
                                }
                                $('#btn01confirmW').show();
                                $('#btn02confirmW').hide();
                            });
                        })

                });
            });
        </script>
    </div>
    <script>
        function setshow(param) {
            var type = param.value;

            if (type == 'bank') {
                $("#showbank").show();
                $("#showwallet").hide();
            } else if (type == 'wallet') {
                $("#showbank").hide();
                $("#showwallet").show();
            }
        }
    </script>
<?php } ?>