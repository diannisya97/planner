<?php 
// Include database configuration
include('config/db.php');

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

// Fetch data from the database
$data = $conn->query("SELECT * FROM content_planner ORDER BY id DESC");
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
            background-color: #f8f9fa; /* Warna latar belakang footer */
            padding: 10px 0;
            box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1); /* Opsional: Tambahkan bayangan */
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

                <!-- Overview Cards -->
                <div class="row g-3">
                    <?php
                    // Count the number of rows in the content_planner table
                    $countQuery = $conn->query("SELECT COUNT(*) AS total_videos FROM content_planner");
                    $countResult = $countQuery->fetch_assoc();
                    $totalVideos = $countResult['total_videos'];

                    // Calculate the total income from the content_planner table
                    $incomeQuery = $conn->query("SELECT SUM(total_jumlah) AS total_income FROM content_planner");
                    $incomeResult = $incomeQuery->fetch_assoc();
                    $totalIncome = $incomeResult['total_income'];

                    // Count the number of rows in the content_planner table with status 'Sudah'
                    $completedQuery = $conn->query("SELECT COUNT(*) AS completed_videos FROM content_planner WHERE status = 'Sudah'");
                    $completedResult = $completedQuery->fetch_assoc();
                    $completedVideos = $completedResult['completed_videos'];

                    // Count the number of rows in the content_planner table with status 'Belum'
                    $uncompletedQuery = $conn->query("SELECT COUNT(*) AS uncompleted FROM content_planner WHERE status = 'Belum'");
                    $uncompletedResult = $uncompletedQuery->fetch_assoc();
                    $belumselesai = $uncompletedResult['uncompleted'];
                    ?>
                   
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
                <h5 class="mt-5">Project/Website List</h5>
                <div class="project-card mb-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Client 1</h6>
                            <p class="text-muted mb-1">https://luthfishogir...</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success mb-1">online</span><br>
                            <small class="text-muted">⏱️ 0.138 ms</small>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>NO</th>
                                <th>Nama Brand</th>
                                <th>Medsos</th>
                                <th>Produk</th>
                                <th>Tanggal</th>
                                <th>Video</th>
                                <th>Rate</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
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
        date_default_timezone_set('Asia/Jakarta'); // Set timezone ke Asia/Jakarta
        echo "Tanggal: " . date('d-m-Y') . " | Jam: " . date('H:i:s'); 
        ?>
    </p>
    <p>© 2025 Made By dikodein </p>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>
