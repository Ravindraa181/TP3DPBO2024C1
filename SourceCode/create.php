<?php

include('config/db.php');
include('classes/DB.php');
include('classes/Idol.php');
include('classes/Group.php');
include('classes/Companies.php');
include('classes/Template.php');

// membuat objek Idol
$idol = new Idol($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$idol->open();
$idol->getPosition();

// membuat array position
$arrayPosition = [];
while ($position = $idol->getResult()) {
    $arrayPosition[] = $position;
}

// membuat objek Group
$group = new Group($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$group->open();
$group->getGroup();

// membuat array Group
$arrayGroup = [];
while ($groups = $group->getResult()) {
    $arrayGroup[] = $groups;
}

// membuat objek Companies
$companies = new Companies($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$companies->open();
$companies->getCompanies();

// membuat array Companies
$arrayCompanies = [];
while ($companiess = $companies->getResult()) {
    $arrayCompanies[] = $companiess;
}

$title = '';
$to_file = '';
$form = null;
$data_btn = '';

if (isset($_POST['add-Group'])) { // jika yang akan ditambahkan adalah Group
    $title = 'Group';
    $to_file = 'group.php';

    // menampilkan form untuk menambah data group
    $form = '<div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Group Name" required />
            </div>
            <div class="mb-3">
                <label for="group">Companies</label>
                <select class="form-select" aria-label="companies" id="companies" name="companies" required>
                DATA_COMPANIES
                </select>
            </div>
            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <input class="form-control" type="file" id="logo" name="logo" required />
            </div>
            <div class="mb-3">
                <label for="leader" class="form-label">Leader</label>
                <input type="text" class="form-control" id="leader" name="leader" placeholder="Enter Leader" required />
            </div>';

    $data_btn = 'btn-create-group';
} else if (isset($_POST['add-Companies'])) { // jika yang akan ditambahkan adalah Companies
    $title = 'Companies';
    $to_file = 'companies.php';

    // menampilkan form untuk menambah data companies
    $form = '<div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Companies Name" required />
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" placeholder="Enter Location" required />
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Photo</label>
                <input class="form-control" type="file" id="photo" name="photo" required />
            </div>';

    $data_btn = 'btn-create-Companies';
} else if (isset($_POST['add-Idol'])) { // jika yang akan ditambahkan adalah Idol
    $title = 'Idol';
    $to_file = 'create.php';

    // menampilkan form untuk menambah data Idol
    $form = '<div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Idol Name" required />
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Photo</label>
                <input class="form-control" type="file" id="photo" name="photo" required />
            </div>
            <div class="mb-3">
                <label for="group">Group</label>
                <select class="form-select" aria-label="Group" id="group" name="group" required>
                DATA_GROUP
                </select>
            </div>
            <div class="mb-3">
                <label for="position">Position</label>
                <select class="form-select" aria-label="Position" id="position" name="position" required>
                DATA_POSITION
                </select>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" name="age" placeholder="Enter Age" required />
            </div>';

    $data_btn = 'btn-create-Idol';
}

// menangkap data yang akan ditambahkan
if (isset($_POST['btn-create-Idol'])) {
    // menambahkan data ke database
    if ($idol->addData($_POST, $_FILES) > 0) {
        // jika berhasil
        echo "<script>
            alert('Data added successfully!');
            document.location.href = 'index.php';
        </script>";
    } else {
        // jika gagal
        echo "<script>
            alert('Failed to add data!');
            document.location.href = 'index.php';
        </script>";
    }
}

$dataPosition = null;
$dataGroup = null;
$dataCompanies = null;

// menampilkan data position dengan bentuk dropdown
foreach ($arrayPosition as $positionData) {
    $dataPosition .= '<option value="' . $positionData['id'] . '">' . $positionData['name'] . '</option>';
}

// menampilkan data group dengan bentuk dropdown
foreach ($arrayGroup as $groupData) {
    $dataGroup .= '<option value="' . $groupData['id'] . '">' . $groupData['name'] . '</option>';
}

// menampilkan data Companies dengan bentuk dropdown
foreach ($arrayCompanies as $companiesData) {
    $dataCompanies .= '<option value="' . $companiesData['id'] . '">' . $companiesData['name'] . '</option>';
}

// menutup koneksi database
$idol->close();
$group->close();
$companies->close();

// menampilkan halaman form create
$add = new Template('templates/skinform.html');

// mengisi template dengan data yang sudah diproses
$add->replace('TYPE', 'Add');
$add->replace('DATA_TITLE', $title);
$add->replace('TO_FILE', $to_file);
$add->replace('SET_FORM', $form);
$add->replace('DATA_BTN', $data_btn);
$add->replace('DATA_POSITION', $dataPosition);
$add->replace('DATA_GROUP', $dataGroup);
$add->replace('DATA_COMPANIES', $dataCompanies);

// menampilkan template
$add->write();