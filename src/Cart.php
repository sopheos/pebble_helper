<?php

namespace Pebble\Helpers;

use InvalidArgumentException;

/**
 * @author Mathieu
 * @author Maxime
 */
class Cart
{
    public bool $isTtc;
    public array $items;

    /**
     * @param boolean $isTtc
     */
    public function __construct(bool $isTtc)
    {
        $this->isTtc = $isTtc;
        $this->items = [];
    }

    /**
     * @param boolean $isTtc
     * @return Cart
     */
    public static function create(bool $isTtc): Cart
    {
        return new static($isTtc);
    }

    // -------------------------------------------------------------------------

    /**
     * Ajout d'une entrée dans le panier
     *
     * @param integer $quantity
     * @param float $unit_price
     * @param float|null $taxe
     * @return Cart
     */
    public function add(int $quantity, float $unit_price, ?float $taxe = null): Cart
    {
        if ($taxe === null) {
            $taxe = 0;
        } elseif ($taxe < 0) {
            throw new InvalidArgumentException("taxe MUST BE supperior or equal to 0. {$taxe} is given");
        }

        $taxe = (string) $taxe;

        if (!isset($this->items[$taxe])) {
            $this->items[$taxe] = 0;
        }

        $this->items[$taxe] += $quantity * $unit_price;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Retourne le total en fonction du isTtc
     *
     * @return array
     */
    public function total(): array
    {
        if ($this->isTtc) {
            return $this->totalFromTtc();
        } else {
            return $this->totalFromHt();
        }
    }

    /**
     * Calcule du total HT
     *
     * @return array
     */
    private function totalFromHt(): array
    {
        $totals = [
            "ttc" => 0.0,
            "ht" => 0.0,
            "tva" => [],
            "total_tva" => 0.0
        ];

        foreach ($this->items as $taxe => $ht) {
            $tx = (float) $taxe;
            $ttc = 0;
            $tva = 0;

            // Calcul du ttc en fonction du taux de tva
            if ($tx > 0) {
                $tva = self::tvaFromHt($ht, $tx);
                $ttc = $ht + $tva;
            } else {
                $ttc = $ht;
            }

            // Incrementation des totaux
            $totals['ttc'] += $ttc;
            $totals['ht'] += $ht;
            $totals['total_tva'] += $tva;

            if ($tx > 0) {
                $totals['tva'][$taxe] = $tva;
            }
        }

        // Mise à jours du total tva si necessaire
        if (!$totals['total_tva']) {
            $totals['tva'] = [];
            $totals['ttc'] = 0;
        }

        return $totals;
    }

    /**
     * Calcule du total TTC
     *
     * @return array
     */
    private function totalFromTtc(): array
    {
        $totals = [
            "ttc" => 0.0,
            "ht" => 0.0,
            "tva" => [],
            "total_tva" => 0.0
        ];

        foreach ($this->items as $taxe => $ttc) {
            $tx = (float) $taxe;
            $ht = 0;
            $tva = 0;

            // Calcul du ht en fonction du taux de tva
            if ($tx > 0) {
                $tva = self::tvaFromTtc($ttc, $tx);
                $ht = $ttc - $tva;
            } else {
                $ht = $ttc;
            }

            // Incrementation des totaux
            $totals['ttc'] += $ttc;
            $totals['ht'] += $ht;
            $totals['total_tva'] += $tva;

            if ($tx > 0) {
                $totals['tva'][$taxe] = $tva;
            }
        }

        // Mise à jours du total tva si necessaire
        if (!$totals['total_tva']) {
            $totals['tva'] = [];
            $totals['ttc'] = 0;
        }

        return $totals;
    }

    // -------------------------------------------------------------------------

    /**
     * Calcule du prix TTC à partir du prix HT
     *
     * @param float $ht
     * @param float $tx
     * @return float
     */
    public static function tvaFromHt(float $ht, float $tx): float
    {
        return $ht * $tx;
    }

    /**
     * Calcule du total tva unitaire
     *
     * @param float $ttc
     * @param float $tx
     * @return float
     */
    public static function tvaFromTtc(float $ttc, float $tx): float
    {
        return $ttc * (1 - 1 / (1 + $tx));
    }
}
