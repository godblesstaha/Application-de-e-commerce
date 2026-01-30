<?php

class PDFGenerator {
    private $pdf;
    private $commande;
    private $utilisateur;
    private $lignes;
    private $logo;
    private $shipping;
    
    public function __construct($commande, $utilisateur, $lignes, $logo, $shipping = null) {
        $this->commande = $commande;
        $this->utilisateur = $utilisateur;
        $this->lignes = $lignes;
        $this->logo = $logo;
        $this->shipping = $shipping;
    }
    
    public function generate() {
        if (!class_exists('FPDF')) {
            $paths = [
                __DIR__ . '/../public/fpdf/fpdf.php',
                __DIR__ . '/../public/fpdf186/fpdf.php'
            ];
            $found = false;
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    require_once $path;
                    $found = true;
                    break;
                }
            }
            if (!$found || !class_exists('FPDF')) {
                return null;
            }
        }
        
        $this->pdf = new FPDF();
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', 'B', 16);
        // fixed but bad usage 
        if ($this->logo) {
            $logoPath = $this->logo['urlImage'];

            if (preg_match('/^https?:\/\//', $logoPath)) {
                // nothing
            }
            // If absolute or exists as given path
            elseif (file_exists($logoPath)) {
                // nothing
            }
            elseif (defined('BASE_PATH') && file_exists(BASE_PATH . '/' . $logoPath)) {
                $logoPath = BASE_PATH . '/' . $logoPath;
            } elseif (defined('BASE_PATH') && file_exists(BASE_PATH . '/App/' . $logoPath)) {
                $logoPath = BASE_PATH . '/App/' . $logoPath;
            } elseif (defined('BASE_PATH') && file_exists(BASE_PATH . '/App/public/' . $logoPath)) {
                $logoPath = BASE_PATH . '/App/public/' . $logoPath;
            } elseif (defined('BASE_PATH') && file_exists(BASE_PATH . '/App/public/uploads/' . basename($logoPath))) {
                $logoPath = BASE_PATH . '/App/public/uploads/' . basename($logoPath);
            } elseif (file_exists(__DIR__ . '/../public/uploads/' . basename($logoPath))) {
                $logoPath = __DIR__ . '/../public/uploads/' . basename($logoPath);
            }

            $this->pdf->Image($logoPath, 10, 10, 30);
        }
        
        $this->pdf->Cell(0, 10, 'BON DE COMMANDE', 0, 1, 'C');
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Ln(5);
        
        $this->pdf->SetFont('Arial', 'B', 11);
        $this->pdf->Cell(0, 7, 'Numero de Commande: ' . $this->commande['idCommande'], 0, 1);
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Cell(0, 7, 'Date: ' . date('d/m/Y H:i', strtotime($this->commande['dateCommande'])), 0, 1);
        $this->pdf->Ln(5);
        
        $client = $this->shipping ?? $this->utilisateur;
        $this->pdf->SetFont('Arial', 'B', 11);
        $this->pdf->Cell(0, 7, 'INFORMATIONS CLIENT', 0, 1);
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Cell(0, 6, 'Nom: ' . ($client['prenom'] ?? ($this->utilisateur['prenom'] ?? '')) . ' ' . ($client['nom'] ?? ($this->utilisateur['nom'] ?? '')), 0, 1);
        $this->pdf->Cell(0, 6, 'Email: ' . ($client['email'] ?? ($this->utilisateur['email'] ?? '')), 0, 1);
        $this->pdf->Cell(0, 6, 'Telephone: ' . (!empty($client['telephone']) ? $client['telephone'] : (!empty($this->utilisateur['telephone']) ? $this->utilisateur['telephone'] : 'Non fourni')), 0, 1);
        $this->pdf->Cell(0, 6, 'Adresse: ' . (!empty($client['adresse']) ? $client['adresse'] : (!empty($this->utilisateur['adresse']) ? $this->utilisateur['adresse'] : 'Non fournie')), 0, 1);
        $this->pdf->Cell(0, 6, 'Ville: ' . (!empty($client['ville']) ? $client['ville'] : (!empty($this->utilisateur['ville']) ? $this->utilisateur['ville'] : 'Non fournie')), 0, 1);
        $this->pdf->Ln(5);
        
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetFillColor(200, 200, 200);
        $this->pdf->Cell(70, 7, 'Produit', 1, 0, 'L', true);
        $this->pdf->Cell(30, 7, 'Quantite', 1, 0, 'C', true);
        $this->pdf->Cell(30, 7, 'Prix Unit.', 1, 0, 'R', true);
        $this->pdf->Cell(30, 7, 'Total', 1, 1, 'R', true);
        
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetFillColor(255, 255, 255);
        
        foreach ($this->lignes as $ligne) {
            $total = $ligne['quantite'] * $ligne['prixUnitaire'];
            $this->pdf->Cell(70, 6, substr($ligne['nomProduit'], 0, 30), 1, 0, 'L');
            $this->pdf->Cell(30, 6, $ligne['quantite'], 1, 0, 'C');
            $this->pdf->Cell(30, 6, number_format($ligne['prixUnitaire'], 2) . ' Dh', 1, 0, 'R');
            $this->pdf->Cell(30, 6, number_format($total, 2) . ' Dh', 1, 1, 'R');
        }
        $this->pdf->SetFont('Arial', 'B', 11);
        $this->pdf->Cell(130, 7, 'TOTAL', 1, 0, 'R');
        $this->pdf->Cell(30, 7, number_format($this->commande['montantTotal'], 2) . ' Dh', 1, 1, 'R');
        
        $this->pdf->Ln(10);
        $this->pdf->SetFont('Arial', 'I', 10);
        $this->pdf->MultiCell(0, 5, 'Merci pour votre commande');
        
        return $this->pdf;
    }
    
    public function download($filename = 'commande.pdf') {
        $pdf = $this->generate();
        if (!$pdf || !class_exists('FPDF')) {
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            $_SESSION['erreur'] = 'Bibliotheque FPDF introuvable. PDF non genere.';
            return false;
        }
        $this->pdf = $pdf;
        $this->pdf->Output('D', $filename);
        return true;
    }
    
    public function display() {
        $this->generate();
        $this->pdf->Output('I');
    }
}
?>
