<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/controller/divisiController.php';
require_once __DIR__ . '/controller/karyawanController.php';

$divisiController   = new DivisiController($conn);
$karyawanController = new KaryawanController($conn);

$divisions = $divisiController->all();
if ($divisions === false) {
    $divisions = array();
}

$errors = array();
$old = array(
    'division_id' => '',
    'username'    => '',
    'password'    => '',
    'email'       => '',
    'fullname'    => '',
    'NIK'         => '',
    'gender'      => '',
    'address'     => '',
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $key => $val) {
        $old[$key] = isset($_POST[$key]) ? trim($_POST[$key]) : '';
    }

    if ($old['division_id'] === '') {
        $errors[] = 'Divisi wajib dipilih.';
    }
    if ($old['username'] === '') {
        $errors[] = 'Username wajib diisi.';
    }
    if ($old['password'] === '') {
        $errors[] = 'Password wajib diisi.';
    }
    if ($old['email'] === '') {
        $errors[] = 'Email wajib diisi.';
    }
    if ($old['fullname'] === '') {
        $errors[] = 'Nama lengkap wajib diisi.';
    }
    if ($old['NIK'] === '') {
        $errors[] = 'NIK wajib diisi.';
    }
    if ($old['gender'] !== 'L' && $old['gender'] !== 'P') {
        $errors[] = 'Gender wajib dipilih.';
    }

    if (empty($errors)) {
        $id = $karyawanController->create($old);
        if ($id) {
            header('Location: index.php?success=1');
            exit;
        } else {
            $errors[] = 'Gagal menyimpan data: ' . mysql_error($conn);
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Test | Tambah Karyawan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.3.0/index.css"
      crossorigin="anonymous"
      media="print"
      onload="this.media = 'all'"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="frontend_template/admin-lte-v4.10.1/dist/css/adminlte.css" />
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="index.php" class="nav-link">Home</a></li>
          </ul>
        </div>
      </nav>

      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href="index.php" class="brand-link">
            <span class="brand-text fw-light">CRUD Test</span>
          </a>
        </div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation">
              <li class="nav-item">
                <a href="index.php" class="nav-link active">
                  <i class="nav-icon bi bi-people"></i>
                  <p>Karyawan</p>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </aside>

      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h1 class="mb-0 fs-3">Tambah Karyawan</h1>
              </div>
              <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Karyawan</a></li>
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
                  <div class="card-header">
                    <h3 class="card-title">Form Karyawan Baru</h3>
                  </div>

                  <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger m-3 mb-0">
                      <ul class="mb-0">
                        <?php foreach ($errors as $e) : ?>
                          <li><?php echo htmlspecialchars($e); ?></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  <?php endif; ?>

                  <form method="post" action="tambah.php">
                    <div class="card-body">
                      <div class="mb-3">
                        <label for="division_id" class="form-label">Divisi</label>
                        <select id="division_id" name="division_id" class="form-select" required>
                          <option value="">-- Pilih Divisi --</option>
                          <?php foreach ($divisions as $div) : ?>
                            <option value="<?php echo htmlspecialchars($div['id']); ?>" <?php echo ($old['division_id'] == $div['id']) ? 'selected' : ''; ?>>
                              <?php echo htmlspecialchars($div['division_name']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <?php if (empty($divisions)) : ?>
                          <div class="form-text text-warning">Belum ada data divisi. Jalankan migrasi dan tambahkan divisi terlebih dahulu.</div>
                        <?php endif; ?>
                      </div>

                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="username" class="form-label">Username</label>
                          <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($old['username']); ?>" required maxlength="50" />
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="password" class="form-label">Password</label>
                          <input type="password" id="password" name="password" class="form-control" required maxlength="255" />
                        </div>
                      </div>

                      <div class="mb-3">
                        <label for="fullname" class="form-label">Nama Lengkap</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo htmlspecialchars($old['fullname']); ?>" required maxlength="150" />
                      </div>

                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($old['email']); ?>" required maxlength="100" />
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="NIK" class="form-label">NIK</label>
                          <input type="text" id="NIK" name="NIK" class="form-control" value="<?php echo htmlspecialchars($old['NIK']); ?>" required maxlength="20" />
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label d-block">Gender</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="gender" id="genderL" value="L" <?php echo ($old['gender'] === 'L') ? 'checked' : ''; ?> required />
                          <label class="form-check-label" for="genderL">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="gender" id="genderP" value="P" <?php echo ($old['gender'] === 'P') ? 'checked' : ''; ?> required />
                          <label class="form-check-label" for="genderP">Perempuan</label>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($old['address']); ?></textarea>
                      </div>
                    </div>

                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                      <a href="index.php" class="btn btn-secondary">Batal</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>

      <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">CRUD Test</div>
        <strong>AdminLTE v4 template</strong>
      </footer>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <script src="frontend_template/admin-lte-v4.10.1/dist/js/adminlte.js"></script>
  </body>
</html>
