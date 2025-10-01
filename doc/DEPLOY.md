# Procédure de Déploiement

Décrivez ci-dessous votre procédure de déploiement en détaillant chacune des étapes. De la préparation du VPS à la méthodologie de déploiement continu.

Pour toute nouveaux déploiement d'application en production, voici les étapes à réaliser pour préparer le VPS pour la mise en ligne du projet: 

Identifiants aapanel utilisés pour ce projet: 
username: f1anqlqz
password: 0ed1272f

## 1 - Installation de aaPanel pour la gestion des application web: 

- Se placer à la racine du vps
- Récupérer l'application aaPanel depuis sont début officiel à l'aide de wget:
sudo wget -O install.sh http://www.aapanel.com/script/install-ubuntu_6.0_en.sh

-Puis effectuer l'installation depuis le script install.sh.

sudo bash install.sh

Une fois l'installation de l'interface terminée, utiliser le service d'installation automatique de modules (PHP, MySQL etc) proposé par l'application pour installer les éléments techniques en lien avec le projet.

## 2 - Création du dépôt de production 

Ce dépôt contiendra l'ensemble du code source de l'application qui servira de base pour le déploiement.

Dans le dossier /var, créer un dépot personnalisé sous le nom de 'depot_git'.

- sudo mkdir -p /var/depot_git

Se rendre de le dossier et initaliser un dépôt de type bare à l'aide de Git:

- cd /var/depot_git
- git init --bare

aaPanel créé une structure de fichier qui lui est propre. Se rendre dans le dossier prévu pour le stockage des projets web et créer le futur dossier du projet:

- cd /www/wwwroot/<nom_du_projet> (dans le cas présent "app")

## 3 - Configuration entre le dépôt local et distant

Cette étape permet de faire le lien entre dépôt local et celui précédement créé. Dans un premier temps, il faut ajouter le dépôt distant à notre projet: le nom indiqué entre "add" et les identifiants de connexions est le nom de cette liaison.

- git remote add prod root@172.17.4.19:/var/depot_git

## 4 - Envoi du code source vers le serveur

Le code source étant stocker dans /var/depot_git, il faut désormais le pousser vers le dossier du projet pour effectuer la mise en ligne. 

Pour faciliter cette étape, il faut procéder à la création d'un script bash pour réaliser cette action.

A la raçine du projet, créer un fichier où seront écrites les actions à effectuer:

- cd / 
- sudo touch deploy.sh

Editer le fichier:

- sudo nano deploy.sh 

Dans la console d'édition, écrire ces insctructions :

#!/bin/bash
GIT_DIR=/var/depot_git
WORK_TREE=/www/wwwroot/app
git --work-tree=$WORK_TREE --git-dir=$GIT_DIR checkout -f $1

Enregistrer et rendre le fichier executable:

- sudo chmod +x deploy.sh

## Mise en production d'une version 

L'ensemble des éléments pour effectuer le déploiement sont présents. Pour pousser une version spécifique de l'application, voici les étapes à réaliser:

Sur le projet local, créer un tag au nom de la version en cours:
- git tag 1.x.x

Pousser ce code en direction du depot présent dans le VPS:
- git push prod 1.x.x

Sur le VPS :

Lancer le script deploy.sh en indiquant le nom de la version voulue: 
- sudo ./deploy.sh 1.x.x

L'application est désormais présente sur le serveur.

## Configuration de l'application depuis l'interface de gestion aaPanel

Se rendre sur l'interface d'administration et s'identifier : https://172.17.4.19:40130/0061dbb1

- Se rendre dans la catégorie "website" puis sur le bouton "add site".
- Depuis la fenêtre modale, renseigner le nom de domaine, ajouter une description du projet. 
- Dans Website Path, selectionner le point d'entrer de l'application. Dans le cas présent, il s'agit de "/public".
- Se rendre dans la section "URL Rewite" de la configuration du site et indiquer "mvc".

## Création de la base de données à partir de Phpmyadmin intégré à aapanel

Se rendre dans la section "Database" et récupérer "rootpassword"puis accéder à Phpmyadmin via le bouton prévu à cet effet.

- Après s'être identifié, créer une nouvelle base de donnée "habit_tracker".
- Après la création, se rendre dans "importer" et importer "database.sql" présent dans le projet
- Une fois la base de donnée correctement créée, il est possible d'ajouter des données de tests en injectant dans la console SQL, le contenu du fichier "demo_data.sqm".




