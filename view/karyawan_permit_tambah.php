<?php
require_once __DIR__ . '/../controller/karyawanPermitController.php';
require_once __DIR__ . '/../controller/karyawanController.php';
require_once __DIR__ . '/../controller/permitController.php';
require_once __DIR__ . '/../utils/validation.php';

$kpController       = new KaryawanPermitController($conn);
$karyawanController = new KaryawanController($conn);
$permitController   = new PermitController($conn);

$karyawans = $karyawanController->all();
if ($karyawans === false) { $karyawans = array(); }
$permits = $permitController->all();
if ($permits === false) { $permits = array(); }

$errors = array();
$old = array('karyawan_id' => '', 'permit_id' => '', 'year' => date('Y'), 'days_amount' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $k => $v) {
        $old[$k] = isset($_POST[$k]) ? trim($_POST[$k]) : '';
    }
    $e = validate_required($old['karyawan_id'], 'Karyawan'); if ($e) { $errors[] = $e; }
    $e = validate_required($old['permit_id'], 'Permit'); if ($e) { $errors[] = $e; }
    $e = validate_number_field($old['year'], 'Tahun'); if ($e) { $errors[] = $e; }
    $e = validate_number_field($old['days_amount'], 'Jumlah hari'); if ($e) { $errors[] = $e; }

    if (empty($errors)) {
        $id = $kpController->create($old);
        if ($id) {
            header('Location: karyawan_permit.php?success=1');
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
    <title>CRUD Test | Tambah Permit Karyawan</title>
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
              <div class="col-sm-6"><h1 class="mb-0 fs-3">Tambah Permit Karyawan</h1></div>
              <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="karyawan_permit.php">Permit Karyawan</a></li>
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
                  <div class="card-header"><h3 class="card-title">Form Permit Karyawan</h3></div>
                  <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger m-3 mb-0"><ul class="mb-0"><?php foreach ($errors as $e) : ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div>
                  <?php endif; ?>
                  <form method="post" action="karyawan_permit_tambah.php">
                    <div class="card-body">
                      <div class="mb-3">
                        <label for="karyawan_id" class="form-label">Karyawan</label>
                        <select id="karyawan_id" name="karyawan_id" class="form-select" required>
                          <option value="">-- Pilih Karyawan --</option>
                          <?php foreach ($karyawans as $k) : ?>
                            <option value="<?php echo htmlspecialchars($k['id']); ?>" <?php echo ($old['karyawan_id'] == $k['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($k['fullname']); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="permit_id" class="form-label">Permit</label>
                        <select id="permit_id" name="permit_id" class="form-select" required>
                          <option value="">-- Pilih Permit --</option>
                          <?php foreach ($permits as $p) : ?>
                            <option value="<?php echo htmlspecialchars($p['id']); ?>" <?php echo ($old['permit_id'] == $p['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['permit_name']); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="year" class="form-label">Tahun</label>
                          <input type="text" id="year" name="year" class="form-control" value="<?php echo htmlspecialchars($old['year']); ?>" required maxlength="4" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')" />
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="days_amount" class="form-label">Jumlah Hari</label>
                          <input type="text" id="days_amount" name="days_amount" class="form-control" value="<?php echo htmlspecialchars($old['days_amount']); ?>" required inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')" />
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                      <a href="karyawan_permit.php" class="btn btn-secondary">Batal</a>
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
