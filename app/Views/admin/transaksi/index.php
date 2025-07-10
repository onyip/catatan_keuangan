<?= $this->extend('template/index') ?>
<?= $this->section('content') ?>
<main id="main-content">
    <!-- Halaman Transaksi -->
    <div id="transaksi">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Transaksi</h2>
            <button class="btn btn-primary" id="tambahTransaksi" data-bs-toggle="modal">
                <i class="bi bi-plus-circle me-2"></i>Tambah Transaksi
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <!-- Navigasi Tab Transaksi -->
                <ul class="nav nav-tabs mb-3" id="transaction-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tabel-filter active" id="trans-all-tab" data-code="2" data-bs-toggle="tab" type="button" role="tab">Semua</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tabel-filter" id="trans-in-tab" data-code="1" data-bs-toggle="tab" type="button" role="tab">Pemasukan</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tabel-filter" id="trans-out-tab" data-code="0" data-bs-toggle="tab" type="button" role="tab">Pengeluaran</button>
                    </li>
                </ul>
                <!-- Konten Tab Transaksi -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="transactionTable">
                        <thead>
                            <tr>
                                <th class="text-center" width="20%">Tanggal</th>
                                <th class="text-center" width="10%">Type</th>
                                <th class="text-center" width="25%">Keterangan</th>
                                <th class="text-center" width="15%">Kategori</th>
                                <th class="text-end">Nominal</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transaction-list-body">
                            <!-- Data transaksi akan dimuat di sini oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    var transaksiTable;
    var transaksiFilter = {
        transaksi_type: "2"
    };
    $(document).ready(function() {
        transaksiTable = $('#transactionTable').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('transaksi/list_datatables') ?>",
                "type": "POST",
                "data": {
                    "transaksi_type": function() {
                        return transaksiFilter.transaksi_type || "";
                    }
                },
            },
            "columnDefs": [{
                    className: "text-center",
                    "targets": [0, 1, 5]
                },
                {
                    className: "text-end",
                    "targets": [4]
                }
            ],
            "columns": [{
                    "data": "transaksi_tgl_formatted",
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
                    "data": "keterangan",
                    "orderable": false,
                },
                {
                    "data": "kategori_nm",
                    "orderable": false,
                },
                {
                    "data": "nominal",
                    "orderable": false,
                    "render": function(data, type, row) {
                        if (row.transaksi_type == 1) {
                            return '<span class="text-success">+ Rp' + parseInt(data).toLocaleString('id-ID') + '</span>'
                        } else {
                            return '<span class="text-danger">- Rp' + parseInt(data).toLocaleString('id-ID') + '</span>'
                        }
                    }
                },
                {
                    "data": "transaksi_id",
                    "orderable": false,
                    "render": function(data, type, row) {
                        return `
                        <a href="#" class="btn btn-xs btn-warning" onclick="editTransaksi('${data}')"><i class="text-white bi bi-pencil-fill"></i></a>
                            <a href="#" class="btn btn-xs btn-danger" onclick="deleteTransaksi('${data}')"><i class="text-white bi bi-trash-fill"></i></a>
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

        $('#tambahTransaksi').on('click', function() {
            $('#transactionForm')[0].reset();
            $('.btn-transaksi-type[data-code="0"]').click();
            $('#transactionModal').modal('show');
            $('#trans-keterangan').text('');
            $('#transaksi_id').val('');
        });

        $('.tabel-filter').on('click', function() {
            const code = $(this).data('code');
            transaksiFilter = {
                transaksi_type: "" + code + ""
            };
            transaksiTable.draw()
        });
    });

    function editTransaksi(id) {
        $.ajax({
            url: "<?= base_url('transaksi/get_data') ?>",
            type: "POST",
            data: {
                transaksi_id: id
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 'success') {
                    $('#transactionForm')[0].reset();
                    $('#trans-keterangan').text('');
                    $('#transaksi_id').val('');
                    $('#transaksi_id').val(response.data.transaksi_id);
                    $('.kategori-' + response.data.transaksi_type).val(response.data.kategori_id).trigger('change');
                    $('#trans-jumlah').val(response.data.nominal);
                    $('#trans-keterangan').text(response.data.keterangan);
                    $('#trans-tanggal').val(response.data.transaksi_tgl);
                    $('.btn-transaksi-type[data-code="' + response.data.transaksi_type + '"]').click();
                    $('#transactionModal').modal('show');
                } else {
                    toas('error', response.message);
                }
            }
        })
    }

    function deleteTransaksi(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success mx-2",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: "Apakah Anda ya?",
            text: "Transaksi yang dihapus tidak bisa dikembalikan!!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!!",
            cancelButtonText: "Tidak!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('transaksi/delete') ?>",
                    type: "POST",
                    data: {
                        transaksi_id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        toas(response.status, response.message);
                        transaksiTable.draw();
                    },
                    error: function() {
                        toas('error', 'Gagal menghapus transaksi. Silakan coba lagi.');
                    }
                });
            }
        });
    }
</script>
<?= $this->include('admin/transaksi/form') ?>
<?= $this->endSection() ?>