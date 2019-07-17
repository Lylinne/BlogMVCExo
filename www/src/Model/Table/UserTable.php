<?php
namespace App\Model\Table;

use Core\Model\Table;
use phpDocumentor\Reflection\Types\Boolean;

class UserTable extends Table
{
    public function getUser($mail, $password)
    {
        $user = $this->query("SELECT * FROM $this->table 
            WHERE mail = ?", [$mail], true);
        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $user->setPassword('');
                return $user;
            }
        }
        return false;
    }

    public function getUserByid($id)
    {
        return $this->query("SELECT * FROM $this->table
        WHERE $this->table.id = ?", [$id], true);
    }

    public function newUser(array $datas): bool
    {
        $sqlParts = [];
        foreach ($datas as $nom => $value) {
            $sqlParts[] = "$nom = :$nom";
        }
        //$sqlParts = ['mail = :mail', 'password = :password'];
        //After Join method
        //$sqlParts = "mail = :mail', password = :password"
        //statement = "INSERT INTO user SET mail = :mail', password = :password"
        $statement = "INSERT INTO {$this->table} SET ".join(', ', $sqlParts);
        return $this->query($statement, $datas);
    }
}
