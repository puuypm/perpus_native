<?php
session_start();
include 'config/config.php';
// ob_start();
// ob_clean();
// kalo /jika session tidak ada, tolong redirect ke login
if (!isset($_SESSION['nama']))
    header("location:index.php?error=acces-failed");


// jika button disubmit, ambil nilai dari form, nama, email, password
if (isset($_POST['simpan'])) {
    $id_buku = $_POST['id_buku'];
    $id_anggota = $_POST['id_anggota'];
    $no_transaksi = $_POST['no_transaksi'];

    $insertPeminjam = mysqli_query($koneksi, "INSERT INTO
    peminjam (id_anggota, no_transaksi)
    VALUES('$id_anggota','$no_transaksi')");
    $id_peminjam = mysqli_insert_id($koneksi);

    foreach ($id_buku as $key => $buku) {
        $books = $id_buku[$key];
        $tanggal_pinjam = $_POST['tanggal_pinjam'][$key];
        $tanggal_pengembalian = $_POST['tanggal_pengembalian'][$key];

        $insertDetail = mysqli_query($koneksi, "INSERT INTO
        detail_peminjam (id_peminjam, id_buku, tanggal_pinjam, tanggal_pengembalian)
        VALUES('$id_peminjam','$books','$tanggal_pinjam','$tanggal_pengembalian')");
    }
    header("location:peminjaman.php?notif=tambah-success");



    // masukkan ke dalam table user dimana kolom nama di ambil nilainya dari inputan nama 
    //$insertPeminjam = mysqli_query($koneksi, "INSERT INTO
    //    peminjam (id_anggota)
    //    VALUES('$id_anggota')");
    //header("location:peminjaman.php?notif=tambah-success");
}

// jika parameter delete ada, buat perintah/query delete
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $delete = mysqli_query($koneksi, "DELETE FROM peminjam WHERE id='$id'");

    header('location:peminjaman.php?notif=delete-success');
}

// tampilkan semua data dari tabel user dimana id nya di ambil dari params edit
if (isset($_GET['detail'])) {
    $id = $_GET['detail'];


    $queryDetail = mysqli_query($koneksi, "SELECT * FROM peminjam WHERE id='$id'");
    $dataDetail  = mysqli_fetch_assoc($queryDetail);
}

if (isset($_POST['detail'])) {
    $id_anggota = $_POST['id_anggota'];

    $id = $_GET['detail'];

    // ubah data dari table user dimana nilai nama di ambil dari inputan nama 
    // dan nilai id user nya di ambil dari parameter

    $detail = mysqli_query($koneksi, "UPDATE peminjam SET 
        id_anggota='$id_anggota' WHERE id = '$id'");
}
$queryAnggota = mysqli_query($koneksi, "SELECT * FROM anggota ORDER BY id DESC");
$no_transaksi = mysqli_query($koneksi, "SELECT max(id) as kode FROM peminjam");
$data = mysqli_fetch_assoc($no_transaksi);
$huruf = "TR";
$urutan = $data['kode'];
$urutan++;

$kode_transaksi = $huruf . date("dmY") . sprintf("%03s", $urutan);

$queryBuku = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Blank</title>

    <!-- Custom fonts for this template-->
    <link href="assets/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/admin/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'inc/sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'inc/navbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <?php if (isset($_GET['detail'])) { ?>
                        <h1 class="h3 mb-4 text-gray-800">Detail Peminjaman</h1>
                    <?php } else { ?>
                        <h1 class="h3 mb-4 text-gray-800">Tambah Peminjaman</h1>
                    <?php } ?>

                    <?php if (isset($_GET['detail'])) { ?>
                        <div class="card">
                            <div class="card-header">Detail Peminjaman</div>
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="mb-3">
                                        <label for="">ID Anggota</label>
                                        <input value="<?php echo $dataDetail['id_anggota'] ?>" type="text" class="form-control" name="id_anggota" placeholder="Masukkan Nama Anggota..">
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Nomor Transaksi</label>
                                        <input type="text" readonly name="no_transaksi" value="<?php echo $kode_transaksi ?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <button onclick="window.print()" class="btn btn-primary">Print</button>
                                        <a href="peminjaman.php" class="btn btn-danger">Kembali</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="card">
                            <div class="card-header">Tambah Peminjaman</div>
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="mb-3 row">
                                        <div class="col-sm-3">
                                            <label for="">Nama Anggota</label>
                                            <select required name="id_anggota" id="" class="form-control">
                                                <option value="">Pilih Anggota</option>
                                                <?php while ($rowAnggota = mysqli_fetch_assoc($queryAnggota)) : ?>
                                                    <option value="<?php echo $rowAnggota['id'] ?>">
                                                        <?php echo $rowAnggota['nama_anggota'] ?></option>
                                                <?php endwhile ?>
                                            </select>
                                        </div>
                                        <div class=" col-sm-3">
                                            <button type="button" class="btn btn-success btn-sm">Anggota
                                                Baru</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Nomor Transaksi</label>
                                        <input type="text" readonly name="no_transaksi" value="<?php echo $kode_transaksi ?>" class="form-control">
                                    </div>
                                    <br><br>
                                    <div class="table-transaction">
                                        <div align="right">
                                            <button type="button" class="btn btn-primary btn-sm-3 btn-add mb-4">Tambah</button>
                                        </div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama Buku</th>
                                                    <th>Tanggal Peminjaman</th>
                                                    <th>Tanggal Pengembalian</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class=" mb-3">
                                        <input type="submit" class="btn btn-primary" name="simpan" value="Simpan">
                                        <a href="user.php" class="btn btn-danger">Kembali</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class=" sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current
                    session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="#">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="assets/admin/vendor/jquery/jquery.min.js"></script>
    <script src="assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/admin/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/admin/js/sb-admin-2.min.js"></script>
    <script>
        $('.btn-add').click(function() {

            let tbody = $('tbody');
            let newTr = "<tr>";

            newTr += "<td>";
            newTr += "<select class='form-control' name='id_buku[]'>";
            newTr += "<option>Pilih Buku</option>";
            newTr += "<?php while ($rowBuku = mysqli_fetch_assoc($queryBuku)) : ?>"
            newTr += "<option value=<?php echo $rowBuku['id'] ?>><?php echo $rowBuku['nama_buku'] ?></option>";
            newTr += "<?php endwhile ?>"
            newTr += "</select>";
            newTr += "</td>"
            newTr += "<td><input type='date' name='tanggal_pinjam[]' class='form-control'></td>";
            newTr += "<td><input type='date' name='tanggal_pengembalian[]' class='form-control'></td>";
            newTr += "<td><button type='button' class='btn btn-danger btn-sm-3 mb-3'>Hapus</button></td>";
            newTr += "</tr>";
            tbody.append(newTr);

        });
    </script>

</body>

</html>