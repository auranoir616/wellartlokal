<?php
$this->template->title->set('Generation');
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Generation</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                <thead>
                    <tr>
                        <td width="5%">#</td>
                        <td>Generation</td>
                        <td width="25%">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $arrstart = 1;
                    for ($x = 1; $x <= 10; $x++) {

                        $this->db->where('titiklevel_userid', userid());
                        $this->db->where('titiklevel_level', $x);
                        $ceklevel = $this->db->get('tb_titiklevel');
                        if ($ceklevel->num_rows() != 0) {
                    ?>
                            <tr>
                                <th><?php echo $no++ ?></th>
                                <th>Generation <?php echo $x ?></th>
                                <th>
                                    <a href="<?php echo site_url('generation/' . $x) ?>" class="btn btn-info btn-sm" title="Tampilkan">
                                        Tampilkan
                                    </a>
                                </th>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>