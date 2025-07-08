<?php
require_once __DIR__ . '/../models/PretClient.php';
require_once __DIR__ . '/../db.php';

class PretClientController {
    public static function getAll() {
        $prets = PretClient::getAll();
        Flight::json($prets);
    }

    public static function create() {
        try {
$data = Flight::request()->data;

        // Validation minimum
        if ($data->montant < 1000000) {
            Flight::json(['error' => 'Montant trop faible (min 1 000 000 Ar)'], 400);
            return;
        }

        $db = getDB();

        // Somme des fonds disponibles
        $totalFonds = $db->query("SELECT SUM(montant) as total FROM fond")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Somme des prêts accordés
        $totalPrets = PretClient::getTotalPret();

        // Calcul fonds restant si prêt accordé
        $fondsRestant = $totalFonds - $totalPrets - $data->montant;

        if ($fondsRestant < $totalFonds * 0.3) {
            Flight::json(['error' => 'Fonds insuffisants. Il doit rester au moins 30% en banque.'], 400);
            return;
        }

        // Crée le prêt
        $idPret = PretClient::create($data);

        Flight::json(['message' => 'Prêt accordé', 'id' => $idPret]);
        
        
    }catch(Exception $e)
    {
  Flight::json(['error' => $e->getMessage()], 500);
    }
}

    public static function getTotal() {
        $total = PretClient::getTotalPret();
        Flight::json(['total_pret' => $total]);
    }

    public static function getInterets() {
    $query = Flight::request()->query;
    $dateDebut = $query->date_debut ?? '2000-01-01'; // valeur par défaut large
    $dateFin = $query->date_fin ?? date('Y-m-d');

    $interets = PretClient::getInteretsByPeriod($dateDebut, $dateFin);
    Flight::json($interets);
}

public static function getEcheancier($id) {
    $pret = PretClient::getById($id);
    if (!$pret) {
        Flight::json(['error' => 'Prêt introuvable'], 404);
        return;
    }

    $typePret = TypePret::getByID($pret['id_type_pret']);
    if (!$typePret) {
        Flight::json(['error' => 'Type de prêt introuvable'], 404);
        return;
    }

    // Optionnel : assurance si delai < 6 mois → 1% par mois
    $assurance = ($typePret['delai'] < 6) ? 0.01 : 0.00;

    $echeancier = Finance::calculerEcheancier(
        $pret['montant'],
        $typePret['taux'],
        $typePret['delai'],
        $assurance
    );

    Flight::json($echeancier);
}

public static function simuler() {
    $data = Flight::request()->data;

    $montant = floatval($data->montant);
    $taux = floatval($data->taux);
    $duree = intval($data->duree);
    $assurance = isset($data->assurance) ? floatval($data->assurance) : 0;

    if ($montant <= 0 || $taux < 0 || $duree <= 0) {
        Flight::json(['error' => 'Données invalides'], 400);
        return;
    }

    $echeancier = PretClient::calculerEcheancier($montant, $taux, $duree, $assurance);
    Flight::json($echeancier);
}


}
