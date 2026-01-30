<?php $title = 'Détails Commande #' . $commande['idCommande'] . ' - E-Commerce'; ?>
<?php include __DIR__ . '/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2>Commande #<?php echo $commande['idCommande']; ?></h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="index.php?page=profil" class="btn btn-outline-secondary">
            &larr; Retour au Profil
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5>Informations Commande</h5>
            </div>
            <div class="card-body">
                <p><strong>Numéro:</strong> #<?php echo $commande['idCommande']; ?></p>
                <p><strong>Date:</strong> <?php echo date('d/m/Y H:i', strtotime($commande['dateCommande'])); ?></p>
                <p>
                    <strong>Statut:</strong> 
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
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5>Informations Client</h5>
            </div>
            <div class="card-body">
                <p><strong>Nom:</strong> <?php echo htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($commande['email']); ?></p>
                <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($commande['telephone'] ?? 'Non fourni'); ?></p>
                <p><strong>Adresse:</strong> <?php echo htmlspecialchars($commande['adresse'] ?? 'Non fournie'); ?></p>
                <p><strong>Ville:</strong> <?php echo htmlspecialchars($commande['ville'] ?? 'Non fournie'); ?></p>
            </div>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h5>Articles Commandés</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Sous-Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes as $ligne): ?>
                        <tr>
                            <td>
                                <?php if ($ligne['urlImage']): ?>
                                    <img src="<?php echo htmlspecialchars($ligne['urlImage']); ?>" style="max-width: 40px; margin-right: 10px;">
                                <?php endif; ?>
                                <?php echo htmlspecialchars($ligne['nomProduit']); ?>
                            </td>
                            <td><?php echo $ligne['quantite']; ?></td>
                            <td><?php echo number_format($ligne['prixUnitaire'], 2); ?> Dh</td>
                            <td class="fw-bold">
                                <?php echo number_format($ligne['quantite'] * $ligne['prixUnitaire'], 2); ?> Dh
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row justify-content-end mb-4">
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5>Total</h5>
                <h3 class="text-success fw-bold"><?php echo number_format($commande['montantTotal'], 2); ?> Dh</h3>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
