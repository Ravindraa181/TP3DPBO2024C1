<?php

include('config/db.php');
include('classes/DB.php');
include('classes/Group.php');
include('classes/Companies.php');
include('classes/Template.php');

// membuat objek group
$group = new Group($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$group->open();
$group->getGroup();

// membuat objek Companies
$companies = new Companies($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$companies->open();
$companies->getCompanies();

// membuat array Companies
$arrayCompanies = [];
while ($companiess = $companies->getResult()) {
    $arrayCompanies[] = $companiess;
}

$mainTitle = 'Group'; // judul

// cari Group
if (isset($_POST['search-in-table'])) {
    // methode mencari data Group
    $group->searchGroup($_POST['search-' . $mainTitle]);
} else {
    // method menampilkan data Group
    $group->getGroupJoin();
}

// menangkap data yang akan ditambahkan
if (isset($_POST['btn-create-group'])) {
    // menyimpan data yang ditambahkan ke database
    if ($group->addGroup($_POST, $_FILES) > 0) {
        // jika berhasil
        echo "<script>
                alert('Data added successfully!');
                document.location.href = 'group.php';
            </script>";
    } else {
        // jika gagal
        echo "<script>
                 alert('Failed to add data!');
                document.location.href = 'group.php';
            </script>";
    }
}

// membuat objek template
$view = new Template('templates/skintabel.html');

// membuat tabel group list
$header = '<tr>
<th scope="row">No.</th>
<th scope="row">Logo</th>
<th scope="row">group Name</th>
<th scope="row">companies</th>
<th scope="row">leader</th>
<th scope="row">Action</th>
</tr>';

$data = null;
$no = 1;
$formLabel = 'group';

while ($groupData = $group->getResult()) {
    // menampilkan setiap data match
    $data .= '<tr>
    <th scope="row">' . $no . '</th>
    <td><img src="assets/images/logos/' . $groupData['logo'] . '" class="card-img-top" alt="' . $groupData['logo'] . '"></td>
    <td>' . $groupData['name'] . '</td>
    <td>' . $groupData['companies'] . '</td>
    <td>' . $groupData['leader'] . '</td>
    <td style="font-size: 22px;">
        <a type="button" data-bs-toggle="modal" data-bs-target="#update-' . $mainTitle . '-' . $groupData['id'] . '"><i class="bi bi-pencil-square text-warning"></i></a>&nbsp;<a href="group.php?delete=' . $groupData['id'] . '" title="Delete Data"><i class="bi bi-trash-fill text-danger"></i></a>
    </td>
    </tr>';

    // variabel untuk menampung data companies
    $dataCompanies = null;
    $selectedOptions = [];

    // menampilkan data companies
    foreach ($arrayCompanies as $companiesData) {
        if (!in_array($companiesData['name'], $selectedOptions)) {
            $selectedOptions[] = $companiesData['name'];
            $selectedCompanies = ($companiesData['name'] == $groupData['companies']) ? "selected" : null;
            $dataCompanies .= '<option value="' . $companiesData['id'] . '" ' . $selectedCompanies . '>' . $companiesData['name'] . '</option>';
        }
    }

    // form berbentuk modal untuk memperbarui data group
    $data .= '<div class="modal fade" id="update-' . $mainTitle . '-' . $groupData['id'] . '" tabindex="-1" aria-labelledby="update-' . $mainTitle . '-' . $groupData['id'] . '-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="update-' . $mainTitle . '-' . $groupData['id'] . '-label">Update ' . $mainTitle . '</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="group.php?id=' . $groupData['id'] . '" method="POST" id="form-update' . $groupData['id'] . '" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="' . $groupData['name'] . '" placeholder="Enter group Name" required />
                    </div>
                    <div class="mb-3">
                        <label for="companies">Home group</label>
                        <select class="form-select" aria-label="companies" id="companies" name="companies" required>
                        ' . $dataCompanies . '
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <div class="input-group">
                            <input class="form-control" type="file" id="logo" name="logo" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="leader" class="form-label">leader</label>
                        <input type="text" class="form-control" id="leader" name="leader" value="' . $groupData['leader'] . '" placeholder="Enter leader" required />
                    </div>
                </form>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background-color: #a9dbcf; color: #2e4f4f" name="btn-update-group" id="btn-update-group" form="form-update' . $groupData['id'] . '">Update group</button>
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
if (isset($_POST['btn-update-group'])) {
    $id = $_GET['id']; // menangkap id group yang akan diperbarui
    // memperbarui data di database dengan data baru
    if ($group->updateGroup($id, $_POST, $_FILES) > 0) {
        // jika berhasil
        echo "<script>
                alert('Data updated successfully!');
                document.location.href = 'group.php';
            </script>";
    } else {
        // jika gagal
        echo "<script>
                alert('Failed to update data!');
                document.location.href = 'group.php';
            </script>";
    }
}

// menangkap data yang akan dihapus
if (isset($_GET['delete'])) {
    $id = $_GET['delete']; // menangkap id group yang akan dihapus
    if ($id > 0) {
        // menghapus data di database
        if ($group->deleteGroup($id) > 0) {
            // jika berhasil
            echo "<script>
                alert('Data deleted successfully!');
                document.location.href = 'group.php';
            </script>";
        } else {
            // jika gagal
            echo "<script>
                alert('Failed to delete data!');
                document.location.href = 'group.php';
            </script>";
        }
    }
}

// menutup koneksi database
$group->close();
$companies->close();

// mengisi template dengan data yang sudah diproses
$view->replace('DATA_MAIN_TITLE', $mainTitle);
$view->replace('SET_COLOR_1', '#000000');
$view->replace('SET_COLOR_2', 'white');
$view->replace('SET_COLOR_3', 'white');
$view->replace('DATA_TABEL_HEADER', $header);
$view->replace('DATA_FORM_LABEL', $formLabel);
$view->replace('DATA_TABEL', $data);
$view->replace('SET_FOOTER', $footer);

// menampilkan template
$view->write();