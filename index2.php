<?php include('config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_brand'];
    $medsos = $_POST['target_medsos'];
    $produk = $_POST['jenis_produk'];
    $tanggal = $_POST['tanggal_upload'];
    $jumlah = $_POST['jumlah_video'];
    $rate = $_POST['rate_per_video'];
    $status = $_POST['status'];
    $total = $jumlah * $rate;

    $sql = "INSERT INTO content_planner (nama_brand, target_medsos, jenis_produk, tanggal_upload, jumlah_video, rate_per_video, total_jumlah, status)
            VALUES ('$nama', '$medsos', '$produk', '$tanggal', $jumlah, $rate, $total, '$status')";
    if ($conn->query($sql)) {
        // Redirect to the same page to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$data = $conn->query("SELECT * FROM content_planner ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Content Planner</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-4">
  <div class="container">
    <h2>Content Planner</h2>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addContentModal">
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
                <input name="target_medsos" class="form-control" placeholder="Target Medsos" required>
              </div>
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

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>NO</th><th>Nama Brand</th><th>Medsos</th><th>Produk</th><th>Tanggal</th><th>Video</th><th>Rate</th><th>Total</th><th>Status</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
       
        <?php 
                  $no = 1; // Initialize $no before the loop
                  while($row = $data->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
          
            <td><?= $row['nama_brand'] ?></td>
            <td><?= $row['target_medsos'] ?></td>
            <td><?= $row['jenis_produk'] ?></td>
            <td><?= $row['tanggal_upload'] ?></td>
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
</body>
</html>
