<?php
function creerPDFFiche($lesFraisHorsForfait, $lesFraisForfaits, $mois) {
    //Permet d'afficher les mois correctement
    $listeMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    //Nom Prenom du visiteur
    $nom = $_SESSION['nom'];
    $prenom = $_SESSION['prenom'];
    require dirname(__DIR__) . DIRECTORY_SEPARATOR . "fpdf" . DIRECTORY_SEPARATOR . "fpdf.php";
    //Création du PDF
    $pdf = new FPDF();
    //Ajoute une nouvelle page
    $pdf->AddPage();
    //Ajoute une image
    $pdf->Image("images/logo.jpg", 77, 10, 50, 36);
    //Défini la font-police bold
    $pdf->SetFont('Arial', 'B', 24);
    //Titre de la page
    $pdf->Cell(0, 100, utf8_decode("Remboursement de frais engages"), 0, 0, 'C');
    //Défini la police par défaut
    $pdf->SetFont('Arial', '', 12);
    //Saut de ligne d'une certaine taille
    $pdf->Ln(60);
    //Fiche Visiteur
    $pdf->Cell(50, 10, "Visiteur", 0, 0, 'L');
    $pdf->Cell(50, 10, utf8_decode(($nom . ' ' . $prenom)));
    $pdf->Ln(10);
    $pdf->Cell(50, 10, "Mois", 0, 0, 'L');
    $pdf->Cell(50, 10, utf8_decode($listeMois[date('n', strtotime("01-" . substr($mois, 4, 2)) . '-' . substr($mois, 0, 4)) - 1] . ' ' . substr($mois, 0, 4)), 0, 0, 'C');
    $pdf->Ln(20);
    // Frais forfaitaires
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(45, 10, "Frais Forfaitaires", 1, 0, 'C');
    $pdf->Cell(45, 10, utf8_decode("Quantité"), 1, 0, 'C');
    $pdf->Cell(45, 10, "Montant Unitaire", 1, 0, 'C');
    $pdf->Cell(45, 10, "Total", 1, 0, 'C');
    $pdf->Ln(10);
    $totalFraisForfaits = 0;
    //Génération de chacun des élements du tableau des frais forfaits
    foreach ($lesFraisForfaits as $unFraisForfait) {
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(45, 10, utf8_decode($unFraisForfait['libelle']), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(45, 10, $unFraisForfait['quantite'], 1, 0, 'C');
        $pdf->Cell(45, 10, $montant, 1, 0, 'C');
        $pdf->Cell(45, 10, ($unFraisForfait['quantite'] * $unFraisForfait['montant']), 1, 0, 'C');
        $pdf->Ln(10);
        $totalFraisForfaits+=$unFraisForfait['quantite'] * $unFraisForfait['montant'];
    }
    $totalFraisHorsForfait = 0;
    $pdf->Ln(20);
    // Position en X avec 3 colonnes
    $pdf->SetFont('Arial', '', 12);
    //Autres frais
    $pdf->Cell(180, 10, "Autres Frais", 1, 0, 'C');
    $pdf->Ln(10);
    $pdf->Cell(60, 10, "Date", 1, 0, 'C');
    $pdf->Cell(60, 10, "Libelle", 1, 0, 'C');
    $pdf->Cell(60, 10, "Montant", 1, 0, 'C');
    $pdf->Ln(10);
    //Génération de chacun des éléments du tableau des autres frais (frais hors forfait)
    foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(60, 10, $unFraisHorsForfait['date'], 1, 0, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(60, 10, utf8_decode($unFraisHorsForfait['libelle']), 1, 0, 'C');
        $pdf->Cell(60, 10, $unFraisHorsForfait['montant'], 1, 0, 'C');
        $pdf->Ln(10);
        $totalFraisHorsForfait+=$unFraisHorsForfait['montant'];
    }
    $total = $totalFraisForfaits + $totalFraisHorsForfait;
    $pdf->Ln(10);
    $pdf->Cell(50, 10, 'Total', 1, 0, 'C');
    $pdf->Cell(50, 10, $total, 1, 0, 'C');
    //Signature
    $pdf->Ln(20);
    $pdf->Cell(50, 10, utf8_decode('Fait à Paris le ' . date('j') . ' ' . $listeMois[date('n') - 1] . ' ' . date('Y')));
    $pdf->Ln(10);
    $pdf->Cell(50, 10, utf8_decode('Vu l\'agent comptable'));
    $pdf->Ln(10);
    $pdf->Image('images/signature.jpg');
    ob_end_clean();
    $pdf->Output();
}