# wasyoku-sensei
## Procédure de déploiement

### Prérequis
- Docker et Docker Compose installés sur le serveur cible

### 1. Récupération du dépôt
```bash
git clone https://github.com/YnYnm8/wasyoku-sensei.git
cd wasyoku-sensei
```

### 2. Configuration des variables d'environnement de production
Créer un fichier `.env.local` (non versionné, à créer directement sur le serveur) avec :

```env
APP_ENV=prod
APP_SECRET=<chaîne aléatoire de 32 caractères>
MAILER_DSN=gmail://<adresse>:<mot de passe d'application>@default
MYSQL_DATABASE=app
MYSQL_USER=app
MYSQL_PASSWORD=<mot de passe>
MYSQL_ROOT_PASSWORD=<mot de passe>
```

### 3. Build et démarrage des conteneurs
`compose.override.yaml` contient des ajouts pour le développement (Mailpit, port DB exposé) : en production, on utilise uniquement `compose.yaml`.

```bash
docker compose -f compose.yaml up -d --build
```

Cela démarre le service `php` (construit depuis le `Dockerfile`, FrankenPHP) et le service `database` (MySQL 8). Le service `php` attend que `database` soit en bonne santé (healthcheck) avant de démarrer.

### 4. Création du schéma de base de données
```bash
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

### 5. Vérification
L'application est accessible sur le port 8080 de l'hôte (mappé vers le port 80 du conteneur).

### Mise à jour
```bash
git pull
docker compose -f compose.yaml up -d --build
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

### 6. Sauvegarde et restauration
Les scripts `scripts/backup-db.sh` et `scripts/restore-db.sh` permettent de sauvegarder/restaurer la base de données MySQL du conteneur via `mysqldump` (utilisent `MYSQL_USER` / `MYSQL_PASSWORD` / `MYSQL_DATABASE` définis dans `compose.yaml`, ou leurs valeurs par défaut `app` / `!ChangeMe!`).

**Sauvegarde** (crée `backups/backup_YYYYMMDD_HHMMSS.sql`, dossier non versionné) :
```bash
./scripts/backup-db.sh
```

**Restauration** (écrase les données existantes, une confirmation est demandée avant l'exécution) :
```bash
./scripts/restore-db.sh backups/backup_YYYYMMDD_HHMMSS.sql
```

### Notes
- Les données MySQL sont persistées dans le volume nommé `database_data` : elles ne sont pas perdues lors du redémarrage des conteneurs.
- En production avec un vrai nom de domaine, FrankenPHP (basé sur Caddy) peut activer HTTPS automatiquement en définissant `SERVER_NAME` — non testé ici, piste d'évolution.