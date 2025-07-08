<?php
require_once __DIR__ . '/../db.php';

class PretClient {
    public static function getAll() {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT p.id, c.nom AS client_nom, c.prenom AS client_prenom, tp.nom AS type_pret, tp.taux, tp.delai, p.montant, p.date_pret
            FROM pret_client p
            JOIN client c ON p.id_client = c.id
            JOIN type_pret tp ON p.id_type_pret = tp.id
            ORDER BY p.date_pret DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("ERREUR getAll PretClient : " . $e->getMessage());
        return [];
    }
}

public static function getById($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT p.id, c.nom AS client_nom, c.prenom AS client_prenom, tp.nom AS type_pret, tp.taux, tp.delai, p.montant, p.date_pret
            FROM pret_client p
            JOIN client c ON p.id_client = c.id
            JOIN type_pret tp ON p.id_type_pret = tp.id
            ORDER BY p.date_pret DESC
            WHERE p.id = ?");
            $stmt -> execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("ERREUR getById PretClient : " . $e->getMessage());
        return [];
    }
}

    public static function create($data) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO pret_client (id_client, id_type_pret, montant, date_pret, assurance) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $data->id_client,
        $data->id_type_pret,
        $data->montant,
        $data->date_pret ?? date('Y-m-d'),
        $data->assurance ?? 0
    ]);
    return $db->lastInsertId();
}

    public static function getTotalPret() {
        $db = getDB();
        $stmt = $db->query("SELECT SUM(montant) as total_pret FROM pret_client");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_pret'] ?? 0;
    }

    public static function getInteretsByPeriod($dateDebut, $dateFin) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT 
            DATE_FORMAT(p.date_pret, '%Y-%m') AS mois_annee,
            SUM(p.montant * t.taux / 12) AS interets_mensuels
        FROM pret_client p
        JOIN type_pret t ON p.id_type_pret = t.id
        WHERE p.date_pret BETWEEN ? AND ?
        GROUP BY mois_annee
        ORDER BY mois_annee
    ");
    $stmt->execute([$dateDebut, $dateFin]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

 public static function calculerEcheancier($montant, $taux_annuel, $duree_mois, $assurance_percent = 0) {
    $taux_mensuel = $taux_annuel / 12;
    $n = $duree_mois;

    if ($taux_mensuel == 0) {
        $annuite = $montant / $n;
    } else {
        $annuite = ($montant * $taux_mensuel) / (1 - pow(1 + $taux_mensuel, -$n));
    }

$assurance_mensuelle = ($montant * $assurance_percent) / 100 / $n;

    $capital_restant = $montant;
    $echeancier = [];

    for ($mois = 1; $mois <= $n; $mois++) {
        $interet = $capital_restant * $taux_mensuel;
        $capital = $annuite - $interet;
        $total = $annuite + $assurance_mensuelle;
        $capital_restant -= $capital;

        $echeancier[] = [
            'mois' => $mois,
            'capital' => round($capital, 2),
            'interet' => round($interet, 2),
            'assurance' => round($assurance_mensuelle, 2),
            'total' => round($total, 2),
            'capital_restant' => max(round($capital_restant, 2), 0)
        ];
    }

    return $echeancier;
}


}
