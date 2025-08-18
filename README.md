# Centre Aéré – Les Loupiots

Application MVC PHP (WAMP) pour gérer les activités et inscriptions d’un centre aéré.
- Capacité par défaut : 75 (centre), 54 (sortie)
- Certificats requis (ex. natation / vélo) avec upload par les parents et validation par le staff

---

## Prérequis
- PHP ≥ 8.1 avec PDO MySQL (WAMP recommandé)
- MySQL/MariaDB
- Navigateur web

---

## Installation

### 1) Base de données
- Créer une base nommée `centre_aere_les_loupiots`.
- Importer `schema.sql` (via phpMyAdmin ou client MySQL).
  - `schema.sql` contient la structure des tables et un petit jeu d’exemple.

### 2) Configuration
- Copier la configuration d’exemple vers la configuration locale :
    Copy-Item app\config.example.php app\config.php
- Ouvrir `app/config.php` et renseigner vos accès MySQL (host, user, pass, etc.).
- Laisser `base_path` vide si vous lancez avec le serveur PHP intégré.
- Mot de passe staff par défaut : `loupiots2025` (modifiable).

### 3) Lancer en local (WAMP)
- Terminal PowerShell à la racine du projet :
    "C:\wamp64\bin\php\php8.3.14\php.exe" -S 127.0.0.1:8000 -t public
- Ouvrir : http://127.0.0.1:8000/
- Si le port 8000 est occupé, utilisez 8001 :
    "C:\wamp64\bin\php\php8.3.14\php.exe" -S 127.0.0.1:8001 -t public

---

## Fonctionnel
- Inscription avec contrôle de capacité et anti-doublon (même enfant / même activité).
- Sans certificat requis → inscription validée si places disponibles.
- Certificat requis non fourni → inscription refusée (message affiché aux parents).
- Certificat uploadé → inscription `en_attente` (le staff valide).
- Promotion automatique en `valide` après validation staff (si places).
- Espace staff protégé (validation des certificats, liste des inscriptions par activité).

---

## Routes principales
- Accueil (liste activités) : `/`
- Nouvelle inscription (parents) : `/inscriptions/create`
- Liste des inscriptions : `/inscriptions`
- Inscriptions d’une activité (staff) : `/activites/{id}/inscriptions`
- Certificats requis (AJAX) : `/activites/{id}/certifs`
- Espace staff – connexion : `/login`
- Staff – certificats à valider : `/staff/certificats`
- Staff – action de validation : `POST /certificats/valider`

---

## Comptes / Accès
- Staff : page `/login`
- Mot de passe : défini dans `app/config.php` → `staff.password` (par défaut : `loupiots2025`).

---

## Arborescence (extrait)
    app/
      core/            # Autoloader, Router, Database (PDO)
      controllers/     # Home, Inscription, Activite, Auth, Certificat
      models/          # Activite, Inscription, Enfant, Certificat
      views/
        inscriptions/  # home (liste activités), create, index
        activites/     # inscriptions par activité (staff)
        auth/          # login staff
        staff/         # validation des certificats
    public/
      index.php        # front controller
      uploads/
        certifs/       # fichiers uploadés (non versionnés)

---

## Scripts SQL
- Capacités par défaut : centre = 75, sortie = 54.
- Ajuster une capacité (exemple) :
    UPDATE activites SET capacity = 30 WHERE id = 1;

---

## Bonnes pratiques de versioning
- `app/config.php` n’est pas versionné (fichier local) → utiliser `app/config.example.php` comme base.
- Les fichiers uploadés sont ignorés par Git (dossiers conservés via `.gitkeep`).

---

## Dépannage (FAQ)
- ERR_CONNECTION_REFUSED → relancer la commande de lancement (voir “Lancer en local”).
- « Failed opening required app/config.php » → recopier l’exemple :
    Copy-Item app\config.example.php app\config.php -Force
- Erreur MySQL / table manquante → réimporter `schema.sql` dans `centre_aere_les_loupiots`.
- 404 sous Apache → vérifier le `.htaccess` dans `public/` et que la racine du site pointe bien sur `public/`.

---

## Licence
Projet scolaire — usage pédagogique.
