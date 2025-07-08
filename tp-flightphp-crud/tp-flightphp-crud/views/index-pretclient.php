<!-- <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo "Test PHP ok";
?> -->

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Historique des prêts clients</title>
  <!-- <style>
    body { font-family: sans-serif; padding: 20px; }
    button { margin: 10px 0; padding: 10px 20px; font-size: 1rem; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style> -->
  <link rel="stylesheet" href="css/style_index_pret_client.css">
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

  <h1>Historique des prêts clients</h1>

  <button onclick="window.location.href='form-pretclient.php'">Nouveau prêt</button>

  <h2>Statistiques</h2>
  <p>Total fonds en banque : <strong id="totalFonds">0</strong> Ar</p>
  <p>Total prêts accordés : <strong id="totalPrets">0</strong> Ar</p>

  <table>
    <thead>
      <tr>
        <th>ID</th><th>Client</th><th>Type de prêt</th><th>Montant</th><th>Date</th>
      </tr>
    </thead>
    <tbody id="pretList"></tbody>
  </table>

<script>
const apiBase = "http://localhost/tp-flightphp-crud/tp-flightphp-crud/ws";
// const apiBase = "/ETU003194/t/tp-flightphp-crud/tp-flightphp-crud/ws";

function ajax(method, url, data, callback) {
  const xhr = new XMLHttpRequest();
  xhr.open(method, apiBase + url, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = () => {
    if(xhr.readyState === 4) {
      if(xhr.status === 200) {
        try {
          callback(JSON.parse(xhr.responseText));
        } catch(e) {
          alert("Erreur parsing JSON : " + e.message);
        }
      } else {
        alert("Erreur serveur : " + xhr.status + "\nRéponse : " + xhr.responseText);
      }
    }
  };
  xhr.send(data);
}

function loadPrets() {
  ajax("GET", "/prets-clients", null, (data) => {
    const tbody = document.getElementById("pretList");
    tbody.innerHTML = "";
    data.forEach(p => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${p.id}</td>
        <td>${p.client_nom} ${p.client_prenom}</td>
        <td>${p.type_pret}</td>
        <td>${p.montant}</td>
        <td>${p.date_pret}</td>
      `;
      tbody.appendChild(tr);
    });
  });
}

function loadStats() {
  ajax("GET", "/fonds/total", null, (data) => {
    document.getElementById("totalFonds").textContent = data.total || 0;
  });
  ajax("GET", "/prets-clients/total", null, (data) => {
    document.getElementById("totalPrets").textContent = data.total_pret || 0;
  });
}

loadPrets();
loadStats();
</script>



</body>
</html>
