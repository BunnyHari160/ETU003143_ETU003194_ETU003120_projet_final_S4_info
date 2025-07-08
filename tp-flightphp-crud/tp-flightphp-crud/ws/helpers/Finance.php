<?php

class Finance {
    public static function calculerEcheancier($montant, $taux, $mois, $assurance = 0.0) {
        if ($mois <= 0) return [];

        $tauxMensuel = $taux / 12;
        $assuranceMensuelle = ($assurance > 0) ? ($montant * $assurance / 100) / $mois : 0;
        $annuite = ($montant * $tauxMensuel) / (1 - pow(1 + $tauxMensuel, -$mois));
        $echeancier = [];
        $capitalRestant = $montant;

        for ($i = 1; $i <= $mois; $i++) {
            $interet = $capitalRestant * $tauxMensuel;
            $capital = $annuite - $interet;

            // Dernier mois : ajuste pour retomber sur 0
            if ($i === $mois) {
                $capital = $capitalRestant;
                $annuite = $capital + $interet;
            }

            $total = $annuite + $assuranceMensuelle;

            $echeancier[] = [
                'mois' => $i,
                'capital' => round($capital, 2),
                'interet' => round($interet, 2),
                'assurance' => round($assuranceMensuelle, 2),
                'total' => round($total, 2),
                'capital_restant' => round($capitalRestant - $capital, 2)
            ];

            $capitalRestant -= $capital;
        }

        return $echeancier;
    }
}
