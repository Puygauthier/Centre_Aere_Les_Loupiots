# Centre Aéré – Les Loupiots

MVC PHP (WAMP). Capacités: 75 (centre), 54 (sortie). Certificats requis (natation / vélo) avec upload et validation.

## Installation
1. Importer `schema.sql` dans MySQL (phpMyAdmin).
2. Configurer `app/core/Database.php` (hôte, user, pass).
3. Démarrer le serveur PHP intégré :

ou via Wamp/Apache (VirtualHost).

## Fonctionnel
- Inscription avec contrôle capacité.
- Certificats requis : upload si manquants, statut `en_attente`, validation staff.

## Arborescence
- `app/core` : Database, …
- `app/models` : Activite, Inscription, Certificat, …
- `app/controllers` : InscriptionController, CertificatController, …
- `public/` : index.php, assets, uploads (ignoré par Git).

## Scripts SQL
Voir `schema.sql` (tables + triggers + jeu d'essai).
