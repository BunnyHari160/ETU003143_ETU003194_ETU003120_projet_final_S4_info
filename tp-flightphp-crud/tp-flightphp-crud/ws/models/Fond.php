<?php 

require_once __DIR__ . '/../db.php';

class Fond 
{
    public static function getAll()
    {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM fond");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByID($id)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM fond WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO fond (montant) VALUES (?)");
        $stmt->execute([$data->montant]);
        return $db->lastInsertId();
    }

    public static function update($id, $data)
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE fond SET montant = ? WHERE id = ?");
        $stmt->execute([$data->montant, $id]);
    }

    public static function delete($id)
    {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM fond WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>
