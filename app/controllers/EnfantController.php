<?php
namespace App\Controllers;

use App\Models\Enfant;
use App\Models\Responsable;

class EnfantController
{
    public function index(): void
    {
        $enfants = Enfant::all();
        $title = 'Liste des enfants';
        $contentView = ROOT . '/app/views/enfants/index.php';
        require ROOT . '/app/views/layout.php';
    }

    public function create(): void
    {
        $responsables = Responsable::all();
        $title = 'Ajouter un enfant';
        $contentView = ROOT . '/app/views/enfants/create.php';
        require ROOT . '/app/views/layout.php';
    }

    public function store(): void
    {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $date_naissance = $_POST['date_naissance'] ?? '';
        $responsable_princ = (int)($_POST['responsable_princ'] ?? 0);

        $errors = [];
        if ($nom === '') $errors[] = 'Nom requis';
        if ($prenom === '') $errors[] = 'Prénom requis';
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_naissance)) $errors[] = 'Date de naissance invalide (YYYY-MM-DD)';
        if ($responsable_princ <= 0) $errors[] = 'Responsable requis';

        if ($errors) {
            $responsables = Responsable::all();
            $title = 'Ajouter un enfant';
            $contentView = ROOT . '/app/views/enfants/create.php';
            // Données du formulaire pour ré-affichage
            $formData = compact('nom','prenom','date_naissance','responsable_princ','errors');
            require ROOT . '/app/views/layout.php';
            return;
        }

        Enfant::create([
            'nom' => $nom,
            'prenom' => $prenom,
            'date_naissance' => $date_naissance,
            'responsable_princ' => $responsable_princ,
        ]);

        $base = defined('BASE_PATH') ? BASE_PATH : '';
        header('Location: ' . $base . '/enfants');
        exit;
    }
}
