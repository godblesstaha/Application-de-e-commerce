<?php $title = 'Connexion - E-Commerce'; ?>
<?php include __DIR__ . '/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body p-5">
                <h3 class="card-title text-center mb-4">Connexion</h3>

                <form method="POST" action="index.php?page=connexion&action=traiter">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="motdepasse" class="form-label">Mot de passe:</label>
                        <input type="password" class="form-control" id="motdepasse" name="motdepasse" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">Se Connecter</button>
                </form>

                <hr>

                <p class="text-center">
                    Pas encore inscrit? 
                    <a href="index.php?page=inscription">Créer un compte</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
