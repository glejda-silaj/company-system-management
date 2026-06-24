<?php
include "../config/db.php";
if(!isset($_GET['id'])){
    header("Location: list.php");
    exit();
}
$id = (int) $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM packages WHERE id = $id");
$package = mysqli_fetch_assoc($result);
if(!$package){
    header("Location: list.php");
    exit();
}
$message  = "";
$msg_type = "";
if(isset($_POST['update_package'])){
    $package_name = mysqli_real_escape_string($conn, $_POST['package_name']);
    $speed        = mysqli_real_escape_string($conn, $_POST['speed']);
    $price        = mysqli_real_escape_string($conn, $_POST['price']);
    $description  = mysqli_real_escape_string($conn, $_POST['description']);
    $query = "UPDATE packages SET
                package_name='$package_name',
                speed='$speed',
                price='$price',
                description='$description'
              WHERE id=$id";
    if(mysqli_query($conn, $query)){
        $message  = "Paketa u përditësua me sukses!";
        $msg_type = "success";
        $result   = mysqli_query($conn, "SELECT * FROM packages WHERE id = $id");
        $package  = mysqli_fetch_assoc($result);
    } else {
        $message  = "Gabim: " . mysqli_error($conn);
        $msg_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edito Paketën</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }

        .page-wrapper {
            max-width: 580px;
            margin: 2.5rem auto;
            padding: 0 1.5rem;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.75rem;
        }

        .back-btn {
            width: 38px; height: 38px;
            border-radius: 50%;
            border: 1px solid #dee2e6;
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.15s;
            flex-shrink: 0;
        }
        .back-btn:hover { background: #f0f0f0; color: #343a40; }

        .title-group h2 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 600;
            color: #1a1a2e;
        }
        .title-group small { color: #868e96; font-size: 13px; }

        .form-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .form-card-header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex; align-items: center; gap: 12px;
        }
        .pkg-icon {
            width: 40px; height: 40px; border-radius: 9px;
            background: #dbeafe;
            display: flex; align-items: center; justify-content: center;
            color: #1d4ed8; font-size: 20px;
            flex-shrink: 0;
        }
        .form-card-header .pkg-info span {
            font-size: 15px; font-weight: 600; color: #1a1a2e; display: block;
        }
        .form-card-header .pkg-info small {
            font-size: 12px; color: #868e96;
        }

        .form-card-body { padding: 1.5rem; }

        .field-group { margin-bottom: 1.2rem; }
        .field-group label {
            display: flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 500;
            color: #495057; margin-bottom: 6px;
        }
        .field-group label i { font-size: 15px; color: #adb5bd; }

        .field-group input,
        .field-group textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 14px;
            color: #1a1a2e;
            background: #fff;
            outline: none;
            transition: border 0.15s, box-shadow 0.15s;
        }
        .field-group input:focus,
        .field-group textarea:focus {
            border-color: #1a7a4a;
            box-shadow: 0 0 0 3px rgba(26,122,74,0.08);
        }

        .input-with-suffix { position: relative; }
        .input-with-suffix input { padding-right: 52px; }
        .input-suffix {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            font-size: 13px; color: #adb5bd; font-weight: 500;
            pointer-events: none;
        }

        .field-group textarea { resize: vertical; min-height: 90px; }

        .alert-success-custom {
            display: flex; align-items: center; gap: 8px;
            background: #d4f0e3; border: 1px solid #9fd8bf;
            color: #0f6e56; border-radius: 8px;
            padding: 10px 14px; font-size: 14px;
            margin-bottom: 1.2rem;
        }
        .alert-error-custom {
            display: flex; align-items: center; gap: 8px;
            background: #fde8e8; border: 1px solid #f5c6cb;
            color: #c0392b; border-radius: 8px;
            padding: 10px 14px; font-size: 14px;
            margin-bottom: 1.2rem;
        }

        .form-actions {
            display: flex; gap: 10px; margin-top: 1.5rem;
            padding-top: 1.2rem;
            border-top: 1px solid #f0f0f0;
        }
        .btn-submit {
            display: flex; align-items: center; gap: 6px;
            padding: 9px 20px; font-size: 14px; font-weight: 500;
            background: #1a7a4a; color: #fff;
            border: none; border-radius: 8px;
            cursor: pointer; transition: background 0.15s;
        }
        .btn-submit:hover { background: #155e39; }

        .btn-back-list {
            display: flex; align-items: center; gap: 6px;
            padding: 9px 18px; font-size: 14px; font-weight: 500;
            background: #fff; color: #495057;
            border: 1px solid #dee2e6; border-radius: 8px;
            text-decoration: none; transition: all 0.15s;
        }
        .btn-back-list:hover { background: #f8f9fa; color: #343a40; }
    </style>
</head>
<body>

<div class="page-wrapper">

    <!-- Header -->
    <div class="page-header">
        <a href="javascript:history.back()" class="back-btn" title="Kthehu mbrapa">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div class="title-group">
            <h2>Edito Paketën</h2>
            <small>Përditëso të dhënat e paketës #<?php echo $id; ?></small>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="pkg-icon">
                <i class="ti ti-wifi"></i>
            </div>
            <div class="pkg-info">
                <span><?php echo htmlspecialchars($package['package_name']); ?></span>
                <small>
                    <i class="ti ti-gauge" style="font-size:12px;vertical-align:-1px;"></i>
                    <?php echo htmlspecialchars($package['speed']); ?> Mbps
                    &nbsp;·&nbsp;
                    <i class="ti ti-currency-dollar" style="font-size:12px;vertical-align:-1px;"></i>
                    <?php echo htmlspecialchars($package['price']); ?> ALL
                </small>
            </div>
        </div>

        <div class="form-card-body">

            <?php if($message): ?>
                <div class="<?php echo $msg_type === 'success' ? 'alert-success-custom' : 'alert-error-custom'; ?>">
                    <i class="ti <?php echo $msg_type === 'success' ? 'ti-circle-check' : 'ti-alert-circle'; ?>"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="field-group">
                    <label>
                        <i class="ti ti-box"></i>
                        Emri i Paketës <span style="color:#c0392b;">*</span>
                    </label>
                    <input type="text" name="package_name"
                           value="<?php echo htmlspecialchars($package['package_name']); ?>"
                           required>
                </div>

                <div class="field-group">
                    <label>
                        <i class="ti ti-gauge"></i>
                        Shpejtësia e Internetit
                    </label>
                    <div class="input-with-suffix">
                        <input type="text" name="speed"
                               value="<?php echo htmlspecialchars($package['speed']); ?>">
                        <span class="input-suffix">Mbps</span>
                    </div>
                </div>

                <div class="field-group">
                    <label>
                        <i class="ti ti-currency-dollar"></i>
                        Çmimi
                    </label>
                    <div class="input-with-suffix">
                        <input type="text" name="price"
                               value="<?php echo htmlspecialchars($package['price']); ?>">
                        <span class="input-suffix">ALL</span>
                    </div>
                </div>

                <div class="field-group">
                    <label>
                        <i class="ti ti-notes"></i>
                        Përshkrim
                    </label>
                    <textarea name="description"><?php echo htmlspecialchars($package['description']); ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" name="update_package" class="btn-submit">
                        <i class="ti ti-device-floppy"></i> Ruaj Ndryshimet
                    </button>
                    <a href="list.php" class="btn-back-list">
                        <i class="ti ti-list"></i> Shiko Listën
                    </a>
                </div>
            </form>

        </div>
    </div>

</div>

<script src="assets/js/script.js"></script>
</body>
</html>