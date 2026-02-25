# Plateforme de gestion d'événements étudiants

![CI Status](https://github.com/USER/REPO/actions/workflows/ci.yml/badge.svg)

Application Laravel : création d'événements, inscriptions, Mes événements.

## Démarrage

- **Local :** cloner le dépôt, `composer install`, copier `.env.example` en `.env`, `php artisan key:generate`, configurer la BDD dans `.env`, `php artisan migrate`, `php artisan serve`.
- **Docker :** `docker-compose up --build`. L’app est sur http://localhost:8000, MySQL sur le port 3306 (voir `docs/document-technique.md`).

## CI & TDD

Le pipeline GitHub Actions (job **TDD – Tests & qualité**) exécute la suite de tests sur chaque push vers `main` et `develop`. Les tests (unitaires et feature) suivent une approche TDD : couverture accueil, recherche, auth, événements (consulter, inscription, enregistrements, inviter), profil et suivre. Remplacer `USER/REPO` dans l’URL du badge par votre organisation et dépôt.

## Documentation

Voir `docs/` et `CONTRIBUTING.md` pour le workflow Git (GitFlow).
