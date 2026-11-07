<?php
require_once __DIR__ . '/../controller/divisiController.php';
require_once __DIR__ . '/../utils/validation.php';

$divisiController = new DivisiController($conn);
$errors = array();
$old = array('division_name' => '', 'divisi_code' => '', 'description' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $k => $v) {
        $old[$k] = isset($_POST[$k]) ? trim($_POST[$k]) : '';
    }
    $e = validate_required($old['division_name'], 'Nama divisi'); if ($e) { $errors[] = $e; }
    $e = validate_max_length($old['divisi_code'], 5, 'Kode divisi'); if ($e) { $errors[] = $e; }
    if (empty($errors)) {
        $id = $divisiController->create($old);
        if ($id) {
            header('Location: divisi.php?success=1');
            exit;
        } else {
            $errors[] = 'Gagal menyimpan data: ' . mysql_error($conn);
        }
    }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Test | Tambah Divisi</title>
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
              <div class="col-sm-6"><h1 class="mb-0 fs-3">Tambah Divisi</h1></div>
              <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="divisi.php">Divisi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-8">
                <div class="card card-primary card-outline mb-4">
                  <div class="card-header"><h3 class="card-title">Form Divisi Baru</h3></div>
                  <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger m-3 mb-0"><ul class="mb-0"><?php foreach ($errors as $e) : ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div>
                  <?php endif; ?>
                  <form method="post" action="divisi_tambah.php">
                    <div class="card-body">
                      <div class="mb-3">
                        <label for="division_name" class="form-label">Nama Divisi</label>
                        <input type="text" id="division_name" name="division_name" class="form-control" value="<?php echo htmlspecialchars($old['division_name']); ?>" required maxlength="100" />
                      </div>
                      <div class="mb-3">
                        <label for="divisi_code" class="form-label">Kode Divisi</label>
                        <input type="text" id="divisi_code" name="divisi_code" class="form-control" value="<?php echo htmlspecialchars($old['divisi_code']); ?>" maxlength="5" />
                      </div>
                      <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" rows="3"><?php echo htmlspecialchars($old['description']); ?></textarea>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                      <a href="divisi.php" class="btn btn-secondary">Batal</a>
                    </div>
                  </form>
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
