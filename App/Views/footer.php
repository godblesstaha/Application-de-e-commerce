    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>À Propos</h5>
                    <p>Ce site a été créé par Taha EL OMARI EL ALAOUI pour des fins éducatifs</p>
                </div>
                <div class="col-md-4">
                    <h5>Liens Utiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php?page=accueil" class="text-white-50 text-decoration-none">Accueil</a></li>
                        <li><a href="index.php?page=produits" class="text-white-50 text-decoration-none">Produits</a></li>
                        <li><a href="index.php?page=categories" class="text-white-50 text-decoration-none">Catégories</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p class="text-white-50">
                        Email: elomarielalaouitaha@gmail.com<br>
                        Téléphone: +212 6 71868163
                    </p>
                </div>
            </div>
            <hr class="bg-white-50">
            <div class="text-center text-white-50">
                <p>&copy; 2026 E-Commerce. Tous droits non réservés.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function ajouterPanier(idProduit, quantite = 1) {
        fetch(`index.php?page=panier&action=add&id=${idProduit}&quantite=${quantite}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('cart-count');
                    if (badge) {
                        badge.textContent = (typeof data.count !== 'undefined') ? data.count : badge.textContent;
                    }
                    alert(data.message || 'Produit ajouté au panier');
                } else {
                    alert(data.message || 'Erreur lors de l\'ajout au panier');
                }
            }).catch(() => alert('Erreur réseau lors de l\'ajout au panier'));
    }
    </script>
</body>
</html>
