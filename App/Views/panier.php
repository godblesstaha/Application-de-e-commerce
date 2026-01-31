<?php
$title = "Mon Panier"; // titre de la page
$logo = true;           // afficher le logo si tu veux
include 'header.php';
?>

<h2 class="my-4">Mon Panier</h2>

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
            <th>Sous-total</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($panier as $item): ?>
            <tr id="ligne-<?php echo $item['idProduit']; ?>">
                <td class="d-flex align-items-center">
                    <?php if (!empty($item['urlImage'])): ?>
                        <img src="<?php echo htmlspecialchars($item['urlImage']); ?>" width="60" class="me-2 rounded" style="object-fit: cover;" alt="<?php echo htmlspecialchars($item['nomProduit']); ?>">
                    <?php else: ?>
                        <span class="me-2">Pas d'image</span>
                    <?php endif; ?>
                    <?php echo htmlspecialchars($item['nomProduit']); ?>
                </td>
                <td><?php echo number_format($item['prix'], 2); ?> Dh</td>
                <td>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-secondary me-1" onclick="changerQuantite(<?php echo $item['idProduit']; ?>, -1)">-</button>
                        <input type="text" value="<?php echo $item['quantite']; ?>" id="quantite-<?php echo $item['idProduit']; ?>" class="form-control form-control-sm text-center" style="width:50px;" readonly>
                        <button class="btn btn-sm btn-outline-secondary ms-1" onclick="changerQuantite(<?php echo $item['idProduit']; ?>, 1)">+</button>
                    </div>
                </td>
                <td id="sous-total-<?php echo $item['idProduit']; ?>" class="fw-bold"><?php echo number_format($item['prix'] * $item['quantite'], 2); ?> Dh</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="supprimer(<?php echo $item['idProduit']; ?>)">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="table-primary">
            <td colspan="3" class="text-end"><strong>Total :</strong></td>
            <td colspan="2" id="total-panier"><strong><?php echo number_format($total, 2); ?> Dh</strong></td>
        </tr>
    </tfoot>
</table>

<div class="d-flex justify-content-between mb-4">
    <a href="index.php?page=produits" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Continuer les achats
    </a>

    <div>
        <button class="btn btn-warning me-2" onclick="viderPanier()">
            <i class="bi bi-trash-fill"></i> Vider le panier
        </button>

        <a href="index.php?page=commande" class="btn btn-success">
            <i class="bi bi-cart-check"></i> Passer la commande
        </a>
    </div>
</div>

<script>
function supprimer(id) {
    fetch(`index.php?page=panier&action=remove&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('ligne-' + id).remove();
                recalculerTotal();
            }
        });
}

function viderPanier() {
    if(confirm("Voulez-vous vraiment vider le panier ?")) {
        fetch(`index.php?page=panier&action=vider`)
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
            });
    }
}

function changerQuantite(idProduit, delta) {
    let input = document.getElementById('quantite-' + idProduit);
    let nouvelleQuantite = parseInt(input.value) + delta;
    if(nouvelleQuantite < 1) return;

    fetch(`index.php?page=panier&action=updateQuantite&id=${idProduit}&quantite=${nouvelleQuantite}`)
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                input.value = nouvelleQuantite;
                let prixUnitaire = parseFloat(data.prix);
                document.getElementById('sous-total-' + idProduit).innerText = (prixUnitaire * nouvelleQuantite).toFixed(2) + ' Dh';
                document.getElementById('total-panier').innerHTML = '<strong>' + parseFloat(data.total).toFixed(2) + ' Dh</strong>';
            }
        });
}

function recalculerTotal() {
    let total = 0;
    document.querySelectorAll('tbody tr').forEach(tr => {
        let st = tr.querySelector('td[id^="sous-total-"]').innerText.replace(' Dh','');
        total += parseFloat(st);
    });
    document.getElementById('total-panier').innerHTML = '<strong>' + total.toFixed(2) + ' Dh</strong>';
}
</script>
<?php endif; ?>
<?php
include 'footer.php';
?>
