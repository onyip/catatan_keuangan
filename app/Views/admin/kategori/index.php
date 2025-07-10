<?= $this->extend('template/index') ?>
<?= $this->section('content') ?>
<main id="main-content">
    <div id="kategori">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kelola Kategori</h2>
            <button class="btn btn-primary" id="tambahKategori" data-bs-toggle="modal">
                <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs mb-3" id="category-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tabel-filter active" data-code="2" id="cat-all-tab" data-bs-toggle="tab" type="button" role="tab">Semua</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tabel-filter" data-code="1" id="cat-in-tab" data-bs-toggle="tab" type="button" role="tab">Pemasukan</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tabel-filter" data-code="0" id="cat-out-tab" data-bs-toggle="tab" type="button" role="tab">Pengeluaran</button>
                    </li>
                </ul>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="kategoriTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Type</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="category-list-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    var kategoriTable;
    var kategoriFilter = {
        transaksi_type: "2"
    };
    $(document).ready(function() {
        kategoriTable = $('#kategoriTable').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('kategori/list_datatables') ?>",
                "type": "POST",
                "data": {
                    "transaksi_type": function() {
                        return kategoriFilter.transaksi_type || "";
                    }
                },
            },
            "columnDefs": [{
                className: "text-center",
                "targets": [0, 2, 3]
            }],
            "columns": [{
                    "data": "kategori_id",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1 + meta.settings._iDisplayStart;
                    },
                },
                {
                    "data": "kategori_nm",
                    "orderable": false,
                },
                {
                    "data": "transaksi_type",
                    "orderable": false,
                    "render": function(data, type, row) {
                        var label = `<span class="badge ${data == 1 ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis'}">
                                ${data == 1 ? 'Pemasukan' : 'Pengeluaran'}
                            </span>`;
                        return label;
                    }
                },
                {
                    "data": "kategori_id",
                    "orderable": false,
                    "render": function(data, type, row) {
                        return `
                        <a href="#" class="btn btn-xs btn-warning" onclick="editKategori('${data}')"><i class="text-white bi bi-pencil-fill"></i></a>
                            <a href="#" class="btn btn-xs btn-danger" onclick="deleteKategori('${data}')"><i class="text-white bi bi-trash-fill"></i></a>
                            `;
                    }
                }
            ],
            "language": {
                "url": "<?= base_url(); ?>assets/lib/datatables/id.json",
                "paginate": {
                    "first": "&laquo;",
                    "last": "&raquo;",
                    "next": "&rsaquo;",
                    "previous": "&lsaquo;"
                }
            },
            "pagingType": "simple_numbers",
        });

        $('#tambahKategori').on('click', function() {
            $('#categoryForm')[0].reset();
            $('#kategori_id').val('');
            $('#categoryModal').modal('show');
        });

        $('.tabel-filter').on('click', function() {
            const code = $(this).data('code');
            kategoriFilter = {
                transaksi_type: "" + code + ""
            };
            kategoriTable.draw()
        });
    });

    function editKategori(id) {
        $.ajax({
            url: "<?= base_url('kategori/get_data') ?>",
            type: "POST",
            data: {
                kategori_id: id
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 'success') {
                    $('#categoryForm')[0].reset();
                    $('#kategori_id').val('');
                    $('#kategori_id').val(response.data.kategori_id);
                    $('#kategori_nm').val(response.data.kategori_nm);
                    $('#transaksi_type').val(response.data.transaksi_type).trigger('change');
                    $('#categoryModal').modal('show');
                } else {
                    toas('error', response.message);
                }
            }
        })
    }

    function deleteKategori(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success mx-2",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: "Apakah Anda ya?",
            text: "Kategori yang dihapus tidak bisa dikembalikan!!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!!",
            cancelButtonText: "Tidak!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('kategori/delete') ?>",
                    type: "POST",
                    data: {
                        kategori_id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        toas(response.status, response.message);
                        kategoriTable.draw();
                    },
                    error: function() {
                        toas('error', 'Gagal menghapus kategori. Silakan coba lagi.');
                    }
                });
            }
        });
    }
</script>
<?= $this->include('admin/kategori/form') ?>
<?= $this->endSection() ?>