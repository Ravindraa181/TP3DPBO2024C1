<?php

class Companies extends DB
{
    function getCompanies()
    {
        // Mengambil data dari tabel companies
        $query = "SELECT * FROM companies";

        return $this->execute($query);
    }

    function getCompaniesById($id)
    {
        // Mengambil data berdasarkan id
        $query = "SELECT * FROM companies WHERE id = $id";

        return $this->execute($query);
    }

    function searchCompanies($keyword)
    {
        // Mencari data berdasarkan nama Companies atau lokasi
        $query = "SELECT * FROM companies WHERE companies.name LIKE '%{$keyword}%' OR companies.location LIKE '%{$keyword}%'";

        return $this->execute($query);
    }

    function addCompanies($data, $file)
    {
        // Menambahkan data ke dalam tabel companies
        $uploadDirectory = 'assets/images/companiess/';
        $photoName = $file['photo']['name'];
        $photoPath = $uploadDirectory . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            $name = $data['name'];
            $location = $data['location'];
        } else {
            $photoName = 'noPhoto.png';
        }

        $query = "INSERT INTO companies VALUES('', '$name', '$location', '$photoName')";

        return $this->executeAffected($query);
    }

    function updateCompanies($id, $data, $file)
    {
        $uploadDirectory = 'assets/images/companiess/';
        $photoName = $file['photo']['name'];
        $photoPath = $uploadDirectory . $photoName;

        $query1 = "SELECT photo FROM companies WHERE id='$id'";
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
        $location = $data['location'];

        $query2 = "UPDATE companies SET name='$name', location='$location', photo='$photoName' WHERE id='$id'";

        return $this->executeAffected($query2);
    }


    function deleteCompanies($id)
    {
        $query1 = "SELECT photo FROM companies WHERE id='$id'";
        $result = $this->executeSingleResult($query1);
        $previousPhotoPath = 'assets/images/companiess/' . $result['photo'];

        if (file_exists($previousPhotoPath)) {
            unlink($previousPhotoPath);
        }

        $query = "DELETE FROM companies WHERE id = $id";

        return $this->executeAffected($query);
    }
}