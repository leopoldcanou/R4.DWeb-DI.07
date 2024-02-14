<?php

namespace App\Service;

use PDO;


class DatabaseInterface
{
    public function getAllLegos(): array
    {
            $db = new PDO('mysql:host=tp-symfony-mysql;dbname=lego_store', 'root', 'root');
            $query = $db->prepare('SELECT * FROM lego');
            $query->execute();
            return $query->fetchAll();
    }
}
