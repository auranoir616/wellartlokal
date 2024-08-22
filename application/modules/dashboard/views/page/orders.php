<?php
$this->template->title->set('Orders');

$this->db->where('userstokis_type', 'masterstokis');
$this->db->where('userstokis_userid', userid());
$cekSTOKIS = $this->db->get('tb_users_stokis');
?>
<div class="row">
    <div class="col-sm-12 col-lg-4 col-md-4">
        <?php
        if ($cekSTOKIS->num_rows() != 0) {
        ?>
            <div class="card">
                <div class="card-header">
                    <h3>Form Orders</h3>
                </div>
                <div class="card-body">
                    <center class="h5">Master Stokis Dilayani Perusahaan Langsung</center>
                    <hr>
                    <?php echo form_open('', 'id="form-orderadmin"'); ?>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="">Packages</label>
                                <select class="form-control" name="order_package" id="pin_package" onchange="getharga()" required>
                                    <option disabled selected>Select Packages</option>
                                    <?php
                                    $getPakcages = $this->db->get('tb_packages');
                                    foreach ($getPakcages->result() as $paket) {
                                    ?>
                                        <option value="<?php echo $paket->package_code; ?>"><?php echo $paket->package_name; ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" id="harga_paket" name="order_price">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="">Total Orders</label>
                                <input id="jmlpin" type="text" class="form-control" name="order_total" placeholder="Total Orders" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">SubTotal</label>
                        <input type="text" class="form-control" placeholder="SubTotal Bayar" id="subtotal" readonly>
                    </div>
                    <script>
                        function getharga() {
                            var paket_id = $('#pin_package').val();
                            $("#harga_paket").val('');
                            $.ajax({
                                url: '<?php echo site_url('getdata/user_get/getdaerah/getdetailpaket') ?>',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    paket_id: paket_id,
                                }
                            }).done(function(data) {
                                document.getElementById("harga_paket").value = data.harga;
                                document.getElementById("jmlpin").value = '';
                                document.getElementById("subtotal").value = "Rp. 0";
                            })
                        }

                        $(document).ready(function() {
                            $("#jmlpin").keyup(function() {
                                var jmlpin = $('#jmlpin').val();
                                var harga_pin = $('#harga_paket').val();
                                var paket_id = $('#pin_package').val();
                                if (!paket_id) {
                                    Swal.fire(
                                        'Error',
                                        'Please Choose a Package First',
                                        'error'
                                    )
                                } else {
                                    $.ajax({
                                        url: '<?php echo site_url('getdata/user_get/getdaerah/ngitung') ?>',
                                        type: 'GET',
                                        dataType: 'json',
                                        data: {
                                            paket_id: paket_id,
                                            jmlpin: jmlpin,
                                            harga_pin: harga_pin,
                                        }
                                    }).done(function(data) {

                                        var setharga = data.harga.toString().split('').reverse().join(''),
                                            hargaaaaa = setharga.match(/\d{1,3}/g);
                                        hargaaaaa = hargaaaaa.join('.').split('').reverse().join('');

                                        document.getElementById("subtotal").value = "Rp. " + hargaaaaa;
                                    })
                                }
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="input-group" id="show_hide_password">
                            <input type="password" class="form-control" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="basic-addon2" name="order_password" autocomplete="new-password">
                            <div class="input-group-append">
                                <button style="border:1px solid rgba(120, 130, 140, 0.2)" class="btn" type="button" id=""><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $("#show_hide_password button").on('click', function(event) {
                                event.preventDefault();
                                if ($('#show_hide_password input').attr("type") == "text") {
                                    $('#show_hide_password input').attr('type', 'password');
                                    $('#show_hide_password i').addClass("fa-eye-slash");
                                    $('#show_hide_password i').removeClass("fa-eye");
                                } else if ($('#show_hide_password input').attr("type") == "password") {
                                    $('#show_hide_password input').attr('type', 'text');
                                    $('#show_hide_password i').removeClass("fa-eye-slash");
                                    $('#show_hide_password i').addClass("fa-eye");
                                }
                            });
                        });
                    </script>
                    <div class="form-group">
                        <button type="submit" id="btn01" class="btn btn-block btn-md" style="background:#2c3e50!important;color:#fff!important;">CREATE INVOICE</button>
                        <button type="button" id="btn02" class="btn btn-block btn-md" style="background:#2c3e50!important;color:#fff!important;" disabled>PROCESSING</button>
                    </div>
                    <?php echo form_close(); ?>
                    <script>
                        $('#btn02').hide();
                        $('#form-orderadmin').submit(function(event) {
                            event.preventDefault();
                            $('#btn01').hide();
                            $('#btn02').show();

                            $.ajax({
                                    url: '<?php echo site_url('postdata/user_post/invoice/neworderadmin') ?>',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: $('#form-orderadmin').serialize(),
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
                                    $('#btn01').show();
                                    $('#btn02').hide();
                                })
                        });
                    </script>
                </div>
            </div>
        <?php } else {
            $this->db->where('userstokis_userid', userid());
            $cekSTOKIS = $this->db->get('tb_users_stokis');
            if ($cekSTOKIS->num_rows() != 0) {
                $dataSTOKIS = $cekSTOKIS->row();
                // JIKA ANDA STOKIS
                if ($dataSTOKIS->userstokis_type == 'mobilestokis') {
                    $whereee = 'masterstokis';
                } else {
                    $whereee = 'mobilestokis';
                }
            } else {
                $whereee = 'mobilestokis';
            }
            $this->db->where('userstokis_status', 'active');
            $this->db->where('userstokis_kota', $userdata->user_kota);
            $this->db->where('userstokis_type', $whereee);
            $this->db->join('tb_users', 'id = userstokis_userid');
            $getuserrrr = $this->db->get('tb_users_stokis');

        ?>
            <!-- are pngerjaan form -->
            <div class="card">
                <div class="card-header">
                    <h3>Form Orders</h3>
                </div>
                <div class="card-body">
                    <?php echo form_open('', 'id="form-order"'); ?>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="">Stokis</label>
                                <select class="form-control" name="order_penjualcode" id="penjual_id" onchange="getpackage()" required>
                                    <option selected disabled>Select Stokis</option>
                                    <?php
                                    if ($getuserrrr->num_rows() == 0) {
                                    ?>
                                        <option value="ls7afu5yrnufkpjixcho1c0qijigduo8m9by6lpz7kvoq4kpb9vgzf4nesw1ghjo">ADMINISTRATOR</option>
                                        <?php
                                    } else {
                                        foreach ($getuserrrr->result() as $stooookis) {
                                        ?>
                                            <option value="<?php echo $stooookis->user_code; ?>"><?php echo strtoupper($stooookis->user_fullname); ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="">Packages</label>
                                <select class="form-control" name="order_package" id="pin_package" onchange="getharga()" required>
                                    <option disabled selected>Select Packages</option>
                                </select>
                                <input type="hidden" id="harga_paket" name="order_price">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Total Orders</label>
                        <input id="jmlpin" type="text" class="form-control" name="order_total" placeholder="Total Orders" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="">SubTotal</label>
                        <input type="text" class="form-control" placeholder="SubTotal Bayar" id="subtotal" readonly>
                    </div>
                    <script>
                        function getpackage() {
                            var penjual_id = $('#penjual_id').val();

                            $.ajax({
                                url: '<?php echo site_url('getdata/user_get/getdaerah/get_package') ?>',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    penjual_id: penjual_id,
                                }
                            }).done(function(data) {
                                $('#pin_package').empty();
                                $('#pin_package').append('<option disabled selected>Select Packages</option>');
                                $.each(data.result, function(index, val) {
                                    var disabledddd = '';
                                    if (val.stockpin == 0) {
                                        var disabledddd = 'disabled';
                                    }
                                    var showstock = '';
                                    if (val.statusshow) {
                                        var showstock = '( ' + val.stockpin + ' Packages )';
                                    }
                                    $('#pin_package').append('<option value="' + val.paket_code + '" ' + disabledddd + '>' + val.paket + showstock + '</option>');
                                });
                            })
                        }

                        function getharga() {
                            var paket_id = $('#pin_package').val();
                            var penjual_id = $('#penjual_id').val();
                            $("#harga_paket").val('');
                            $.ajax({
                                url: '<?php echo site_url('getdata/user_get/getdaerah/getdetailpaket') ?>',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    paket_id: paket_id,
                                    penjual_id: penjual_id,
                                }
                            }).done(function(data) {
                                document.getElementById("harga_paket").value = data.harga;
                                document.getElementById("jmlpin").value = '';
                                document.getElementById("subtotal").value = "Rp. 0";
                            })
                        }

                        $(document).ready(function() {
                            $("#jmlpin").keyup(function() {
                                var jmlpin = $('#jmlpin').val();
                                var harga_pin = $('#harga_paket').val();
                                var paket_id = $('#pin_package').val();
                                if (!paket_id) {
                                    Swal.fire(
                                        'Error',
                                        'Please Choose a Package First',
                                        'error'
                                    )
                                } else {
                                    $.ajax({
                                        url: '<?php echo site_url('getdata/user_get/getdaerah/ngitung') ?>',
                                        type: 'GET',
                                        dataType: 'json',
                                        data: {
                                            paket_id: paket_id,
                                            jmlpin: jmlpin,
                                            harga_pin: harga_pin,
                                        }
                                    }).done(function(data) {

                                        var setharga = data.harga.toString().split('').reverse().join(''),
                                            hargaaaaa = setharga.match(/\d{1,3}/g);
                                        hargaaaaa = hargaaaaa.join('.').split('').reverse().join('');

                                        document.getElementById("subtotal").value = "Rp. " + hargaaaaa;
                                    })
                                }
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="input-group" id="show_hide_password">
                            <input type="password" class="form-control" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="basic-addon2" name="order_password" autocomplete="new-password">
                            <div class="input-group-append">
                                <button style="border:1px solid rgba(120, 130, 140, 0.2)" class="btn" type="button" id=""><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $("#show_hide_password button").on('click', function(event) {
                                event.preventDefault();
                                if ($('#show_hide_password input').attr("type") == "text") {
                                    $('#show_hide_password input').attr('type', 'password');
                                    $('#show_hide_password i').addClass("fa-eye-slash");
                                    $('#show_hide_password i').removeClass("fa-eye");
                                } else if ($('#show_hide_password input').attr("type") == "password") {
                                    $('#show_hide_password input').attr('type', 'text');
                                    $('#show_hide_password i').removeClass("fa-eye-slash");
                                    $('#show_hide_password i').addClass("fa-eye");
                                }
                            });
                        });
                    </script>
                    <div class="form-group">
                        <button type="submit" id="btn01" class="btn btn-block btn-md" style="background:#2c3e50!important;color:#fff!important;">CREATE INVOICE</button>
                        <button type="button" id="btn02" class="btn btn-block btn-md" style="background:#2c3e50!important;color:#fff!important;" disabled>PROCESSING</button>
                    </div>
                    <?php echo form_close(); ?>
                    <script>
                        $('#btn02').hide();
                        $('#form-order').submit(function(event) {
                            event.preventDefault();
                            $('#btn01').hide();
                            $('#btn02').show();

                            $.ajax({
                                    url: '<?php echo site_url('postdata/user_post/invoice/neworder') ?>',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: $('#form-order').serialize(),
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
                                    $('#btn01').show();
                                    $('#btn02').hide();
                                })
                        });
                    </script>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-sm-12 col-lg-8 col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Purchase History</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Stokis</th>
                            <th>Packages</th>
                            <th>SubTotal</th>
                            <th width="20%">Date</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $limit       = 15;
                        $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                        $no          = $offset + 1;

                        $this->db->order_by('invoice_date_add', 'DESC');
                        $this->db->where('invoice_userfromid', userid());
                        $this->db->join('tb_users', 'id = invoice_usertoid');
                        $this->db->join('tb_packages', 'package_id = invoice_package_id');
                        $GetData = $this->db->get('tb_users_invoice', $limit, $offset);

                        $this->db->where('invoice_userfromid', userid());
                        $this->db->join('tb_users', 'id = invoice_usertoid');
                        $this->db->join('tb_packages', 'package_id = invoice_package_id');
                        $Gettotal = $this->db->get('tb_users_invoice')->num_rows();
                        foreach ($GetData->result() as $row) {
                        ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?= $row->user_fullname ?></td>
                                <td><?= $row->invoice_total ?> (<?= $row->package_name ?>)</td>
                                <td>
                                    <?php if ($row->invoice_status == 'pending') { ?>
                                        <span class="rounded-pill badge badge-warning bg-warning">Pending</span>
                                    <?php } elseif ($row->invoice_status == 'process') {  ?>
                                        <span class="rounded-pill badge badge-info bg-info">Process</span>
                                    <?php } elseif ($row->invoice_status == 'success') {  ?>
                                        <span class="rounded-pill badge badge-success bg-success">Success</span>
                                    <?php } ?>
                                    <br>
                                    Rp. <?php echo number_format($row->invoice_total * $row->invoice_amount, 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <?= date('H:i:s', strtotime($row->invoice_date_add)) ?>
                                    <br>
                                    <?= date('d-M-Y', strtotime($row->invoice_date_add)) ?>
                                </td>
                                <td>
                                    <?php if ($row->invoice_status == 'pending') { ?>
                                        <!-- <a data-href="<? //php echo site_url('modal/member/order-confirm?code=' . $row->invoice_code) 
                                                            ?>" data-title="Confirmation" data-remote="false" data-toggle="modal" data-target="#dinamicModal" data-backdrop="static" data-keyboard="false" class="btn btn-sm btn-primary text-white p-1" title="Confirmation">
                                            COFIRM
                                        </a> -->
                                        <a data-href="<?php echo site_url('modal/member/order-confirm?code=' . $row->invoice_code) ?>" data-bs-title="CONFIRM" data-bs-remote="false" data-bs-toggle="modal" data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false" title="CONFIRM" class="btn btn-sm btn-primary text-white p-1 m-1">
                                            Confirm
                                        </a>
                                        
                                        <a href="javascript:void(0)" onclick="actioncancel('<?php echo $row->invoice_code ?>')" class="btn btn-sm btn-danger text-white m-1" title="Cancel"><i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="p-2">
                <?php echo $this->paginationmodel->paginate('orders', $Gettotal, $limit) ?>
            </div>
        </div>
    </div>
</div>

<script>
    function actioncancel(code) {
        Swal.fire({
            allowOutsideClick: false,
            title: 'Are you sure?',
            text: "This transaction will be canceled and cannot continue!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YES Cancel',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/user_post/invoice/cancelINV') ?>',
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