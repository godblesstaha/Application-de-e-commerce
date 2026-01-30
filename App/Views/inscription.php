<?php $title = 'Inscription - E-Commerce'; ?>
<?php include __DIR__ . '/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body p-5">
                <h3 class="card-title text-center mb-4">Créer un Compte</h3>

                <form method="POST" action="index.php?page=inscription&action=traiter">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom:</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom:</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="motdepasse" class="form-label">Mot de passe:</label>
                            <input type="password" class="form-control" id="motdepasse" name="motdepasse" required minlength="6">
                            <small class="text-muted">Minimum 6 caractères</small>
                        </div>
                        <div class="col-md-6">
                            <label for="confirmation" class="form-label">Confirmer le mot de passe:</label>
                            <input type="password" class="form-control" id="confirmation" name="confirmation" required minlength="6">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone (optionnel):</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone">
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse (optionnel):</label>
                        <input type="text" class="form-control" id="adresse" name="adresse">
                    </div>

                    <div class="mb-3">
                        <label for="ville" class="form-label">Ville (optionnel):</label>
                        <input type="text" class="form-control" id="ville" name="ville">
                    </div>

                    <button type="submit" class="btn btn-success w-100 mb-3">S'inscrire</button>
                </form>

                <hr>

                <p class="text-center">
                    Déjà inscrit? 
                    <a href="index.php?page=connexion">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
