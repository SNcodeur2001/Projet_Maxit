<?php
$title = "Rechercher un compte";
$headerTitle = "Rechercher un compte";
ob_start();
?>
<form method="post" action="/recherche-compte" class="max-w-md mx-auto bg-white p-8 rounded-xl shadow space-y-6">
    <h2 class="text-xl font-bold mb-6 text-primary">Rechercher un compte</h2>
    <input type="text" name="numero_compte" placeholder="Numéro de compte" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl" />
    <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-xl font-semibold text-lg">Rechercher</button>
</form>
<?php
$contentForLayout = ob_get_clean();
require __DIR__ . '/layout/gestionnaire.layout.php';
?>