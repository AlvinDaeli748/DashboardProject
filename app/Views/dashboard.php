<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>
<body>

<div class="container">
    <h1 class="mt-5">Welcome <?php echo session()->get('username') ?></h1>

    

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show alert-position" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

    <a href="<?= base_url('/logout') ?>" class="btn btn-danger mt-3">Logout</a>
</div>

<div class="container">
    <h1 class="mt-5">Dashboard <?php echo session()->get('region') ?></h1>

    <canvas id="chartPenjualan"></canvas>

    <div id="chartPenjualanPlaceholder" style="display: none; text-align: center; margin-top: 20px;">
        <p>Pilih data untuk ditampilkan.</p>
    </div>

    <script>
        var ctx = document.getElementById('chartPenjualan').getContext('2d');

        var chartPenjualan = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= $juneDates; ?>,
                datasets: [
                    {
                        label: 'Penjualan Juni',
                        data: <?= $juneSales; ?>,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: false,
                        hidden: false
                    },
                    {
                        label: 'Penjualan Juli',
                        data: <?= $julySales; ?>,
                        borderColor: 'rgba(255, 159, 64, 1)',
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        fill: false,
                        hidden: true
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
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

        document.getElementById('chartPenjualan').addEventListener("mouseover", function() {
            var checkJune = chartPenjualan.data.datasets[0].hidden; 
            var checkJuly = chartPenjualan.data.datasets[1].hidden;
            console.log(checkJune);
            console.log(checkJuly);
            if(checkJune && checkJuly){
                document.getElementById('chartPenjualanPlaceholder').style.display = 'block';
            } else {
                document.getElementById('chartPenjualanPlaceholder').style.display = 'none';
            }
        });

    </script>

    <a href="<?= site_url('export/downloadExcel') ?>" class="btn btn-success mb-3">Download sebagai Excel</a>
    <table class="table table-striped">
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
            <?php if (!empty($penjualan) && is_array($penjualan)): ?>
                <?php foreach ($penjualan as $sale): ?>
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
    <?= $pager->links(); ?>
</div>

<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
