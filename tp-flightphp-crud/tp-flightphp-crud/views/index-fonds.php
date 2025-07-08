<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des fonds</title>
  <!-- <style>
    body { font-family: sans-serif; padding: 20px; }
    input, button { margin: 5px; padding: 5px; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style> -->
  <link rel="stylesheet" href="css/style_fonds.css">
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


  <h1>Gestion des fonds</h1>

  <div>
    <input type="hidden" id="id">
    <input type="number" step="0.01" id="montant" placeholder="Montant (ex: 10000.00)">
    <button onclick="ajouterOuModifier()">Ajouter / Modifier</button>
  </div>

  <table id="table-fonds">
    <thead>
      <tr>
        <th>ID</th><th>Montant</th><th>Date d'ajout</th><th>Actions</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <script>
const apiBase = "http://localhost/tp-flightphp-crud/tp-flightphp-crud/ws";
// const apiBase = "/ETU003194/t/tp-flightphp-crud/tp-flightphp-crud/ws";

    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
          } else {
            alert("Erreur serveur : " + xhr.status);
          }
        }
      };
      xhr.send(data);
    }

    function chargerFonds() {
      ajax("GET", "/fonds", null, (data) => {
        const tbody = document.querySelector("#table-fonds tbody");
        tbody.innerHTML = "";
        data.forEach(f => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${f.id}</td>
            <td>${f.montant}</td>
            <td>${f.date_ajout}</td>
            <td>
              <button onclick='remplirFormulaire(${JSON.stringify(f)})'>✏️</button>
              <button onclick='supprimerFond(${f.id})'>🗑️</button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      });
    }

    function ajouterOuModifier() {
      const id = document.getElementById("id").value;
      const montant = document.getElementById("montant").value;

      const data = `montant=${encodeURIComponent(montant)}`;

      if (id) {
        ajax("PUT", `/fonds/${id}`, data, () => {
          resetForm();
          chargerFonds();
        });
      } else {
        ajax("POST", "/fonds", data, () => {
          resetForm();
          chargerFonds();
        });
      }
    }

    function remplirFormulaire(f) {
      document.getElementById("id").value = f.id;
      document.getElementById("montant").value = f.montant;
    }

    function supprimerFond(id) {
      if (confirm("Supprimer ce fond ?")) {
        ajax("DELETE", `/fonds/${id}`, null, () => {
          chargerFonds();
        });
      }
    }

    function resetForm() {
      document.getElementById("id").value = "";
      document.getElementById("montant").value = "";
    }

    chargerFonds();
  </script>

</body>
</html>
