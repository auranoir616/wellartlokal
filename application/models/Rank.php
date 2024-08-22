<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rank extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function myBV($userid = null)
    {

        $totalBV = 0;
        $userid = ($userid == null) ? userid() : $userid;

        $this->db->select_sum('historiro_bv');
        $this->db->where('historiro_userid', $userid);
        $get    = $this->db->get('tb_historiro');
        $get_BV     = $get->row()->historiro_bv;

        if (!empty($get_BV)) {
            $totalBV     = $get_BV;
        }

        return $totalBV;
    }

    function bulanlaluBV($userid = null)
    {

        $totalBV    = 0;
        $date_start = date('Y-m-01 00:00:00', strtotime('-1 month', now()));
        $date_end   = date('Y-m-t 23:59:59', strtotime('-1 month', now()));

        $userid     = ($userid == null) ? userid() : $userid;

        $this->db->select_sum('historiro_bv');
        $this->db->where('historiro_date BETWEEN "' . $date_start . '" AND "' . $date_end . '"');
        $this->db->where('historiro_userid', $userid);
        $get    = $this->db->get('tb_historiro');
        $get_BV     = $get->row()->historiro_bv;

        if (!empty($get_BV)) {
            $totalBV     = $get_BV;
        }

        return $totalBV;
    }

    function myrank($userid = null)
    {
        $myrank = 'MEMBER';
        $userid = ($userid == null) ? userid() : $userid;

        $this->db->where('id', $userid);
        $cekuser = $this->db->get('tb_users')->row();

        $this->db->order_by('userrank_rankid', 'DESC');
        $this->db->where('userrank_userid', $userid);
        $cekRANK = $this->db->get('tb_userrank');
        $rankid = ($cekRANK->num_rows() != 0) ? $cekRANK->row()->userrank_rankid : 0;
        if ($cekuser->user_rankid < $rankid) {
            $this->db->update(
                'tb_users',
                [
                    'user_rankid'    => $rankid
                ],
                [
                    'id'             => $userid
                ]
            );
        }
        $this->db->where('rank_id', $rankid);
        $rankSaatini = $this->db->get('tb_rank');
        if ($rankSaatini->num_rows() != 0) {
            $myrank = $rankSaatini->row()->rank_name;
        }

        // $this->db->where('userrank_userid', $userid);
        // $cekRANK = $this->db->get('tb_userrank');
        // if ($cekRANK->num_rows() != 0) {
        //     $myrank = $cekRANK->row()->userrank_ranklabel;
        // }
        return $myrank;
    }

    function getmyrank($userid = null)
    {
        $this->db->select('userrank_userid, SUM(userrank_omset) AS total_omset');
        $this->db->from('tb_userrank');
    
        if ($userid) {
            $this->db->where('userrank_userid', $userid);
        }
    
        $this->db->group_by('userrank_userid');
        $query = $this->db->get();
        $result = $query->result_array();
    
        foreach ($result as &$row) {
            $total_omset = $row['total_omset'];
    
            $this->db->select('rank_name');
            $this->db->from('tb_rank');
            $this->db->where($total_omset .' BETWEEN rank_min AND rank_max');

            $rank_query = $this->db->get();
            $rank = $rank_query->row();
            $row['rank_name'] = $rank ? $rank->rank_name : null; 
        }
        if (empty($result)) {
            $result[] = [
                'userrank_userid' => $userid,
                'total_omset' => 0,
                'rank_name' => 'MEMBER'
            ];
        }
        return $result;
    }    
    function ranking($userid = null, $required = 2)
    {
        $result     = array();
        $userid     = ($userid == null) ? userid() : $userid;
        $userdata   = userdata(['id' => $userid]);

        $getRANK = $this->db->get('tb_rank');

        $idrank = 0;
        $myrank = 'MEMBER';
        $myomset = $userdata->user_omset;

        // GET SEMUA REFERRAL

        //RUMUS AWAL
        // $this->db->where('referral_id', $userid);

        //SETELAH EDIT
        $this->db->where('upline_id', $userid);
        $getDown  = $this->db->get('tb_users');

        // GET RANK
        foreach ($getRANK->result() as $showR) {
            // DATA REFERRAL / DOWNLINE
            $totQualif   = 0;
            foreach ($getDown->result() as $userREF) {
                // CEK APAKAH SETIAP USER MEMENUHI KULIFIKASI
                if (($userREF->user_omset >= $showR->rank_min && $userREF->user_omset <= $showR->rank_max)) {
                    // JIKA ADA USER YANG MEMENUHI KULIFIKASI MAKA DIHITUNG
                    $totQualif += 1;
                }
            }

            if ($totQualif >= $required) {
                // AMBIL RANK YANG MEMENUHI SYARAT
                $idrank = $showR->rank_id;
                $myrank = $showR->rank_name;
                $myomset = $userdata->user_omset;
            }
        }


        $this->db->where('userrank_rankid !=', 0);
        $this->db->where('userrank_userid', $userid);
        $cekkkRANK = $this->db->get('tb_userrank');
        if ($cekkkRANK->num_rows() == 0) {
            // INPUT JIKA BELUM PERNAH MEMENUHI RANK
            $this->db->insert(
                'tb_userrank',
                [
                    'userrank_rankid'       => $idrank,
                    'userrank_userid'       => $userid,
                    'userrank_ranklabel'    => $myrank,
                    'userrank_omset'        => $myomset,
                    'userrank_code'         => strtolower(random_string('alnum', 64)),
                ]
            );
        } else {
            // JIKA SUDAH ADA RANK
            $rankNOW = $cekkkRANK->row();
            // JIKA ID RANK TERBARU LEBIH BESAR DARI RANK LAMA
            if ($idrank > $rankNOW->userrank_rankid) {
                $this->db->update(
                    'tb_userrank',
                    [
                        'userrank_rankid'    => $idrank,
                        'userrank_ranklabel' => $myrank,
                        'userrank_omset'     => $myomset,

                    ],
                    [
                        'userrank_userid'    => $userid,
                        'userrank_code'      => $rankNOW->userrank_code,
                    ]
                );
            }
        }
        $result = [
            'idrank'    => $idrank,
            'myrank'    => $myrank,
            'myomset'   => $myomset,
        ];

        return $result;
    }

    function qualifSP($userid = null, $required = 2)
    {
        $result     = array();
        $userid     = ($userid == null) ? userid() : $userid;
        $userdata   = userdata(['id' => $userid]);

        $this->db->where('rank_id !=', (int)1);
        $getRANK = $this->db->get('tb_rank');

        $settttt = [];

        $this->db->where('upline_id', $userid);
        $getDown  = $this->db->get('tb_users');
        foreach ($getRANK->result() as $showR) {
            // DATA REFERRAL / DOWNLINE
            $totQualif  = 0;
            $totOMSET   = 0;
            foreach ($getDown->result() as $userREF) {
                // CEK APAKAH SETIAP USER MEMENUHI KULIFIKASI
                // if (($userREF->user_omset >= $showR->rank_min && $userREF->user_omset <= $showR->rank_max)) {
                if (($userREF->user_omset >= $showR->rank_min)) {
                    // JIKA ADA USER YANG MEMENUHI KULIFIKASI MAKA DIHITUNG
                    $totQualif += 1;
                    $totOMSET += $userREF->user_omset;
                }
            }

            if ($totQualif >= $required) {
                // AMBIL RANK YANG MEMENUHI SYARAT
                $settttt = [
                    'idrank'    => $showR->rank_id,
                    'myrank'    => $showR->rank_name,
                    'myomset'   => $totOMSET,
                ];
            }
        }

        $result = $settttt;

        return $result;
    }

    function bagian($amount = 0, $type = 'ranking', $rankid = 0){
        $return = 0;
        $this->db->where('rank_id', $rankid);
        $gettttRANK = $this->db->get('tb_rank');
        $this->db->where('usershare_status', $type);
        $this->db->where('usershare_rankid', $rankid);
        $tooooot = $this->db->get('tb_usershare')->num_rows();
        if ($gettttRANK->num_rows() != 0) {
            if ($type == 'ranking') {
                $pesen = $gettttRANK->row()->rank_ranking;
            } else {
                $pesen = $gettttRANK->row()->rank_royalty;
            }
            // AMBIL PERSEN BERDASARKAN KUALIFIKASINYA DARI TOTAL OMSET
            $getbagian = ($pesen / 100) * $amount;
            // BAGIAN DARI PERSEN DI BAGI KE TOTAL USER SESUAI KUALIFIKASINYA
            $return = $getbagian / $tooooot;
        }

        return $return;
    }
    
}
