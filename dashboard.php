<?php
require_once 'includes/session.php';
require_once 'classes/Wine.php';

// Vérifier que l'utilisateur est connecté
requireLogin();

$user = getCurrentUser();
$wine = new Wine();
$wineCount = $wine->countByUserId($user['id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard – MyCave</title>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body class="dashboard">
  <header class="dashboard-header">
    <div class="header-content">
      <img src="assets/img/logo-large.png" alt="myCAVE logo" class="logo">
      <div class="header-text">
        <h1>Bienvenue dans votre cave <?= htmlspecialchars($user['name']) ?></h1>
        <p>Elle contient déjà <span id="bottle-count"><?= $wineCount ?></span> bouteilles</p>
      </div>
      <div class="header-actions">
        <button class="btn-primary" onclick="window.location.href='add-wine.php'">
          Ajouter une nouvelle bouteille
        </button>
        <button class="btn-outline" onclick="logout()">
          Déconnexion
        </button>
      </div>
    </div>
  </header>

  <main class="dashboard-main">
    <div class="background-cave">
      <img src="assets/img/pexels-finn-ruijter-2153149058-32906672.jpg" alt="Image cave" class="cave-image">
    </div>
    <div class="wines-container" id="wines-container">
      <div class="loading" style="text-align: center; color: white; font-size: 1.2rem; margin: 2rem;">
        🍷 Chargement de votre cave...
      </div>
    </div>
  </main>

  <footer class="dashboard-footer">
    <p>© 2025 MyCave - Votre cave à vin digitale</p>
  </footer>

  <script>
    let currentUser = <?= json_encode($user) ?>;

    // Charger les bouteilles au chargement de la page
    document.addEventListener('DOMContentLoaded', async () => {
      await loadWines();
    });

    // Charger les bouteilles depuis l'API
    async function loadWines() {
      try {
        const response = await fetch('api/wines.php');
        const data = await response.json();
        
        if (data.success) {
          displayWines(data.wines);
          updateBottleCount(data.count);
        } else {
          showError('Erreur lors du chargement des bouteilles');
        }
      } catch (error) {
        showError('Erreur de connexion');
        console.error('Erreur:', error);
      }
    }

    // Afficher les bouteilles
    function displayWines(wines) {
      const container = document.getElementById('wines-container');
      
      if (wines.length === 0) {
        container.innerHTML = `
          <div style="text-align: center; color: white; padding: 3rem;">
            <h3 style="margin-bottom: 1rem;">🍷 Votre cave est vide</h3>
            <p style="margin-bottom: 2rem;">Commencez par ajouter votre première bouteille !</p>
            <button class="btn-primary" onclick="window.location.href='add-wine.php'">
              Ajouter une bouteille
            </button>
          </div>
        `;
        return;
      }
      
      container.innerHTML = wines.map(wine => createWineCard(wine)).join('');
    }

    // Créer une carte de vin
    function createWineCard(wine) {
      const imageUrl = wine.picture ? `uploads/${wine.picture}` : 'assets/img/default-wine.jpg';
      
      return `
        <div class="wine-card" data-id="${wine.id}">
          <div class="wine-image">
            <img src="${imageUrl}" alt="${wine.name}" onerror="this.src='assets/img/default-wine.jpg'">
          </div>
          <div class="wine-info">
            <h3>${wine.name}</h3>
            <div class="wine-details">
              <span><strong>Année:</strong> ${wine.year}</span>
              <span><strong>Cépage:</strong> ${wine.grapes}</span>
              <span><strong>Pays:</strong> ${wine.country}</span>
              <span><strong>Région:</strong> ${wine.region}</span>
            </div>
            <div class="wine-description">${wine.description}</div>
            <div class="wine-actions">
              <button class="btn-icon" onclick="editWine(${wine.id})" title="Modifier">
                ✏️
              </button>
              <button class="btn-icon" onclick="deleteWine(${wine.id})" title="Supprimer">
                🗑️
              </button>
            </div>
          </div>
        </div>
      `;
    }

    // Supprimer une bouteille
    async function deleteWine(wineId) {
      if (!confirm('Êtes-vous sûr de vouloir supprimer cette bouteille ?')) {
        return;
      }
      
      try {
        const response = await fetch(`api/wines.php?id=${wineId}`, {
          method: 'DELETE'
        });
        
        const data = await response.json();
        
        if (data.success) {
          showMessage('🗑️ Bouteille supprimée avec succès', 'success');
          await loadWines(); // Recharger la liste
        } else {
          showError(data.error || 'Erreur lors de la suppression');
        }
      } catch (error) {
        showError('Erreur de connexion');
      }
    }

    // Modifier une bouteille
    function editWine(wineId) {
      window.location.href = `add-wine.php?id=${wineId}`;
    }

    // Mettre à jour le compteur de bouteilles
    function updateBottleCount(count) {
      document.getElementById('bottle-count').textContent = count;
    }

    // Déconnexion
    async function logout() {
      try {
        const formData = new FormData();
        formData.append('action', 'logout');
        
        await fetch('api/auth.php', {
          method: 'POST',
          body: formData
        });
        
        window.location.href = 'login.php';
      } catch (error) {
        window.location.href = 'login.php';
      }
    }

    // Afficher un message d'erreur
    function showError(message) {
      showMessage(message, 'error');
    }

    // Afficher un message
    function showMessage(message, type) {
      // Créer ou utiliser un div de message existant
      let messageDiv = document.getElementById('message');
      if (!messageDiv) {
        messageDiv = document.createElement('div');
        messageDiv.id = 'message';
        messageDiv.style.cssText = `
          position: fixed;
          top: 20px;
          right: 20px;
          padding: 1rem 1.5rem;
          border-radius: 8px;
          color: white;
          font-weight: bold;
          z-index: 1000;
          max-width: 300px;
        `;
        document.body.appendChild(messageDiv);
      }
      
      messageDiv.textContent = message;
      messageDiv.style.background = type === 'success' 
        ? 'rgba(0, 255, 0, 0.8)' 
        : 'rgba(255, 0, 0, 0.8)';
      messageDiv.style.display = 'block';
      
      setTimeout(() => {
        messageDiv.style.display = 'none';
      }, 3000);
    }
  </script>
</body>
</html>