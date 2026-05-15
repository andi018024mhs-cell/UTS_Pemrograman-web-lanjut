<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> - CodeIgniter 4 CRUD AJAX</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #007bff;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .container-main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 15px;
        }

        .card {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: none;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px;
        }

        .btn-custom {
            border-radius: 5px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
        }

        .table-hover tbody tr:hover {
            background-color: #f9f9f9;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .loading {
            display: none;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .alert {
            border: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">
                <i class="fas fa-graduation-cap"></i> UTS CRUD Mahasiswa -
            </span>
        </div>
    </nav>

    <!-- MAIN CONTAINER -->
    <div class="container-main">
        <!-- ALERT PLACEHOLDER -->
        <div id="alertContainer"></div>

        <!-- CARD UTAMA -->
        <div class="card fade-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-table me-2"></i>Data Mahasiswa
                </h4>
                <button class="btn btn-light btn-custom" data-bs-toggle="modal" data-bs-target="#modalMahasiswa" onclick="resetForm()">
                    <i class="fas fa-plus me-2"></i>Tambah Data
                </button>
            </div>
            
            <div class="card-body">
                <!-- LOADING SPINNER -->
                <div class="loading" id="loadingSpinner">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data...</p>
                    </div>
                </div>

                <!-- TABLE MAHASISWA -->
                <div id="tableContainer" style="display: none;">
                    <table class="table table-hover table-striped" id="tabelMahasiswa">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 15%;">NIM</th>
                                <th style="width: 35%;">Nama</th>
                                <th style="width: 30%;">Jurusan</th>
                                <th style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data diisi via AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- NO DATA MESSAGE -->
                <div id="noDataMessage" class="no-data">
                    <i class="fas fa-inbox" style="font-size: 48px; color: #ddd;"></i>
                    <p class="mt-3">Tidak ada data mahasiswa. Klik tombol "Tambah Data" untuk mulai.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FORM TAMBAH/EDIT -->
    <div class="modal fade" id="modalMahasiswa" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Data Mahasiswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- ALERT DI MODAL -->
                    <div id="modalAlert"></div>

                    <form id="formMahasiswa">
                        <!-- Hidden input untuk ID (untuk keperluan edit) -->
                        <input type="hidden" id="id" name="id">

                        <!-- Input NIM -->
                        <div class="form-group mb-3">
                            <label for="nim">Nomor Induk Mahasiswa (NIM) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nim" name="nim" 
                                   placeholder="Contoh: 2401001" maxlength="12" required>
                            <small class="form-text text-muted">Format: 10 digit angka (atau sesuai ketentuan kampus)</small>
                            <div class="invalid-feedback" id="nimError"></div>
                        </div>

                        <!-- Input Nama -->
                        <div class="form-group mb-3">
                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   placeholder="Contoh: Ahmad Ramadhan" maxlength="100" required>
                            <div class="invalid-feedback" id="namaError"></div>
                        </div>

                        <!-- Input Jurusan -->
                        <div class="form-group mb-3">
                            <label for="jurusan">Jurusan <span class="text-danger">*</span></label>
                            <select class="form-control" id="jurusan" name="jurusan" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="Teknik Informatika">Teknik Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Teknik Komputer">Teknik Komputer</option>
                                <option value="Teknik Elektro">Teknik Elektro</option>
                                <option value="Teknik Mesin">Teknik Mesin</option>
                                <option value="Manajemen">Manajemen</option>
                                <option value="Akuntansi">Akuntansi</option>
                            </select>
                            <div class="invalid-feedback" id="jurusanError"></div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="btnSimpan" onclick="simpanData()">
                        <i class="fas fa-save me-2"></i><span id="btnSimpanText">Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CONFIRM DELETE -->
    <div class="modal fade" id="modalConfirmDelete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-trash me-2"></i>Konfirmasi Hapus Data
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmDeleteMessage">Apakah Anda yakin ingin menghapus data mahasiswa ini?</p>
                    <div class="alert alert-info mb-0">
                        <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash me-2"></i>Ya, Hapus Data
                    </button>
                </div>
            </div>
        </div>
    </div>





    <!-- ///////////////    SCRIPTS       ///////////////////-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    const BASE_URL = '<?= base_url('mahasiswa'); ?>';

    let deleteId = null;
    let isEditMode = false;

    function showAlert(message, type = 'success', containerId = 'alertContainer') {
        const alertHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <strong>${type === 'success' ? '<i class="fas fa-check-circle"></i>' : 
                         type === 'danger' ? '<i class="fas fa-exclamation-circle"></i>' : 
                         '<i class="fas fa-info-circle"></i>'}</strong> 
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $(`#${containerId}`).html(alertHTML);
    }

    function showLoading() {
        $('#loadingSpinner').show();
        $('#tableContainer').hide();
        $('#noDataMessage').hide();
    }

    function hideLoading() {
        $('#loadingSpinner').hide();
    }

    function renderTable(data) {
        const tableBody = $('#tableBody');
        tableBody.empty();

        if (data.length === 0) {
            $('#tableContainer').hide();
            $('#noDataMessage').show();
            return;
        }

        let no = 1;

        data.forEach(function(item) {

            const row = `
                <tr class="fade-in">
                    <td>${no++}</td>
                    <td><code>${item.nim}</code></td>
                    <td>${item.nama}</td>
                    <td><span class="badge bg-info badge-custom">${item.jurusan}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-warning btn-custom"
                                    onclick="editData(${item.id})">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-sm btn-danger btn-custom"
                                    onclick="hapusData(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;

            tableBody.append(row);
        });

        $('#tableContainer').show();
        $('#noDataMessage').hide();
    }

    function loadData() {

        showLoading();

        $.ajax({

            // PERBAIKAN:
            // URL AJAX disesuaikan dengan route
            // pada controller CodeIgniter.
            url: BASE_URL + '/getData',

            type: 'GET',

            dataType: 'JSON',

            success: function(response) {

                hideLoading();

                if (response.status) {

                    renderTable(response.data);

                } else {

                    showAlert(response.message || 'Gagal memuat data', 'danger');
                }
            },

            error: function(xhr, status, error) {

                hideLoading();

                console.error('AJAX Error:', error);
 
                showAlert('Gagal memuat data dari server', 'danger');
            }
        });
    }

    function simpanData() {

        $('.invalid-feedback').empty();
        $('.form-control').removeClass('is-invalid');

        const id       = $('#id').val();
        const nim      = $('#nim').val().trim();
        const nama     = $('#nama').val().trim();
        const jurusan  = $('#jurusan').val().trim();

        let isValid = true;

        if (!nim || nim.length < 8 || nim.length > 12) {

            $('#nim').addClass('is-invalid');
            $('#nimError').text('NIM harus diisi dengan 8-12 karakter');

            isValid = false;
        }

        if (!nama || nama.length < 3 || nama.length > 100) {

            $('#nama').addClass('is-invalid');
            $('#namaError').text('Nama harus diisi dengan minimal 3 karakter');

            isValid = false;
        }

        if (!jurusan) {

            $('#jurusan').addClass('is-invalid');
            $('#jurusanError').text('Jurusan harus dipilih');

            isValid = false;
        }

        if (!isValid) {

            showAlert('Mohon lengkapi form dengan benar', 'warning', 'modalAlert');

            return;
        }

        const data = {
            nim: nim,
            nama: nama,
            jurusan: jurusan
        };

        if (id) {
            data.id = id;
        }

        $('#btnSimpan')
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');

        // PERBAIKAN:
        // Method POST digunakan untuk proses
        // insert dan update data.
        const url = id ? (BASE_URL + '/update') : (BASE_URL + '/store');

        $.ajax({

            url: url,

            type: 'POST',

            dataType: 'JSON',

            data: data,

            success: function(response) {

                $('#btnSimpan')
                    .prop('disabled', false)
                    .html('<i class="fas fa-save me-2"></i><span id="btnSimpanText">Simpan</span>');

                if (response.status) {

                    showAlert(response.message, 'success', 'alertContainer');

                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById('modalMahasiswa')
                    );

                    modal.hide();

                    loadData();

                } else {

                    // PERBAIKAN:
                    // Menambahkan validasi error
                    // agar user mengetahui input yang salah.
                    if (response.errors) {

                        Object.keys(response.errors).forEach(function(field) {

                            const errorId = field + 'Error';
                            const inputId = field;

                            $(`#${inputId}`).addClass('is-invalid');
                            $(`#${errorId}`).text(response.errors[field]);
                        });
                    }

                    showAlert(
                        response.message || 'Gagal menyimpan data',
                        'danger',
                        'modalAlert'
                    );
                }
            },

            error: function(xhr, status, error) {

                $('#btnSimpan')
                    .prop('disabled', false)
                    .html('<i class="fas fa-save me-2"></i><span id="btnSimpanText">Simpan</span>');

                console.error('AJAX Error:', xhr.responseJSON);

                showAlert('Terjadi kesalahan: ' + error, 'danger', 'modalAlert');
            }
        });
    }

    function editData(id) {

        $.ajax({

            // PERBAIKAN:
            // URL disesuaikan dengan method show()
            // pada controller.
            url: BASE_URL + '/show/' + id,

            type: 'GET',

            dataType: 'JSON',

            success: function(response) {

                if (response.status) {

                    $('#id').val(response.data.id);
                    $('#nim').val(response.data.nim);
                    $('#nama').val(response.data.nama);
                    $('#jurusan').val(response.data.jurusan);

                    $('#modalLabel')
                        .html('<i class="fas fa-edit me-2"></i>Edit Data Mahasiswa');

                    $('#btnSimpanText').text('Update');

                    isEditMode = true;

                    new bootstrap.Modal(
                        document.getElementById('modalMahasiswa')
                    ).show();

                } else {

                    showAlert(
                        'Gagal mengambil data: ' + response.message,
                        'danger'
                    );
                }
            },

            error: function(xhr, status, error) {

                console.error('AJAX Error:', error);

                showAlert('Gagal mengambil data dari server');
            }
        });
    }

    function hapusData(id) {

        deleteId = id;

        $.ajax({

            url: BASE_URL + '/show/' + id,

            type: 'GET',

            dataType: 'JSON',

            success: function(response) {

                if (response.status) {

                    const nama = response.data.nama;

                    $('#confirmDeleteMessage').text(
                        `Apakah Anda yakin ingin menghapus data mahasiswa: "${nama}"?`
                    );

                    new bootstrap.Modal(
                        document.getElementById('modalConfirmDelete')
                    ).show();
                }
            }
        });
    }

    function confirmDelete() {

        if (!deleteId) return;

        $.ajax({

            // PERBAIKAN:
            // Request delete menggunakan POST
            // agar data ID dapat dikirim dengan aman.
            url: BASE_URL + '/delete',

            type: 'POST',

            dataType: 'JSON',

            data: {
                id: deleteId
            },

            success: function(response) {

                bootstrap.Modal.getInstance(
                    document.getElementById('modalConfirmDelete')
                ).hide();

                if (response.status) {

                    showAlert(response.message, 'success', 'alertContainer');

                    loadData();

                } else {

                    showAlert(
                        response.message || 'Gagal menghapus data',
                        'danger',
                        'alertContainer'
                    );
                }
            },

            error: function(xhr, status, error) {

                console.error('AJAX Error:', error);

                showAlert(
                    'Gagal menghapus data: ' + error,
                    'danger',
                    'alertContainer'
                );
            }
        });

        deleteId = null;
    }

    function resetForm() {

        $('#formMahasiswa')[0].reset();

        $('#id').val('');

        $('#modalLabel')
            .html('<i class="fas fa-plus-circle me-2"></i>Tambah Data Mahasiswa');

        $('#btnSimpanText').text('Simpan');

        $('.invalid-feedback').empty();

        $('.form-control').removeClass('is-invalid');

        $('#modalAlert').empty();

        isEditMode = false;
    }

    $(document).ready(function() {

        // PERBAIKAN:
        // Data otomatis dimuat saat halaman dibuka.
        loadData();

    });
</script>
</body>
</html>
