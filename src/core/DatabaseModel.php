<?php

namespace Dulannadeeja\Mvc;

use Dulannadeeja\Mvc\Application;
use Dulannadeeja\Mvc\Model;

abstract class DatabaseModel extends Model
{
    abstract public static function tableName(): string;

    abstract protected function attributes(): array;

    abstract public static function primaryKey(): string;

    public function save(): bool
    {

        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $params = array_map(fn($attr) => ":$attr", $attributes);

        $pdo = Application::$app->db->pdo;

        $statement = $pdo->prepare("INSERT INTO $tableName (".implode(',', $attributes).") VALUES (".implode(',', $params).")");

        foreach($attributes as $attribute){
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        try {
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            echo $th->getMessage();
            return false;
        }

    }

    public static function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);

        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));

        $pdo = Application::$app->db->pdo;

        $statement = $pdo->prepare("SELECT * FROM $tableName WHERE $sql");

        foreach($where as $key => $item){
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();

        return $statement->fetchObject(static::class) ?? false;
    }


}