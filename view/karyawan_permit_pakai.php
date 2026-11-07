<?php
require_once __DIR__ . '/../controller/karyawanPermitController.php';
require_once __DIR__ . '/../utils/validation.php';

$kpController = new KaryawanPermitController($conn);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { header('Location: karyawan_permit.php'); exit; }

$row = $kpController->find($id);
if (!$row) { header('Location: karyawan_permit.php?error=notfound'); exit; }

$errors = array();
$form = array('start_date' => '', 'end_date' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['start_date'] = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
    $form['end_date']   = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';

    $e = validate_date_field($form['start_date'], 'Tanggal mulai'); if ($e) { $errors[] = $e; }
    $e = validate_date_field($form['end_date'], 'Tanggal selesai'); if ($e) { $errors[] = $e; }

    if (empty($errors)) {
        $startTs = parse_date_ymd($form['start_date']);
        $endTs   = parse_date_ymd($form['end_date']);
        if ($endTs < $startTs) {
            $errors[] = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
        } else {
            $amountDate = count_days_inclusive($startTs, $endTs);
            $quota = (int) $row['days_amount'];
            if ($amountDate > $quota) {
                $errors[] = 'Jumlah hari (' . $amountDate . ') melebihi sisa kuota (' . $quota . ').';
            } else {
                $res = $kpController->useQuota($id, $form['start_date'], $form['end_date'], $amountDate);
                if ($res === true) {
                    header('Location: karyawan_permit_detail.php?id=' . (int) $id . '&used=1');
                    exit;
                } else {
                    $errors[] = $res;
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Test | Pakai Kuota Permit</title>
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
              <div class="col-sm-6"><h1 class="mb-0 fs-3">Pakai Kuota Permit</h1></div>
              <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="karyawan_permit.php">Permit Karyawan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pakai Kuota</li>
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
                <div class="card card-info card-outline mb-4">
                  <div class="card-header"><h3 class="card-title">Form Pakai Kuota</h3></div>
                  <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger m-3 mb-0"><ul class="mb-0"><?php foreach ($errors as $e) : ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div>
                  <?php endif; ?>
                  <form method="post" action="karyawan_permit_pakai.php?id=<?php echo (int) $id; ?>">
                    <div class="card-body">
                      <div class="mb-3">
                        <label class="form-label">Karyawan</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['karyawan_name']); ?>" readonly />
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Permit</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['permit_name']); ?>" readonly />
                      </div>
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label class="form-label">Tahun</label>
                          <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['year']); ?>" readonly />
                        </div>
                        <div class="col-md-6 mb-3">
                          <label class="form-label">Jumlah Hari (Kuota)</label>
                          <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['days_amount']); ?>" readonly />
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="start_date" class="form-label">Tanggal Mulai</label>
                          <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($form['start_date']); ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="end_date" class="form-label">Tanggal Selesai</label>
                          <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($form['end_date']); ?>" required />
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-info"><i class="bi bi-calendar-check"></i> Simpan</button>
                      <a href="karyawan_permit_detail.php?id=<?php echo (int) $id; ?>" class="btn btn-secondary">Batal</a>
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
