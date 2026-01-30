<?php $title = 'Catégories - E-Commerce'; ?>
<?php include __DIR__ . '/header.php'; ?>

<h2 class="mb-4">Catégories</h2>

<div class="row">
    <?php foreach ($categories as $categorie): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 product-card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($categorie['nomCategorie']); ?></h5>
                    <p class="card-text">
                        <?php echo htmlspecialchars(substr($categorie['description'] ?? 'Pas de description', 0, 100)); ?>...
                    </p>
                </div>
                <div class="card-footer bg-white">
                    <a href="index.php?page=produits&categorie=<?php echo $categorie['idCategorie']; ?>" class="btn btn-primary w-100">
                        Voir Produits
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
