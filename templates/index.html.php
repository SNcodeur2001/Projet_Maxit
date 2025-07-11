<!-- <!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MAXITSA - Services de Transfert et Paiements</title>
  <style>
    /* Votre CSS existant reste identique */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: rgb(237, 235, 235);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      width: 100%;
      max-width: 900px;
      margin: 20px;
      position: relative;
    }

    .header {
      background: #ff6500;
      color: white;
      text-align: center;
      padding: 40px 20px;
      position: relative;
    }

    .logo {
      font-size: 2.5em;
      font-weight: bold;
      margin-bottom: 10px;
      z-index: 1;
      position: relative;
    }

    .tagline {
      font-size: 1.1em;
      opacity: 0.9;
      z-index: 1;
      position: relative;
    }

    .form-container {
      padding: 30px 40px;
      max-height: 100vh;
      overflow-y: auto;
      box-sizing: border-box;
      position: relative;
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .form-tabs {
      display: flex;
      margin-bottom: 30px;
      border-bottom: 2px solid #f0f0f0;
    }

    .tab-button {
      flex: 1;
      padding: 15px 20px;
      background: none;
      border: none;
      font-size: 1.1em;
      cursor: pointer;
      transition: all 0.3s ease;
      border-bottom: 3px solid transparent;
      color: #666;
    }

    .tab-button.active {
      color: #ff6b35;
      border-bottom-color: #ff6b35;
      font-weight: 600;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    .form-group {
      margin-bottom: 25px;
    }

    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
    }

    .form-row .form-group {
      flex: 1 1 250px;
      min-width: 250px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #333;
    }

    .required {
      color: #ff6b35;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="tel"],
    input[type="file"],
    select,
    textarea {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 1em;
      transition: border-color 0.3s ease;
      box-sizing: border-box;
    }

    textarea {
      min-height: 80px;
      resize: vertical;
    }

    input:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: #ff6b35;
    }

    /* ✨ Style pour les champs avec erreur */
    .error-field {
      border-color: #dc3545 !important;
      background-color: #fff5f5;
    }

    /* ✨ Style pour les messages d'aide */
    .help-text {
      font-size: 0.85em;
      color: #666;
      margin-top: 5px;
    }

    .help-text.error {
      color: #dc3545;
    }

    .btn {
      background: #ff6500;
      color: white;
      border: none;
      padding: 15px 30px;
      border-radius: 8px;
      font-size: 1.1em;
      font-weight: 600;
      cursor: pointer;
      width: 100%;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(255, 107, 53, 0.3);
    }

    .login-link {
      text-align: center;
      margin-top: 20px;
      color: #666;
    }

    .login-link a {
      color: #ff6b35;
      text-decoration: none;
      font-weight: 600;
    }

    .checkbox-group {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 25px;
    }

    .checkbox-group input[type="checkbox"] {
      width: auto;
    }

    .checkbox-group label {
      margin-bottom: 0;
      font-weight: normal;
    }

    .error-message {
      background-color: #f8d7da;
      color: #721c24;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      border: 1px solid #f5c6cb;
    }

    .error-message ul {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .error-message li {
      margin-bottom: 5px;
    }

    .success-message {
      background-color: #d4edda;
      color: #155724;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      border: 1px solid #c3e6cb;
    }

    @media (max-width: 768px) {
      .container {
        margin: 10px;
      }

      .form-container {
        padding: 30px 20px;
        min-height: 550px;
        display: flex;
        flex-direction: column;
        justify-content: center;
      }

      .form-row {
        flex-direction: column;
        gap: 0;
      }
    }
  </style>
</head>
<body>
  <?php
  // Récupération des données de session
  $errors = $_SESSION['errors'] ?? [];
  $oldData = $_SESSION['old_data'] ?? [];
  $success = $_SESSION['success'] ?? '';
    
  // Nettoyer les données de session après récupération
  unset($_SESSION['errors'], $_SESSION['old_data'], $_SESSION['success']);
  ?>
   -->
  <div class="container">
      <div class="header">
          <div class="logo">MAXITSA</div>
          <div class="tagline">Services de Transfert et Paiements</div>
      </div>

      <div class="form-container">
          <?php if (!empty($errors)): ?>
              <div class="error-message">
                  <ul>
                      <?php foreach ($errors as $field => $error): ?>
                          <li><?= htmlspecialchars($error) ?></li>
                      <?php endforeach; ?>
                  </ul>
              </div>
          <?php endif; ?>

          <?php if ($success): ?>
              <div class="success-message">
                  <?= htmlspecialchars($success) ?>
              </div>
          <?php endif; ?>

          <div class="form-tabs">
              <button class="tab-button active" onclick="showTab('register')">
                  Créer un compte
              </button>
              <button class="tab-button" onclick="showTab('login')">
                  Se connecter
              </button>
          </div>

          <!-- Formulaire de création de compte -->
          <div id="register-tab" class="tab-content active">
              <form id="registerForm" method="POST" action="/register" enctype="multipart/form-data">
                  <div class="form-row">
                      <div class="form-group">
                          <label for="prenom">Prénom <span class="required">*</span></label>
                          <input
                              type="text"
                              id="prenom"
                              name="prenom"
                              value="<?= htmlspecialchars($oldData['prenom'] ?? '') ?>"
                              class="<?= isset($errors['prenom']) ? 'error-field' : '' ?>"
                          />
                      </div>
                      <div class="form-group">
                          <label for="nom">Nom <span class="required">*</span></label>
                          <input
                              type="text"
                              id="nom"
                              name="nom"
                              value="<?= htmlspecialchars($oldData['nom'] ?? '') ?>"
                              class="<?= isset($errors['nom']) ? 'error-field' : '' ?>"
                          />
                      </div>
                  </div>

                  <div class="form-group">
                      <label for="adresse">Adresse <span class="required">*</span></label>
                      <textarea
                          id="adresse"
                          name="adresse"
                          rows="3"
                          placeholder="Votre adresse complète"
                          class="<?= isset($errors['adresse']) ? 'error-field' : '' ?>"
                      ><?= htmlspecialchars($oldData['adresse'] ?? '') ?></textarea>
                  </div>

                  <div class="form-group">
                      <label for="telephone">Téléphone <span class="required">*</span></label>
                      <input
                          type="tel"
                          id="telephone"
                          name="telephone"
                          value="<?= htmlspecialchars($oldData['telephone'] ?? '') ?>"
                          placeholder="Ex: +221701234567 ou 701234567"
                          class="<?= isset($errors['telephone']) ? 'error-field' : '' ?>"
                      />
                      <div class="help-text">Format sénégalais requis (ex: +221701234567 ou 701234567)</div>
                  </div>

                  <div class="form-group">
                      <label for="numero_piece_identite">Numéro de CNI <span class="required">*</span></label>
                      <input
                          type="text"
                          id="numero_piece_identite"
                          name="numero_piece_identite"
                          value="<?= htmlspecialchars($oldData['numero_piece_identite'] ?? '') ?>"
                          placeholder="Ex: 1234567890123"
                          maxlength="13"
                          class="<?= isset($errors['numero_piece_identite']) ? 'error-field' : '' ?>"
                          oninput="validateCNI(this)"
                      />
                      <div class="help-text" id="cni-help">
                          ✨ Numéro CNI sénégalais : exactement 13 chiffres
                      </div>
                  </div>

                  <div class="form-group">
                      <label for="photo_recto">Photo recto de la CNI <span class="required">*</span></label>
                      <input
                          type="file"
                          id="photo_recto"
                          name="photo_recto"
                          accept="image/jpeg,image/png,image/jpg"
                          class="<?= isset($errors['photo_recto']) ? 'error-field' : '' ?>"
                      />
                      <small style="color: #666; font-size: 0.9em;">Formats acceptés: JPG, JPEG, PNG (max 5MB)</small>
                  </div>

                  <div class="form-group">
                      <label for="photo_verso">Photo verso de la CNI <span class="required">*</span></label>
                      <input
                          type="file"
                          id="photo_verso"
                          name="photo_verso"
                          accept="image/jpeg,image/png,image/jpg"
                          class="<?= isset($errors['photo_verso']) ? 'error-field' : '' ?>"
                      />
                      <small style="color: #666; font-size: 0.9em;">Formats acceptés: JPG, JPEG, PNG (max 5MB)</small>
                  </div>

                  <div class="checkbox-group">
                      <input type="checkbox" id="terms" name="terms"  />
                      <label for="terms">
                          J'accepte les
                          <a href="#" style="color: #ff6b35">conditions d'utilisation</a>
                          et la
                          <a href="#" style="color: #ff6b35">politique de confidentialité</a>
                      </label>
                  </div>

                  <button type="submit" class="btn">
                      Créer mon compte principal
                  </button>

                  <div class="login-link">
                      Vous avez déjà un compte ?
                      <a href="#" onclick="showTab('login')">Se connecter</a>
                  </div>
              </form>
          </div>

          <!-- Formulaire de connexion -->
          <div id="login-tab" class="tab-content">
              <form id="loginForm" method="POST" action="/login">
                  <div class="form-group">
                      <label for="loginTelephone">Téléphone <span class="required">*</span></label>
                      <input
                          type="tel"
                          id="loginTelephone"
                          name="loginTelephone"
                            
                          placeholder="Ex: +221701234567 ou 701234567"
                          class="<?= isset($errors['loginTelephone']) ? 'error-field' : '' ?>"
                      />
                  </div>

                  <button type="submit" class="btn">Se connecter</button>

                  <div class="login-link">
                      Pas encore de compte ?
                      <a href="#" onclick="showTab('register')">Créer un compte</a>
                  </div>
              </form>
          </div>
      </div>
  </div>

  <script>
      function showTab(tabName) {
          // Masquer tous les contenus d'onglets
          document.querySelectorAll(".tab-content").forEach((content) => {
              content.classList.remove("active");
          });

          // Désactiver tous les boutons d'onglets
          document.querySelectorAll(".tab-button").forEach((button) => {
              button.classList.remove("active");
          });

          // Activer l'onglet sélectionné
          document.getElementById(tabName + "-tab").classList.add("active");
          event.target.classList.add("active");
      }

      // ✨ Fonction de validation en temps réel du CNI
      function validateCNI(input) {
          const value = input.value;
          const helpText = document.getElementById('cni-help');
            
          // Supprimer tous les caractères non numériques
          const numericValue = value.replace(/\D/g, '');
            
          // Limiter à 13 chiffres
          if (numericValue.length > 13) {
              input.value = numericValue.substring(0, 13);
              return;
          }
            
          input.value = numericValue;
            
          // Validation visuelle
          if (numericValue.length === 0) {
              helpText.textContent = "✨ Numéro CNI sénégalais : exactement 13 chiffres";
              helpText.className = "help-text";
              input.classList.remove('error-field');
          } else if (numericValue.length < 13) {
              helpText.textContent = `❌ ${numericValue.length}/13 chiffres - Il manque ${13 - numericValue.length} chiffre(s)`;
              helpText.className = "help-text error";
              input.classList.add('error-field');
          } else if (numericValue.length === 13) {
              helpText.textContent = "✅ Format CNI valide !";
              helpText.className = "help-text";
              helpText.style.color = "#28a745";
              input.classList.remove('error-field');
          }
      }

      // ✨ Validation du formulaire avant soumission
      document.getElementById('registerForm').addEventListener('submit', function(e) {
          const cniInput = document.getElementById('numero_piece_identite');
          const cniValue = cniInput.value.replace(/\D/g, '');
            
          if (cniValue.length !== 13) {
              e.preventDefault();
              alert('Le numéro CNI doit contenir exactement 13 chiffres.');
              cniInput.focus();
              return false;
          }
            
          // Vérifier les conditions d'utilisation
          const termsCheckbox = document.getElementById('terms');
          if (!termsCheckbox.checked) {
              e.preventDefault();
              alert('Vous devez accepter les conditions d\'utilisation.');
              termsCheckbox.focus();
              return false;
          }
      });

      // ✨ Validation du téléphone en temps réel
      document.getElementById('telephone').addEventListener('input', function(e) {
          const value = e.target.value;
          const phonePattern = /^(\+221|7[056789])[0-9]{7}$/;
            
          if (value && !phonePattern.test(value)) {
              e.target.classList.add('error-field');
          } else {
              e.target.classList.remove('error-field');
          }
      });

      document.getElementById('loginTelephone').addEventListener('input', function(e) {
          const value = e.target.value;
          const phonePattern = /^(\+221|7[056789])[0-9]{7}$/;
            
          if (value && !phonePattern.test(value)) {
              e.target.classList.add('error-field');
          } else {
              e.target.classList.remove('error-field');
          }
      });
  </script>
<!-- </body>
</html> -->
