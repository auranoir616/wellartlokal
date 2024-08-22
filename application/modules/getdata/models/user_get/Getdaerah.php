<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Getdaerah extends CI_Model
{

    private static $data = [
        'status'     => true,
        'message'     => null,
    ];

    public function __construct()
    {
        parent::__construct();
    }

    // function getkab_member()
    // {
    //     $datasssss = array();

    //     $this->db->where('province_id', $this->input->get('provinsi_id'));
    //     $getkabupatenn = $this->db->get('tb_kabupaten');
    //     foreach ($getkabupatenn->result() as $show) {

    //         $this->db->where('id !=', userid());
    //         $this->db->where('user_status', 'agen');
    //         $this->db->where('pin_status', 'available');
    //         $this->db->where('user_kota', $show->id);
    //         $this->db->join('tb_users_pin', 'pin_userid = id');
    //         $cekpinnn = $this->db->get('tb_users');
    //         if ($cekpinnn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'id'            => $show->id,
    //                 'name'          => $show->name,
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function getkec_member()
    // {
    //     $datasssss = array();
    //     $this->db->where('regency_id', $this->input->get('kabkota_id'));
    //     $getkecamatan = $this->db->get('tb_kecamatan');
    //     foreach ($getkecamatan->result() as $show) {

    //         $this->db->where('id !=', userid());
    //         $this->db->where('user_status', 'agen');
    //         $this->db->where('pin_status', 'available');
    //         $this->db->where('user_kecamatan', $show->id);
    //         $this->db->join('tb_users_pin', 'pin_userid = id');
    //         $cekpinnn = $this->db->get('tb_users');
    //         if ($cekpinnn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'id'            => $show->id,
    //                 'name'          => $show->name,
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function getkel_member()
    // {
    //     $datasssss = array();
    //     $this->db->where('district_id', $this->input->get('kecamatan_id'));
    //     $getkelurahan = $this->db->get('villages');
    //     foreach ($getkelurahan->result() as $show) {

    //         $this->db->where('id !=', userid());
    //         $this->db->where('user_status', 'agen');
    //         $this->db->where('pin_status', 'available');
    //         $this->db->where('user_kelurahan', $show->id);
    //         $this->db->join('tb_users_pin', 'pin_userid = id');
    //         $cekpinnn = $this->db->get('tb_users');
    //         if ($cekpinnn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'id'            => $show->id,
    //                 'name'          => $show->name,
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function get_agen()
    // {
    //     $this->db->where('id !=', userid());
    //     $this->db->where('user_status', 'agen');
    //     $this->db->where('user_kelurahan', $this->input->get('kelurahan_id'));
    //     $getuserrrr = $this->db->get('tb_users');

    //     foreach ($getuserrrr->result() as $getsss) {

    //         $this->db->where('pin_userid', $getsss->id);
    //         $this->db->where('pin_status', 'available');
    //         $cekpinn = $this->db->get('tb_users_pin');

    //         if ($cekpinn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'user_code'             => $getsss->user_code,
    //                 'user_fullname'         => strtoupper($getsss->user_fullname),
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function getkab_distributor()
    // {
    //     $datasssss = array();

    //     $this->db->where('province_id', $this->input->get('provinsi_id'));
    //     $getkabupatenn = $this->db->get('tb_kabupaten');
    //     foreach ($getkabupatenn->result() as $show) {

    //         $this->db->where('id !=', userid());
    //         $this->db->where('user_status', 'distributor');
    //         $this->db->where('pin_status', 'available');
    //         $this->db->where('user_kota', $show->id);
    //         $this->db->join('tb_users_pin', 'pin_userid = id');
    //         $cekpinnn = $this->db->get('tb_users');
    //         if ($cekpinnn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'id'            => $show->id,
    //                 'name'          => $show->name,
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function getkec_distributor()
    // {
    //     $datasssss = array();
    //     $this->db->where('regency_id', $this->input->get('kabkota_id'));
    //     $getkecamatan = $this->db->get('tb_kecamatan');
    //     foreach ($getkecamatan->result() as $show) {

    //         $this->db->where('id !=', userid());
    //         $this->db->where('user_status', 'distributor');
    //         $this->db->where('pin_status', 'available');
    //         $this->db->where('user_kecamatan', $show->id);
    //         $this->db->join('tb_users_pin', 'pin_userid = id');
    //         $cekpinnn = $this->db->get('tb_users');
    //         if ($cekpinnn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'id'            => $show->id,
    //                 'name'          => $show->name,
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function getkel_distributor()
    // {
    //     $datasssss = array();
    //     $this->db->where('district_id', $this->input->get('kecamatan_id'));
    //     $getkelurahan = $this->db->get('villages');
    //     foreach ($getkelurahan->result() as $show) {

    //         $this->db->where('id !=', userid());
    //         $this->db->where('user_status', 'distributor');
    //         $this->db->where('pin_status', 'available');
    //         $this->db->where('user_kelurahan', $show->id);
    //         $this->db->join('tb_users_pin', 'pin_userid = id');
    //         $cekpinnn = $this->db->get('tb_users');
    //         if ($cekpinnn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'id'            => $show->id,
    //                 'name'          => $show->name,
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function get_distributor()
    // {
    //     $this->db->where('id !=', userid());
    //     $this->db->where('user_status', 'distributor');
    //     $this->db->where('user_kelurahan', $this->input->get('kelurahan_id'));
    //     $getuserrrr = $this->db->get('tb_users');

    //     foreach ($getuserrrr->result() as $getsss) {

    //         $this->db->where('pin_userid', $getsss->id);
    //         $this->db->where('pin_status', 'available');
    //         $cekpinn = $this->db->get('tb_users_pin');

    //         if ($cekpinn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'user_code'             => $getsss->user_code,
    //                 'user_fullname'         => strtoupper($getsss->user_fullname),
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function getkab_grdistributor()
    // {
    //     $datasssss = array();

    //     $this->db->where('province_id', $this->input->get('provinsi_id'));
    //     $getkabupatenn = $this->db->get('tb_kabupaten');
    //     foreach ($getkabupatenn->result() as $show) {

    //         $this->db->where('id !=', userid());
    //         $this->db->where('user_status', 'grand-distributor');
    //         $this->db->where('pin_status', 'available');
    //         $this->db->where('user_kota', $show->id);
    //         $this->db->join('tb_users_pin', 'pin_userid = id');
    //         $cekpinnn = $this->db->get('tb_users');
    //         if ($cekpinnn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'id'            => $show->id,
    //                 'name'          => $show->name,
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function getkec_grdistributor()
    // {
    //     $datasssss = array();
    //     $this->db->where('regency_id', $this->input->get('kabkota_id'));
    //     $getkecamatan = $this->db->get('tb_kecamatan');
    //     foreach ($getkecamatan->result() as $show) {

    //         $this->db->where('id !=', userid());
    //         $this->db->where('user_status', 'grand-distributor');
    //         $this->db->where('pin_status', 'available');
    //         $this->db->where('user_kecamatan', $show->id);
    //         $this->db->join('tb_users_pin', 'pin_userid = id');
    //         $cekpinnn = $this->db->get('tb_users');
    //         if ($cekpinnn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'id'            => $show->id,
    //                 'name'          => $show->name,
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function getkel_grdistributor()
    // {
    //     $datasssss = array();
    //     $this->db->where('district_id', $this->input->get('kecamatan_id'));
    //     $getkelurahan = $this->db->get('villages');
    //     foreach ($getkelurahan->result() as $show) {

    //         $this->db->where('id !=', userid());
    //         $this->db->where('user_status', 'grand-distributor');
    //         $this->db->where('pin_status', 'available');
    //         $this->db->where('user_kelurahan', $show->id);
    //         $this->db->join('tb_users_pin', 'pin_userid = id');
    //         $cekpinnn = $this->db->get('tb_users');
    //         if ($cekpinnn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'id'            => $show->id,
    //                 'name'          => $show->name,
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function get_grdistributor()
    // {
    //     $this->db->where('id !=', userid());
    //     $this->db->where('user_status', 'grand-distributor');
    //     $this->db->where('user_kelurahan', $this->input->get('kelurahan_id'));
    //     $getuserrrr = $this->db->get('tb_users');

    //     foreach ($getuserrrr->result() as $getsss) {

    //         $this->db->where('pin_userid', $getsss->id);
    //         $this->db->where('pin_status', 'available');
    //         $cekpinn = $this->db->get('tb_users_pin');

    //         if ($cekpinn->num_rows() != 0) {
    //             $datasssss[] = array(
    //                 'user_code'             => $getsss->user_code,
    //                 'user_fullname'         => strtoupper($getsss->user_fullname),
    //             );
    //         }
    //     }
    //     Self::$data['result'] = $datasssss;
    //     return Self::$data;
    // }

    // function get_datapin()
    // {
    //     $stockpinn = array();

    //     $this->db->where('user_code', $this->input->get('penjual_id'));
    //     $getttttttt = $this->db->get('tb_users');
    //     if ($getttttttt->num_rows() != 0) {
    //         $userdata = $getttttttt->row();

    //         $getpaket = $this->db->get('tb_packages');
    //         foreach ($getpaket->result() as $show) {

    //             if ($userdata->id != (int)1) {

    //                 $this->db->where('pin_status', 'available');
    //                 $this->db->where('pin_userid', (int)$userdata->id);
    //                 $this->db->where('pin_package_id', (int)$show->package_id);
    //                 $this->db->join('tb_packages', 'pin_package_id = package_id');
    //                 $getpinnnn = $this->db->get('tb_users_pin');
    //                 $stockpinn[] = array(
    //                     'statusshow'    => true,
    //                     'paket'         => $show->package_name,
    //                     'stockpin'      => $getpinnnn->num_rows(),
    //                     'paket_code'    => $show->package_code,
    //                 );
    //             }
    //         }
    //     }

    //     Self::$data['result'] = $stockpinn;
    //     return Self::$data;
    // }

    // function getdetailpaket()
    // {
    //     $harga['harga'] = 0;
    //     $this->db->where('package_code', $this->input->get('paket_id'));
    //     $cekpaket = $this->db->get('tb_packages');
    //     if ($cekpaket->num_rows() != 0) {
    //         $harga['harga'] = $cekpaket->row()->package_price;
    //     }
    //     return $harga;
    // }

    // function hitungharga()
    // {
    //     $jmlpin     = preg_replace('/[^0-9.]+/', '', preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $this->input->get('jmlpin')));
    //     $harga_pin  = preg_replace('/[^0-9.]+/', '', preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $this->input->get('harga_pin')));

    //     $totalll    = (int)$harga_pin * (int)$jmlpin;

    //     $hitung['harga']    = $totalll;
    //     return $hitung;
    // }

    function getkab_stokis()
    {
        $datasssss = array();
        $getkabupatenn =  $this->db->query("SELECT * FROM wilayah WHERE CHAR_LENGTH(kode) = 5 AND LEFT(kode, 2) = '" . $this->input->get('provinsi_id') . "'");

        foreach ($getkabupatenn->result() as $show) {

            $datasssss[] = array(
                'kode'          => $show->kode,
                'nama'          => $show->nama,
            );
        }
        Self::$data['result'] = $datasssss;
        return Self::$data;
    }

    function get_stokis()
    {
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
        $this->db->where('userstokis_kota', $this->input->get('kabkota_id'));
        $this->db->where('userstokis_type', $whereee);
        $this->db->join('tb_users', 'id = userstokis_userid');
        $getuserrrr = $this->db->get('tb_users_stokis');

        // if ($getuserrrr->num_rows() != 0) {

        foreach ($getuserrrr->result() as $getsss) {

            $datasssss[] = array(
                'user_code'             => $getsss->user_code,
                'user_fullname'         => strtoupper($getsss->user_fullname),
            );
        }
        // } else {
        $datasssss[] = array(
            'user_code'             => 'ls7afu5yrnufkpjixcho1c0qijigduo8m9by6lpz7kvoq4kpb9vgzf4nesw1ghjo',
            'user_fullname'         => strtoupper('ADMINISTRATOR'),
        );
        // }
        array_push($datasssss);

        Self::$data['result'] = $datasssss;
        return Self::$data;
    }

    function get_package()
    {
        $stockpinn = array();

        $this->db->where('user_code', $this->input->get('penjual_id'));
        $getttttttt = $this->db->get('tb_users');
        if ($getttttttt->num_rows() != 0) {
            $userdata = $getttttttt->row();

            $getpaket = $this->db->get('tb_packages');
            foreach ($getpaket->result() as $show) {

                $this->db->where('pin_userid', (int)$userdata->id);
                $this->db->where('pin_package_id', (int)$show->package_id);
                $this->db->join('tb_packages', 'pin_package_id = package_id');
                $getpinnnn = $this->db->get('tb_users_pin');

                if ($this->input->get('penjual_id') == 'ls7afu5yrnufkpjixcho1c0qijigduo8m9by6lpz7kvoq4kpb9vgzf4nesw1ghjo') {
                    // JIKA ADMIN
                    $admin = false;
                    $stockkkk = 999999999;
                } else {
                    // JIKA BUKAN ADMIN
                    $admin = true;
                    $stockkkk = $getpinnnn->num_rows();
                }

                $stockpinn[] = array(
                    'statusshow'    => $admin,
                    'paket'         => $show->package_name,
                    'stockpin'      => $stockkkk,
                    'paket_code'    => $show->package_code,
                );
            }
        }

        Self::$data['result'] = $stockpinn;
        return Self::$data;
    }

    function getdetailpaket()
    {
        $harga['harga'] = 0;
        $discount = 0;
        $this->db->where('package_code', $this->input->get('paket_id'));
        $cekpaket = $this->db->get('tb_packages');
        if ($cekpaket->num_rows() != 0) {
            $paketORDER = $cekpaket->row();
            $this->db->where('userstokis_userid', userid());
            $cekSTOKIS = $this->db->get('tb_users_stokis');

            if ($cekSTOKIS->num_rows() != 0) {
                $dataSTOKIS = $cekSTOKIS->row();
                // JIKA ANDA STOKIS
                if ($dataSTOKIS->userstokis_type == 'mobilestokis') {
                    // JIKA ANDA MOBILE STOKIS
                    $discount = (5 / 100) * $paketORDER->package_price;

                    // if ($paketORDER->package_id == 6) {
                    //     // PAKET PLATINUM
                    //     $discount = (int)170000;
                    // } elseif ($paketORDER->package_id == 5) {
                    //     // PAKET DIAMOND
                    //     $discount = (int)85000;
                    // } elseif ($paketORDER->package_id == 4) {
                    //     // PAKET GOLD
                    //     $discount = (int)42500;
                    // } elseif ($paketORDER->package_id == 3) {
                    //     // PAKET SILVER
                    //     $discount = (int)25500;
                    // } elseif ($paketORDER->package_id == 2) {
                    //     // PAKET BRONZE
                    //     $discount = (int)8500;
                    // } else {
                    //     $discount = (int)4250;
                    // }
                } elseif ($dataSTOKIS->userstokis_type == 'masterstokis') {
                    // JIKA ANDA MASTER STOKIS
                    $discount = (7 / 100) * $paketORDER->package_price;

                    // if ($paketORDER->package_id == 6) {
                    //     // PAKET PLATINUM
                    //     $discount = (int)300000;
                    // } elseif ($paketORDER->package_id == 5) {
                    //     // PAKET DIAMOND
                    //     $discount = (int)150000;
                    // } elseif ($paketORDER->package_id == 4) {
                    //     // PAKET GOLD
                    //     $discount = (int)75000;
                    // } elseif ($paketORDER->package_id == 3) {
                    //     // PAKET SILVER
                    //     $discount = (int)45000;
                    // } elseif ($paketORDER->package_id == 3) {
                    //     // PAKET BRONZE
                    //     $discount = (int)15000;
                    // } else {
                    //     $discount = (int)7500;
                    // }
                }
            } else {
                $discount = (int)0;
            }

            $harga['harga'] = $cekpaket->row()->package_price - $discount;
        }
        return $harga;
    }

    function ngitung()
    {
        $userdata = userdata();

        $jmlpin     = preg_replace('/[^0-9.]+/', '', preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $this->input->get('jmlpin')));
        $harga_pin  = preg_replace('/[^0-9.]+/', '', preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $this->input->get('harga_pin')));

        $totalll    = (int)$harga_pin * (int)$jmlpin;

        $hitung['harga']    = $totalll;
        return $hitung;
    }
}
