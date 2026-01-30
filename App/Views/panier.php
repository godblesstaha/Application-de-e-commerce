<?php 
$title = 'Mon Panier - E-Commerce';
include __DIR__ . '/header.php'; 
?>

<h2>Mon Panier</h2>

<?php if (empty($panier)): ?>
    <div class="alert alert-info">
        Votre panier est vide.
        <a href="index.php?page=produits" class="alert-link">Continuer vos achats</a>
    </div>
<?php else: ?>

<table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>Produit</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Sous-Total</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($panier as $item): ?>
            <tr>
                <td>
                    <?php if (!empty($item['urlImage'])): ?>
                        <img 
                            src="<?php echo htmlspecialchars($item['urlImage']); ?>" 
                            width="60"
                            style="margin-right:10px; object-fit: cover;"
                            alt="<?php echo htmlspecialchars($item['nomProduit']); ?>"
                        >
                    <?php endif; ?>
                    <?php echo htmlspecialchars($item['nomProduit']); ?>
                </td>

                <td><?php echo number_format($item['prix'], 2); ?> Dh</td>

                <td><?php echo $item['quantite']; ?></td>

                <td class="fw-bold">
                    <?php echo number_format($item['prix'] * $item['quantite'], 2); ?> Dh
                </td>

                <td>
                    <button 
                        class="btn btn-danger btn-sm"
                        onclick="supprimer(<?php echo $item['idProduit']; ?>)">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="table-primary">
            <td colspan="3" class="text-end"><strong>Total :</strong></td>
            <td colspan="2"><strong><?php echo number_format($total, 2); ?> Dh</strong></td>
        </tr>
    </tfoot>
</table>

<div class="d-flex justify-content-between mb-4">
    <a href="index.php?page=produits" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Continuer les achats
    </a>
    
    <?php if (isset($_SESSION['utilisateur_id'])): ?>
        <a href="index.php?page=commande" class="btn btn-success">
            <i class="bi bi-cart-check"></i> Passer la commande
        </a>
    <?php else: ?>
        <a href="index.php?page=connexion" class="btn btn-warning">
            <i class="bi bi-person"></i> Connectez-vous pour commander
        </a>
    <?php endif; ?>
</div>

<?php endif; ?>

<script>
function supprimer(id) {
    fetch(`index.php?page=panier&action=remove&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
}
</script>

<?php include __DIR__ . '/footer.php'; ?>