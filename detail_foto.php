<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "gallery");
require 'fungsi.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$foto = query("SELECT * FROM foto WHERE FotoID = $id")[0];
$komen = query("SELECT * FROM komentarfoto WHERE FotoID=$id");
$jumlah_like = mysqli_query($conn, "SELECT COUNT(*) as total_like FROM likefoto WHERE FotoID = $id");
$total_like = mysqli_fetch_assoc($jumlah_like)['total_like'];

$userID = $_SESSION['userID'];
$user_like_query = mysqli_query($conn, "SELECT * FROM likefoto WHERE FotoID = $id AND UserID = $userID");
$user_already_liked = mysqli_num_rows($user_like_query) > 0;

if (isset($_POST["komentar"])) {

    if (komentar($_POST) > 0) {
        echo "<script>
        alert('berhasil menambah komentar');
        document.location.href = 'detail_foto.php';
        </script>";
    } else {
        echo "<script>
        alert('gagal menambah komentar');
        document.location.href = 'detail_foto.php';
        </script>";
    }
}
if (isset($_POST["like"])) {
    if ($user_already_liked) {
        // If user has already liked, perform unlike
        $query = "DELETE FROM likefoto WHERE FotoID = $id AND UserID = $userID";
        $result = mysqli_query($conn, $query);
        if ($result) {
            echo "<script>
            alert('Anda tidak menyukai foto ini lagi.');
            document.location.href = 'detail_foto.php?id=$id';
            </script>";
        } else {
            echo "<script>
            alert('Gagal menghapus like.');
            document.location.href = 'detail_foto.php?id=$id';
            </script>";
        }
    } else {
        // If user hasn't liked, perform like
        $tanggalLike = date('Y-m-d H:i:s');
        $query = "INSERT INTO likefoto VALUES ('', '$id', '$userID', '$tanggalLike')";
        $result = mysqli_query($conn, $query);
        if ($result) {
            echo "<script>
            alert('Anda menyukai foto ini.');
            document.location.href = 'detail_foto.php?id=$id';
            </script>";
        } else {
            echo "<script>
            alert('Gagal menyukai foto ini.');
            document.location.href = 'detail_foto.php?id=$id';
            </script>";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Gambar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="card mt-4 mx-auto" style="width:80%">
                <img class="card-img-left rounded img-fluid" src="<?= $foto['LokasiFile']; ?>">
                <div class="card-body">
                    <h4 class="card-title h5 h4-sm"><?= $foto['JudulFoto'] ?></h4>
                    <p class="card-text"><?= $foto['DeskripsiFoto']; ?></p>
                    <p class="card-text">Jumlah Like: <?= $total_like ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?= $foto['FotoID']; ?>">
                        <p class="card-text mt-3" style="display: flex; align-items: center;">
                            Apakah fotonya bagus ?
                            <button type="submit" class="btn btn-primary" name="like" style="margin-left: auto;">
                                <?php if ($user_already_liked) : ?>
                                    <i class="bi bi-heart-fill"></i> Unlike
                                <?php else : ?>
                                    <i class="bi bi-heart"></i> Like
                                <?php endif; ?>
                            </button>
                        </p>
                    </form>
                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?= $foto['FotoID']; ?>">
                        <textarea class="form-control mb-3" name="komen" id="komen" cols="30" rows="3" placeholder="Tuliskan komentar disini ..."></textarea>
                        <button type="submit" class="btn btn-primary" name="komentar">
                            Kirim
                        </button>
                    </form>
                </div>
                <div class="card-footer">
                    <p>Daftar Komentar</p>
                    <?php foreach ($komen as $k) : ?>
                        <p><b>User <?= $k['UserID']; ?></b> <?= $k['IsiKomentar']; ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>