# Plateforme de gestion d'événements étudiants

Application Laravel : création d'événements, inscriptions, Mes événements.

## Démarrage

- **Local :** cloner le dépôt, `composer install`, copier `.env.example` en `.env`, `php artisan key:generate`, configurer la BDD dans `.env`, `php artisan migrate`, `php artisan serve`.
- **Docker :** `docker-compose up --build`. L’app est sur http://localhost:8000, MySQL sur le port 3306 (voir `docs/document-technique.md`).

## Documentation

Voir `docs/` et `CONTRIBUTING.md` pour le workflow Git (GitFlow).
