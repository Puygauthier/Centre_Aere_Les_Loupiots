# Centre Aéré – Les Loupiots

Application **MVC PHP** (WAMP) pour gérer les activités et inscriptions d’un centre aéré.  
- **Capacité par défaut** : 75 (centre), 54 (sortie)  
- **Certificats requis** (ex. natation / vélo) avec **upload** par les parents et **validation** par le staff

---

## Prérequis
- **PHP ≥ 8.1** avec PDO MySQL (WAMP recommandé)
- **MySQL/MariaDB**
- Navigateur web

---

## Installation

### 1) Base de données
- Créez une base **centre_aere_les_loupiots**.
- Importez le fichier **schema.sql** (via phpMyAdmin ou client MySQL).
  *schema.sql contient la structure et un jeu d’exemple minimal.*

### 2) Configuration
- Copiez l’exemple puis adaptez vos accès MySQL :

    Copy-Item app\config.example.php app\config.php

- Éditez **app/config.php** :
  - Renseignez `host`, `user`, `pass`, etc.
  - Laissez `base_path` **vide** si vous lancez avec le serveur PHP intégré.
  - Mot de passe staff par défaut : **loupiots2025** (modifiable).

### 3) Lancer en local (WAMP)
- Terminal PowerShell à la racine du projet :

    "C:\wamp64\bin\php\php8.3.14\php.exe" -S 127.0.0.1:8000 -t public

- Ouvrez : **http://127.0.0.1:8000/**
- Si le port 8000 est occupé, utilisez 8001 :

    "C:\wamp64\bin\php\php8.3.14\php.exe" -S 127.0.0.1:8001 -t public

---

## Fonctionnel
- Inscription avec **contrôle de capacité** et **anti-doublon** (même enfant / même activité).
- **Sans certificat requis** → inscription **validée** si places disponibles.
- **Certificat requis non fourni** → **inscription refusée** (message aux parents).
- **Certificat uploadé** → inscription **en_attente** (validation par le staff).
- **Promotion automatique** en `valide` après validation staff (si places).
- Espace **staff** protégé (validation des certificats, liste des inscriptions par activité).

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
- **Staff** : `/login`  
  Mot de passe défini dans `app/config.php` → `staff.password` (par défaut : **loupiots2025**).

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
Capacités par défaut : `centre` = **75**, `sortie` = **54**.  
Ajuster au besoin :

    UPDATE activites SET capacity = 30 WHERE id = 1;

---

## Bonnes pratiques de versioning
- **app/config.php** n’est pas versionné (local) → utilisez **app/config.example.php**.
- Les fichiers d’uploads sont **ignorés** (dossiers gardés via `.gitkeep`).

---

## Dépannage (FAQ)
- **ERR_CONNECTION_REFUSED** → relancez la commande de lancement (voir ci-dessus).
- **“Failed opening required app/config.php”** → recopiez l’exemple :

    Copy-Item app\config.example.php app\config.php -Force

- **Erreur MySQL / table manquante** → réimportez `schema.sql` dans `centre_aere_les_loupiots`.
- **404 sous Apache** → vérifiez le `.htaccess` dans `public/` et que la racine pointe bien sur `public/`.

---

## Licence
Projet scolaire — usage pédagogique.
