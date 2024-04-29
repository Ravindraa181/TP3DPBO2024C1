<?php

include('config/db.php');
include('classes/DB.php');
include('classes/Idol.php');
include('classes/Group.php');
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

$title = '';
$form = null;

// menangkap data yang akan diupdate
if (isset($_GET['id'])) {
    $id = $_GET['id']; // menangkap id yang akan diupdate
    if ($id > 0) { // jika id yang akan diupdate ada
        // ambil data Idol yang akan diupdate
        $idol->getIdolById($id);
        $data = $idol->getResult();

        // variabel untuk menampung data position
        $dataPosition = null;
        $selectedOptionsPos = [];

        // menampilkan data position
        foreach ($arrayPosition as $positionData) {
            if (!in_array($positionData['name'], $selectedOptionsPos)) {
                $selectedOptionsPos[] = $positionData['name'];
                $selectedPosition = ($positionData['id'] == $data['position_id']) ? "selected" : null;
                $dataPosition .= '<option value="' . $positionData['id'] . '" ' . $selectedPosition . '>' . $positionData['name'] . '</option>';
            }
        }

        // variabel untuk menampung data Group
        $dataGroup = null;
        $selectedOptionsGroup = [];

        // menampilkan data Group
        foreach ($arrayGroup as $groupData) {
            if (!in_array($groupData['name'], $selectedOptionsGroup)) {
                $selectedOptionsGroup[] = $groupData['name'];
                $selectedGroup = ($groupData['id'] == $data['group_id']) ? "selected" : null;
                $dataGroup .= '<option value="' . $groupData['id'] . '" ' . $selectedGroup . '>' . $groupData['name'] . '</option>';
            }
        }

        // form update
        $form = '<div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="' . $data['name'] . '" required />
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input class="form-control" type="file" id="photo" name="photo"/>
        </div>
        <div class="mb-3">
            <label for="group">Group</label>
            <select class="form-select" aria-label="Group" id="group" name="group" required>
            ' . $dataGroup . '
            </select>
        </div>
        <div class="mb-3">
            <label for="position">Position</label>
            <select class="form-select" aria-label="Position" id="position" name="position" required>
            ' . $dataPosition . '
            </select>
        </div>
        <div class="mb-3">
            <label for="age" class="form-label">Age</label>
            <input type="number" class="form-control" id="age" name="age" value="' . $data['age'] . '" required />
        </div>';

        $title = 'Idol';
        $to_file = 'update.php?id=' . $data['id'] . '';

        $data_btn = 'btn-update-idol';
    }
}

// menangkap data yang akan diupdate
if (isset($_POST['btn-update-idol'])) {
    $id = $_GET['id']; // menangkap id yang akan diupdate
    // mengupdate data di database dengan data baru
    $idol->getIdolById($id);
    $data = $idol->getResult();
    if ($idol->updateIdol($id, $_POST, $_FILES) > 0) {
        // jika update berhasil
        echo "<script>
            alert('Data updated successfully!');
            document.location.href = 'detail.php?id={$data['id']}';
        </script>";
    } else {
        // jika update gagal
        echo "<script>
            alert('Failed to update data!');
            document.location.href = 'detail.php?id={$data['id']}';
        </script>";
    }
}

// menutup koneksi database
$idol->close();
$group->close();

// menampilkan halaman form update
$add = new Template('templates/skinform.html');

// mengisi template dengan data yang sudah diproses
$add->replace('TYPE', 'Update');
$add->replace('DATA_TITLE', $title);
$add->replace('TO_FILE', $to_file);
$add->replace('SET_FORM', $form);
$add->replace('DATA_BTN', $data_btn);
$add->replace('DATA_GROUP', $dataGroup);

// menampilkan template
$add->write();