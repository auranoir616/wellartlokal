<?php
$this->template->title->set("Level ", $genke);

?>
<div class="card">
    <div class="card-header">
        <div class="card-title">Level <?php echo $genke ?></div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td width="5%">#</td>
                        <td>User Data</td>
                        <td>Kode</td>
                        <td>Kontak</td>
                        <td>Referral</td>
                        <td>Omset</td>
                        <td>Tanggal</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit       = 15;
                    $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                    $no          = $offset + 1;

                    $this->db->order_by('titiklevel_date', 'DESC');
                    $this->db->where('titiklevel_level', $genke);
                    $this->db->where('titiklevel_userid', userid());
                    $this->db->join('tb_users', 'id = titiklevel_downlineid');
                    $getdata = $this->db->get('tb_titiklevel', $limit, $offset);

                    $this->db->where('titiklevel_level', $genke);
                    $this->db->where('titiklevel_userid', userid());
                    $this->db->join('tb_users', 'id = titiklevel_downlineid');
                    $Gettotal = $this->db->get('tb_titiklevel')->num_rows();

                    foreach ($getdata->result() as $show) {
                        $refdata    = userdata(['id' => $show->referral_id]);
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td>
                                <?php echo $show->user_fullname ?>
                                <br>
                                <small>@<?php echo $show->username; ?></small>
                            </td>
                            <td><span onclick="copyyyKODE(`<?php echo $show->user_referral_code; ?>`)"><?php echo $show->user_referral_code; ?></span></td>
                            <td>
                                <?php echo $show->email ?>
                                <br>
                                <?php echo $show->user_phone ?>
                            </td>
                            <td><?php echo $refdata->user_fullname ?>
                                <br>
                                <small>@<?php echo $refdata->username; ?></small>
                            </td>
                            <td>
                                <?php echo $show->user_omset; ?> BV
                            </td>
                            <td>
                                <?php echo $this->rank->myrank($show->id); ?>
                                <br>
                                <?php echo date('d-M-Y', $show->created_on) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo $this->paginationmodel->paginate('', $Gettotal, $limit) ?>
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