# Nada_zirari_CustomerCarApi
# Nada_zirari_CustomerCarApi
Customer Care API

Description

Customer Care API est une application Laravel qui fournit une API RESTful pour la gestion des tickets de support client. Cette API permet aux utilisateurs de créer, consulter, mettre à jour et supprimer des tickets, ainsi que de gérer les réponses associées.

Fonctionnalités

Authentification : Système d'authentification JWT pour sécuriser l'API
Gestion des tickets :
Création de tickets avec titre, description, priorité et catégorie
Consultation des tickets avec pagination
Mise à jour des tickets (statut, assignation, etc.)
Suppression de tickets

Gestion des réponses : Possibilité d'ajouter des réponses aux tickets
Catégorisation : Organisation des tickets par catégories
Priorités : Définition de priorités pour les tickets (low, medium, high)
Assignation : Possibilité d'assigner des tickets à des agents spécifiques
Documentation API : Documentation complète avec Swagger/OpenAPI

Prérequis

PHP 8.1 ou supérieur
Composer
PostgreSQL
Laravel 10.x
Installation
Cloner le dépôt :https://github.com/Youcode-Classe-E-2024-2025/Nada_zirari_CustomerCarApi

Démarrer le serveur de développement :

php artisan serve

Structure de la base de données:

users : Stocke les informations des utilisateurs
tickets : Stocke les tickets de support avec leurs détails
categories : Stocke les catégories de tickets
responses : Stocke les réponses aux tickets

Endpoints API

Authentification

POST /api/register : Inscription d'un nouvel utilisateur
POST /api/login : Connexion d'un utilisateur
POST /api/logout : Déconnexion d'un utilisateur
GET /api/user : Récupération des informations de l'utilisateur connecté

Tickets

GET /api/tickets : Récupération de la liste des tickets (paginée)
POST /api/tickets : Création d'un nouveau ticket
GET /api/tickets/{id} : Récupération des détails d'un ticket
PUT /api/tickets/{id} : Mise à jour d'un ticket
DELETE /api/tickets/{id} : Suppression d'un ticket
Réponses
POST /api/responses : Ajout d'une réponse à un ticket
PUT /api/responses/{id} : Mise à jour d'une réponse
DELETE /api/responses/{id} : Suppression d'une réponse
Documentation API
La documentation complète de l'API est disponible via Swagger.
