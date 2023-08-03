<?php

namespace Pebble\Helpers\Osm;

/**
 * Gps
 *
 * @author mathieu
 */
class Gps
{

    public $lat;
    public $lon;
    public $zoom;

    public function toTile()
    {
        $n = pow(2, $this->zoom);

        $tile       = new Tile();
        $tile->zoom = $this->zoom;
        $tile->x    = floor((($this->lon + 180) / 360) * $n);
        $tile->y    = floor((1 - log(tan(deg2rad($this->lat)) + 1 / cos(deg2rad($this->lat))) / pi()) / 2 * $n);

        return $tile;
    }
}
