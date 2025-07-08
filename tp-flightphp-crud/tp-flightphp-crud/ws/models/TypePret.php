<?php 

require_once __DIR__ . '/../db.php';

class TypePret
{
    public static function getAll()
    {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM type_pret ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByID($id)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM type_pret WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $data = Flight::request()->data;
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO type_pret (nom, taux, delai) VALUES (?, ?, ?)");
        $stmt->execute([$data->nom, $data->taux, $data->delai]);
        return $db->lastInsertId();
    }

    public static function update($id, $data)
    {
        $data = Flight::request()->data;
        $db = getDB();
        $stmt = $db->prepare("UPDATE type_pret SET nom = ?, taux = ?, delai = ? WHERE id = ?");
        $stmt->execute([$data->nom, $data->taux, $data->delai, $id]);
    }

    public static function delete($id)
    {
        $db = getDb();
        $stmt = $db -> prepare("DELETE FROM type_pret WHERE id = ?");
        $stmt-> execute([$id]);
    }
}

?>