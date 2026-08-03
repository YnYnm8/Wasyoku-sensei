# wasyoku-sensei
## デプロイ手順 / Procédure de déploiement

### 前提条件 / Prérequis
- 対象サーバーに **Docker** と **Docker Compose** がインストールされていること
- Docker et Docker Compose installés sur le serveur cible

### 1. リポジトリの取得 / Récupération du dépôt
```bash
git clone https://github.com/YnYnm8/wasyoku-sensei.git
cd wasyoku-sensei
```

### 2. 本番用の環境変数を設定 / Configuration des variables d'environnement de production
`.env.local` を作成し、以下を設定する（このファイルはGitで管理されていないため、サーバー上で直接作成する必要がある）。
Créer un fichier `.env.local` (non versionné, à créer directement sur le serveur) avec :

```env
APP_ENV=prod
APP_SECRET=<32文字のランダムな文字列 / chaîne aléatoire de 32 caractères>
MAILER_DSN=gmail://<adresse>:<mot de passe d'application>@default
MYSQL_DATABASE=app
MYSQL_USER=app
MYSQL_PASSWORD=<パスワード / mot de passe>
MYSQL_ROOT_PASSWORD=<パスワード / mot de passe>
```

### 3. コンテナのビルドと起動 / Build et démarrage des conteneurs
`compose.override.yaml` は開発用の追加設定（メール確認用のMailpit、DBポートの外部公開）なので、本番環境では `compose.yaml` のみを使う。
`compose.override.yaml` contient des ajouts pour le développement (Mailpit, port DB exposé) : en production, on utilise uniquement `compose.yaml`.

```bash
docker compose -f compose.yaml up -d --build
```

これにより、`php` サービス（Dockerfileからビルド、FrankenPHP）と `database` サービス（MySQL 8）が起動する。`php` は `database` のヘルスチェックが通るまで待機してから起動する。
Cela démarre le service `php` (construit depuis le `Dockerfile`, FrankenPHP) et le service `database` (MySQL 8). Le service `php` attend que `database` soit en bonne santé (healthcheck) avant de démarrer.

### 4. データベースの構築 / Création du schéma de base de données
```bash
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

### 5. 動作確認 / Vérification
アプリはコンテナのポート80番（ホスト側は `8080` にマッピング）で動作する。
L'application est accessible sur le port 8080 de l'hôte (mappé vers le port 80 du conteneur) :

### 更新時 / Mise à jour
```bash
git pull
docker compose -f compose.yaml up -d --build
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

### 補足 / Notes
- MySQLのデータは名前付きボリューム `database_data` に永続化されるため、コンテナを再起動してもデータは消えない。
  Les données MySQL sont persistées dans le volume nommé `database_data` : elles ne sont pas perdues lors du redémarrage des conteneurs.
- 実運用でドメイン名を使う場合、FrankenPHP（Caddyベース）は `SERVER_NAME` にドメインを設定することで自動的にHTTPS化できる（今回は未検証・今後の課題）。
  En production avec un vrai nom de domaine, FrankenPHP (basé sur Caddy) peut activer HTTPS automatiquement en définissant `SERVER_NAME` — non testé ici, piste d'évolution.