<?php
    
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include "config/db.php";
$total_clients = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM clients")
)['total'];
$total_packages = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM packages")
)['total'];
$total_subscriptions = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM subscriptions")
)['total'];
$active = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM subscriptions WHERE status='Active'")
)['total'];
$inactive = $total_subscriptions - $active;

// Recent subscriptions
$recent = mysqli_query($conn,
    "SELECT s.*, c.full_name, p.package_name 
     FROM subscriptions s
     JOIN clients c ON s.client_id = c.id
     JOIN packages p ON s.package_id = p.id
     ORDER BY s.id DESC LIMIT 5"
);
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — NovaNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        * { box-sizing: border-box; }
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; margin: 0; }

        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            display: flex;
            flex-direction: column;
            padding: 0;
            z-index: 100;
            box-shadow: 2px 0 12px rgba(0,0,0,0.12);
        }

        .sidebar-logo {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-logo .brand {
            display: flex; align-items: center; gap: 10px;
        }
        .brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, #1a7a4a, #22c55e);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff; flex-shrink: 0;
        }
        .brand-name {
            font-size: 1.2rem; font-weight: 700;
            color: #fff; letter-spacing: 0.5px;
        }
        .brand-sub {
            font-size: 11px; color: rgba(255,255,255,0.4);
            margin-top: 1px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 10px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: rgba(255,255,255,0.3);
            padding: 0 0.5rem;
            margin: 1rem 0 0.4rem;
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 14px; font-weight: 500;
            transition: all 0.15s;
            margin-bottom: 2px;
        }
        .nav-item i { font-size: 18px; flex-shrink: 0; }
        .nav-item:hover {
            background: rgba(255,255,255,0.07);
            color: #fff;
        }
        .nav-item.active {
            background: rgba(26,122,74,0.25);
            color: #4ade80;
        }
        .nav-item.active i { color: #4ade80; }

        .nav-item.logout {
            color: rgba(255,100,100,0.7);
        }
        .nav-item.logout:hover {
            background: rgba(255,80,80,0.1);
            color: #f87171;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.07);
            font-size: 12px; color: rgba(255,255,255,0.25);
        }

   
        .main {
            margin-left: 240px;
            min-height: 100vh;
            padding: 2rem;
        }

     
        .topbar {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .topbar-left h1 {
            font-size: 1.5rem; font-weight: 700;
            color: #1a1a2e; margin: 0;
        }
        .topbar-left p {
            color: #868e96; font-size: 13px; margin: 2px 0 0;
        }
        .topbar-right {
            display: flex; align-items: center; gap: 10px;
        }
        .date-badge {
            display: flex; align-items: center; gap: 6px;
            background: #fff; border: 1px solid #e9ecef;
            border-radius: 8px; padding: 7px 14px;
            font-size: 13px; color: #495057;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            padding: 1.25rem;
            display: flex; align-items: center; gap: 14px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; flex-shrink: 0;
        }
        .icon-blue   { background: #dbeafe; color: #1d4ed8; }
        .icon-green  { background: #dcfce7; color: #16a34a; }
        .icon-purple { background: #ede9fe; color: #7c3aed; }
        .icon-orange { background: #ffedd5; color: #ea580c; }

        .stat-info { flex: 1; min-width: 0; }
        .stat-value {
            font-size: 1.6rem; font-weight: 700;
            color: #1a1a2e; line-height: 1;
        }
        .stat-label {
            font-size: 12px; color: #868e96;
            margin-top: 4px; font-weight: 500;
        }

        
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .panel {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        .panel-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex; align-items: center;
            justify-content: space-between;
        }
        .panel-title {
            font-size: 14px; font-weight: 600; color: #1a1a2e;
            display: flex; align-items: center; gap: 7px;
        }
        .panel-title i { color: #868e96; font-size: 16px; }
        .panel-link {
            font-size: 12px; color: #1a7a4a;
            text-decoration: none; font-weight: 500;
        }
        .panel-link:hover { text-decoration: underline; }
        .panel-body { padding: 1.25rem; }


        .recent-table { width: 100%; border-collapse: collapse; }
        .recent-table th {
            font-size: 11px; text-transform: uppercase;
            letter-spacing: 0.05em; color: #868e96;
            font-weight: 600; padding: 0 0 10px;
            border-bottom: 1px solid #f0f0f0;
            text-align: left;
        }
        .recent-table td {
            padding: 10px 0;
            border-bottom: 1px solid #f8f8f8;
            font-size: 13px; color: #495057;
            vertical-align: middle;
        }
        .recent-table tr:last-child td { border-bottom: none; }

        .client-name { font-weight: 500; color: #1a1a2e; }

        .status-pill {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600;
        }
        .pill-active   { background: #dcfce7; color: #16a34a; }
        .pill-inactive { background: #fee2e2; color: #dc2626; }

     
        .quick-links {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .quick-link {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 14px;
            border: 1px solid #e9ecef; border-radius: 10px;
            text-decoration: none; color: #495057;
            font-size: 13px; font-weight: 500;
            transition: all 0.15s;
        }
        .quick-link:hover {
            border-color: #1a7a4a;
            background: #f0fdf4;
            color: #1a7a4a;
        }
        .quick-link i { font-size: 18px; color: #1a7a4a; }

      
        .overview-item { margin-bottom: 1rem; }
        .overview-item:last-child { margin-bottom: 0; }
        .overview-row {
            display: flex; justify-content: space-between;
            font-size: 13px; margin-bottom: 5px;
        }
        .overview-row span:first-child { color: #495057; font-weight: 500; }
        .overview-row span:last-child { color: #868e96; }
        .progress-bar-wrap {
            height: 7px; background: #f0f0f0;
            border-radius: 10px; overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%; border-radius: 10px;
            transition: width 0.6s ease;
        }
        .fill-green  { background: linear-gradient(90deg, #1a7a4a, #22c55e); }
        .fill-red    { background: linear-gradient(90deg, #dc2626, #f87171); }
        .fill-blue   { background: linear-gradient(90deg, #1d4ed8, #60a5fa); }
        .fill-purple { background: linear-gradient(90deg, #7c3aed, #a78bfa); }

       
       @media (max-width: 768px) {
    .menu-toggle {
        display: flex;
    }
    .menu-toggle.hide {
    opacity: 0;
    pointer-events: none;
}
    .sidebar {
        position: fixed;
        width: 240px;
        height: 100vh;
        min-height: 100vh;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 150;
    }
    .sidebar.active {
        transform: translateX(0);
    }
    .main {
        margin-left: 0;
        padding: 1rem;
        padding-top: 4.5rem;
    }
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .bottom-grid {
        grid-template-columns: 1fr;
    }
    .quick-links {
        grid-template-columns: 1fr;
    }
}

    </style>
</head>
<body>
<button class="menu-toggle" onclick="toggleSidebar()">
    <i class="ti ti-menu-2"></i>
</button>
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-logo">
        <div class="brand">
            <div class="brand-icon"><i class="ti ti-globe"></i></div>
            <div>
                <div class="brand-name">NovaNet</div>
                <div class="brand-sub">ISP Management</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Kryesore</div>
        <a href="dashboard.php" class="nav-item active">
            <i class="ti ti-layout-dashboard"></i> Dashboard
        </a>

        <div class="nav-label">Menaxhim</div>
        <a href="clients/list.php" class="nav-item">
            <i class="ti ti-users"></i> Klientët
        </a>
        <a href="packages/list.php" class="nav-item">
            <i class="ti ti-box"></i> Paketat
        </a>
        <a href="subscriptions/list.php" class="nav-item">
            <i class="ti ti-file-invoice"></i> Abonimet
        </a>

        <div class="nav-label">Llogaria</div>
        <a href="logout.php" class="nav-item logout">
            <i class="ti ti-logout"></i> Dilni
        </a>
    </nav>

    <div class="sidebar-footer">
        © 2025 NovaNet ISP
    </div>
</div>


<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <h1>Dashboard</h1>
            <p>Mirësevini në sistemin e menaxhimit</p>
        </div>
        <div class="topbar-right">
            <div class="date-badge">
                <i class="ti ti-calendar"></i>
                <?php echo date('d M Y'); ?>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <i class="ti ti-users"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $total_clients; ?></div>
                <div class="stat-label">Total Klientë</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-purple">
                <i class="ti ti-box"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $total_packages; ?></div>
                <div class="stat-label">Paketa</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-orange">
                <i class="ti ti-file-invoice"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $total_subscriptions; ?></div>
                <div class="stat-label">Abonime</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-green">
                <i class="ti ti-circle-check"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $active; ?></div>
                <div class="stat-label">Aktive</div>
            </div>
        </div>
    </div>

    <div class="bottom-grid">

     
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="ti ti-clock"></i> Abonimet e Fundit
                </div>
                <a href="subscriptions/list.php" class="panel-link">Shiko të gjitha →</a>
            </div>
            <div class="panel-body">
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th>Klienti</th>
                            <th>Paketa</th>
                            <th>Statusi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($row = mysqli_fetch_assoc($recent)): ?>
                        <tr>
                            <td class="client-name"><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['package_name']); ?></td>
                            <td>
                                <span class="status-pill <?php echo $row['status'] === 'Active' ? 'pill-active' : 'pill-inactive'; ?>">
                                    <i class="ti <?php echo $row['status'] === 'Active' ? 'ti-circle-check' : 'ti-circle-x'; ?>"></i>
                                    <?php echo $row['status'] === 'Active' ? 'Aktiv' : 'Joaktiv'; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                        
                    </tbody>
                </table>
            </div>
        </div>

    
        <div style="display:flex; flex-direction:column; gap:1.25rem;">

            <!-- Overview -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="ti ti-chart-bar"></i> Përmbledhje
                    </div>
                </div>
                <div class="panel-body">
                    <?php $pct = $total_subscriptions > 0 ? round($active / $total_subscriptions * 100) : 0; ?>
                    <div class="overview-item">
                        <div class="overview-row">
                            <span>Abonime Aktive</span>
                            <span><?php echo $active; ?> / <?php echo $total_subscriptions; ?></span>
                        </div>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar-fill fill-green" style="width:<?php echo $pct; ?>%"></div>
                        </div>
                    </div>
                    <div class="overview-item">
                        <div class="overview-row">
                            <span>Abonime Joaktive</span>
                            <span><?php echo $inactive; ?> / <?php echo $total_subscriptions; ?></span>
                        </div>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar-fill fill-red" style="width:<?php echo 100 - $pct; ?>%"></div>
                        </div>
                    </div>
                    <div class="overview-item">
                        <div class="overview-row">
                            <span>Klientë me Abonim</span>
                            <span><?php echo $total_subscriptions; ?> / <?php echo $total_clients; ?></span>
                        </div>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar-fill fill-blue" style="width:<?php echo $total_clients > 0 ? round($total_subscriptions/$total_clients*100) :                                 0; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="ti ti-bolt"></i> Veprime të Shpejta
                    </div>
                </div>
                <div class="panel-body">
                    <div class="quick-links">
                        <a href="clients/add.php" class="quick-link">
                            <i class="ti ti-user-plus"></i> Shto Klient
                        </a>
                        <a href="packages/add.php" class="quick-link">
                            <i class="ti ti-plus"></i> Shto Paketë
                        </a>
                        <a href="subscriptions/add.php" class="quick-link">
                            <i class="ti ti-file-plus"></i> Shto Abonim
                        </a>
                        <a href="subscriptions/list.php" class="quick-link">
                            <i class="ti ti-list"></i> Të gjitha
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="assets/js/script.js?v=2"></script>
    <script>

setTimeout(function() {
    location.reload(true);
}, 5000);
</script>
</body>
</html>