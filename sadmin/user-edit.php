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

$id_karyawan = $_GET["id"];

$query_karyawan = "SELECT B.nm_bagian, U.* FROM tbl_bagian B, tbl_user U WHERE B.id_bagian = U.id_bagian AND U.id_user = $id_karyawan";
$result_karyawan = mysqli_query($conn, $query_karyawan);
$row_karyawan = mysqli_fetch_assoc($result_karyawan);

if (isset($_POST["submit"])) {

    $nm_user = htmlspecialchars($_POST["nm_user"]);
    $nik = htmlspecialchars($_POST["nik"]);
    $id_bagian = htmlspecialchars($_POST["id_bagian"]);
    $telp = htmlspecialchars($_POST["telp"]);
    $email = htmlspecialchars($_POST["email"]);
    $level = htmlspecialchars($_POST["level"]);
    $status = htmlspecialchars($_POST["status"]);

    $query = "UPDATE tbl_user SET
    nm_user ='$nm_user',
    nik ='$nik',
    id_bagian ='$id_bagian',
    telp ='$telp',
    email ='$email',
    level ='$level',
    status ='$status'
    WHERE id_user = $id_karyawan";
    $edit = mysqli_query($conn, $query);

    if ($edit) {
        echo "<script>
            alert('Data berhasil update...!');
            document.location.href = 'user.php';
            </script>";
    } else {
        echo "<script>
            alert('Data gagal update..!');
            history.go(-1);
            </script>";
    }
}
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
    <title>Simawar - Edit User</title>
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
                                <li class="breadcrumb-item"><a href="user.php">Data User</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
                <div class="row">
                    <div class="col-xl-12 mx-auto">
                        <h6 class="mb-0 text-uppercase">Data User</h6>
                        <hr />
                        <div class="card border-top border-0 border-4 border-primary">
                            <div class="card-body px-5 pb-5">
                                <div class="card-title d-flex align-items-center">
                                    <div><i class="bx bx-plus me-1 font-22 text-primary"></i>
                                    </div>
                                    <h5 class="mb-0 text-primary">Edit User</h5>
                                </div>
                                <hr>
                                <p>Apabila diblokir, maka user tiak akan bisa login..!</p>
                                <form class="row g-3" method="POST" target="">
                                    <div class="col-12">
                                        <label for="username" class="form-label">Username:</label>
                                        <input type="text" class="form-control" name="username" id="username" value="<?php echo $row_karyawan["username"] ?>" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label for="nm_user" class="form-label">Name Lengkap :</label>
                                        <input type="text" class="form-control" name="nm_user" id="nm_user" value="<?php echo $row_karyawan["nm_user"] ?>" placeholder="Nama lengkap beserta gelar" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="nik" class="form-label">NIP/NIK :</label>
                                        <input type="number" class="form-control" name="nik" id="nik" value="<?php echo $row_karyawan["nik"] ?>" placeholder="NIP/NIK Kepegawaian" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="id_bagian" class="form-label">Bagian :</label>
                                        <select name="id_bagian" id="id_bagian" class="form-select" required>
                                            <?php
                                            $query_bagian = "SELECT * FROM tbl_bagian";
                                            $result_bagian = mysqli_query($conn, $query_bagian);
                                            while ($row_bagian = mysqli_fetch_assoc($result_bagian)) {;
                                            ?>
                                                <option value="<?php echo $row_bagian["id_bagian"] ?>" <?php if (!(strcmp($row_bagian["id_bagian"], htmlentities($row_karyawan["id_user"], ENT_COMPAT, 'utf-8')))) {
                                                                                                            echo "SELECTED";
                                                                                                        } ?>><?php echo $row_bagian["nm_bagian"] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="telp" class="form-label">Telepon :</label>
                                        <input type="number" class="form-control" name="telp" id="telp" value="<?php echo $row_karyawan["telp"] ?>" placeholder="Telpon aktif WA" required>
                                    </div>

                                    <div class="col-12">
                                        <label for="email" class="form-label">Email :</label>
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $row_karyawan["email"] ?>" placeholder="Email aktif" required>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Level :</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="level" id="inlineRadio1" value="1" <?php if ($row_karyawan["level"] == 1) echo 'checked' ?>>
                                            <label class="form-check-label" for="inlineRadio1">Super Admin</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="level" id="inlineRadio2" value="2" <?php if ($row_karyawan["level"] == 2) echo 'checked' ?>>
                                            <label class="form-check-label" for="inlineRadio2">Admin</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="level" id="inlineRadio3" value="3" <?php if ($row_karyawan["level"] == 3) echo 'checked' ?>>
                                            <label class="form-check-label" for="inlineRadio3">User</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="level" id="inlineRadio4" value="4" <?php if ($row_karyawan["level"] == 4) echo 'checked' ?>>
                                            <label class="form-check-label" for="inlineRadio4">Pimpinan</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Status :</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="1" <?php if ($row_karyawan["status"] == 1) echo 'checked' ?>>
                                            <label class="form-check-label" for="inlineRadio5">Aktif</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadio4" value="0" <?php if ($row_karyawan["status"] == 0) echo 'checked' ?>>
                                            <label class="form-check-label" for="inlineRadio6">Blokir</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary px-5" name="submit">Simpan</button>
                                        <button type="button" class="btn btn-secondary px-5" onclick="self.history.back()">Cancel</button>
                                    </div>
                                </form>
                            </div>
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

        <!--end wrapper-->
        <!-- Bootstrap JS -->
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <!--plugins-->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/plugins/simplebar/js/simplebar.min.js"></script>
        <script src="../assets/plugins/metismenu/js/metisMenu.min.js"></script>
        <script src="../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
        <!--app JS-->
        <script src="../assets/js/app.js"></script>
</body>

</html>