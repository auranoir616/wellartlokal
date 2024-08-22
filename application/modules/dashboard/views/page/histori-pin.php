<?php
$this->template->title->set('Histori PIN Kode');
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Histori PIN Kode</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                <thead>
                    <tr>
                        <td width="5%">#</td>
                        <td>Deskripsi</td>
                        <td width="25%">Tanggal</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit       = 10;
                    $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                    $no          = $offset + 1;

                    $this->db->order_by('histori_userpindate', 'DESC');
                    $this->db->where('histori_userid', userid());
                    $getdata = $this->db->get('tb_histori_userpin', $limit, $offset);

                    $this->db->where('histori_userid', userid());
                    $Gettotal = $this->db->get('tb_histori_userpin')->num_rows();
                    foreach ($getdata->result() as $show) {
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $show->histori_userpindesc ?></td>
                            <td><?php echo $show->histori_userpindate ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo $this->paginationmodel->paginate('histori-pin', $Gettotal, $limit) ?>
        </div>
    </div>
</div>