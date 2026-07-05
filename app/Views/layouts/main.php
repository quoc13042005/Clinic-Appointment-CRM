<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= e($title ?? 'Clinic Appointment CRM') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #eaf4f4; }
        .header { background: #0077b6; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav a { color: white; margin-left: 15px; text-decoration: none; }
        .container { padding: 20px; max-width: 1200px; margin: auto; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .text-danger { color: red; font-size: 0.9em; }
        .btn { padding: 10px 15px; background: #00b4d8; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-danger { background: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white;}
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        .pagination { margin-top: 20px; }
        .pagination a { padding: 5px 10px; border: 1px solid #ddd; margin-right: 5px; text-decoration: none; color: black; }
        .pagination a.active { background: #00b4d8; color: white; }
    </style>
</head>
<body>
    <?php require __DIR__ . '/../partials/nav.php'; ?>
    <div class="container">
        <?php require __DIR__ . '/../partials/flash.php'; ?>
        <?= $content ?>
    </div>
</body>
</html>