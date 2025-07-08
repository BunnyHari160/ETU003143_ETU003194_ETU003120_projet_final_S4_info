<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Simulateur comparatif de prêts</title>
  <link rel="stylesheet" href="css/style_index_pret.css" />
  <style>
    body { font-family: Arial, sans-serif; max-width: 900px; margin: 20px auto; padding: 0 15px; }
    h1 { text-align: center; }
    form { display: flex; justify-content: space-between; margin-bottom: 30px; }
    fieldset { border: 1px solid #ccc; padding: 15px; width: 48%; }
    legend { font-weight: bold; }
    label { display: block; margin-top: 10px; }
    input[type="number"] { width: 100%; padding: 6px; margin-top: 4px; }
    button { margin-top: 15px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
    table { border-collapse: collapse; width: 100%; text-align: center; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    th { background-color: #f4f4f4; }
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


  <h1>Simulateur comparatif de prêts</h1>

  <form id="comparateurPretForm">
    <fieldset>
      <legend>Prêt 1</legend>
      <label>Montant (Ar)
        <input type="number" name="montant1" min="0" required />
      </label>
      <label>Taux annuel (%)
        <input type="number" name="taux1" step="0.01" min="0" required />
      </label>
      <label>Durée (mois)
        <input type="number" name="duree1" min="1" required />
      </label>
      <label>Assurance (%)
        <input type="number" name="assurance1" step="0.01" min="0" value="0" />
      </label>
    </fieldset>

    <fieldset>
      <legend>Prêt 2</legend>
      <label>Montant (Ar)
        <input type="number" name="montant2" min="0" required />
      </label>
      <label>Taux annuel (%)
        <input type="number" name="taux2" step="0.01" min="0" required />
      </label>
      <label>Durée (mois)
        <input type="number" name="duree2" min="1" required />
      </label>
      <label>Assurance (%)
        <input type="number" name="assurance2" step="0.01" min="0" value="0" />
      </label>
    </fieldset>

    <div style="align-self: flex-end;">
      <button type="submit">Comparer</button>
    </div>
  </form>

  <div id="resultatsComparaison"></div>

  <script>
const apiBase = "http://localhost/tp-flightphp-crud/tp-flightphp-crud/ws";
// const apiBase = "/ETU003194/t/tp-flightphp-crud/tp-flightphp-crud/ws";

    document.getElementById('comparateurPretForm').onsubmit = function(e) {
      e.preventDefault();

      const fd = new FormData(this);
      const data1 = {
        montant: parseFloat(fd.get('montant1')),
        taux: parseFloat(fd.get('taux1')) / 100,
        duree: parseInt(fd.get('duree1')),
        assurance: parseFloat(fd.get('assurance1'))
      };
      const data2 = {
        montant: parseFloat(fd.get('montant2')),
        taux: parseFloat(fd.get('taux2')) / 100,
        duree: parseInt(fd.get('duree2')),
        assurance: parseFloat(fd.get('assurance2'))
      };

      Promise.all([
        fetch(apiUrl, {
          method: 'POST',
          headers: {'Content-Type':'application/x-www-form-urlencoded'},
          body: new URLSearchParams(data1)
        }).then(res => {
          if (!res.ok) throw new Error('HTTP error ' + res.status);
          return res.json();
        }),
        fetch(apiUrl, {
          method: 'POST',
          headers: {'Content-Type':'application/x-www-form-urlencoded'},
          body: new URLSearchParams(data2)
        }).then(res => {
          if (!res.ok) throw new Error('HTTP error ' + res.status);
          return res.json();
        })
      ]).then(([pret1, pret2]) => {
        afficherComparaison(pret1, pret2);
      }).catch((err) => {
        alert("Erreur lors de la simulation : " + err.message);
      });
    };

    function afficherComparaison(p1, p2) {
      if(!Array.isArray(p1) || !Array.isArray(p2)) {
        document.getElementById('resultatsComparaison').innerHTML = "<p>Erreur dans les données reçues.</p>";
        return;
      }

      let html = `<table>
        <thead>
          <tr>
            <th>Mois</th>
            <th>Prêt 1 - Capital</th><th>Prêt 1 - Intérêt</th><th>Prêt 1 - Assurance</th><th>Prêt 1 - Total</th>
            <th>Prêt 2 - Capital</th><th>Prêt 2 - Intérêt</th><th>Prêt 2 - Assurance</th><th>Prêt 2 - Total</th>
          </tr>
        </thead>
        <tbody>`;

      const maxMois = Math.max(p1.length, p2.length);
      for(let i = 0; i < maxMois; i++) {
        const m1 = p1[i] || {capital:'-', interet:'-', assurance:'-', total:'-'};
        const m2 = p2[i] || {capital:'-', interet:'-', assurance:'-', total:'-'};

        html += `<tr>
          <td>${i + 1}</td>
          <td>${m1.capital}</td><td>${m1.interet}</td><td>${m1.assurance}</td><td>${m1.total}</td>
          <td>${m2.capital}</td><td>${m2.interet}</td><td>${m2.assurance}</td><td>${m2.total}</td>
        </tr>`;
      }

      html += '</tbody></table>';

      document.getElementById('resultatsComparaison').innerHTML = html;
    }
  </script>

</body>
</html>
