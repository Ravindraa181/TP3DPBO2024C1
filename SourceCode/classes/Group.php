<?php
class Group extends DB
{
    function getGroup()
    {
        // Mengambil data Group
        $query = "SELECT * FROM `group` ORDER BY name ASC";
        return $this->execute($query);
    }

    function getGroupById($id)
    {
        // Mengambil data Group berdasarkan id
        $query = "SELECT * FROM `group` WHERE id = $id";
        return $this->execute($query);
    }

    function getGroupJoin()
    {
        // Mengambil data dari Group dan companiesnya
        $query = "SELECT `group`.id, `group`.name, `group`.logo, `group`.leader, companies.name AS companies 
                  FROM `group` 
                  JOIN companies ON `group`.companies_id = companies.id";
        return $this->execute($query);
    }

    function searchGroup($keyword)
    {
        // Mencari data berdasarkan nama Group atau nama pelatih
        $query = "SELECT `group`.id, `group`.name, `group`.logo, `group`.leader, companies.name AS companies 
                  FROM `group` 
                  JOIN companies ON `group`.companies_id = companies.id 
                  WHERE `group`.name LIKE '%{$keyword}%' OR `group`.leader LIKE '%{$keyword}%'";
        return $this->execute($query);
    }

    function addGroup($data, $file)
    {
        // Menambahkan data Group
        $uploadDirectory = 'assets/images/logos/';
        $photoName = $file['logo']['name'];
        $photoPath = $uploadDirectory . $photoName;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $photoPath)) {
            $name = $data['name'];
            $companies_id = $data['companies'];
            $leader = $data['leader'];
        } else {
            $photoName = 'noPhoto.png';
        }

        $query = "INSERT INTO `group` VALUES('', '$name', '$companies_id', '$photoName', '$leader')";
        return $this->executeAffected($query);
    }

    function updateGroup($id, $data, $file)
    {
        $uploadDirectory = 'assets/images/logos/';
        $photoName = $file['logo']['name'];
        $photoPath = $uploadDirectory . $photoName;

        $query1 = "SELECT logo FROM `group` WHERE id='$id'";
        $result = $this->executeSingleResult($query1);
        $previousPhotoName = $result['logo'];

        if ($photoName !== '') {
            if ($previousPhotoName !== 'noPhoto.png') {
                $previousPhotoPath = $uploadDirectory . $previousPhotoName;
                if (file_exists($previousPhotoPath)) {
                    unlink($previousPhotoPath);
                }
            }
            move_uploaded_file($_FILES['logo']['tmp_name'], $photoPath);
        } else {
            $photoName = $previousPhotoName;
        }

        $name = $data['name'];
        $companies_id = $data['companies'];
        $leader = $data['leader'];

        $query2 = "UPDATE `group` SET name='$name', companies_id='$companies_id', logo='$photoName', leader='$leader' WHERE id='$id'";
        return $this->executeAffected($query2);
    }

    function deleteGroup($id)
    {
        $query1 = "SELECT logo FROM `group` WHERE id='$id'";
        $result = $this->executeSingleResult($query1);
        $previousPhotoPath = 'assets/images/logos/' . $result['logo'];

        if (file_exists($previousPhotoPath)) {
            unlink($previousPhotoPath);
        }

        // Menghapus data Group
        $query = "DELETE FROM `group` WHERE id='$id'";
        return $this->executeAffected($query);
    }
}