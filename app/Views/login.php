<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #333;
        }
        .form-control {
            background-color: #f7f7f7;
            border: none;
            border-radius: 50px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .btn-login {
            background-color: #007bff;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            color: #fff;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-login:hover {
            background-color: #0056b3;
        }
        .form-check-label {
            margin-left: 10px;
        }
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
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert-container">
        <div id="fadeAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<div class="login-container">
    <h2 class="text-center">Login Dashboard</h2>
    <form action="<?= base_url('/login/auth') ?>" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-login w-100 mt-3 ">Login</button>
    </form>
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

<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
