<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Créer un nouveau prêt client</title>
  <link rel="stylesheet" href="css/style_formulaire_client.css">
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


  <h1>Créer un nouveau prêt client</h1>

  <form id="pretForm">
    <h2>Informations Client</h2>
    <input type="text" id="nom" placeholder="Nom" required />
    <input type="text" id="prenom" placeholder="Prénom" required />
    <input type="email" id="email" placeholder="Email" required />
    <input type="date" id="date_naissance" placeholder="Date de naissance" required />

    <h2>Informations Prêt</h2>
    <select id="type_pret" required></select>
    <input type="number" id="montant" placeholder="Montant (min 1 000 000 Ar)" min="1000000" required />
    <input type="number" id="duree" placeholder="Durée (mois)" min="1" required />
    <input type="number" id="assurance" placeholder="Assurance (%)" step="0.01" min="0" value="0" />

    <button type="submit">Accorder prêt</button>
  </form>

  <script>
    const apiBase = "http://localhost/tp-flightphp-crud/tp-flightphp-crud/ws";
    // const apiBase = "/ETU003194/t/tp-flightphp-crud/tp-flightphp-crud/ws";

    // Charge les types de prêt
    function loadTypes() {
      fetch(apiBase + "/types-prets")
        .then(res => res.json())
        .then(data => {
          const select = document.getElementById("type_pret");
          data.forEach(t => {
            const opt = document.createElement("option");
            opt.value = t.id;
            opt.textContent = `${t.nom} (${(t.taux*100).toFixed(2)}% - ${t.delai} mois)`;
            select.appendChild(opt);
          });
        });
    }

    // Forcer assurance si durée ≤ 6
    const dureeInput = document.getElementById("duree");
    const assuranceInput = document.getElementById("assurance");

    dureeInput.addEventListener("input", () => {
      if(parseInt(dureeInput.value) <= 6) {
        assuranceInput.required = true;
        if(assuranceInput.value == "" || parseFloat(assuranceInput.value) === 0) {
          assuranceInput.value = 2; // Valeur par défaut obligatoire (2%)
        }
      } else {
        assuranceInput.required = false;
      }
    });

    document.getElementById("pretForm").addEventListener("submit", e => {
      e.preventDefault();

      const clientData = {
        nom: document.getElementById("nom").value.trim(),
        prenom: document.getElementById("prenom").value.trim(),
        email: document.getElementById("email").value.trim(),
        date_naissance: document.getElementById("date_naissance").value
      };

      const pretData = {
        id_type_pret: document.getElementById("type_pret").value,
        montant: parseFloat(document.getElementById("montant").value),
        duree: parseInt(document.getElementById("duree").value),
        assurance: parseFloat(document.getElementById("assurance").value)
      };

      // Vérifier client existant
      fetch(apiBase + `/clients/email?email=${encodeURIComponent(clientData.email)}`)
        .then(res => res.json())
        .then(client => {
          if(client && client.id) {
            pretData.id_client = client.id;
            createPret(pretData);
          } else {
            fetch(apiBase + "/clients", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify(clientData)
            })
            .then(res => res.json())
            .then(newClient => {
              if(newClient.id) {
                pretData.id_client = newClient.id;
                createPret(pretData);
              } else {
                alert("Erreur création client");
              }
            });
          }
        });
    });

    function createPret(pretData) {
      fetch(apiBase + "/prets-clients", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(pretData)
      })
      .then(res => {
        if(res.ok) return res.json();
        else return res.json().then(err => { throw err; });
      })
      .then(data => {
        alert(data.message || "Prêt accordé");
        window.location.href = "index-pretclient.php";
      })
      .catch(err => alert("Erreur : " + (err.error || "Inconnue")));
    }

    loadTypes();
  </script>

</body>
</html>
