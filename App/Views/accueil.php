<?php $title = 'Accueil - E-Commerce'; ?>
<?php include __DIR__ . '/header.php'; ?>

<?php if (!empty($publicites)): ?>
    <div id="carouselPublicities" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($publicites as $index => $pub): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="<?= htmlspecialchars($pub['urlImage'] ?? 'https://via.placeholder.com/800x300'); ?>" 
                         class="banner-img" alt="Publicité">
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselPublicities" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselPublicities" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
<?php endif; ?>

<div class="mb-5">
    <h3 class="mb-3">Catégories</h3>
    <div class="categories-slider" style="direction: rtl;">
        <?php foreach ($categories as $categorie): ?>
            <a href="index.php?page=produits&categorie=<?= $categorie['idCategorie']; ?>" class="category-item">
                <?= htmlspecialchars($categorie['nomCategorie']); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php if (!empty($promotions)): ?>
    <div class="mb-5">
        <h3 class="mb-3">Promotions en Cours</h3>
        <div class="row">
            <?php foreach ($promotions as $promo): ?>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card product-card h-100">
                        <?php if (!empty($promo['urlImage'])): ?>
                            <img src="<?= htmlspecialchars($promo['urlImage']); ?>" 
                                 class="card-img-top" style="height:150px; object-fit:contain; padding:10px; background:#fff;">
                        <?php else: ?>
                            <div style="height:150px; background:#f8f9fa; display:flex; align-items:center; justify-content:center;">
                                No Image
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($promo['nomProduit']); ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($promo['nomCategorie']); ?></p>
                            
                            <?php if (!empty($promo['pourcentageReduction']) && $promo['pourcentageReduction'] > 0): ?>
                                <div class="promo-badge">
                                    -<?= $promo['pourcentageReduction']; ?>%
                                </div>
                                <p>
                                    <span class="text-muted"><del><?= number_format($promo['prix'], 2); ?> Dh</del></span>
                                    <span class="text-danger fw-bold">
                                        <?= number_format($promo['prix'] * (1 - $promo['pourcentageReduction']/100), 2); ?> Dh
                                    </span>
                                </p>
                            <?php elseif (!empty($promo['montantReduction']) && $promo['montantReduction'] > 0): ?>
                                <div class="promo-badge">
                                    -<?= number_format($promo['montantReduction'], 2); ?> Dh
                                </div>
                                <p>
                                    <span class="text-muted"><del><?= number_format($promo['prix'], 2); ?> Dh</del></span>
                                    <span class="text-danger fw-bold">
                                        <?= number_format(max($promo['prix'] - $promo['montantReduction'], 0), 2); ?> Dh
                                    </span>
                                </p>
                            <?php endif; ?>
                            
                            <a href="index.php?page=produit&id=<?= $promo['idProduit']; ?>" class="btn btn-primary btn-sm">
                                Voir Détails
                            </a>
                            <button type="button" class="btn btn-success btn-sm" onclick="ajouterPanier(<?= $promo['idProduit']; ?>)">
                                <i class="bi bi-cart-plus"></i> Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty($produits)): ?>
    <div class="mb-5">
        <h3 class="mb-3">Produits populaires</h3>
        <div class="row">
            <?php $count = 0; foreach ($produits as $produit): ?>
                <?php if ($count++ >= 6) break; ?>
                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <div class="card product-card h-100">
                        <?php if (!empty($produit['urlImage'])): ?>
    <img src="<?= htmlspecialchars($produit['urlImage']); ?>" 
         class="card-img-top" style="height:150px; object-fit:contain; padding:10px; background:#fff;">
<?php else: ?>
    <div style="height:150px; background:#f8f9fa; display:flex; align-items:center; justify-content:center;">
        <img src="public/uploads/default.png" alt="Image par défaut" style="max-height:100px;">
    </div>
<?php endif; ?>
                        <div class="card-body p-2">
                            <h6 class="card-title mb-1" style="font-size:0.9rem;"><?= htmlspecialchars($produit['nomProduit']); ?></h6>
                            <p class="mb-0 text-primary fw-bold"><?= number_format($produit['prix'], 2); ?> Dh</p>
                            <div class="mt-2">
                                <a href="index.php?page=produit&id=<?= $produit['idProduit']; ?>" class="btn btn-info btn-sm">Voir Détails</a>
                                <button type="button" class="btn btn-success btn-sm" onclick="ajouterPanier(<?= $produit['idProduit']; ?>)">
                                    <i class="bi bi-cart-plus"></i> Ajouter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
