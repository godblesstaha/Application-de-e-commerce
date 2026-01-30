<?php $title = 'Mon Profil - E-Commerce'; ?>
<?php include __DIR__ . '/header.php'; ?>

<div class="row">
    <div class="col-md-8">
        <h2>Mon Profil</h2>

        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5>Informations Personnelles</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=profil&action=update">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom:</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom:</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($utilisateur['nom']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($utilisateur['email']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone:</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo htmlspecialchars($utilisateur['telephone'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse:</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo htmlspecialchars($utilisateur['adresse'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="ville" class="form-label">Ville:</label>
                        <input type="text" class="form-control" id="ville" name="ville" value="<?php echo htmlspecialchars($utilisateur['ville'] ?? ''); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Mettre à Jour</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Bienvenue!</h5>
                <p class="card-text">
                    Bonjour <strong><?php echo htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom']); ?></strong>
                </p>
                <p class="text-muted small">
                    Membre depuis: <?php echo date('d/m/Y', strtotime($utilisateur['dateInscription'])); ?>
                </p>
                <hr>
                <a href="index.php?page=connexion&action=logout" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </div>
        </div>
    </div>
</div>
<div class="mt-5">
    <h3>Historique des Commandes</h3>
    
    <?php if (empty($commandes)): ?>
        <div class="alert alert-info">
            Vous n'avez pas encore passé de commande.
            <a href="index.php?page=produits">Commencer vos achats</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>N° Commande</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td>#<?php echo $commande['idCommande']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($commande['dateCommande'])); ?></td>
                            <td><?php echo number_format($commande['montantTotal'], 2); ?> Dh</td>
                            <td>
                                <span class="badge bg-<?php 
                                    $status = $commande['statut'] ?? '';
                                    $bg = 'secondary';
                                    if ($status === 'en_attente') {
                                        $bg = 'warning';
                                    } elseif ($status === 'confirmée') {
                                        $bg = 'info';
                                    } elseif ($status === 'expédiée') {
                                        $bg = 'primary';
                                    } elseif ($status === 'livrée') {
                                        $bg = 'success';
                                    }
                                    echo $bg;
                                ?>">
                                    <?php echo htmlspecialchars($commande['statut']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="index.php?page=profil&action=commande&id=<?php echo $commande['idCommande']; ?>" class="btn btn-sm btn-info">
                                    Détails
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
