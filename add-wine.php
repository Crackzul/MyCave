<?php
require_once 'includes/session.php';
require_once 'classes/Wine.php';

// Vérifier que l'utilisateur est connecté
requireLogin();

$user = getCurrentUser();
$wine = new Wine();
$isEdit = false;
$wineData = null;

// Vérifier si on est en mode édition
if (isset($_GET['id'])) {
    $wineId = intval($_GET['id']);
    if ($wine->getById($wineId)) {
        // Vérifier que l'utilisateur a le droit de modifier cette bouteille
        if ($wine->user_id == $user['id'] || $user['role'] === 'admin') {
            $isEdit = true;
            $wineData = [
                'id' => $wine->id,
                'name' => $wine->name,
                'year' => $wine->year,
                'grapes' => $wine->grapes,
                'country' => $wine->country,
                'region' => $wine->region,
                'description' => $wine->description,
                'picture' => $wine->picture
            ];
        } else {
            header("Location: dashboard.php");
            exit();
        }
    }
}

$wineCount = $wine->countByUserId($user['id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?= $isEdit ? 'Modifier' : 'Ajouter' ?> une bouteille – MyCave</title>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body class="add-wine">
  <header class="add-wine-header">
    <div class="header-content">
      <img src="assets/img/logo-large.png" alt="myCAVE logo" class="logo">
      <div class="header-text">
        <h1>Bienvenue dans votre cave <?= htmlspecialchars($user['name']) ?></h1>
        <p>Elle contient déjà <span id="bottle-count"><?= $wineCount ?></span> bouteilles</p>
      </div>
    </div>
  </header>

  <main class="add-wine-main">
    <!-- Image de fond identique au dashboard -->
    <div class="background-cave">
      <img src="assets/img/cave-background.jpg" alt="Cave background" class="cave-image">
    </div>

    <div class="add-wine-modal">
      <div class="modal-header">
        <button class="btn-back" onclick="window.location.href='dashboard.php'">← Retour</button>
        <h2><?= $isEdit ? 'Modifier votre bouteille' : 'Ajouter votre nouvelle bouteille' ?></h2>
      </div>

      <form class="add-wine-form" id="wineForm" enctype="multipart/form-data">
        <?php if ($isEdit): ?>
        <input type="hidden" name="wine_id" value="<?= $wineData['id'] ?>">
        <?php endif; ?>
        
        <div class="form-group">
          <label>Nom du vin</label>
          <input type="text" name="name" placeholder="Nom du vin" required 
                 value="<?= $isEdit ? htmlspecialchars($wineData['name']) : '' ?>">
        </div>

        <div class="form-group">
          <label>Année</label>
          <input type="number" name="year" placeholder="Année" min="1900" max="2025" required
                 value="<?= $isEdit ? $wineData['year'] : '' ?>">
        </div>

        <div class="form-group">
          <label>Cépage</label>
          <input type="text" name="grapes" placeholder="Cépage" required
                 value="<?= $isEdit ? htmlspecialchars($wineData['grapes']) : '' ?>">
        </div>

        <div class="form-group">
          <label>Pays</label>
          <input type="text" name="country" placeholder="Pays" required
                 value="<?= $isEdit ? htmlspecialchars($wineData['country']) : '' ?>">
        </div>

        <div class="form-group">
          <label>Région</label>
          <input type="text" name="region" placeholder="Région" required
                 value="<?= $isEdit ? htmlspecialchars($wineData['region']) : '' ?>">
        </div>

        <div class="form-group">
          <label>Description</label>
          <textarea name="description" placeholder="Description du vin" rows="3" required><?= $isEdit ? htmlspecialchars($wineData['description']) : '' ?></textarea>
        </div>

        <div class="form-group">
          <label>Photo</label>
          <?php if ($isEdit && $wineData['picture']): ?>
          <div class="current-image">
            <img src="uploads/<?= htmlspecialchars($wineData['picture']) ?>" alt="Image actuelle" style="max-width: 100px; height: auto;">
            <p>Image actuelle</p>
          </div>
          <?php endif; ?>
          <input type="file" name="picture" accept="image/*" <?= !$isEdit ? '' : '' ?>>
          <?php if ($isEdit): ?>
          <small>Laissez vide pour conserver l'image actuelle</small>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">
          💾 <?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?> la bouteille
        </button>
      </form>

      <div id="message" class="message"></div>
    </div>
  </main>

  <footer class="add-wine-footer">
    <p>© 2025 MyCave - Votre cave à vin digitale</p>
  </footer>

  <script>
    const isEdit = <?= $isEdit ? 'true' : 'false' ?>;
    const wineData = <?= $isEdit ? json_encode($wineData) : 'null' ?>;

    document.getElementById('wineForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const formData = new FormData(e.target);
      
      try {
        let response;
        
        if (isEdit) {
          // Mode édition - utiliser PUT avec JSON pour les données texte
          const textData = {
            id: wineData.id,
            name: formData.get('name'),
            year: formData.get('year'),
            grapes: formData.get('grapes'),
            country: formData.get('country'),
            region: formData.get('region'),
            description: formData.get('description')
          };
          
          response = await fetch('api/wines.php', {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(textData)
          });
        } else {
          // Mode création - utiliser POST avec FormData pour gérer l'upload
          response = await fetch('api/wines.php', {
            method: 'POST',
            body: formData
          });
        }
        
        const data = await response.json();
        
        if (data.success) {
          showMessage(data.message || 'Bouteille sauvegardée avec succès !', 'success');
          setTimeout(() => {
            window.location.href = 'dashboard.php';
          }, 1500);
        } else {
          showMessage(data.error || 'Erreur lors de la sauvegarde', 'error');
        }
      } catch (error) {
        showMessage('Erreur de connexion', 'error');
      }
    });

    function showMessage(message, type) {
      const messageDiv = document.getElementById('message');
      messageDiv.textContent = message;
      messageDiv.className = `message ${type}`;
      messageDiv.style.display = 'block';
      
      setTimeout(() => {
        messageDiv.style.display = 'none';
      }, 5000);
    }

    // Pré-remplir le formulaire en mode édition
    if (isEdit && wineData) {
      console.log('Mode édition - Données chargées:', wineData);
    }
  </script>
</body>
</html>