<?php
include "../config/db.php";
$message = "";
$msg_type = "";
$clients = mysqli_query($conn, "SELECT * FROM clients ORDER BY full_name ASC");
$packages = mysqli_query($conn, "SELECT * FROM packages ORDER BY package_name ASC");

if(isset($_POST['add_subscription'])){
    $client_id = $_POST['client_id'];
    $package_id = $_POST['package_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $query = "INSERT INTO subscriptions (client_id, package_id, start_date, end_date, status)
              VALUES ('$client_id','$package_id','$start_date','$end_date','$status')";
    if(mysqli_query($conn, $query)){
      header("Location: list.php");
       exit();
    } else {
        $message = "Gabim: " . mysqli_error($conn);
        $msg_type = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Shto Abonim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }

        .page-wrapper { max-width: 620px; margin: 2rem auto; padding: 0 1.5rem; }

        .page-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.5rem;
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

        .title-group { flex: 1; }
        .title-group h2 { margin: 0; font-size: 1.4rem; font-weight: 600; color: #1a1a2e; }
        .title-group small { color: #868e96; font-size: 13px; }

        .card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        .card-body { padding: 1.75rem; }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            font-size: 14px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 9px 12px;
            transition: border 0.15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #1a7a4a;
            box-shadow: 0 0 0 3px rgba(26,122,74,0.08);
        }

        .input-icon-wrap {
            position: relative;
        }
        .input-icon-wrap i {
            position: absolute;
            left: 11px; top: 50%;
            transform: translateY(-50%);
            color: #adb5bd; font-size: 16px;
            pointer-events: none;
        }
        .input-icon-wrap .form-control,
        .input-icon-wrap .form-select {
            padding-left: 36px;
        }

        .section-divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 1.25rem 0;
        }

        .btn-save {
            display: inline-flex; align-items: center; gap: 6px;
            background: #1a7a4a; color: #fff;
            border: none; border-radius: 8px;
            padding: 9px 22px; font-size: 14px; font-weight: 500;
            transition: background 0.15s;
            text-decoration: none;
        }
        .btn-save:hover { background: #155e39; color: #fff; }

        .btn-cancel {
            display: inline-flex; align-items: center; gap: 6px;
            background: #fff; color: #6c757d;
            border: 1px solid #dee2e6; border-radius: 8px;
            padding: 9px 18px; font-size: 14px; font-weight: 500;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn-cancel:hover { background: #f8f9fa; color: #343a40; }

        .alert {
            border-radius: 8px;
            font-size: 13.5px;
            padding: 10px 14px;
            margin-bottom: 1.25rem;
        }

        .status-row { display: flex; gap: 10px; }
        .status-option { flex: 1; }
        .status-option input[type="radio"] { display: none; }
        .status-option label {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%; padding: 9px;
            border: 1px solid #dee2e6; border-radius: 8px;
            font-size: 13px; font-weight: 500; cursor: pointer;
            color: #6c757d; background: #fff;
            transition: all 0.15s;
        }
        .status-option input[type="radio"]:checked + label.active-lbl {
            background: #d1fae5; border-color: #1a7a4a; color: #1a7a4a;
        }
        .status-option input[type="radio"]:checked + label.inactive-lbl {
            background: #fee2e2; border-color: #c0392b; color: #c0392b;
        }
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
            <h2>Shto Abonim</h2>
            <small>Krijo abonim të ri për klient</small>
        </div>
    </div>

    <!-- Card -->
    <div class="card">
        <div class="card-body">

            <?php if($message): ?>
            <div class="alert alert-<?php echo $msg_type; ?>">
                <i class="ti ti-<?php echo $msg_type === 'success' ? 'circle-check' : 'alert-circle'; ?>"></i>
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="add.php" autocomplete="off">

                <!-- Client -->
                <div class="mb-3">
                    <label class="form-label">Klienti</label>
                    <div class="input-icon-wrap">
                        <i class="ti ti-user"></i>
                        <select name="client_id" class="form-select">
                            <?php while($row = mysqli_fetch_assoc($clients)): ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['full_name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- Package -->
                <div class="mb-3">
                    <label class="form-label">Paketa</label>
                    <div class="input-icon-wrap">
                        <i class="ti ti-wifi"></i>
                        <select name="package_id" class="form-select">
                            <?php while($row = mysqli_fetch_assoc($packages)): ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['package_name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <hr class="section-divider">

                <!-- Dates -->
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Data e Fillimit</label>
                        <div class="input-icon-wrap">
                            <i class="ti ti-calendar"></i>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Data e Mbarimit</label>
                        <div class="input-icon-wrap">
                            <i class="ti ti-calendar-off"></i>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="form-label">Statusi</label>
                    <div class="status-row">
                        <div class="status-option">
                            <input type="radio" name="status" id="st_active" value="Active" checked>
                            <label for="st_active" class="active-lbl">
                                <i class="ti ti-circle-check"></i> Aktiv
                            </label>
                        </div>
                        <div class="status-option">
                            <input type="radio" name="status" id="st_inactive" value="Inactive">
                            <label for="st_inactive" class="inactive-lbl">
                                <i class="ti ti-circle-x"></i> Joaktiv
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-2">
                    <button type="submit" name="add_subscription" class="btn-save">
                        <i class="ti ti-device-floppy"></i> Shto Abonim
                    </button>
                    <a href="javascript:history.back()" class="btn-cancel">
                        <i class="ti ti-x"></i> Anulo
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<script src="assets/js/script.js"></script>
</body>
</html>