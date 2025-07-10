<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="categoryForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="kategori_id" name="kategori_id">
                    <div class="mb-3">
                        <label for="kategori_nm" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="kategori_nm" name="kategori_nm" placeholder="Contoh: Transportasi" required>
                    </div>
                    <div class="mb-3">
                        <label for="transaksi_type" class="form-label">Jenis Kategori</label>
                        <select class="form-select" id="transaksi_type" name="transaksi_type" required>
                            <option value="1">Pemasukan</option>
                            <option value="0">Pengeluaran</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#categoryForm').on('submit', function(e) {
            e.preventDefault();
            let data = $('#categoryForm').serializeArray();
            $.ajax({
                url: '<?= base_url('kategori/save') ?>',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    toas(response.status, response.message);
                }
            }).done(function() {
                $('#categoryModal').modal('hide');
                $('#categoryForm')[0].reset();
                $('#kategori_id').val('');
                kategoriTable.draw();
            }).fail(function(xhr, status, error) {
                console.error('Error saving transaction:', error);
                alert('Gagal menyimpan kategori. Silakan coba lagi.');
            });

        })
    })
</script>