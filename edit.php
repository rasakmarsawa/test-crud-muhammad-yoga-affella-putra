<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/controller/divisiController.php';
require_once __DIR__ . '/controller/karyawanController.php';

$divisiController   = new DivisiController($conn);
$karyawanController = new KaryawanController($conn);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$row = $karyawanController->find($id);
if (!$row) {
    header('Location: index.php?error=notfound');
    exit;
}

$divisions = $divisiController->all();
if ($divisions === false) {
    $divisions = array();
}

$errors = array();
$data = array(
    'division_id' => $row['division_id'],
    'username'    => $row['username'],
    'password'    => '',
    'email'       => $row['email'],
    'fullname'    => $row['fullname'],
    'NIK'         => $row['NIK'],
    'gender'      => $row['gender'],
    'address'     => $row['address'],
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['division_id'] = isset($_POST['division_id']) ? trim($_POST['division_id']) : '';
    $data['username']    = isset($_POST['username']) ? trim($_POST['username']) : '';
    $data['password']    = isset($_POST['password']) ? trim($_POST['password']) : '';
    $data['email']       = isset($_POST['email']) ? trim($_POST['email']) : '';
    $data['fullname']    = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $data['NIK']         = isset($_POST['NIK']) ? trim($_POST['NIK']) : '';
    $data['gender']      = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $data['address']     = isset($_POST['address']) ? trim($_POST['address']) : '';

    if ($data['division_id'] === '') {
        $errors[] = 'Divisi wajib dipilih.';
    }
    if ($data['username'] === '') {
        $errors[] = 'Username wajib diisi.';
    }
    if ($data['email'] === '') {
        $errors[] = 'Email wajib diisi.';
    }
    if ($data['fullname'] === '') {
        $errors[] = 'Nama lengkap wajib diisi.';
    }
    if ($data['NIK'] === '') {
        $errors[] = 'NIK wajib diisi.';
    }
    if ($data['gender'] !== 'L' && $data['gender'] !== 'P') {
        $errors[] = 'Gender wajib dipilih.';
    }

    if (empty($errors)) {
        $ok = $karyawanController->update($id, $data);
        if ($ok) {
            header('Location: index.php?updated=1');
            exit;
        } else {
            $errors[] = 'Gagal memperbarui data: ' . mysql_error($conn);
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Test | Edit Karyawan</title>
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
                <h1 class="mb-0 fs-3">Edit Karyawan</h1>
              </div>
              <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Karyawan</a></li>
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
                  <div class="card-header">
                    <h3 class="card-title">Edit Data: <?php echo htmlspecialchars($row['fullname']); ?></h3>
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

                  <form method="post" action="edit.php?id=<?php echo (int) $id; ?>">
                    <div class="card-body">
                      <div class="mb-3">
                        <label for="division_id" class="form-label">Divisi</label>
                        <select id="division_id" name="division_id" class="form-select" required>
                          <option value="">-- Pilih Divisi --</option>
                          <?php foreach ($divisions as $div) : ?>
                            <option value="<?php echo htmlspecialchars($div['id']); ?>" <?php echo ($data['division_id'] == $div['id']) ? 'selected' : ''; ?>>
                              <?php echo htmlspecialchars($div['division_name']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="username" class="form-label">Username</label>
                          <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($data['username']); ?>" required maxlength="50" />
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="password" class="form-label">Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                          <input type="password" id="password" name="password" class="form-control" maxlength="255" />
                        </div>
                      </div>

                      <div class="mb-3">
                        <label for="fullname" class="form-label">Nama Lengkap</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo htmlspecialchars($data['fullname']); ?>" required maxlength="150" />
                      </div>

                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data['email']); ?>" required maxlength="100" />
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="NIK" class="form-label">NIK</label>
                          <input type="text" id="NIK" name="NIK" class="form-control" value="<?php echo htmlspecialchars($data['NIK']); ?>" required maxlength="20" />
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label d-block">Gender</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="gender" id="genderL" value="L" <?php echo ($data['gender'] === 'L') ? 'checked' : ''; ?> required />
                          <label class="form-check-label" for="genderL">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="gender" id="genderP" value="P" <?php echo ($data['gender'] === 'P') ? 'checked' : ''; ?> required />
                          <label class="form-check-label" for="genderP">Perempuan</label>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($data['address']); ?></textarea>
                      </div>
                    </div>

                    <div class="card-footer">
                      <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Perbarui</button>
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
