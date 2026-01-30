<?php $title = 'Produits - E-Commerce'; ?>
<?php include __DIR__ . '/header.php'; ?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5>Catégories</h5>
            </div>
            <div class="card-body">
                <a href="index.php?page=produits" class="btn btn-light btn-sm w-100 mb-2">Tous les Produits</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="index.php?page=produits&categorie=<?php echo $cat['idCategorie']; ?>" 
                       class="btn btn-outline-secondary btn-sm w-100 mb-2">
                        <?php echo htmlspecialchars($cat['nomCategorie']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <?php if ($categorie): ?>
            <h3 class="mb-4"><?php echo htmlspecialchars($categorie['nomCategorie']); ?></h3>
        <?php else: ?>
            <h3 class="mb-4">Tous les Produits</h3>
        <?php endif; ?>

        <?php if (empty($produits)): ?>
            <div class="alert alert-info">Aucun produit trouvé dans cette catégorie.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($produits as $produit): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card product-card h-100">
                            <div class="position-relative">
                                <?php if ($produit['urlImage']): ?>
                                    <img src="<?php echo htmlspecialchars($produit['urlImage']); ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (isset($produit['promotion']) && $produit['promotion']): ?>
                                    <div class="promo-badge">
                                        <?php if ($produit['promotion']['pourcentageReduction'] > 0): ?>
                                            -<?php echo $produit['promotion']['pourcentageReduction']; ?>%
                                        <?php else: ?>
                                            -<?php echo number_format($produit['promotion']['montantReduction'], 2); ?> Dh
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars(substr($produit['nomProduit'], 0, 40)); ?></h5>
                                <p class="card-text text-muted small">
                                    <?php echo htmlspecialchars(substr($produit['description'] ?? '', 0, 60)); ?>...
                                </p>
                                <p class="card-text">
                                    <strong><?php echo htmlspecialchars($produit['marque'] ?? 'Sans marque'); ?></strong>
                                </p>

                                <?php if (isset($produit['promotion']) && $produit['promotion']): ?>
                                    <?php 
                                    $promotion = new Promotion();
                                    $prixFinal = $promotion->calculateFinalPrice($produit['prix'], $produit['promotion']);
                                    ?>
                                    <p class="card-text">
                                        <span class="text-muted"><del><?php echo number_format($produit['prix'], 2); ?> Dh</del></span><br>
                                        <span class="text-danger fw-bold h5"><?php echo number_format($prixFinal, 2); ?> Dh</span>
                                    </p>
                                <?php else: ?>
                                    <p class="card-text">
                                        <span class="fw-bold h5"><?php echo number_format($produit['prix'], 2); ?> Dh</span>
                                    </p>
                                <?php endif; ?>

                                <div class="d-grid gap-2">
                                    <a href="index.php?page=produit&id=<?php echo $produit['idProduit']; ?>" class="btn btn-info btn-sm">
                                        Voir Détails
                                    </a>
                                    <button type="button" class="btn btn-success btn-sm" onclick="ajouterPanier(<?php echo $produit['idProduit']; ?>)">
                                        <i class="bi bi-cart-plus"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function ajouterPanier(idProduit) {
    fetch(`index.php?page=panier&action=add&id=${idProduit}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        });
}
</script>

<?php include __DIR__ . '/footer.php'; ?>
