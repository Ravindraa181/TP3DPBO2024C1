<?php

include('config/db.php');
include('classes/DB.php');
include('classes/Idol.php');
include('classes/Group.php');
include('classes/Companies.php');
include('classes/Template.php');

// buat instance Idol
$listidol = new Idol($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

// buka koneksi
$listidol->open();
// tampilkan data Idol
$listidol->getIdol();

// cari Idol
if (isset($_POST['search-in-index'])) {
    // methode mencari data Idol
    $listidol->searchIdol($_POST['search']);
} else {
    // method menampilkan data Idol
    if (isset($_GET['sort'])) {
        $sortDirection = isset($_GET['sort']) && in_array($_GET['sort'], ['asc', 'desc']) ? $_GET['sort'] : 'asc';
        $listidol->getIdolSort($sortDirection);
    } else {
        $listidol->getIdolJoin();
    }
}

$data = null;

// ambil data Idol
// gabungkan dengan tag html
// untuk dipassing ke skin/template
$data = null;
$i = 0;
while ($row = $listidol->getResult()) {
    $data .= '<div class="col gx-2 gy-3 justify-content-center">' .
        '<div class="card pt-4 px-2 idol-thumbnail">
        <a href="detail.php?id=' . $row['id'] . '">
            <div class="row justify-content-center">
                <img src="assets/images/idols/' . $row['photo'] . '" class="card-img-top" alt="' . $row['photo'] . '"style="width: 250px; height: auto;">
            </div>
            <div class="card-body">
                <p class="card-text idol-name my-0">' . $row['name'] . '</p>
                <p class="card-text group-name">' . $row['group_name'] . '</p>
                <p class="card-text position my-0">' . $row['position_name'] . '</p>
            </div>
        </a>
    </div>    
    </div>';
    $i++;
}

// pengaturan footer agar tetap di bawah walaupun datanya sedikit
$footer = null;

if ($i > 5) {
    $footer .= '
    <script>
      window.addEventListener("DOMContentLoaded", function () {
        document.getElementById("footer").classList.add("non-fixed");
      });
    </script>
    ';
}

// menangkap data yang akan dihapus
if (isset($_GET['delete'])) {
    $id = $_GET['delete']; // menangkap id idol yang akan dihapus
    if ($id > 0) {
        // menghapus data dari database
        if ($listidol->deleteIdol($id) > 0) {
            // jika berhasil
            echo "<script>
                alert('Data deleted successfully!');
                document.location.href = 'index.php';
            </script>";
        } else {
            // jika gagal
            echo "<script>
                alert('Failed to delete data!');
                document.location.href = 'index.php';
            </script>";
        }
    }
}

// tutup koneksi
$listidol->close();

// buat instance template
$home = new Template('templates/skin.html');

// simpan data ke template
$home->replace('IDOL_DATA', $data);
$home->replace('SET_FOOTER', $footer);
$home->write();