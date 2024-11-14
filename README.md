# Projet Symfony - Kinejaj Lumoj

Ce projet est une application Symfony qui utilise l'API de [The Movie Database](https://developer.themoviedb.org/) pour rechercher des films par leur titre. Ce guide vous aidera à configurer et démarrer le projet, ainsi qu'à exécuter les tests.

## Prérequis

Assurez-vous d'avoir les éléments suivants installés :

- **PHP** (version 8.2 ou supérieure)
- **Composer** (gestionnaire de dépendances PHP)
- **Symfony CLI** (dernière version)

## Installation

### 1. Cloner le dépôt

Clonez le dépôt du projet dans votre environnement local :

```bash
git clone https://github.com/nellosan/kinejaj-lumoj.git
cd kinejaj-lumoj
```

### 2. Installer les dépendances

Installez les dépendances PHP du projet avec Composer :

```bash
composer install
```

### 3. Configurer les variables d'environnement

Créez un fichier .env.local à la racine du projet pour surcharger les configurations par défaut, notamment les paramètres de base de données :

```php
# Copiez et ajustez les valeurs ci-dessous dans votre fichier .env.local
APP_ENV=dev
API_KEY=your_api_key
```

### 4. Lancer le serveur de développement

Utilisez la CLI Symfony pour lancer un serveur de développement :

```bash
symfony local:server:start
```

L'application sera accessible à l'adresse http://localhost:8000.

## Exécuter les tests

### 1. Configuration de l'environnement de test

Avant d'exécuter les tests, assurez-vous que l'environnement test est configuré. Vérifiez que vous avez un fichier `phpunit.xml.dist` à la racine du projet.

### 2. Lancer les tests avec PHPUnit

Lancez les tests en exécutant la commande suivante :

```
php bin/phpunit
```

Cela exécute tous les tests définis dans le dossier `tests` et affiche les résultats dans le terminal.

## Consulter les logs

Les logs sont stockés dans `./var/log/env_name.log`.

## Structure du projet

- `src/` : Contient le code source de l'application, notamment:
    - le contrôleur `HomeController.php` qui permet de router la page home de l'application et d'exécuter la recherche de films,
    - le service `ApiClient.php` qui effectue le pont vers l'API de The Movie Database et retourne les résultats de la recherche,
- `tests/` : Contient les tests unitaires et fonctionnels de l'application
- `templates/` : Contient les fichiers de template Twig pour le rendu des vues, notamment la vue `home` qui apparaît grâce au fichier `index.html.twig`,
- `var/` : Contient les fichiers de cache et de logs
- `public/` : Contient les fichiers accessibles publiquement (point d'entrée index.php) avec le `main.css` de l'application et le logo

## Utilisation de l'application

L'esthétique a été voulue minimaliste et responsive en utilisant le thème et la grille fournis par Bootstrap.

Un champ de recherche apparaît au centre de la page avec un bouton "Rechercher". Il n'est pas possible d'effectuer une recherche vide ni d'effectuer une recherche de plus de 100 caractères.

Il est possible de rechercher un film avec du texte, des caractères spéciaux ou des nombres.

Après validation, les résultats apparaîssent au-dessous sous forme de cartes positionnées en grille flex. Seul le premier résultat, plus pertinent, est mis en valeur par rapport aux autres.

Si aucun résultat n'est trouvé pour la recherche, un texte apparaîtra pour le signaler.

Une pagination apparaît également lorsqu'au moins une page de résultats est fournie. Bien qu'elle ne soit pas utile avec une seule page elle permet toutefois de constater qu'il n'y a pas plus de résultats.

Chaque page affiche au maximum 20 films.

La pagination s'adapte au format de l'écran, comme le reste de l'application. Si une requête renvoie beaucoup de résultats, elle s'adaptera pour éluder le numéro des pages éloignées.
