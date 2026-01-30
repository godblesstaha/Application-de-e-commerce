<?php $title = 'Passer la Commande - E-Commerce'; ?>
<?php include __DIR__ . '/header.php'; ?>

<div class="row">
    <div class="col-md-8">
        <h2>Passer la Commande</h2>

        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5>Adresse de Livraison</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=commande&action=placer">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="useDifferent" name="useDifferent">
                        <label class="form-check-label" for="useDifferent">
                            Livrer à une adresse différente
                        </label>
                    </div>

                    <div id="defaultAddress">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Prénom:</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nom:</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($utilisateur['nom']); ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($utilisateur['email']); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Téléphone:</label>
                            <input type="tel" class="form-control" value="<?php echo htmlspecialchars($utilisateur['telephone'] ?? 'Non fourni'); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Adresse:</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($utilisateur['adresse'] ?? 'Non fournie'); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ville:</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($utilisateur['ville'] ?? 'Non fournie'); ?>" readonly>
                        </div>
                    </div>

                    <div id="alternateAddress" style="display:none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="shipping_prenom" class="form-label">Prénom (livraison):</label>
                                <input type="text" name="shipping_prenom" id="shipping_prenom" class="form-control" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="shipping_nom" class="form-label">Nom (livraison):</label>
                                <input type="text" name="shipping_nom" id="shipping_nom" class="form-control" disabled>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_email" class="form-label">Email (livraison):</label>
                            <input type="email" name="shipping_email" id="shipping_email" class="form-control" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_telephone" class="form-label">Téléphone (livraison):</label>
                            <input type="tel" name="shipping_telephone" id="shipping_telephone" class="form-control" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_adresse" class="form-label">Adresse (livraison):</label>
                            <input type="text" name="shipping_adresse" id="shipping_adresse" class="form-control" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_ville" class="form-label">Ville (livraison):</label>
                            <input type="text" name="shipping_ville" id="shipping_ville" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="bi bi-info-circle"></i>
                            Vous pouvez choisir d'utiliser une adresse différente pour la livraison.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-credit-card"></i> Confirmer et Télécharger le PDF
                    </button>
                </form>
            </div>
        </div>

        <script>
            document.getElementById('useDifferent').addEventListener('change', function (e) {
                var alt = document.getElementById('alternateAddress');
                var inputs = alt.querySelectorAll('input');
                if (this.checked) {
                    alt.style.display = 'block';
                    inputs.forEach(function(i){ i.disabled = false; });
                } else {
                    alt.style.display = 'none';
                    inputs.forEach(function(i){ i.disabled = true; i.value = ''; });
                }
            });
        </script>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5>Résumé de la Commande</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Qté</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($panier as $idProduit => $item): ?>
                                <tr>
                                    <td colspan="3" class="border-bottom">
                                        <small>Produit #<?php echo $idProduit; ?></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td><?php echo number_format($item['prix'] * $item['quantite'], 2); ?> Dh</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <h4 class="text-success"><strong><?php echo number_format($total, 2); ?> Dh</strong></h4>
                </div>

                <a href="index.php?page=panier" class="btn btn-outline-secondary w-100">
                    Modifier le Panier
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
