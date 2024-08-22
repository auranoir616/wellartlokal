<?php
$this->template->title->set('PIN Kode');
?>
<div class="form-group">
    <a data-href="<?php echo site_url('modal/member/kirim-pin') ?>" data-bs-title="Kirim PIN Kode" data-bs-remote="false" data-bs-toggle="modal" data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false" title="Kirim PIN Kode" style="color:#fff;font-weight: bold;" class="btn btn-block btn-primary">
        Kirim PIN Kode
    </a>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">PIN Kode</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                <thead>
                    <tr>
                        <td width="5%">#</td>
                        <td>Paket</td>
                        <td>PIN Kode</td>
                        <td>Tanggal</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit       = 15;
                    $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                    $no          = $offset + 1;

                    $this->db->order_by('pin_date_add', 'DESC');
                    $this->db->where('pin_userid', userid());
                    $this->db->join('tb_packages', 'pin_package_id = package_id');
                    $getdata = $this->db->get('tb_users_pin', $limit, $offset);


                    $this->db->where('pin_userid', userid());
                    $this->db->join('tb_packages', 'pin_package_id = package_id');
                    $Gettotal = $this->db->get('tb_users_pin')->num_rows();
                    foreach ($getdata->result() as $show) {
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $show->package_name ?></td>
                            <td><span onclick="copyyyPIN(`<?php echo $show->pin_kode; ?>`)"><?php echo $show->pin_kode; ?></span></td>
                            <td><?php echo $show->pin_date_add ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo $this->paginationmodel->paginate('pin-serial', $Gettotal, $limit) ?>
        </div>
    </div>
</div>
<script>
    function copyyyPIN(text) {
        var copy = document.createElement("textarea");
        document.body.appendChild(copy);
        copy.value = text;
        copy.select();
        document.execCommand("copy");
        document.body.removeChild(copy);


        Swal.fire(
            "Berhasil",
            "PIN Kode <strong>" + text + "</strong> Dicopy",
            "success"
        )
    }
</script>