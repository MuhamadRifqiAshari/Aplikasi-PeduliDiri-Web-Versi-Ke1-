<?php

namespace App\Controllers;

class User extends BaseController
{
    public function index()
    {
        if (session('nik') == NULL) {
            return redirect()->to(base_url('login'));
        } else {
            return view('user/index');
        }
    }
    public function catatan()
    {
        if (session('nik') == NULL) {
            return redirect()->to(base_url('login'));
        } else {
            return view('user/catatan');
        }
    }
    public function riwayat()
    {
        if (session('nik') == NULL) {
            return redirect()->to(base_url('login'));
        } else {
            $data = [
                'data' => file('catatan.txt', FILE_IGNORE_NEW_LINES)
            ];
            return view('user/riwayat', $data);
        }
    }

    public function simpan_catatan()
    {
        $tanggal = $this->request->getVar('tanggal');
        $jam = $this->request->getVar('jam');
        $lokasi = $this->request->getVar('lokasi');
        $suhu = $this->request->getVar('suhu');
        $nama = session('nama');
        $nik = session('nik');
        $format = "\n$nik|$nama|$tanggal|$jam|$lokasi|$suhu °";
        $file = fopen('catatan.txt', 'a');
        fwrite($file, $format);
        fclose($file);
        session()->set('simpan_catatan', 'Catatan Berhasil Disimpan');
        $session = session();
        $session->markAsFlashdata('simpan_catatan');
        return redirect()->to(base_url('riwayat'));
    }
}
