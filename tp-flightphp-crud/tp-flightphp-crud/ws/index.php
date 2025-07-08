<?php 


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_exception_handler(function ($e) {
    echo "<pre>Exception : " . $e->getMessage() . "\n" . $e->getTraceAsString() . "</pre>";
});



?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'db.php';
require 'routes/fonds_routes.php';
require 'routes/typepret_routes.php';
require 'routes/pretclient_routes.php';
require 'routes/client_routes.php';







// === ROUTES ÉTUDIANTS ===
// Flight::route('GET /etudiants', function() {
//     $db = getDB();
//     $stmt = $db->query("SELECT * FROM etudiant");
//     Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
// });

// Flight::route('GET /etudiants/@id', function($id) {
//     $db = getDB();
//     $stmt = $db->prepare("SELECT * FROM etudiant WHERE id = ?");
//     $stmt->execute([$id]);
//     Flight::json($stmt->fetch(PDO::FETCH_ASSOC));
// });

// Flight::route('POST /etudiants', function() {
//     $data = Flight::request()->data;
//     $db = getDB();
//     $stmt = $db->prepare("INSERT INTO etudiant (nom, prenom, email, age) VALUES (?, ?, ?, ?)");
//     $stmt->execute([$data->nom, $data->prenom, $data->email, $data->age]);
//     Flight::json(['message' => 'Étudiant ajouté', 'id' => $db->lastInsertId()]);
// });

// Flight::route('PUT /etudiants/@id', function($id) {
//     $data = Flight::request()->data;
//     $db = getDB();
//     $stmt = $db->prepare("UPDATE etudiant SET nom = ?, prenom = ?, email = ?, age = ? WHERE id = ?");
//     $stmt->execute([$data->nom, $data->prenom, $data->email, $data->age, $id]);
//     Flight::json(['message' => 'Étudiant modifié']);
// });

// Flight::route('DELETE /etudiants/@id', function($id) {
//     $db = getDB();
//     $stmt = $db->prepare("DELETE FROM etudiant WHERE id = ?");
//     $stmt->execute([$id]);
//     Flight::json(['message' => 'Étudiant supprimé']);
// });

// // === ROUTES FONDS ===
// Flight::route('GET /fonds', function() {
//     $db = getDB();
//     $stmt = $db->query("SELECT * FROM fond ORDER BY date_ajout DESC");
//     Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
// });

// Flight::route('POST /fonds', function() {
//     $data = Flight::request()->data;
//     $db = getDB();
//     $stmt = $db->prepare("INSERT INTO fond (montant) VALUES (?)");
//     $stmt->execute([$data->montant]);
//     Flight::json(['message' => 'Fonds ajouté', 'id' => $db->lastInsertId()]);
// });

// Flight::route('PUT /fonds/@id', function($id) {
//     $data = Flight::request()->data;
//     $db = getDB();
//     $stmt = $db->prepare("UPDATE fond SET montant = ? WHERE id = ?");
//     $stmt->execute([$data->montant, $id]);
//     Flight::json(['message' => 'Fonds modifié']);
// });

// Flight::route('DELETE /fonds/@id', function($id) {
//     $db = getDB();
//     $stmt = $db->prepare("DELETE FROM fond WHERE id = ?");
//     $stmt->execute([$id]);
//     Flight::json(['message' => 'Fonds supprimé']);
// });

// // === ROUTES CLIENTS ===
// Flight::route('GET /clients', function() {
//     $db = getDB();
//     $stmt = $db->query("SELECT * FROM clients");
//     Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
// });

// Flight::route('POST /clients', function() {
//     $data = Flight::request()->data;
//     $db = getDB();
//     $stmt = $db->prepare("INSERT INTO client (nom, prenom, email, date_naissance) VALUES (?,?,?,?)");
//     $stmt->execute([$data->nom,$data->prenom,$data->email,$data->date_naissance]);
//     Flight::json(['message' => 'Client ajouté', 'id' => $db->lastInsertId()]);
// });

// Flight::route('PUT /clients/@id', function($id) {
//     $data = Flight::request()->data;
//     $db = getDB();
//     $stmt = $db->prepare("UPDATE clients SET email = ? WHERE id = ?");
//     $stmt->execute([$data->email, $id]);
//     Flight::json(['message' => 'Email du client modifié']);
// });

// // Flight::route('DELETE /clients/@id', function($id) {
// //     $db = getDB();
// //     $stmt = $db->prepare("DELETE FROM client WHERE id = ?");
// //     $stmt->execute([$id]);
// //     Flight::json(['message' => 'Client supprimé']);
// // });




// 👇 Ajouté pour bien lire les données x-www-form-urlencoded
// Flight::before('start', function(&$params, &$headers) {
//     if (Flight::request()->type == 'application/x-www-form-urlencoded') {
//         parse_str(file_get_contents("php://input"), $vars);
//         Flight::request()->data = (object)$vars;
//     }
// });

Flight::start();
