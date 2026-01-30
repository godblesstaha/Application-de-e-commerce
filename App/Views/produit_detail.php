<?php $title = htmlspecialchars($produit['nomProduit']) . ' - E-Commerce'; ?>
<?php include __DIR__ . '/header.php'; ?>

<div class="row">
    <div class="col-md-6">
        <?php if (!empty($produit['urlImage'])): ?>
            <img src="<?php echo htmlspecialchars($produit['urlImage']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($produit['nomProduit']); ?>">
        <?php else: ?>
            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <h1><?php echo htmlspecialchars($produit['nomProduit']); ?></h1>
        
        <p class="text-muted">
            <strong>Catégorie:</strong> 
            <a href="index.php?page=produits&categorie=<?php echo $produit['idCategorie']; ?>">
                <?php echo htmlspecialchars($produit['nomCategorie'] ?? 'Non catégorisé'); ?>
            </a>
        </p>

        <p class="text-muted">
            <strong>Marque:</strong> <?php echo htmlspecialchars($produit['marque'] ?? 'Sans marque'); ?>
        </p>

        <p>
            <strong>Description:</strong><br>
            <?php echo nl2br(htmlspecialchars($produit['description'] ?? 'Pas de description')); ?>
        </p>

        <div class="alert alert-info py-3">
            <?php if (isset($produit['promotion']) && $produit['promotion']): ?>
                <?php 
                $promotion = new Promotion();
                $prixFinal = $promotion->calculateFinalPrice($produit['prix'], $produit['promotion']);
                ?>
                <h3>Prix</h3>
                <p>
                    <span class="text-muted"><del><?php echo number_format($produit['prix'], 2); ?> Dh</del></span>
                </p>
                <h4 class="text-danger fw-bold"><?php echo number_format($prixFinal, 2); ?> Dh</h4>
                
                <?php if ($produit['promotion']['pourcentageReduction'] > 0): ?>
                    <div class="badge bg-danger">
                        Réduction de <?php echo $produit['promotion']['pourcentageReduction']; ?>%
                    </div>
                <?php else: ?>
                    <div class="badge bg-danger">
                        Réduction de <?php echo number_format($produit['promotion']['montantReduction'], 2); ?> Dh
                    </div>
                <?php endif; ?>
                <p class="text-muted small mt-2">
                    Promotion valable jusqu'au: <?php echo date('d/m/Y', strtotime($produit['promotion']['dateLimite'])); ?>
                </p>
            <?php else: ?>
                <h3>Prix: <span class="text-success fw-bold"><?php echo number_format($produit['prix'], 2); ?> Dh</span></h3>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <?php if ($produit['quantiteStock'] > 0): ?>
                <span class="badge bg-success">En Stock (<?php echo $produit['quantiteStock']; ?> unités)</span>
            <?php else: ?>
                <span class="badge bg-danger">Rupture de Stock</span>
            <?php endif; ?>
        </div>
        <?php if ($produit['quantiteStock'] > 0): ?>
            <div class="mb-3">
                <label for="quantite" class="form-label">Quantité:</label>
                <input type="number" id="quantite" class="form-control" value="1" min="1" max="<?php echo $produit['quantiteStock']; ?>">
            </div>

            <button class="btn btn-success btn-lg w-100" onclick="ajouterPanier(<?php echo $produit['idProduit']; ?>)">
                <i class="bi bi-cart-plus"></i> Ajouter au Panier
            </button>
        <?php else: ?>
            <button class="btn btn-danger btn-lg w-100" disabled>
                Produit non disponible
            </button>
        <?php endif; ?>
        <p class="mt-4">
            <a href="index.php?page=produits&categorie=<?php echo $produit['idCategorie']; ?>" class="btn btn-outline-secondary">
                &larr; Retour à la catégorie
            </a>
        </p>
    </div>
</div>
<script>
function ajouterPanier(idProduit) {
    const quantite = document.getElementById('quantite').value || 1;
    fetch(`index.php?page=panier&action=add&id=${idProduit}&quantite=${quantite}`)
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