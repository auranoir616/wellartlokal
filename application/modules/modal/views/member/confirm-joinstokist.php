<?php
$code = $this->input->get('code');

$this->db->where('invoice_stokis_code', $code);
$this->db->where('invoice_stokis_status', 'pending');
$this->db->where('invoice_stokis_userid', userid());
$CEKINV = $this->db->get('tb_users_invoice_stokis');
if ($CEKINV->num_rows() == 0) {
?>
    <center>Data Invoice Tidak Ditemukan</center>
<?php } else {
    $DATAINV = $CEKINV->row();
?>
    <?php echo form_open_multipart('', 'id="form-confirm"'); ?>
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <center>
        <h5>Total Tagihan</h5>
        <h4>Rp. <?php echo number_format($DATAINV->invoice_stokis_amount, 0, ',', '.'); ?></h4>
    </center>
    <hr>
    <center class="mb-3">
        <p class="h5">Konfirmasi Pembayaran</p>
    </center>
    <div class="form-group">
        <label for="">Rekening Atasnama</label>
        <input type="text" class="form-control" placeholder="Rekening Atasnama" name="confirm_account" autocomplete="off">
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Nama Bank</label>
                <input type="text" class="form-control" placeholder="Nama Bank" name="confirm_bank" autocomplete="off">
                <small>Contoh: BNI, BRI, BCA, BTN</small>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Nomor Rekening</label>
                <input type="text" class="form-control" placeholder="Nomor Rekening" name="confirm_number" autocomplete="off">
            </div>
        </div>
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
        <button id="btn01confirm" type="submit" class="btn btn-primary btn-block font-weight-bold text-white">KONFIRMASI PEMBAYARAN</button>
        <button id="btn02confirm" type="button" class="btn btn-primary btn-block font-weight-bold text-white" disabled>MEMPROSES</button>
    </div>
    <?php echo form_close(); ?>
    <script>
        jQuery(document).ready(function($) {
            $('#btn02confirm').hide();
            $('#form-confirm').submit(function(event) {
                event.preventDefault();
                $('#btn01confirm').hide();
                $('#btn02confirm').show();

                $.ajax({
                        url: '<?php echo site_url('postdata/user_post/invoice/confirmnewstokist') ?>',
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
                            $('#btn01confirm').show();
                            $('#btn02confirm').hide();
                        });
                    })

            });
        });
    </script>
<?php } ?>