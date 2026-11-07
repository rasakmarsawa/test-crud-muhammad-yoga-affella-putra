<?php
require_once __DIR__ . '/../controller/divisiController.php';

$divisiController = new DivisiController($conn);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: divisi.php');
    exit;
}

$row = $divisiController->find($id);
if (!$row) {
    header('Location: divisi.php?error=notfound');
    exit;
}

$errors = array();
$data = array(
    'division_name' => $row['division_name'],
    'divisi_code'   => isset($row['divisi_code']) ? $row['divisi_code'] : '',
    'description'   => $row['description'],
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['division_name'] = isset($_POST['division_name']) ? trim($_POST['division_name']) : '';
    $data['divisi_code']   = isset($_POST['divisi_code']) ? trim($_POST['divisi_code']) : '';
    $data['description']   = isset($_POST['description']) ? trim($_POST['description']) : '';

    if ($data['division_name'] === '') {
        $errors[] = 'Nama divisi wajib diisi.';
    }
    if ($data['divisi_code'] !== '' && strlen($data['divisi_code']) > 5) {
        $errors[] = 'Kode divisi maksimal 5 karakter.';
    }
    if (empty($errors)) {
        $ok = $divisiController->update($id, $data);
        if ($ok) {
            header('Location: divisi.php?updated=1');
            exit;
        } else {
            $errors[] = 'Gagal memperbarui data: ' . mysql_error($conn);
        }
    }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Test | Edit Divisi</title>
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
              <div class="col-sm-6"><h1 class="mb-0 fs-3">Edit Divisi</h1></div>
              <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="divisi.php">Divisi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                <div class="card card-warning card-outline mb-4">
                  <div class="card-header"><h3 class="card-title">Edit: <?php echo htmlspecialchars($row['division_name']); ?></h3></div>
                  <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger m-3 mb-0"><ul class="mb-0"><?php foreach ($errors as $e) : ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div>
                  <?php endif; ?>
                  <form method="post" action="divisi_edit.php?id=<?php echo (int) $id; ?>">
                    <div class="card-body">
                      <div class="mb-3">
                        <label for="division_name" class="form-label">Nama Divisi</label>
                        <input type="text" id="division_name" name="division_name" class="form-control" value="<?php echo htmlspecialchars($data['division_name']); ?>" required maxlength="100" />
                      </div>
                      <div class="mb-3">
                        <label for="divisi_code" class="form-label">Kode Divisi</label>
                        <input type="text" id="divisi_code" name="divisi_code" class="form-control" value="<?php echo htmlspecialchars($data['divisi_code']); ?>" maxlength="5" />
                      </div>
                      <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" rows="3"><?php echo htmlspecialchars($data['description']); ?></textarea>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Perbarui</button>
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
