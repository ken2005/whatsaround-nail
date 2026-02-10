# Contribution et workflow Git

## GitFlow

- **main** : branche stable (livrable).
- **develop** : branche d'intégration.
- **feature/xxx** : une branche par fonctionnalité, créée depuis `develop`, mergée dans `develop` après revue.

## Règles

- Pas de push direct sur `main` ; tout passe par merge depuis `develop`.
- Commits : format `type(scope): message` (ex. `feat(auth): ajout page connexion`).
- Types : feat, fix, chore, docs, refactor, test.

## Processus

1. Partir de `develop` à jour : `git checkout develop && git pull`.
2. Créer une branche : `git checkout -b feature/nom-us`.
3. Committer par petits pas, puis ouvrir une MR/PR vers `develop`.
