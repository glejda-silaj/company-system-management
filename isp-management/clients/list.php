<?php
include "../config/db.php";
$query = "SELECT * FROM clients ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$total = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Lista e Klientëve</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }

        .page-wrapper { max-width: 1100px; margin: 2rem auto; padding: 0 1.5rem; }

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

        .count-badge {
            display: inline-block;
            background: #e9ecef;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 12px;
            color: #495057;
            margin-left: 8px;
            font-weight: 500;
        }

        .btn-add {
            display: flex; align-items: center; gap: 6px;
            background: #1a7a4a; color: #fff;
            border: none; border-radius: 8px;
            padding: 8px 18px; font-size: 14px; font-weight: 500;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn-add:hover { background: #155e39; color: #fff; }

        .search-wrap {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .search-wrap i {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            color: #adb5bd; font-size: 16px;
        }
        .search-wrap input {
            width: 100%; padding: 9px 12px 9px 38px;
            border: 1px solid #dee2e6; border-radius: 8px;
            font-size: 14px; background: #fff;
            outline: none; transition: border 0.15s;
        }
        .search-wrap input:focus { border-color: #1a7a4a; }

        .card { border: 1px solid #e9ecef; border-radius: 12px; overflow: hidden; }
        .card-body { padding: 0; }

        table { margin: 0; }
        thead tr { background: #f8f9fa; }
        th {
            font-size: 11px; text-transform: uppercase;
            letter-spacing: 0.05em; color: #868e96;
            font-weight: 600; padding: 12px 16px;
            border-bottom: 1px solid #e9ecef;
        }
        td { padding: 13px 16px; vertical-align: middle; border-color: #f0f0f0; }
        tbody tr:hover { background: #f8fffe; }

        .avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: #d4f0e3; color: #0f6e56;
            font-size: 12px; font-weight: 600;
            display: inline-flex; align-items: center; justify-content: center;
            margin-right: 8px;
        }
        .name-cell { display: flex; align-items: center; }

        .email-link { color: #1a7a4a; text-decoration: none; font-size: 13px; }
        .email-link:hover { text-decoration: underline; }

        .phone-cell, .addr-cell {
            display: flex; align-items: center; gap: 5px;
            color: #495057; font-size: 13px;
        }
        .phone-cell i, .addr-cell i { color: #adb5bd; font-size: 14px; }

        .id-cell { color: #adb5bd; font-size: 13px; }

        .btn-edit {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 5px 12px; font-size: 12px; font-weight: 500;
            border-radius: 6px; border: 1px solid #dee2e6;
            background: #fff; color: #495057;
            text-decoration: none; transition: all 0.15s;
        }
        .btn-edit:hover { background: #fff3cd; color: #854f0b; border-color: #ffc107; }

        .btn-del {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 5px 12px; font-size: 12px; font-weight: 500;
            border-radius: 6px; border: 1px solid #f5c6cb;
            background: #fff5f5; color: #c0392b;
            text-decoration: none; transition: all 0.15s;
        }
        .btn-del:hover { background: #f8d7da; color: #7b1c1c; }

        .empty-state { text-align: center; padding: 3rem; color: #adb5bd; }
        .empty-state i { font-size: 2rem; display: block; margin-bottom: 8px; }

        .actions-cell { display: flex; gap: 6px; }
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
            <h2>Lista e Klientëve <span class="count-badge"><?php echo $total; ?> klientë</span></h2>
       
        </div>
        <a href="add.php" class="btn-add">
            <i class="ti ti-plus"></i> Shto Klient
        </a>
    </div>

    <!-- Search -->
    <div class="search-wrap">
        <i class="ti ti-search"></i>
        <input type="text" id="searchInput" placeholder="Kërko sipas emrit, emailit ose telefonit..." oninput="filterTable()">
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body">
             <div class="table-responsive">
            
            
            <table class="table table-hover" id="clientTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Emri</th>
                        <th>Email</th>
                        <th>Telefoni</th>
                        <th>Adresa</th>
                        <th>Veprime</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <?php
                        $words = explode(' ', trim($row['full_name']));
                        $initials = '';
                        foreach ($words as $w) $initials .= strtoupper(substr($w, 0, 1));
                        $initials = substr($initials, 0, 2);
                    ?>
                    <tr>
                        <td class="id-cell"><?php echo $row['id']; ?></td>
                        <td>
                            <div class="name-cell">
                                <span class="avatar"><?php echo htmlspecialchars($initials); ?></span>
                                <?php echo htmlspecialchars($row['full_name']); ?>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="email-link">
                                <i class="ti ti-mail" style="font-size:13px;margin-right:3px;vertical-align:-1px;"></i>
                                <?php echo htmlspecialchars($row['email']); ?>
                            </a>
                        </td>
                        <td>
                            <div class="phone-cell">
                                <i class="ti ti-phone"></i>
                                <?php echo htmlspecialchars($row['phone']); ?>
                            </div>
                        </td>
                        <td>
                            <div class="addr-cell">
                                <i class="ti ti-map-pin"></i>
                                <?php echo htmlspecialchars($row['address']); ?>
                            </div>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-edit">
                                    <i class="ti ti-edit"></i> Edit
                                </a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-del"
                                   onclick="return confirm('Je i sigurt që dëshiron ta fshish këtë klient?');">
                                    <i class="ti ti-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            </div>
                        <div class="empty-state" id="emptyMsg" style="display:none;">

                <i class="ti ti-users"></i>
                Nuk u gjetën klientë.
            </div>
                      
        </div>
    </div>

</div>

<script>
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr');
    let visible = 0;
    rows.forEach(r => {
        const match = r.textContent.toLowerCase().includes(q);
        r.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('emptyMsg').style.display = visible === 0 ? 'block' : 'none';
}
</script>

<script src="assets/js/script.js"></script>
</body>
</html>