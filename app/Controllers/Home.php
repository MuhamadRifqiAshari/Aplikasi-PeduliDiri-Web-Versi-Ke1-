<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('index');
    }
    public function register()
    {
        return view('register');
    }
    public function proses_register()
    {
        $nik = $this->request->getVar('nik');
        $nama = $this->request->getVar('nama');
        $password = sha1($this->request->getVar('password'));

        $data = file("config.txt", FILE_IGNORE_NEW_LINES);
        foreach ($data as $value) {
            $pisah = explode("|", $value);
            if ($nik == $pisah['0']) {
                $cek = TRUE;
            } else {
                $cek = FALSE;
            }
        }

        if ($cek) {
            session()->set('gagal', 'Akun Sudah Terdaftar');
            $session = session();
            $session->markAsFlashdata('gagal');
            return redirect()->to(base_url('register'));
        } else {
            $format = "\n$nik|$nama|$password";
            $file = fopen('config.txt', 'a');
            fwrite($file, $format);
            fclose($file);
            session()->set('pendaftaran', 'Selamat Pendaftaran Anda Berhasil');
            $session = session();
            $session->markAsFlashdata('pendaftaran');
            return redirect()->to(base_url('login'));
        }
    }

    public function auth_login()
    {
        $nik = $this->request->getVar('nik');
        $password = sha1($this->request->getVar('password'));
        $data = file("config.txt", FILE_IGNORE_NEW_LINES);
        foreach ($data as $value) {
            $pisah = explode("|", $value);
            if ($nik == $pisah['0'] && $password == $pisah['2']) {
                $cek = TRUE;
            } else {
                $cek = FALSE;
            }
        }
        if ($cek) {
            session()->set('nik', $pisah['0']);
            session()->set('nama', $pisah['1']);
            session()->set('berhasil', "Login Berhasil");
            $session = session();
            $session->markAsFlashdata('berhasil');
            return redirect()->to(base_url('dashboard'));
        } else {

            session()->set('failed', "Username / Password Salah");
            $session = session();
            $session->markAsFlashdata('failed');
            return redirect()->to(base_url('login'));
        }
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
