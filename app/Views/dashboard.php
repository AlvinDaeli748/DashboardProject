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
    </style>
</head>
<body>
<?= view('layout/navbar') ?>
    &nbsp;
    <div class="container">
    
    <h3 class="mt-5">Summary Penjualan <?php echo session()->get('region') ?></h3>
    <?php if (session()->getFlashdata('success')): ?>
        <div id="loginAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        Login berhasil!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="scorecard bg-primary">
                <div class="row">
                    <div class="col">
                        <p>Total</p> 
                        <h2><?= $outletSales[0]['totalPenjualan'] ?></h2> 
                    </div>
                    <div class="col">
                        <p>Terendah</p>
                        <h2><?= $low ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="scorecard bg-success">
            <div class="row">
                    <div class="col">
                        <p>Outlet</p>
                        <h2><?= $outlet[0]['totalPenjualan'] ?></h2> 
                    </div>
                    <div class="col">
                        <p>Terendah</p>
                        <h2><?= $lowOutlet ?></h2>
                    </div>
                </div>            
            </div>
        </div>
        <div class="col-md-4">
            <div class="scorecard bg-warning">
            <div class="row">
                    <div class="col">
                        <p>Sales</p>
                        <h2><?= $sales[0]['totalPenjualan'] ?></h2> 
                    </div>
                    <div class="col">
                        <p>Terendah</p>
                        <h2><?= $lowSales ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-5">Chart Penjualan <?php echo session()->get('region') ?></h3>

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

        document.getElementById('chartPenjualan').addEventListener("click", function() {
            var checkJune = chartPenjualan.data.datasets[0]; 
            var checkJuly = chartPenjualan.data.datasets[1];
            console.log(checkJune);
            console.log(checkJuly);
            if(checkJune && checkJuly){
                document.getElementById('chartPenjualanPlaceholder').style.display = 'block';
            } else {
                document.getElementById('chartPenjualanPlaceholder').style.display = 'none';
            }
        });

    </script>


    <h3 class="mt-5">Tabel Detail Penjualan <?php echo session()->get('region') ?></h3>
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

    <div class="d-flex justify-content-center">
        <?= $pager->links('group', 'bootstrap_pagination'); ?>
    </div>    

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var loginAlert = document.getElementById('loginAlert');

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
