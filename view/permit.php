<?php
require_once __DIR__ . '/../controller/permitController.php';

$permitController = new PermitController($conn);
$alert = '';

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if ($id > 0) {
        $ok = $permitController->delete($id);
        if ($ok) {
            header('Location: permit.php?deleted=1');
            exit;
        } else {
            $alert = 'Gagal menghapus data: ' . mysql_error($conn);
        }
    }
}

$permits = $permitController->all();
if ($permits === false) {
    $permits = array();
}

if (isset($_GET['deleted'])) {
    $alert = 'Data permit berhasil dihapus.';
}
if (isset($_GET['success'])) {
    $alert = 'Data permit berhasil ditambahkan.';
}
if (isset($_GET['updated'])) {
    $alert = 'Data permit berhasil diperbarui.';
}
?>
<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Test | Data Permit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.3.0/index.css" crossorigin="anonymous" media="print" onload="this.media = 'all'" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="../frontend_template/admin-lte-v4.10.1/dist/css/adminlte.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" />
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
              <div class="col-sm-6"><h1 class="mb-0 fs-3">Data Permit</h1></div>
              <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Permit</li>
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
                    <h3 class="card-title">Daftar Permit</h3>
                    <div class="card-tools">
                      <a href="permit_tambah.php" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <table id="permitTable" class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>Nama Permit</th>
                          <th style="width: 140px">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($permits as $row) : ?>
                          <tr class="align-middle">
                            <td><?php echo htmlspecialchars($row['permit_name']); ?></td>
                            <td>
                              <a href="permit_edit.php?id=<?php echo (int) $row['id']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                              <a href="permit.php?delete=<?php echo (int) $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?');"><i class="bi bi-trash"></i></a>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
      $(function () {
        $('#permitTable').DataTable({
          columnDefs: [{ targets: -1, orderable: false, searchable: false }]
        });
      });
    </script>
  </body>
</html>
