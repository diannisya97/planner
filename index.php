<?php 
// Include database configuration
include('config/db.php');

// Get selected month and year from URL parameters
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_brand'];
    $medsos = $_POST['target_medsos'];
    $produk = $_POST['jenis_produk'];
    $tanggal = $_POST['tanggal_upload'];
    $jumlah = $_POST['jumlah_video'];
    $rate = $_POST['rate_per_video'];
    $status = $_POST['status'];
    $total = $jumlah * $rate;

    // Insert data into the database
    $sql = "INSERT INTO content_planner (nama_brand, target_medsos, jenis_produk, tanggal_upload, jumlah_video, rate_per_video, total_jumlah, status)
            VALUES ('$nama', '$medsos', '$produk', '$tanggal', $jumlah, $rate, $total, '$status')";
    if ($conn->query($sql)) {
        // Redirect to the same page to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch data from the database with month filter
$data = $conn->query("SELECT * FROM content_planner 
                     WHERE MONTH(tanggal_upload) = '$selectedMonth' 
                     AND YEAR(tanggal_upload) = '$selectedYear'
                     ORDER BY id DESC");

// Count the number of rows in the content_planner table for selected month
$countQuery = $conn->query("SELECT COUNT(*) AS total_videos 
                           FROM content_planner 
                           WHERE MONTH(tanggal_upload) = '$selectedMonth' 
                           AND YEAR(tanggal_upload) = '$selectedYear'");
$countResult = $countQuery->fetch_assoc();
$totalVideos = $countResult['total_videos'];

// Calculate the total income from the content_planner table for selected month
$incomeQuery = $conn->query("SELECT SUM(total_jumlah) AS total_income 
                            FROM content_planner 
                            WHERE status = 'Sudah' 
                            AND MONTH(tanggal_upload) = '$selectedMonth' 
                            AND YEAR(tanggal_upload) = '$selectedYear'");
$incomeResult = $incomeQuery->fetch_assoc();
$totalIncome = $incomeResult['total_income'] ?? 0;

// Count completed videos for selected month
$completedQuery = $conn->query("SELECT COUNT(*) AS completed_videos 
                               FROM content_planner 
                               WHERE status = 'Sudah' 
                               AND MONTH(tanggal_upload) = '$selectedMonth' 
                               AND YEAR(tanggal_upload) = '$selectedYear'");
$completedResult = $completedQuery->fetch_assoc();
$completedVideos = $completedResult['completed_videos'];

// Count uncompleted videos for selected month
$uncompletedQuery = $conn->query("SELECT COUNT(*) AS uncompleted 
                                 FROM content_planner 
                                 WHERE status = 'Belum' 
                                 AND MONTH(tanggal_upload) = '$selectedMonth' 
                                 AND YEAR(tanggal_upload) = '$selectedYear'");
$uncompletedResult = $uncompletedQuery->fetch_assoc();
$belumselesai = $uncompletedResult['uncompleted'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MarettaAmp</title>
    <link href="css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Marlin+Sans:wght@400&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px 0;
            box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                height: 100vh;
                z-index: 1000;
                transition: 0.3s;
                background: white;
                padding: 20px;
            }

            .sidebar.active {
                left: 0;
            }

            .card-box {
                margin-bottom: 15px;
            }

            .table-responsive {
                margin-bottom: 60px; /* Memberikan ruang untuk footer */
            }

            .table th, .table td {
                white-space: nowrap;
                font-size: 14px;
            }

            .btn-sm {
                padding: 0.25rem 0.4rem;
                font-size: 12px;
            }

            /* Styling untuk card-box */
            .card-box {
                padding: 15px;
                border-radius: 8px;
                color: white;
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            .card-box span:first-child {
                font-size: 1.2rem;
                font-weight: bold;
            }

            .card-box span:last-child {
                font-size: 1.5rem;
            }

            /* Styling untuk tabel */
            .table {
                width: 100%;
                margin-bottom: 1rem;
                background-color: transparent;
                border-collapse: collapse;
            }

            .table th {
                background-color: #343a40;
                color: white;
                font-weight: 500;
                text-align: center;
                vertical-align: middle;
                padding: 12px 8px;
            }

            .table td {
                padding: 12px 8px;
                vertical-align: middle;
                text-align: center;
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0,0,0,.05);
            }

            .table-bordered {
                border: 1px solid #dee2e6;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #dee2e6;
            }

            /* Styling untuk tombol aksi */
            .btn-group-sm > .btn, .btn-sm {
                margin: 2px;
            }
        </style>
    </head>
    <body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar">
                <h4 class="text-center">Menu</h4>
                <a href="#">Dashboard</a>
                <a href="#">Project</a>
                <a href="#">Settings</a>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
                <div class="pt-3">
                    <h2>Dashboardn Beauty With Maretta</h2>
                    <h5 class="text-muted">Overview</h5>

                    <!-- Month Filter -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" class="d-flex align-items-center" id="filterForm">
                                <select name="month" class="form-select me-2" style="width: auto;" onchange="this.form.submit()">
                                    <?php
                                    $months = [
                                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                                        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                                        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                    ];
                                    foreach ($months as $value => $label) {
                                        $selected = ($value == $selectedMonth) ? 'selected' : '';
                                        echo "<option value='$value' $selected>$label</option>";
                                    }
                                    ?>
                                </select>
                                <select name="year" class="form-select me-2" style="width: auto;" onchange="this.form.submit()">
                                    <?php
                                    $currentYear = date('Y');
                                    for ($year = $currentYear; $year >= $currentYear - 2; $year--) {
                                        $selected = ($year == $selectedYear) ? 'selected' : '';
                                        echo "<option value='$year' $selected>$year</option>";
                                    }
                                    ?>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Overview Cards -->
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card-box bg-success">
                                <span><?= $totalVideos ?><br><small>Jumlah Masuk</small></span><span>✅</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-box" style="background-color:rgb(124, 176, 122);">
                                <span><?= number_format($totalIncome) ?><br><small>Jumlah Pemasukan</small></span><span>🤑</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                          <div class="card-box" style="background-color:rgb(147, 202, 169);">
                            <span><?= $completedVideos ?><br><small>Sudah Selesai</small></span><span>⚠️</span>
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-box" style="background-color:rgb(206, 135, 130);">
                            <span><?= $belumselesai ?><br><small>Belum Selesai</small></span><span>⚠️</span>
                            </div>
                        </div>
                    </div>


                    <!-- Add Data Button -->
                    <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#addContentModal">
                        Tambah Data
                    </button>
                    <!-- Modal -->
            <div class="modal fade" id="addContentModal" tabindex="-1" aria-labelledby="addContentModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="addContentModalLabel">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form method="POST">
                      <div class="mb-3">
                        <input name="nama_brand" class="form-control" placeholder="Nama Brand" required>
                      </div>
                        <div class="mb-3">
                        <label for="target_medsos" class="form-label">Target Medsos</label>
                        <select name="target_medsos[]" id="target_medsos" class="form-control" multiple required>
                          <option value="Instagram">Instagram</option>
                          <option value="TikTok">TikTok</option>
                          <option value="Shopee">Shopee</option>
                        </select>
                        </div>
                        <script>
                        // Ensure the selected values are joined into a string before submission
                        document.querySelector('form').addEventListener('submit', function (e) {
                          const select = document.getElementById('target_medsos');
                          const selectedValues = Array.from(select.selectedOptions).map(option => option.value);
                          const hiddenInput = document.createElement('input');
                          hiddenInput.type = 'hidden';
                          hiddenInput.name = 'target_medsos';
                          hiddenInput.value = selectedValues.join(', ');
                          this.appendChild(hiddenInput);
                          select.disabled = true; // Prevent sending the original select
                        });
                        </script>
                      <div class="mb-3">
                        <input name="jenis_produk" class="form-control" placeholder="Jenis Produk" required>
                      </div>
                      <div class="mb-3">
                        <input type="date" name="tanggal_upload" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <input type="number" name="jumlah_video" class="form-control" placeholder="Jumlah Video" required>
                      </div>
                      <div class="mb-3">
                        <input type="number" name="rate_per_video" class="form-control" placeholder="Rate Harga per Video" required>
                      </div>
                      <div class="mb-3">
                        <select name="status" class="form-control" required>
                          <option value="Belum">Belum</option>
                          <option value="Sudah">Sudah</option>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
                    <!-- Project/Website List -->
                    
                    <div class="project-card mb-4">
                        
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%">NO</th>
                                    <th style="width: 15%">Nama Brand</th>
                                    <th style="width: 15%">Medsos</th>
                                    <th style="width: 15%">Produk</th>
                                    <th style="width: 10%">Tanggal</th>
                                    <th style="width: 8%">Video</th>
                                    <th style="width: 12%">Rate</th>
                                    <th style="width: 12%">Total</th>
                                    <th style="width: 8%">Status</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1; // Initialize $no before the loop
                                while ($row = $data->fetch_assoc()): 
                                    // Format the tanggal_upload field to dd-mm-yy
                                    $formattedDate = date('d-m-Y', strtotime($row['tanggal_upload']));
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['nama_brand'] ?></td>
                                    <td><?= $row['target_medsos'] ?></td>
                                    <td><?= $row['jenis_produk'] ?></td>
                                    <td><?= $formattedDate ?></td>
                                    <td><?= $row['jumlah_video'] ?></td>
                                    <td><?= number_format($row['rate_per_video']) ?></td>
                                    <td><?= number_format($row['total_jumlah']) ?></td>
                                    <td><?= $row['status'] ?></td>
                                    <td>
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</a>
                                        <?php if ($row['status'] == 'Belum'): ?>
                                        <a href="update_status.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Tandai sebagai selesai?')">Selesai</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-4 py-3 bg-light">
        <p>
            <?php 
            date_default_timezone_set('Asia/Jakarta');
            echo "Tanggal: " . date('d-m-Y') . " | Jam: <span id='jam'></span>"; 
            ?>
        </p>
        <p>© 2025 Made By dikodein </p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app.js"></script>
    <script>
        function updateJam() {
            const now = new Date();
            const jam = now.getHours().toString().padStart(2, '0');
            const menit = now.getMinutes().toString().padStart(2, '0');
            const detik = now.getSeconds().toString().padStart(2, '0');
            document.getElementById('jam').textContent = `${jam}:${menit}:${detik}`;
        }

        // Update jam setiap detik
        setInterval(updateJam, 1000);
        // Panggil sekali saat halaman dimuat
        updateJam();
    </script>
    </body>
    </html>
