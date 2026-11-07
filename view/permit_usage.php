<?php
require_once __DIR__ . '/../controller/permitUsageController.php';

$usageController = new PermitUsageController($conn);
$alert = '';

if (isset($_GET['rollback'])) {
    $id = (int) $_GET['rollback'];
    if ($id > 0) {
        $res = $usageController->rollback($id);
        if ($res === true) {
            header('Location: permit_usage.php?rolled=1');
            exit;
        } else {
            $alert = $res;
        }
    }
}

$items = $usageController->all();
if ($items === false) {
    $items = array();
}

if (isset($_GET['rolled'])) {
    $alert = 'Penggunaan permit berhasil di-rollback dan kuota dikembalikan.';
}
?>
<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Test | Penggunaan Permit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.3.0/index.css" crossorigin="anonymous" media="print" onload="this.media = 'all'" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="../frontend_template/admin-lte-v4.10.1/dist/css/adminlte.css" />
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a></li>
            <li class="nav-item d-none d-md-block"><a href="home.php" class="nav-link">Home</a></li>
          </ul>
        </div>
      </nav>

      <?php include __DIR__ . '/sidebar.php'; ?>

      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h1 class="mb-0 fs-3">Penggunaan Permit</h1></div>
              <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Penggunaan Permit</li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <?php if ($alert !== '') : ?>
              <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($alert); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>
            <div class="row">
              <div class="col-12">
                <div class="card mb-4">
                  <div class="card-header">
                    <h3 class="card-title">Daftar Penggunaan Permit</h3>
                  </div>
                  <div class="card-body p-0">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>Karyawan</th>
                          <th>Permit</th>
                          <th>Tahun</th>
                          <th>Tanggal Mulai</th>
                          <th>Tanggal Selesai</th>
                          <th>Jumlah Hari</th>
                          <th>False Entry</th>
                          <th style="width: 120px">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($items as $row) : ?>
                          <tr class="align-middle">
                            <td><?php echo htmlspecialchars($row['karyawan_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['permit_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                            <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['amount_date']); ?></td>
                            <td><?php echo ((int) $row['is_false_entry'] === 1) ? 'Ya' : 'Tidak'; ?></td>
                            <td>
                              <?php if ((int) $row['is_false_entry'] === 0) : ?>
                                <a href="permit_usage.php?rollback=<?php echo (int) $row['id']; ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Rollback penggunaan ini? Kuota akan dikembalikan.');" title="Rollback"><i class="bi bi-arrow-counterclockwise"></i> Rollback</a>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>

      <footer class="app-footer"><div class="float-end d-none d-sm-inline">CRUD Test</div><strong>AdminLTE v4 template</strong></footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="../frontend_template/admin-lte-v4.10.1/dist/js/adminlte.js"></script>
  </body>
</html>
