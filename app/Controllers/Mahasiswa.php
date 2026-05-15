<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Mahasiswa extends ResourceController
{
    use ResponseTrait;

    /**
     * Instance model mahasiswa
     */
    protected $model;

    public function __construct()
    {
        $this->model = new MahasiswaModel();

        // PERBAIKAN:
        // Header JSON dihapus karena method index()
        // mengembalikan tampilan HTML, bukan JSON.
        // Jika dipaksa JSON dapat menyebabkan tampilan error.
        
        // header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * Menampilkan halaman utama
     */
    public function index()
    {
        $data = [
            'title' => 'CRUD Mahasiswa'
        ];

        return view('mahasiswa_view', $data);
    }

    /**
     * Mengambil seluruh data mahasiswa
     */
    public function getData()
    {
        try {

            // PERBAIKAN:
            // Mengambil data mahasiswa melalui Model
            // agar sesuai konsep MVC.
            $mahasiswa = $this->model->getMahasiswa();

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Data berhasil diambil',
                'data'    => $mahasiswa
            ]); 
 
        } catch (\Exception $e) {

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Menyimpan data mahasiswa
     */
    public function store()
    {
        // PERBAIKAN:
        // Mengambil data menggunakan POST
        // agar lebih aman dibanding GET.
        $data = $this->request->getPost();

        // Validasi data
        if (!$this->model->validate($data)) {

            return $this->response->setStatusCode(422)->setJSON([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->model->errors()
            ]);
        }

        try {

            // PERBAIKAN:
            // Data disimpan melalui Model
            // bukan query langsung di View.
            if ($this->model->insert($data)) {

                return $this->response->setJSON([
                    'status'  => true,
                    'message' => 'Data berhasil ditambahkan'
                ]);

            } else {

                return $this->response->setStatusCode(400)->setJSON([
                    'status'  => false,
                    'message' => 'Gagal menambahkan data'
                ]);
            }

        } catch (\Exception $e) {

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Menampilkan detail mahasiswa berdasarkan ID
     */
    public function show($id = null)
    {
        try {

            $mahasiswa = $this->model->find($id);

            // PERBAIKAN:
            // Menambahkan pengecekan data
            // agar tidak terjadi error ketika ID tidak ditemukan.
            if (!$mahasiswa) {

                return $this->response->setStatusCode(404)->setJSON([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            return $this->response->setJSON([
                'status' => true,
                'data'   => $mahasiswa
            ]);

        } catch (\Exception $e) {

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update data mahasiswa
     */
    public function update($id = null)
    {
        // Ambil ID dan data POST
        $id   = $id ?? $this->request->getPost('id');
        $data = $this->request->getPost();

        // Validasi ID
        if (!$id) {

            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'ID tidak valid'
            ]);
        }

        // Cek data tersedia
        if (!$this->model->find($id)) {

            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // PERBAIKAN:
        // Menghapus field ID agar primary key
        // tidak ikut terupdate.
        unset($data['id']);

        // Validasi data
        if (!$this->model->validate($data)) {

            return $this->response->setStatusCode(422)->setJSON([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->model->errors()
            ]);
        }

        try {

            if ($this->model->update($id, $data)) {

                return $this->response->setJSON([
                    'status'  => true,
                    'message' => 'Data berhasil diperbarui'
                ]);

            } else {

                return $this->response->setStatusCode(400)->setJSON([
                    'status'  => false,
                    'message' => 'Gagal memperbarui data'
                ]);
            }

        } catch (\Exception $e) {

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Menghapus data mahasiswa
     */
    public function delete($id = null)
    {
        // Ambil ID
        $id = $id ?? $this->request->getPost('id');

        // Validasi ID
        if (!$id) {

            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'ID tidak valid'
            ]);
        }

        // Cek apakah data tersedia
        if (!$this->model->find($id)) {

            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        try {

            // PERBAIKAN:
            // Menghapus data melalui Model
            // agar sesuai konsep MVC.
            if ($this->model->delete($id)) {

                return $this->response->setJSON([
                    'status'  => true,
                    'message' => 'Data berhasil dihapus'
                ]);

            } else {

                return $this->response->setStatusCode(400)->setJSON([
                    'status'  => false,
                    'message' => 'Gagal menghapus data'
                ]);
            }

        } catch (\Exception $e) {

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}