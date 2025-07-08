<?php
require_once __DIR__ . '/../models/PretClient.php';
require_once __DIR__ . '/../models/Finance.php';

// Sécurise l'entrée
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) die("Prêt non trouvé");

$pret = PretClient::getById($id);
if (!$pret) die("Aucun prêt avec cet ID");

// Paramètres
$montant = $pret['montant'];
$taux = $pret['taux'];
$mois = $pret['delai'];
$assurance = ($mois < 6) ? 1.0 : 0.0; // 1% si < 6 mois

$echeancier = Finance::calculerEcheancier($montant, $taux, $mois, $assurance);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Échéancier du prêt</title>
  <style>
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: right; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
    <header class="main-header">
  <div class="header-container">
    <nav class="nav-links">
      <a href="index-fonds.php">Gestion de fonds</a>
      <a href="index-typepret.php">Types de prêt</a>
      <a href="index-pretclient.php">Prêts clients</a>
      <a href="index-interets.php">Intérêts</a>
      <a href="simulation.php">Simulation</a>
    </nav>
  </div>
</header>


  <h1>Échéancier du prêt (ID: <?= $id ?>)</h1>
  <p><strong>Montant :</strong> <?= number_format($montant, 0, ',', ' ') ?> Ar</p>
  <p><strong>Taux :</strong> <?= $taux * 100 ?> % annuel</p>
  <p><strong>Durée :</strong> <?= $mois ?> mois</p>
  <p><strong>Assurance :</strong> <?= $assurance ?> %</p>

  <table>
    <thead>
      <tr>
        <th>Mois</th>
        <th>Capital</th>
        <th>Intérêt</th>
        <th>Assurance</th>
        <th>Total mensuel</th>
        <th>Capital restant</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($echeancier as $ligne): ?>
        <tr>
          <td><?= $ligne['mois'] ?></td>
          <td><?= number_format($ligne['capital'], 0, ',', ' ') ?></td>
          <td><?= number_format($ligne['interet'], 0, ',', ' ') ?></td>
          <td><?= number_format($ligne['assurance'], 0, ',', ' ') ?></td>
          <td><?= number_format($ligne['total'], 0, ',', ' ') ?></td>
          <td><?= number_format($ligne['capital_restant'], 0, ',', ' ') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
