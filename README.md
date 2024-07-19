# Space Tactics Docker
### Based on Symfony Docker with frankenPHP i refactored the old project and will use it in a docker enviroment.

## Mitwirkende
Wir freuen uns über Beiträge! Bitte erstelle einen Fork des Repositories und öffne einen Pull Request, um Änderungen vorzuschlagen.

## Lizenz
Dieses Projekt steht unter der MIT-Lizenz. Weitere Informationen findest du in der Datei LICENSE.

Willkommen im offiziellen Docker-Repository für **Space Tactics**! Diese Repository enthält alles, was du benötigst, um Space Tactics mit Docker zu betreiben.
- PHP 8.3
- mySQL 8
- mailhog
- phpMyAdmin

## Voraussetzungen

- Docker installiert (mindestens Version 20.10)
- Docker Compose installiert (mindestens Version 1.29)

## Installation

1. **Repository klonen:**
    ```sh
    git clone https://github.com/ShaoKhan/Space-Tactics-Docker.git
    cd Space-Tactics-Docker
    ```

2. **Umgebungsvariablen setzen:**
   Erstelle eine `.env` Datei und setze die erforderlichen Variablen. Ein Beispiel findest du in der Datei `.env.example`.

3. **Container starten:**
    ```sh
    docker-compose up -d
    ```

## Verwendung

Nach dem Start der Container kannst du auf Space Tactics unter `http://localhost:PORT` zugreifen, wobei `PORT` der in der `docker-compose.yml` festgelegte Port ist.

## Konfiguration

### MySQL-Version

Die MySQL-Version kann durch Setzen der Umgebungsvariable `MYSQL_VERSION` angepasst werden. Standardmäßig wird MySQL 8 verwendet.

Beispiel für `docker-compose.yml`:
```yaml
services:
  db:
    image: mysql:${MYSQL_VERSION:-8}
    ...
