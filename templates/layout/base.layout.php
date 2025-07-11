<!DOCTYPE html>
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
  
    
        <?php echo $contentForLayout?>
    </body>
    </html>




    