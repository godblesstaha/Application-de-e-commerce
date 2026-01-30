# Structure du dossier `App/`

Voici l'arborescence du dossier `App/` et une courte description de chaque élément — format adapté pour un README.

App
|_ Config/
|   |_ Database.php         # Configuration et accès à la base de données (Singleton PDO)
|
|_ Controllers/
|   |_ AccueilController.php    # Logique de la page d'accueil
|   |_ AuthController.php       # Connexion / inscription
|   |_ CategoriesController.php # Listing / filtrage des catégories
|   |_ CommandeController.php   # Traitement des commandes
|   |_ PanierController.php     # Gestion du panier (AJAX inclus)
|   |_ ProduitsController.php   # Listing et détail des produits
|   |_ ProfilController.php     # Profil utilisateur, historique commandes
|
|_ Models/
|   |_ Categorie.php        # Accès table categories
|   |_ Commande.php         # Création / lecture commandes et lignes
|   |_ Image.php            # Gestion d'images (logo, uploads)
|   |_ Panier.php           # Classe utilitaire du panier (SESSION)
|   |_ PDFGenerator.php     # Production des PDFs de commande (FPDF)
|   |_ Produit.php          # Accès table produits
|   |_ Promotion.php        # Promotions liées aux produits
|   |_ Publicite.php        # Bannières / publicités
|   |_ Utilisateur.php      # Gestion des utilisateurs
|
|_ Views/
|   |_ 404.php
|   |_ accueil.php
|   |_ categories.php
|   |_ commande.php
|   |_ commande_detail.php
|   |_ connexion.php
|   |_ footer.php
|   |_ header.php
|   |_ inscription.php
|   |_ panier.php
|   |_ produits.php
|   |_ produit_detail.php
|   |_ profil.php
|
|_ public/
|   |_ fpdf186/            # Bibliothèque FPDF incluse
|   |_ uploads/
|       |_ logo.png        # Logo et fichiers uploadés
|
|_ index.php               # Point d'entrée / routeur
|_ database.sql            # Dump / schéma de la base (exemples)

