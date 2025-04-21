<?php
include('config/db.php');
$id = $_GET['id'];
$data = $conn->query("SELECT * FROM content_planner WHERE id=$id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_brand'];
    $medsos = $_POST['target_medsos'];
    $produk = $_POST['jenis_produk'];
    $tanggal = $_POST['tanggal_upload'];
    $jumlah = $_POST['jumlah_video'];
    $rate = $_POST['rate_per_video'];
    $status = $_POST['status'];
    $total = $jumlah * $rate;

    $sql = "UPDATE content_planner SET
            nama_brand='$nama', target_medsos='$medsos', jenis_produk='$produk',
            tanggal_upload='$tanggal', jumlah_video=$jumlah, rate_per_video=$rate,
            total_jumlah=$total, status='$status' WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Data</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h2>Edit Data</h2>
    <form method="POST">
      <div class="row mb-2">
        <div class="col"><input name="nama_brand" class="form-control" value="<?= $data['nama_brand'] ?>" required></div>
        <div class="col"><input name="target_medsos" class="form-control" value="<?= $data['target_medsos'] ?>" required></div>
        <div class="col"><input name="jenis_produk" class="form-control" value="<?= $data['jenis_produk'] ?>" required></div>
      </div>
      <div class="row mb-2">
        <div class="col"><input type="date" name="tanggal_upload" class="form-control" value="<?= $data['tanggal_upload'] ?>" required></div>
        <div class="col"><input type="number" name="jumlah_video" class="form-control" value="<?= $data['jumlah_video'] ?>" required></div>
        <div class="col"><input type="number" name="rate_per_video" class="form-control" value="<?= $data['rate_per_video'] ?>" required></div>
      </div>
      <div class="row mb-3">
        <div class="col">
          <select name="status" class="form-control" required>
            <option value="Belum" <?= $data['status'] == 'Belum' ? 'selected' : '' ?>>Belum</option>
            <option value="Sudah" <?= $data['status'] == 'Sudah' ? 'selected' : '' ?>>Sudah</option>
          </select>
        </div>
        <div class="col">
          <button type="submit" class="btn btn-success">Simpan Perubahan</button>
          <a href="index.php" class="btn btn-secondary">Kembali</a>
        </div>
      </div>
    </form>
  </div>
</body>
</html>
