<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="transactionForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionModalLabel">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="transaksi_id" name="transaksi_id">

                    <!-- Tab Pemasukan/Pengeluaran -->
                    <ul class="nav nav-tabs nav-fill mb-4" id="modalTransactionTypeTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-transaksi-type active" id="modal-pengeluaran-tab" data-type="pengeluaran" data-code="0" data-bs-toggle="tab" type="button" role="tab" data-trans-type="out">Pengeluaran</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-transaksi-type" id="modal-pemasukan-tab" data-type="pemasukan" data-code="1" data-bs-toggle="tab" type="button" role="tab" data-trans-type="in">Pemasukan</button>
                        </li>
                    </ul>

                    <div class="mb-3">
                        <label for="trans-kategori" class="form-label">Kategori</label>
                        <select class="form-select kategori-0" id="trans-kategori-pengeluaran" name="kategori_id" required>
                            <?php foreach ($kategori_pengeluaran as  $pengeluaran): ?>
                                <option value="<?= @$pengeluaran['kategori_id']; ?>"><?= @$pengeluaran['kategori_nm']; ?></option>
                            <?php endforeach ?>
                        </select>
                        <select class="form-select kategori-1" id="trans-kategori-pemasukan" name="kategori_id" required>
                            <?php foreach ($kategori_pemasukan as  $pemasukan): ?>
                                <option value="<?= @$pemasukan['kategori_id']; ?>"><?= @$pemasukan['kategori_nm']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="trans-jumlah" class="form-label">Jumlah (Rp)</label>
                        <input type="number" class="form-control" id="trans-jumlah" name="nominal" placeholder="Contoh: 50000" required>
                    </div>
                    <div class="mb-3">
                        <label for="trans-keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="trans-keterangan" name="keterangan" rows="3" placeholder="Contoh: Gaji bulanan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="trans-tanggal" class="form-label">Tanggal</label>
                        <input type="datetime-local" class="form-control" id="trans-tanggal" name="transaksi_tgl" required>
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
        $('#transactionModal').on('shown.bs.modal', function(e) {
            let type = $('.btn-transaksi-type.active').data('type');
            if (type === 'pemasukan') {
                $('#trans-kategori-pemasukan').show();
                $('#trans-kategori-pemasukan').removeAttr('disabled');
                $('#trans-kategori-pengeluaran').hide();
                $('#trans-kategori-pengeluaran').attr('disabled', true);
            } else {
                $('#trans-kategori-pemasukan').hide();
                $('#trans-kategori-pemasukan').attr('disabled', true);
                $('#trans-kategori-pengeluaran').show();
                $('#trans-kategori-pengeluaran').removeAttr('disabled');
            }
        })

        $('.btn-transaksi-type').on('click', function() {
            let type = $(this).data('type');
            if (type === 'pemasukan') {
                $('#trans-kategori-pemasukan').show();
                $('#trans-kategori-pemasukan').removeAttr('disabled');
                $('#trans-kategori-pengeluaran').hide();
                $('#trans-kategori-pengeluaran').attr('disabled', true);
            } else {
                $('#trans-kategori-pemasukan').hide();
                $('#trans-kategori-pemasukan').attr('disabled', true);
                $('#trans-kategori-pengeluaran').show();
                $('#trans-kategori-pengeluaran').removeAttr('disabled');
            }
        });

        $('#transactionForm').on('submit', function(e) {
            e.preventDefault();
            let data = $('#transactionForm').serializeArray();
            data.push({
                name: 'transaksi_type',
                value: function() {
                    return $('.btn-transaksi-type.active').data('code');
                }()
            });

            $.ajax({
                url: '<?= base_url('transaksi/save') ?>',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    toas(response.status, response.message);
                }
            }).done(function() {
                $('#transactionModal').modal('hide');
                $('#transactionForm')[0].reset();
                transaksiTable.draw();
            }).fail(function(xhr, status, error) {
                console.error('Error saving transaction:', error);
                alert('Gagal menyimpan transaksi. Silakan coba lagi.');
            });

        })
    })
</script>