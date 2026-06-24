<?php
include "../config/db.php";
if(!isset($_GET['id'])){
    header("Location: list.php");
    exit();
}
$id = (int) $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM clients WHERE id = $id");
$client = mysqli_fetch_assoc($result);
if(!$client){
    header("Location: list.php");
    exit();
}
$message  = "";
$msg_type = "";
if(isset($_POST['update_client'])){
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);
    $address   = mysqli_real_escape_string($conn, $_POST['address']);
    $query = "UPDATE clients SET
                full_name='$full_name',
                email='$email',
                phone='$phone',
                address='$address'
              WHERE id=$id";
    if(mysqli_query($conn, $query)){
        $message  = "Klienti u përditësua me sukses!";
        $msg_type = "success";
        $result   = mysqli_query($conn, "SELECT * FROM clients WHERE id = $id");
        $client   = mysqli_fetch_assoc($result);
    } else {
        $message  = "Gabim: " . mysqli_error($conn);
        $msg_type = "error";
    }
}

$words    = explode(' ', trim($client['full_name']));
$initials = '';
foreach ($words as $w) $initials .= strtoupper(substr($w, 0, 1));
$initials = substr($initials, 0, 2);
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edito Klient</title>
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
        .avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: #d4f0e3; color: #0f6e56;
            font-size: 14px; font-weight: 600;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .form-card-header .client-info span {
            font-size: 15px; font-weight: 600; color: #1a1a2e; display: block;
        }
        .form-card-header .client-info small {
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
            <h2>Edito Klient</h2>
            <small>Përditëso të dhënat e klientit #<?php echo $id; ?></small>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="avatar"><?php echo htmlspecialchars($initials); ?></div>
            <div class="client-info">
                <span><?php echo htmlspecialchars($client['full_name']); ?></span>
                <small>ID: <?php echo $id; ?> &nbsp;·&nbsp; <?php echo htmlspecialchars($client['email']); ?></small>
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
                        <i class="ti ti-user"></i>
                        Emri i plotë <span style="color:#c0392b;">*</span>
                    </label>
                    <input type="text" name="full_name"
                           value="<?php echo htmlspecialchars($client['full_name']); ?>"
                           required>
                </div>

                <div class="field-group">
                    <label>
                        <i class="ti ti-mail"></i>
                        Email
                    </label>
                    <input type="email" name="email"
                           value="<?php echo htmlspecialchars($client['email']); ?>">
                </div>

                <div class="field-group">
                    <label>
                        <i class="ti ti-phone"></i>
                        Telefoni
                    </label>
                    <input type="text" name="phone"
                           value="<?php echo htmlspecialchars($client['phone']); ?>">
                </div>

                <div class="field-group">
                    <label>
                        <i class="ti ti-map-pin"></i>
                        Adresa
                    </label>
                    <textarea name="address"><?php echo htmlspecialchars($client['address']); ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" name="update_client" class="btn-submit">
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