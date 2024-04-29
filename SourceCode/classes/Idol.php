<?php

class Idol extends DB
{
    function getIdol()
    {
        // Mengambil data Idol
        $query = "SELECT * FROM idols";

        return $this->execute($query);
    }

    function getIdolById($id)
    {
        // Mengambil data Idol berdasarkan id
        $query = "SELECT idols.*, `group`.name AS group_name, positions.name AS position_name 
              FROM idols 
              JOIN `group` ON idols.group_id = `group`.id 
              JOIN positions ON idols.position_id = positions.id 
              WHERE idols.id = $id";

        return $this->execute($query);
    }

    function getIdolJoin()
    {
        // Mengambil data Idol dan klub tempat Idol bermain
        $query = "SELECT idols.*, `group`.name AS group_name, positions.name AS position_name 
              FROM idols 
              JOIN `group` ON idols.group_id = `group`.id 
              JOIN positions ON idols.position_id = positions.id 
              ORDER BY positions.id DESC";

        return $this->execute($query);
    }


    function getidolsort($typeOfSort = 'asc')
    {
        $query = "SELECT idols.*, `group`.name AS group_name, positions.name AS position_name 
                FROM idols 
                JOIN `group` ON idols.group_id = `group`.id 
                JOIN positions ON idols.position_id = positions.id 
                ORDER BY `group`.id $typeOfSort";
        // Mengambil data Idol dan klub tempat Idol bermain

        return $this->execute($query);
    }


    function getPosition()
    {
        // Mengambil data posisi Idol
        $query = "SELECT * FROM positions ORDER BY positions.id ASC";

        return $this->execute($query);
    }

    function searchIdol($keyword)
    {
        // Mencari data berdasarkan nama Idol, posisi bermain, atau nama group
        $query = "SELECT idols.*, `group`.name AS group_name, positions.name AS position_name 
        FROM idols 
        JOIN `group` ON idols.group_id = `group`.id 
        JOIN positions ON idols.position_id = positions.id 
        WHERE idols.name LIKE '%{$keyword}%' OR positions.name LIKE '%{$keyword}%' OR group.name LIKE '%{$keyword}%' ORDER BY positions.id DESC";

        return $this->execute($query);
    }

    function addData($data, $file)
    {
        // menyimpan data photo
        $uploadDirectory = 'assets/images/idols/';
        $photoName = $file['photo']['name'];
        $photoPath = $uploadDirectory . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            $name = $data['name'];
            $group = $data['group'];
            $position = $data['position'];
            $age = $data['age'];
        } else {
            $photoName = 'noPhoto.png';
        }

        $query = "INSERT INTO idols VALUES('', '$name', '$photoName', '$group', $position, $age)";

        return $this->executeAffected($query);
    }

    function updateIdol($id, $data, $file)
    {
        $uploadDirectory = 'assets/images/idols/';
        $photoName = $file['photo']['name'];
        $photoPath = $uploadDirectory . $photoName;

        $query1 = "SELECT photo FROM idols WHERE id='$id'";
        $result = $this->executeSingleResult($query1);
        $previousPhotoName = $result['photo'];

        if ($photoName !== '') {
            if ($previousPhotoName !== 'noPhoto.png') {
                $previousPhotoPath = $uploadDirectory . $previousPhotoName;
                if (file_exists($previousPhotoPath)) {
                    unlink($previousPhotoPath);
                }
            }
            move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
        } else {
            $photoName = $previousPhotoName;
        }

        $name = $data['name'];
        $group_id = $data['group'];
        $position_id = $data['position'];
        $age = $data['age'];

        $query2 = "UPDATE idols SET name = '$name', photo = '$photoName',  group_id = '$group_id',position_id = '$position_id', age = '$age' WHERE id = '$id'";

        return $this->executeAffected($query2);
    }

    function deleteIdol($id)
    {
        $query1 = "SELECT photo FROM idols WHERE id='$id'";
        $result = $this->executeSingleResult($query1);
        $previousPhotoPath = 'assets/images/idols/' . $result['photo'];

        if (file_exists($previousPhotoPath)) {
            unlink($previousPhotoPath);
        }

        $query2 = "DELETE FROM idols WHERE id='$id'";

        return $this->executeAffected($query2);
    }
}