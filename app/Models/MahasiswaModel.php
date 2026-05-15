<?php

namespace App\Models;

use CodeIgniter\Model;

/* Perbaikan: Model dipisahkan dari Controller sesuai konsep MVC */
class MahasiswaModel extends Model
{
    /* Perbaikan: Menentukan nama tabel database */
    protected $table = 'mahasiswa';

    /* Perbaikan: Menentukan primary key tabel */
    protected $primaryKey = 'id';

    /* Perbaikan: Menggunakan return type array agar data mudah diproses */
    protected $returnType = 'array';

    /* Perbaikan: Menentukan field yang boleh diinput untuk keamanan mass assignment */
    protected $allowedFields = ['nim', 'nama', 'jurusan'];

    /* Perbaikan: Mengaktifkan timestamps otomatis */
    protected $useTimestamps = true;

    /* Perbaikan: Menentukan format tanggal */
    protected $dateFormat = 'datetime';

    /* Perbaikan: Menentukan field created_at dan updated_at */
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /* Perbaikan: Menambahkan validasi input data */
    protected $validationRules = [
        'nim'     => 'required|string|min_length[8]|max_length[12]|is_unique[mahasiswa.nim,id,{id}]',
        'nama'    => 'required|string|min_length[3]|max_length[100]',
        'jurusan' => 'required|string|min_length[3]|max_length[50]',
    ];

    /* Perbaikan: Menambahkan pesan validasi custom agar lebih user friendly */
    protected $validationMessages = [
        'nim' => [
            'required'   => 'NIM harus diisi',
            'is_unique'  => 'NIM sudah terdaftar',
            'min_length' => 'NIM minimal 8 karakter',
        ],
        'nama' => [
            'required'   => 'Nama harus diisi',
            'min_length' => 'Nama minimal 3 karakter',
        ],
        'jurusan' => [
            'required' => 'Jurusan harus diisi',
        ],
    ];

    /* Perbaikan: Mengaktifkan validasi otomatis */
    protected $skipValidation = false;

    /* Perbaikan: Membuat function khusus untuk mengambil data mahasiswa */
    public function getMahasiswa($limit = 10, $offset = 0)
    {
        return $this->orderBy('id', 'DESC')
                    ->limit($limit, $offset)
                    ->findAll();
    }

    /* Perbaikan: Membuat function mengambil data berdasarkan ID */
    public function getMahasiswaById($id)
    {
        return $this->find($id);
    }

    /* Perbaikan: Membuat function menghitung total data mahasiswa */
    public function getTotalMahasiswa()
    {
        return $this->countAll();
    }

    /* Perbaikan: Membuat function tambah data mahasiswa */
    public function tambahMahasiswa($data)
    {
        return $this->insert($data);
    }

    /* Perbaikan: Membuat function update data mahasiswa */
    public function updateMahasiswa($id, $data)
    {
        return $this->update($id, $data);
    }

    /* Perbaikan: Membuat function hapus data mahasiswa */
    public function hapusMahasiswa($id)
    {
        return $this->delete($id);
    }

    /* Perbaikan: Menambahkan fitur pencarian data mahasiswa */
    public function searchMahasiswa($keyword)
    {
        return $this->like('nama', $keyword)
                    ->orLike('nim', $keyword)
                    ->orLike('jurusan', $keyword)
                    ->findAll();
    }
}