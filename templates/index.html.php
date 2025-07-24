<!-- <!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MAXITSA - Services de Transfert et Paiements</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'brand-orange': '#ff6500',
            'brand-light': '#ff6b35',
          },
          animation: {
            'fade-in': 'fadeIn 0.5s ease-in-out',
            'slide-up': 'slideUp 0.3s ease-out',
            'pulse-soft': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0', transform: 'translateY(10px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' },
            },
            slideUp: {
              '0%': { transform: 'translateY(20px)', opacity: '0' },
              '100%': { transform: 'translateY(0)', opacity: '1' },
            }
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gradient-to-br from-gray-100 via-gray-50 to-blue-50 min-h-screen flex items-center justify-center p-4"> -->

<?php
// Récupération des données de session
$errors = $_SESSION['errors'] ?? [];
$oldData = $_SESSION['old_data'] ?? [];
$success = $_SESSION['success'] ?? '';

// Nettoyer les données de session après récupération
unset($_SESSION['errors'], $_SESSION['old_data'], $_SESSION['success']);
?>

<div class="bg-white rounded-3xl shadow-2xl overflow-hidden w-full max-w-4xl relative animate-fade-in">
  <!-- Header avec gradient et animations -->
  <div class="bg-gradient-to-r from-brand-orange to-brand-light text-white text-center py-12 px-6 relative overflow-hidden">
    <div class="absolute inset-0 bg-black/5"></div>
    <div class="absolute top-0 left-0 w-full h-full">
      <div class="absolute top-4 left-4 w-8 h-8 bg-white/20 rounded-full animate-pulse-soft"></div>
      <div class="absolute top-8 right-8 w-4 h-4 bg-white/30 rounded-full animate-pulse-soft" style="animation-delay: 0.5s"></div>
      <div class="absolute bottom-6 left-1/3 w-6 h-6 bg-white/15 rounded-full animate-pulse-soft" style="animation-delay: 1s"></div>
    </div>
    <div class="relative z-10">
      <h1 class="text-4xl md:text-5xl font-bold mb-3 tracking-wide">MAXITSA</h1>
      <p class="text-xl opacity-90 font-light">Services de Transfert et Paiements</p>
    </div>
  </div>

  <div class="p-8 md:p-12">
    <!-- Messages d'erreur -->
    <?php if (!empty($errors)): ?>
      <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg animate-slide-up">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
          </div>
          <div class="ml-3">
            <ul class="text-sm text-red-700">
              <?php foreach ($errors as $field => $error): ?>
                <li class="mb-1"><?= htmlspecialchars($error) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Message de succès -->
    <?php if ($success): ?>
      <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg animate-slide-up">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-green-700"><?= htmlspecialchars($success) ?></p>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Onglets -->
    <div class="border-b border-gray-200 mb-8">
      <nav class="flex space-x-8">
        <button class="tab-button active py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-all duration-300" onclick="showTab('register')">
          <span class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            <span>Créer un compte</span>
          </span>
        </button>
        <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-all duration-300" onclick="showTab('login')">
          <span class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            <span>Se connecter</span>
          </span>
        </button>
      </nav>
    </div>

    <!-- Formulaire de création de compte -->
    <div id="register-tab" class="tab-content active">
      <form id="registerForm" method="POST" action="/register" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="form-group">
            <label for="prenom" class="block text-sm font-semibold text-gray-700 mb-2">
              Prénom <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="prenom"
              name="prenom"
              value="<?= htmlspecialchars($oldData['prenom'] ?? '') ?>"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-light focus:border-transparent transition-all duration-300 <?= isset($errors['prenom']) ? 'border-red-500 bg-red-50' : '' ?>"
              placeholder="Votre prénom" />
          </div>
          <div class="form-group">
            <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
              Nom <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="nom"
              name="nom"
              value="<?= htmlspecialchars($oldData['nom'] ?? '') ?>"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-light focus:border-transparent transition-all duration-300 <?= isset($errors['nom']) ? 'border-red-500 bg-red-50' : '' ?>"
              placeholder="Votre nom" />
          </div>
        </div>

        <div class="form-group">
          <label for="adresse" class="block text-sm font-semibold text-gray-700 mb-2">
            Adresse <span class="text-red-500">*</span>
          </label>
          <textarea
            id="adresse"
            name="adresse"
            rows="3"
            placeholder="Votre adresse complète"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-light focus:border-transparent transition-all duration-300 resize-none <?= isset($errors['adresse']) ? 'border-red-500 bg-red-50' : '' ?>"><?= htmlspecialchars($oldData['adresse'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label for="telephone" class="block text-sm font-semibold text-gray-700 mb-2">
            Téléphone <span class="text-red-500">*</span>
          </label>
          <input
            type="tel"
            id="telephone"
            name="telephone"
            value="<?= htmlspecialchars($oldData['telephone'] ?? '') ?>"
            placeholder="Ex: +221701234567 ou 701234567"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-light focus:border-transparent transition-all duration-300 <?= isset($errors['telephone']) ? 'border-red-500 bg-red-50' : '' ?>" />
          <p class="mt-2 text-sm text-gray-600">Format sénégalais requis (ex: +221701234567 ou 701234567)</p>
        </div>

        <div class="form-group">
          <label for="numero_piece_identite" class="block text-sm font-semibold text-gray-700 mb-2">
            Numéro de CNI <span class="text-red-500">*</span>
          </label>
          <input
            type="text"
            id="numero_piece_identite"
            name="numero_piece_identite"
            value="<?= htmlspecialchars($oldData['numero_piece_identite'] ?? '') ?>"
            placeholder="Ex: 1234567890123"
            maxlength="13"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-light focus:border-transparent transition-all duration-300 <?= isset($errors['numero_piece_identite']) ? 'border-red-500 bg-red-50' : '' ?>"
            oninput="validateCNI(this)" />
          <p class="mt-2 text-sm text-gray-600" id="cni-help">
            ✨ Numéro CNI sénégalais : exactement 13 chiffres
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="form-group">
            <label for="photo_recto" class="block text-sm font-semibold text-gray-700 mb-2">
              Photo recto de la CNI <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input
                type="file"
                id="photo_recto"
                name="photo_recto"
                accept="image/jpeg,image/png,image/jpg"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-light focus:border-transparent transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-light file:text-white hover:file:bg-brand-orange <?= isset($errors['photo_recto']) ? 'border-red-500 bg-red-50' : '' ?>" />
            </div>
            <p class="mt-2 text-xs text-gray-500">Formats acceptés: JPG, JPEG, PNG (max 5MB)</p>
          </div>

          <div class="form-group">
            <label for="photo_verso" class="block text-sm font-semibold text-gray-700 mb-2">
              Photo verso de la CNI <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input
                type="file"
                id="photo_verso"
                name="photo_verso"
                accept="image/jpeg,image/png,image/jpg"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-light focus:border-transparent transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-light file:text-white hover:file:bg-brand-orange <?= isset($errors['photo_verso']) ? 'border-red-500 bg-red-50' : '' ?>" />
            </div>
            <p class="mt-2 text-xs text-gray-500">Formats acceptés: JPG, JPEG, PNG (max 5MB)</p>
          </div>
        </div>

        <div class="flex items-center space-x-3">
          <input type="checkbox" id="terms" name="terms" class="w-5 h-5 text-brand-light border-2 border-gray-300 rounded focus:ring-brand-light focus:ring-2" />
          <label for="terms" class="text-sm text-gray-700">
            J'accepte les
            <a href="#" class="text-brand-light hover:text-brand-orange font-medium underline">conditions d'utilisation</a>
            et la
            <a href="#" class="text-brand-light hover:text-brand-orange font-medium underline">politique de confidentialité</a>
          </label>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-brand-orange to-brand-light text-white py-4 px-6 rounded-xl font-semibold text-lg hover:from-brand-light hover:to-brand-orange transform hover:scale-[1.02] transition-all duration-300 shadow-lg hover:shadow-xl">
          Créer mon compte principal
        </button>

        <div class="text-center text-gray-600">
          Vous avez déjà un compte ?
          <a href="#" onclick="showTab('login')" class="text-brand-light hover:text-brand-orange font-semibold ml-1">Se connecter</a>
        </div>
      </form>
    </div>

    <!-- Formulaire de connexion -->
    <div id="login-tab" class="tab-content hidden">
      <form id="loginForm" method="POST" action="/login" class="space-y-6">
        <div class="form-group">
          <label for="loginTelephone" class="block text-sm font-semibold text-gray-700 mb-2">
            Téléphone <span class="text-red-500">*</span>
          </label>
          <input
            type="tel"
            id="loginTelephone"
            name="loginTelephone"
            placeholder="Ex: +221701234567 ou 701234567"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-light focus:border-transparent transition-all duration-300 <?= isset($errors['loginTelephone']) ? 'border-red-500 bg-red-50' : '' ?>" />
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-brand-orange to-brand-light text-white py-4 px-6 rounded-xl font-semibold text-lg hover:from-brand-light hover:to-brand-orange transform hover:scale-[1.02] transition-all duration-300 shadow-lg hover:shadow-xl">
          Se connecter
        </button>

        <div class="text-center text-gray-600">
          Pas encore de compte ?
          <a href="#" onclick="showTab('register')" class="text-brand-light hover:text-brand-orange font-semibold ml-1">Créer un compte</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function showTab(tabName) {
    // Masquer tous les contenus d'onglets
    document.querySelectorAll(".tab-content").forEach((content) => {
      content.classList.add("hidden");
      content.classList.remove("active");
    });

    // Désactiver tous les boutons d'onglets
    document.querySelectorAll(".tab-button").forEach((button) => {
      button.classList.remove("active", "text-brand-light", "border-brand-light");
      button.classList.add("text-gray-500", "border-transparent");
    });

    // Activer l'onglet sélectionné
    document.getElementById(tabName + "-tab").classList.remove("hidden");
    document.getElementById(tabName + "-tab").classList.add("active");
    event.target.closest('.tab-button').classList.add("active", "text-brand-light", "border-brand-light");
    event.target.closest('.tab-button').classList.remove("text-gray-500", "border-transparent");
  }

  // Fonction de validation en temps réel du CNI
async function validateCNI(input) {
  const value = input.value;
  const helpText = document.getElementById('cni-help');
  const numericValue = value.replace(/\D/g, '');

  // Fonction pour réinitialiser champs préremplis
  function resetFields() {
    const fields = ['nom', 'prenom', 'date-naissance', 'lieu-naissance'];
    fields.forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.value = '';
        el.readOnly = false;
        el.classList.remove('bg-gray-100', 'cursor-not-allowed', 'border-green-500', 'bg-green-50');
        el.classList.add('border-gray-200');
      }
    });
    input.classList.remove('border-green-500', 'bg-green-50', 'border-red-500', 'bg-red-50');
    input.classList.add('border-gray-200');
  }

  if (numericValue.length > 13) {
    input.value = numericValue.substring(0, 13);
    return;
  }

  input.value = numericValue;

  if (numericValue.length === 0) {
    helpText.textContent = "✨ Numéro CNI sénégalais : exactement 13 chiffres";
    helpText.className = "mt-2 text-sm text-gray-600";
    resetFields();
    return;
  }

  if (numericValue.length < 13) {
    helpText.textContent = `❌ ${numericValue.length}/13 chiffres - Il manque ${13 - numericValue.length} chiffre(s)`;
    helpText.className = "mt-2 text-sm text-red-600";
    resetFields();
    input.classList.add('border-red-500', 'bg-red-50');
    input.classList.remove('border-gray-200');
    return;
  }

  // 13 chiffres => vérification API
  helpText.textContent = "🔎 Vérification...";
  helpText.className = "mt-2 text-sm text-blue-600";

  try {
    const response = await fetch(`http://localhost:8081/citoyen/nci/${numericValue}`);
    const json = await response.json();
    console.log('Réponse API CNI:', json);

    if (json.statut === 'success') {
      const citoyen = json.data;
      helpText.textContent = "✅ CNI reconnue ! Pré-remplissage automatique.";
      helpText.className = "mt-2 text-sm text-green-600";

      const fieldsToFill = {
        nom: citoyen.nom,
        prenom: citoyen.prenom,
        'date-naissance': citoyen.dateNaissance,
        'lieu-naissance': citoyen.lieuNaissance,
      };

      for (const [id, value] of Object.entries(fieldsToFill)) {
        const el = document.getElementById(id);
        if (el) {
          el.value = value;
          el.readOnly = true;
          el.classList.add('bg-gray-100', 'cursor-not-allowed');
          el.classList.remove('border-red-500', 'bg-red-50', 'border-gray-200');
          el.classList.add('border-green-500', 'bg-green-50');
        }
      }

      // Photo identité si présente
      const photoIdentite = document.getElementById('photo-identite');
      if (photoIdentite && citoyen.urlPhotoIdentite) {
        photoIdentite.src = citoyen.urlPhotoIdentite;
      }

      input.classList.remove('border-red-500', 'bg-red-50');
      input.classList.add('border-green-500', 'bg-green-50');
    } else {
      helpText.textContent = "❌ CNI introuvable";
      helpText.className = "mt-2 text-sm text-red-600";
      resetFields();
      input.classList.add('border-red-500', 'bg-red-50');
      input.classList.remove('border-green-500', 'bg-green-50');
    }
  } catch (error) {
    console.error('Erreur API CNI:', error);
    helpText.textContent = "❌ Erreur lors de la vérification";
    helpText.className = "mt-2 text-sm text-red-600";
    resetFields();
    input.classList.add('border-red-500', 'bg-red-50');
    input.classList.remove('border-green-500', 'bg-green-50');
  }
}





  // Validation du téléphone en temps réel
  document.getElementById('telephone').addEventListener('input', function(e) {
    const value = e.target.value;
    const phonePattern = /^(\+221|7[056789])[0-9]{7}$/;

    if (value && !phonePattern.test(value)) {
      e.target.classList.add('border-red-500', 'bg-red-50');
      e.target.classList.remove('border-gray-200');
    } else {
      e.target.classList.remove('border-red-500', 'bg-red-50');
      e.target.classList.add('border-gray-200');
    }
  });

  document.getElementById('loginTelephone').addEventListener('input', function(e) {
    const value = e.target.value;
    const phonePattern = /^(\+221|7[056789])[0-9]{7}$/;

    if (value && !phonePattern.test(value)) {
      e.target.classList.add('border-red-500', 'bg-red-50');
      e.target.classList.remove('border-gray-200');
    } else {
      e.target.classList.remove('border-red-500', 'bg-red-50');
      e.target.classList.add('border-gray-200');
    }
  });

  // Initialiser les classes pour les onglets
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.tab-button').forEach(button => {
      if (button.classList.contains('active')) {
        button.classList.add('text-brand-light', 'border-brand-light');
        button.classList.remove('text-gray-500', 'border-transparent');
      } else {
        button.classList.add('text-gray-500', 'border-transparent');
        button.classList.remove('text-brand-light', 'border-brand-light');
      }
    });
  });
</script>

<style>
  .tab-button.active {
    color: #ff6b35;
    border-bottom-color: #ff6b35;
  }

  .tab-button:not(.active) {
    color: #6b7280;
    border-bottom-color: transparent;
  }

  .tab-button:not(.active):hover {
    color: #ff6b35;
    border-bottom-color: #ff6b35;
  }
</style>
<!-- </body>
</html> -->