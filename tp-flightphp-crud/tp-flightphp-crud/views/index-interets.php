<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Intérêts mensuels gagnés</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- <style>
  body { font-family: sans-serif; padding: 20px; max-width: 700px; margin: auto; }
  label, input { margin: 5px 10px 15px 0; }
  table { border-collapse: collapse; width: 100%; margin-top: 20px; }
  th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
  th { background-color: #eee; }
</style> -->
  <link rel="stylesheet" href="css/style_index_interts.css">
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


<h1>Intérêts mensuels gagnés</h1>

<form id="filterForm">
  <label for="date_debut">Date début:</label>
  <input type="month" id="date_debut" name="date_debut" required />
  
  <label for="date_fin">Date fin:</label>
  <input type="month" id="date_fin" name="date_fin" required />
  
  <button type="submit">Filtrer</button>
</form>

<table>
  <thead>
    <tr><th>Mois-Année</th><th>Intérêts (Ar)</th></tr>
  </thead>
  <tbody id="interetsBody"></tbody>
</table>

<canvas id="interetsChart" style="max-width: 700px; margin-top: 40px;"></canvas>

<script>
const apiBase = "http://localhost/tp-flightphp-crud/tp-flightphp-crud/ws";
// const apiBase = "/ETU003194/t/tp-flightphp-crud/tp-flightphp-crud/ws";

function fetchInterets(dateDebut, dateFin) {
  fetch(`${apiBase}/prets-clients/interets?date_debut=${dateDebut}-01&date_fin=${dateFin}-31`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('interetsBody');
      tbody.innerHTML = '';
      const labels = [];
      const values = [];

      data.forEach(row => {
        labels.push(row.mois_annee);
        values.push(parseFloat(row.interets_mensuels).toFixed(2));

        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${row.mois_annee}</td><td>${parseFloat(row.interets_mensuels).toFixed(2)}</td>`;
        tbody.appendChild(tr);
      });

      renderChart(labels, values);
    });
}

function renderChart(labels, data) {
  const ctx = document.getElementById('interetsChart').getContext('2d');
  if(window.chartInstance) window.chartInstance.destroy();
  window.chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Intérêts mensuels (Ar)',
        data,
        backgroundColor: 'rgba(54, 162, 235, 0.7)'
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
}

document.getElementById('filterForm').addEventListener('submit', e => {
  e.preventDefault();
  const dateDebut = document.getElementById('date_debut').value;
  const dateFin = document.getElementById('date_fin').value;
  if (dateDebut > dateFin) {
    alert('La date de début doit être antérieure à la date de fin');
    return;
  }
  fetchInterets(dateDebut, dateFin);
});

// Charge données par défaut : 1 an glissant
const now = new Date();
const lastYear = new Date();
lastYear.setFullYear(now.getFullYear() - 1);
document.getElementById('date_debut').value = lastYear.toISOString().slice(0,7);
document.getElementById('date_fin').value = now.toISOString().slice(0,7);
fetchInterets(
  document.getElementById('date_debut').value,
  document.getElementById('date_fin').value
);
</script>

</body>
</html>
