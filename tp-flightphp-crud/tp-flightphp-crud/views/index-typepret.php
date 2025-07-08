<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des types de prêt</title>
  <link rel="stylesheet" href="css/style_index_pret.css">
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


  <h1>Gestion des types de prêt</h1>

  <div>
    <input type="hidden" id="id">
    <input type="text" id="nom" placeholder="Nom">
    <input type="number" step="0.01" id="taux" placeholder="Taux (ex: 0.03)">
    <input type="number" id="delai" placeholder="Délai (en mois)">
    <button onclick="ajouterOuModifier()">Ajouter / Modifier</button>
  </div>

  <table id="table-typepret">
    <thead>
      <tr>
        <th>ID</th><th>Nom</th><th>Taux</th><th>Délai (mois)</th><th>Actions</th>
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
        if (xhr.readyState === 4 && xhr.status === 200) {
          callback(JSON.parse(xhr.responseText));
        }
      };
      xhr.send(data);
    }

    function chargerTypesPret() {
      ajax("GET", "/types-prets", null, (data) => {
        const tbody = document.querySelector("#table-typepret tbody");
        tbody.innerHTML = "";
        data.forEach(t => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${t.id}</td>
            <td>${t.nom}</td>
            <td>${t.taux}</td>
            <td>${t.delai}</td>
            <td>
              <button onclick='remplirFormulaire(${JSON.stringify(t)})'>✏️</button>
              <button onclick='supprimerTypePret(${t.id})'>🗑️</button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      });
    }

    function ajouterOuModifier() {
      const id = document.getElementById("id").value;
      const nom = document.getElementById("nom").value;
      const taux = document.getElementById("taux").value;
      const delai = document.getElementById("delai").value;

      const data = `nom=${encodeURIComponent(nom)}&taux=${encodeURIComponent(taux)}&delai=${encodeURIComponent(delai)}`;

      if (id) {
        ajax("PUT", `/types-prets/${id}`, data, () => {
          resetForm();
          chargerTypesPret();
        });
      } else {
        ajax("POST", "/types-prets", data, () => {
          resetForm();
          chargerTypesPret();
        });
      }
    }

    function remplirFormulaire(t) {
      document.getElementById("id").value = t.id;
      document.getElementById("nom").value = t.nom;
      document.getElementById("taux").value = t.taux;
      document.getElementById("delai").value = t.delai;
    }

    function supprimerTypePret(id) {
      if (confirm("Supprimer ce type de prêt ?")) {
        ajax("DELETE", `/types-prets/${id}`, null, () => {
          chargerTypesPret();
        });
      }
    }

    function resetForm() {
      document.getElementById("id").value = "";
      document.getElementById("nom").value = "";
      document.getElementById("taux").value = "";
      document.getElementById("delai").value = "";
    }

    chargerTypesPret();
  </script>

</body>
</html>
