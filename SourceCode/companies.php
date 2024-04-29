<?php

include('config/db.php');
include('classes/DB.php');
include('classes/Companies.php');
include('classes/Template.php');

// membuat objek Companies
$companies = new Companies($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$companies->open();
$companies->getCompanies();

$mainTitle = 'Companies'; // judul

// cari Companies
if (isset($_POST['search-in-table'])) {
    // methode mencari data Companies
    $companies->searchCompanies($_POST['search-' . $mainTitle]);
} else {
    // method menampilkan data Companies
    $companies->getCompanies();
}

// menangkap data yang akna ditambahkan
if (isset($_POST['btn-create-Companies'])) {
    // menyimpan data yang ditambahkan ke database
    if ($companies->addCompanies($_POST, $_FILES) > 0) {
        // jika berhasil
        echo "<script>
                alert('Data added successfully!');
                document.location.href = 'companies.php';
            </script>";
    } else {
        // jika gagal
        echo "<script>
                alert('Failed to add data!');
                document.location.href = 'companies.php';
            </script>";
    }
}

// membuat objek template
$view = new Template('templates/skintabel.html');

// membuat tabel Companies list
$header = '<tr>
<th scope="row">No.</th>
<th scope="row">Photo</th>
<th scope="row">Companies Name</th>
<th scope="row">Location</th>
<th scope="row">Action</th>
</tr>';

$data = null;
$no = 1;
$formLabel = 'Companies';

while ($companiesData = $companies->getResult()) {
    // menampilkan setiap data Companies
    $data .= '<tr>
    <th scope="row">' . $no . '</th>
    <td><img src="assets/images/Companiess/' . $companiesData['photo'] . '" class="card-img-top" alt="' . $companiesData['photo'] . '"style="width: 350px; height: auto;"></td>
    <td>' . $companiesData['name'] . '</td>
    <td>' . $companiesData['location'] . '</td>
    <td style="font-size: 22px;">
        <a type="button" data-bs-toggle="modal" data-bs-target="#update-' . $mainTitle . '-' . $companiesData['id'] . '"><i class="bi bi-pencil-square text-warning"></i></a>
        &nbsp;<a href="companies.php?delete=' . $companiesData['id'] . '" title="Delete Data"><i class="bi bi-trash-fill text-danger"></i></a>
        </td>
    </tr>';

    // form berbentuk modal untuk memperbarui data Companies
    $data .= '<div class="modal fade" id="update-' . $mainTitle . '-' . $companiesData['id'] . '" tabindex="-1" aria-labelledby="update-' . $mainTitle . '-' . $companiesData['id'] . '-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="update-' . $mainTitle . '-' . $companiesData['id'] . '-label">Update ' . $mainTitle . '</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="companies.php?id=' . $companiesData['id'] . '" method="POST" id="form-update' . $companiesData['id'] . '" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="' . $companiesData['name'] . '" placeholder="Enter Companies Name" required />
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" value="' . $companiesData['location'] . '" placeholder="Enter Location" required />
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <div class="input-group">
                            <input class="form-control" type="file" id="photo" name="photo" />
                        </div>
                    </div>
                </form>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background-color: #a9dbcf; color: #2e4f4f" name="btn-update-Companies" id="btn-update-Companies" form="form-update' . $companiesData['id'] . '">Update Companies</button>
                </div>
            </div>
        </div>
    </div>';

    $no++;
}

// pengaturan footer agar tetap di bawah walaupun datanya sedikit
$footer = null;

if ($no > 5) {
    $footer .= '
    <script>
      window.addEventListener("DOMContentLoaded", function () {
        document.getElementById("footer").classList.add("non-fixed");
      });
    </script>
    ';
}

// menangkap data yang akan diperbarui
if (isset($_POST['btn-update-Companies'])) {
    $id = $_GET['id']; // menangkap id Companies yang akan diperbarui
    // memperbarui data di database dengan data baru
    if ($companies->updateCompanies($id, $_POST, $_FILES) > 0) {
        // jika berhasil
        echo "<script>
                    alert('Data updated successfully!');
                    document.location.href = 'companies.php';
                </script>";
    } else {
        // jika gagal
        echo "<script>
                alert('Failed to update data!');
                document.location.href = 'companies.php';
            </script>";
    }
}

// menangkap data yang akan dihapus
if (isset($_GET['delete'])) {
    $id = $_GET['delete']; // menangkap id Companies yang akan dihapus
    if ($id > 0) {
        // menghapus data dari database
        if ($companies->deleteCompanies($id) > 0) {
            // jika berhasil
            echo "<script>
                alert('Data deleted successfully!');
                document.location.href = 'companies.php';
            </script>";
        } else {
            // jika gagal
            echo "<script>
                alert('Failed to delete data!');
                document.location.href = 'companies.php';
            </script>";
        }
    }
}

// menutup koneksi database
$companies->close();

// mengisi template dengan data yang sudah diproses
$view->replace('DATA_MAIN_TITLE', $mainTitle);
$view->replace('SET_COLOR_1', 'white');
$view->replace('SET_COLOR_2', '#000000');
$view->replace('SET_COLOR_3', 'white');
$view->replace('DATA_TABEL_HEADER', $header);
$view->replace('DATA_FORM_LABEL', $formLabel);
$view->replace('DATA_TABEL', $data);
$view->replace('SET_FOOTER', $footer);

// menampilkan template
$view->write();