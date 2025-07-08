<?php
require_once __DIR__ . '/../db.php';

class Client {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM client ORDER BY nom, prenom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM client WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByEmail($email) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM client WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO client (nom, prenom, email, date_naissance) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data->nom,
            $data->prenom,
            $data->email,
            $data->date_naissance
        ]);
        return $db->lastInsertId();
    }
}
