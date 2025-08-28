<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Activite;
use PDO;

class ActiviteController
{
    /**
     * GET /activites/{id}/certifs
     * → JSON : liste des certificats requis pour l'activité
     */
    public function certifs($id): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id  = (int) $id;
        $act = Activite::find($id);
        if (!$act) {
            http_response_code(404);
            echo json_encode(['error' => 'Activité introuvable']);
            return;
        }

        $certifs = Activite::certificatsRequis($id); // [] si aucun
        echo json_encode($certifs);
    }

    /**
     * GET /activites/{id}/inscriptions
     * → Vue listant les inscriptions d'une activité (réservé staff)
     */
    public function inscriptions($id): void
    {
        // Protection "staff"
        if (empty($_SESSION['is_staff'])) {
            $_SESSION['flash'] = ['type' => 'error', 'text' => "Espace réservé au personnel. Connectez-vous."];
            $base = defined('BASE_PATH') ? BASE_PATH : '';
            header('Location: ' . $base . '/login');
            return;
        }

        $id  = (int) $id;
        $pdo = Database::getPdo();

        $activite = Activite::find($id);
        if (!$activite) {
            http_response_code(404);
            echo "Activité introuvable";
            return;
        }

        $sql = "SELECT i.id, i.statut, i.created_at,
                       e.id AS enfant_id, e.nom, e.prenom
                FROM inscriptions i
                JOIN enfants e ON e.id = i.enfant_id
                WHERE i.activite_id = ?
                ORDER BY i.id DESC";
        $st = $pdo->prepare($sql);
        $st->execute([$id]);
        $inscriptions = $st->fetchAll(PDO::FETCH_ASSOC);

        // Rend la vue spécifique (sans layout) — elle inclut le CSS global.
        include ROOT . '/app/views/activites/inscriptions.php';
    }
}
