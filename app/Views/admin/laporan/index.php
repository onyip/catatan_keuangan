<?= $this->extend('template/index') ?>
<?= $this->section('content') ?>
<main id="main-content">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Laporan per Kategori</h5>
            <!-- Filter Tanggal -->
            <div class="row g-3 align-items-center mb-4">
                <div class="col-md">
                    <label for="startDate" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="startDate" value="<?= ((new DateTime())->modify('-7 days'))->format('Y-m-d'); ?>">
                </div>
                <div class="col-md">
                    <label for="endDate" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="endDate" value="<?= (new DateTime())->format('Y-m-d'); ?>">
                </div>
                <div class="col-md-auto d-flex align-items-end">
                    <button class="btn btn-primary mt-4" onclick="get_data_per_kategori()">Filter</button>
                </div>
            </div>

            <!-- Grafik -->
            <div class="row text-center mb-4">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h6>Total Pengeluaran per Kategori</h6>
                    <div class="chart-container">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h6>Total Pemasukan per Kategori</h6>
                    <div class="chart-container">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tabel Ringkasan -->
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title mt-4 mb-3">Ringkasan Pengeluaran</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody id="table-pengeluaran-body">
                            </tbody>
                            <tfoot id="table-pengeluaran-foot"></tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="card-title mt-4 mb-3">Ringkasan Pemasukan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody id="table-pemasukan-body">
                            </tbody>
                            <tfoot id="table-pemasukan-foot"></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    $(document).ready(function() {
        get_data_per_kategori();
    });

    let expenseChartInstance, incomeChartInstance, reportChartInstance;

    function get_data_per_kategori() {
        $.ajax({
            url: '<?= base_url('laporan/data_per_kategori') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                startDate: $('#startDate').val(),
                endDate: $('#endDate').val()
            },
            success: function(response) {
                if (response.status === 'success') {
                    if (expenseChartInstance) {
                        expenseChartInstance.destroy();
                    }
                    if (incomeChartInstance) {
                        incomeChartInstance.destroy();
                    }
                    renderCategoryCharts(response.data);
                    renderCategorySummary(response.data);
                } else {
                    console.error('Error fetching data:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }

    function renderCategoryCharts(transaksi) {
        const expenseCtx = $('#expenseChart').get(0).getContext('2d');
        const incomeCtx = $('#incomeChart').get(0).getContext('2d');
        const pengeluaranData = {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: ['#dc3545', '#fd7e14', '#ffc107', '#6f42c1', '#20c997']
            }]
        };
        const pemasukanData = {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: ['#198754', '#0d6efd', '#0dcaf0', '#212529', '#6610f2']
            }]
        };

        const pengeluaranTotal = {};
        const pemasukanTotal = {};
        transaksi.pemasukan.forEach(t => {
            if (!pemasukanTotal[t.kategori_nm]) {
                pemasukanTotal[t.kategori_nm] = 0;
            }
            pemasukanTotal[t.kategori_nm] += t.jumlah;
        });
        transaksi.pengeluaran.forEach(t => {
            if (!pengeluaranTotal[t.kategori_nm]) {
                pengeluaranTotal[t.kategori_nm] = 0;
            }
            pengeluaranTotal[t.kategori_nm] += t.jumlah;
        });

        for (const name in pengeluaranTotal) {
            pengeluaranData.labels.push(name);
            pengeluaranData.datasets[0].data.push(pengeluaranTotal[name]);
        }
        for (const name in pemasukanTotal) {
            pemasukanData.labels.push(name);
            pemasukanData.datasets[0].data.push(pemasukanTotal[name]);
        }

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: context => {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(2) + '%' : '0%';
                            return `${label}: Rp ${value.toLocaleString('id-ID')} (${percentage})`;
                        }
                    }
                }
            }
        };

        if (pengeluaranData.labels.length > 0) {
            expenseChartInstance = new Chart(expenseCtx, {
                type: 'doughnut',
                data: pengeluaranData,
                options: chartOptions
            });
        } else {
            expenseCtx.clearRect(0, 0, expenseCtx.canvas.width, expenseCtx.canvas.height);
            expenseCtx.font = "16px Poppins";
            expenseCtx.fillStyle = "#6c757d";
            expenseCtx.textAlign = "center";
            expenseCtx.fillText("Tidak ada data pengeluaran", expenseCtx.canvas.width / 2, expenseCtx.canvas.height / 2);
        }

        if (pemasukanData.labels.length > 0) {
            incomeChartInstance = new Chart(incomeCtx, {
                type: 'doughnut',
                data: pemasukanData,
                options: chartOptions
            });
        } else {
            incomeCtx.clearRect(0, 0, incomeCtx.canvas.width, incomeCtx.canvas.height);
            incomeCtx.font = "16px Poppins";
            incomeCtx.fillStyle = "#6c757d";
            incomeCtx.textAlign = "center";
            incomeCtx.fillText("Tidak ada data pemasukan", incomeCtx.canvas.width / 2, incomeCtx.canvas.height / 2);
        }
    }

    function renderCategorySummary(transaksi) {
        const tablePengeluaranBody = $('#table-pengeluaran-body');
        const tablePengeluaranFoot = $('#table-pengeluaran-foot');
        const tablePemasukanBody = $('#table-pemasukan-body');
        const tablePemasukanFoot = $('#table-pemasukan-foot');

        var pemasukanTotal = 0,
            pengeluaranTotal = 0;
        let pemasukanTable, pengeluaranTable;
        transaksi.pemasukan.forEach(t => {
            const row = `
                        <tr>
                            <td>
                                <span class="badge bg-success-subtle text-success-emphasis">
                                    ${t.kategori_nm}
                                </span>
                            </td>
                            <td class="text-end fw-bold">Rp ${parseInt(t.jumlah).toLocaleString('id-ID')}</td>
                        </tr>
                    `;
            pemasukanTable += row;
            pemasukanTotal += parseInt(t.jumlah);
        });
        transaksi.pengeluaran.forEach(t => {
            const row = `
                        <tr>
                            <td>
                                <span class="badge bg-danger-subtle text-danger-emphasis">
                                    ${t.kategori_nm}
                                </span>
                            </td>
                            <td class="text-end fw-bold">Rp ${parseInt(t.jumlah).toLocaleString('id-ID')}</td>
                        </tr>
                    `;
            pengeluaranTable += row;
            pengeluaranTotal = parseInt(pengeluaranTotal) + parseInt(t.jumlah);
        });
        tablePengeluaranBody.html(pengeluaranTable);
        tablePemasukanBody.html(pemasukanTable);

        const pemasukanFoot = `
                        <tr>
                            <td style="font-weight: bold; background-color: #d1e7dd">Total Pemasukan</td>
                            <td class="text-end" style="font-weight: bold; background-color: #d1e7dd">Rp ${pemasukanTotal.toLocaleString('id-ID')}</td>
                        </tr>
                    `;
        tablePemasukanFoot.html(pemasukanFoot);

        const pengeluaranFoot = `
                        <tr>
                            <td style="font-weight: bold; background-color: #f8d7da">Total pengeluaran</td>
                            <td class="text-end " style="font-weight: bold; background-color: #f8d7da">Rp ${pengeluaranTotal.toLocaleString('id-ID')}</td>
                        </tr>
                    `;
        tablePengeluaranFoot.html(pengeluaranFoot);
    }
</script>
<?= $this->endSection() ?>