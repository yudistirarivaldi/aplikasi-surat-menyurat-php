<?php
session_start();

if ($_SESSION["level"] == 2 or $_SESSION["level"] == 3 or $_SESSION["level"] == 4) {
    header("Location: logout.php");
    exit;
}

if (!isset($_SESSION["login"])) {
    header("Location: ../index.php");
    exit;
}

include "../koneksi.php";

$id_user = $_SESSION["id_user"];

$query_user = "SELECT * FROM tbl_user WHERE id_user = '$id_user'";
$result_user = mysqli_query($conn, $query_user);
$row_user = mysqli_fetch_assoc($result_user);

$query_srt = "SELECT U.nm_user, SK.* FROM tbl_user U, tbl_srt_klr SK WHERE U.id_user = SK.penandatangan ORDER BY SK.tgl_srt DESC";
$result_srt = mysqli_query($conn, $query_srt);
?>
<!doctype html>
<html lang="en" class="<?php echo $row_user["theme"] ?>">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="../assets/images/favicon-32x32.png" type="image/png" />
    <!--plugins-->
    <link href="../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="../assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="../assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="../assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="../assets/css/pace.min.css" rel="stylesheet" />
    <script src="../assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">
    <link href="../assets/css/icons.css" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="../assets/css/dark-theme.css" />
    <link rel="stylesheet" href="../assets/css/semi-dark.css" />
    <link rel="stylesheet" href="../assets/css/header-colors.css" />
    <title>Simawar - Surat Keluar</title>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">

        <?php include "theme-sidebar.php" ?>

        <?php include "theme-header.php" ?>

        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.php"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Surat Keluar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
                <h5 class="my-4 text-uppercase">Data Surat Keluar</h5>
                <div class="col">
                    <a href="srt-klr-tambah.php" class="btn btn-primary"><i class='bx bx-plus mr-1'></i>Tambah Data</a>
                </div>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table table-hover table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Action</th>
                                        <th>Status</th>
                                        <th>Tgl TTD</th>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Hal</th>
                                        <th>Untuk</th>
                                        <th>Penandatangan</th>
                                        <th>Lampiran</th>
                                        <th>Tgl Input</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row_srt = mysqli_fetch_assoc($result_srt)) { ?>
                                        <tr>
                                            <td>
                                                <?php if ($row_srt["status"] == 'Ditandatangani') { ?>
                                                    <div class="d-flex order-actions">
                                                        <a class="text-light bg-secondary border-0" title="Edit"><i class='bx bxs-edit'></i></a>
                                                        <a class="ms-4 text-light bg-secondary border-0" title="Hapus"><i class='bx bxs-trash'></i></a>
                                                        <a href="../phpqrcode/images/image<?php echo $row_srt["id_srt_klr"] ?>.png" class="ms-4 text-light bg-info border-0" title="Barcode" target="_blank"><i class="bx bx-barcode"></i></a>
                                                    </div>
                                                <?php } else if ($row_srt["status"] == 'New') { ?>
                                                    <div class="d-flex order-actions">
                                                        <a href="srt-klr-edit.php?id=<?php echo $row_srt["id_srt_klr"] ?>" class="text-light bg-success border-0" title="Edit"><i class='bx bxs-edit'></i></a>
                                                        <a href="srt-klr-hapus.php?id=<?php echo $row_srt["id_srt_klr"] ?>" class="ms-4 text-light bg-warning border-0" title="Hapus" onClick="return confirm('Apakah anda yakin ingin menghapus data ini...?')"><i class='bx bxs-trash'></i></a>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($row_srt["status"] == 'New') { ?>
                                                    <a href="srt-klr-acc.php?id=<?php echo $row_srt["id_srt_klr"] ?>"><span class="badge bg-light-danger text-danger w-100"><?php echo $row_srt["status"] ?></span></a>
                                                <?php } else if ($row_srt["status"] == 'Ditandatangani') { ?>
                                                    <span class="badge bg-light-primary text-primary w-100"><?php echo $row_srt["status"] ?></span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($row_srt["status"] == 'New') { ?>
                                                    <span class="badge bg-light-danger text-danger w-100">Belum Ditandatangani</span>
                                                <?php } else if ($row_srt["status"] == 'Ditandatangani') { ?>
                                                    <?php echo $row_srt["tgl_ttd"] ?>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="../assets/files/<?php echo $row_srt["file"] ?>" target="_blank"><?php echo $row_srt["no_srt"] ?><i class="lni lni-link"></i></a>
                                            </td>
                                            <td><?php echo $row_srt["tgl_srt"] ?> </td>
                                            <td><?php echo $row_srt["hal"] ?> </td>
                                            <td><?php echo $row_srt["untuk"] ?> </td>
                                            <td><?php echo $row_srt["nm_user"] ?> </td>
                                            <td><?php echo $row_srt["lampiran"] ?> </td>
                                            <td><?php echo $row_srt["tgl_input"] ?> </td>
                                        <?php } ?>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <?php include "theme-footer.php" ?>

    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="../assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="../assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
    <!--app JS-->
    <script src="../assets/js/app.js"></script>
</body>

</html>