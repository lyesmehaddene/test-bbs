# Test Lyes Mehaddene pour BBS

## Démarrer le projet

Le projet est fourni avec un fichier `Dockerfile` et un fichier `docker-compose.yml` :

### Build

```shell
docker compose build
```

### Démarrer le conteneur
```shell
$ docker up -d
```

### Exécuter bash sur le conteneur

```shell
$ docker compose exec app bash
```

## Déroulement du test

## Les paliers

### Niveau 0

Ce palier est obligatoire. On part à la découverte de [Lighthouse PHP](https://lighthouse-php.com/) et de [Laravel Nova](https://nova.laravel.com).

#### 0.1 - Taks list GraphQL API

Le schéma graphql `graphql/schema.graphql` fourni contient les mutations suivantes :

- [ ] createTask
- [ ] updateTask
- [ ] deleteTask
- [ ] markTaskAsDone
- [ ] markTaskAsUndone

Vous devez implémenter le `revolver` de chaque mutation. Les tests fournis doivent passer. La query `login` ou la mutation `register` montre un exemple de `resolver`.

Cette tâche demande de savoir implémenter un `resolver` ainsi que de valider les données en entrées des mutations.

> Notes : utiliser la commande `php artisan test` pour lancer les tests.

#### 0.2 - Task list Nova

- [ ] Créer une [resource](https://nova.laravel.com/docs/4.0/resources/) `Task`
- [ ] Permettre de voir les tâches dans la vue détail d'un `User`
- [ ] Créer une [action](https://nova.laravel.com/docs/4.0/actions/defining-actions.html) pour marquer une tâche comme terminée
- [ ] Créer une [action](https://nova.laravel.com/docs/4.0/actions/defining-actions.html) pour marquer une tâche comme non terminée

### Niveau 1

#### 1.1 - Refresh Me

Cette application utilise un guard d'authentification [JWT](https://laravel-jwt-auth.readthedocs.io/en/latest/). Une query `login` existe. Cependant, un token JWT a une durée de vie limitée :

- [ ] Ajouter une mutation `refreshToken: String!` qui retourne un nouveau token
- [ ] Implémenter des tests

#### 1.2 - Dans nos régions - Partie 1

- [ ] Créer une resource et un modèle `Department`
- [ ] Créer une resource et un modèle `City`
- [ ] Créer une action `standalone` pour importer les départements depuis de [ce fichier](https://www.data.gouv.fr/fr/datasets/r/de7d0863-13e8-4010-9c75-487269f5d7ac)
- [ ] Créer une action `standalone` pour importer les communes depuis de [ce fichier](https://www.data.gouv.fr/fr/datasets/r/dbe8a621-a9c4-4bc3-9cae-be1699c5ff25)

Il s'agit de faire des imports de fichiers CSV. Attention à l'utilisation de la mémoire.

> Notes : une action standalone est une action Nova attachée à une resource qui est exécutable sans avoir à sélectionner des resources. 

#### 1.3 - To infinity and beyond (https://api.le-systeme-solaire.net/)

Explorons notre système solaire :
- [ ] Créer une resource `Body`
- [ ] Créer une action `standalone` pour importer, en upsert, les corps célestes fournis dans [cette API](https://api.le-systeme-solaire.net)
- [ ] Créer une query `knowncount` qui reproduit le comportement du endpoint [knowncount](https://api.le-systeme-solaire.net/swagger/#/)

### Niveau 2


#### 2.1 - Dans nos régions - Partie 2

- [ ] Créer une query `department(id: ID!): Department` pour avoir les informations d'un département
- [ ] Créer une query `departments: [Department!]!` pour renvoyer une liste paginée de département
- [ ] Créer une query `searchCity(search: String): [City!]!` pour renvoyer une liste de commune paginée dont le nom correspond au critère `search`si il est présent et non vide
