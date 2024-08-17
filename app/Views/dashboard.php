<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard <?php echo session()->get('region') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <style>
        .scorecard {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            color: #fff;
            margin-bottom: 20px;
        }
        .scorecard h2 {
            margin: 0;
            font-size: 2.5rem;
        }
        .scorecard p {
            margin: 0;
            font-size: 1.25rem;
        }
        .bg-primary { background-color: #007bff; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; }
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }

        .alert {
            min-width: 250px;
        }
    </style>
</head>
<body>
<?= view('layout/navbar') ?>
    &nbsp;
    <div class="container">
    
    <h3 class="mt-5" type="button" data-bs-toggle="collapse" data-bs-target="#scorecardCollapse" aria-expanded="true" aria-controls="scorecardCollapse"><?= (current_url() == site_url('dashboard/stocks')) ? 'Stok' : 'Summary'; ?> Penjualan <?php echo session()->get('region') ?></h3>    
    
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert-container">
        <div id="fadeAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Login berhasil!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

    <div id="scorecardCollapse" class="row mt-4 collapse show">
        <div class="col-md-4">
            <div class="scorecard <?= (current_url() == site_url('dashboard/stocks')) ? 'bg-info' : 'bg-primary'; ?>" data-score="Total">
                <div class="row">
                    <div class="col">
                        <p>Total</p> 
                        <h2><?= $totalMainData[0]['totalPenjualan'] ?></h2> 
                    </div>
                    <div class="col">
                        <p>Terendah</p>
                        <h2><?= $low ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="scorecard <?= (current_url() == site_url('dashboard/stocks')) ? 'bg-warning' : 'bg-success'; ?>" data-score="<?= (current_url() == site_url('dashboard/stocks')) ? 'Kartu' : 'Outlet'; ?>">
            <div class="row">
                    <div class="col">
                        <p><?= (current_url() == site_url('dashboard/stocks')) ? 'Kartu' : 'Outlet'; ?></p>
                        <h2><?= $totalFirstData[0]['totalPenjualan'] ?></h2> 
                    </div>
                    <div class="col">
                        <p>Terendah</p>
                        <h2><?= $lowFirstData ?></h2>
                    </div>
                </div>            
            </div>
        </div>
        <div class="col-md-4">
            <div class="scorecard <?= (current_url() == site_url('dashboard/stocks')) ? 'bg-success' : 'bg-warning'; ?>" data-score="<?= (current_url() == site_url('dashboard/stocks')) ? 'Voucher' : 'Sales'; ?>">
            <div class="row">
                    <div class="col">
                        <p><?= (current_url() == site_url('dashboard/stocks')) ? 'Voucher' : 'Sales'; ?></p>
                        <h2><?= $totalSecondData[0]['totalPenjualan'] ?></h2> 
                    </div>
                    <div class="col">
                        <p>Terendah</p>
                        <h2><?= $lowSecondData ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-5" type="button" data-bs-toggle="collapse" data-bs-target="#chartCollapse" aria-expanded="true" aria-controls="chartCollapse">Chart Penjualan <?php echo session()->get('region') ?></h3>

    <?php 
        $juneDatesArray = json_decode($juneDates, true);
        $juneSalesArray = json_decode($juneSales, true);

        $juneDataArray = array_combine($juneDatesArray, $juneSalesArray);

        $juneData = json_encode($juneDataArray);

        $julyDatesArray = json_decode($julyDates, true);
        $julySalesArray = json_decode($julySales, true);

        $julyDataArray = array_combine($julyDatesArray, $julySalesArray);

        $julyData = json_encode($julyDataArray);
    ?>

    <div id="chartCollapse" class="collapse show">
        <canvas id="chartPenjualan"></canvas>
            <script>
            var ctx = document.getElementById('chartPenjualan').getContext('2d');

            var chartPenjualan = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= $finalDates; ?>,
                    datasets: [
                        {
                            label: 'Penjualan Juni',
                            data: <?= $juneData; ?>,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: false,
                            hidden: false
                        },
                        {
                            label: 'Penjualan Juli',
                            data: <?= $julyData; ?>,
                            borderColor: 'rgba(255, 159, 64, 1)',
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            fill: false,
                            hidden: false
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'YYYY-MM-DD',
                            },
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Penjualan'
                            }
                        }
                    }
                }
            });

            function updateChartData(score) {
                let newData;
                // if (score === 'Total') {
                //     newData = juneData;
                // } else if (score === 'july') {
                //     newData = julyData;
                // } else if (score === 'august') {
                //     newData = augustData;
                // }

                // salesChart.data.datasets[0].data = newData;
                // salesChart.update();
                console.log(score);
            }

            console.log($juneDates);
            document.querySelectorAll('.scorecard').forEach(card => {
                card.addEventListener('click', function() {
                    const score = this.getAttribute('data-score');
                    updateChartData(score);
                });
            });

        </script>
    </div>

    


    <h3 class="mt-5" type="button" data-bs-toggle="collapse" data-bs-target="#tableCollapse" aria-expanded="true" aria-controls="tableCollapse">Tabel Detail Penjualan <?php echo session()->get('region') ?></h3>
    <div id="tableCollapse" class="collapse show">
        <a href="<?= (current_url() == site_url('dashboard/stocks')) ? site_url('export/downloadExcel' . '?data=stocks') : site_url('export/downloadExcel');?>" class="btn btn-success mb-3">Download sebagai Excel</a>
        <table id="pagination" class="table table-striped">
            <thead>
                <tr>
                    <th>Tgl Penjualan</th>
                    <th>ID</th>
                    <th>Provinsi</th>
                    <th>Jenis</th>
                    <th>Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mainData) && is_array($mainData)): ?>
                    <?php foreach ($mainData as $sale): ?>
                        <tr>
                            <td><?= esc($sale['tgl_penjualan']) ?></td>
                            <td><?= esc($sale['id']) ?></td>
                            <td><?= esc($sale['provinsi']) ?></td>
                            <td><?= esc($sale['jenis']) ?></td>
                            <td><?= esc($sale['total_penjualan']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data penjualan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            <?= $pager->links('group', 'bootstrap_pagination'); ?>
        </div>
    </div>
        

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var loginAlert = document.getElementById('fadeAlert');

        setTimeout(function () {
        var alert = new bootstrap.Alert(loginAlert);
        alert.close();
        }, 5000);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
