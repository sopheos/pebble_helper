<?php

namespace Pebble\Helpers;

/**
 * Point
 *
 * @author mathieu
 */
class Point
{
    const EARTH_RADIUS = 6378137;

    public $lat;
    public $lon;

    // -------------------------------------------------------------------------
    // Construct
    // -------------------------------------------------------------------------

    public function __construct(float $lat = 0, float $lon = 0)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }

    /**
     * Valeurs en degrés
     *
     * @param float $lat
     * @param float $lon
     * @return static
     */
    public static function fromDegrees(float $lat, float $lon)
    {
        return new static($lat, $lon);
    }

    /**
     * Valeurs en radians
     *
     * @param float $lat
     * @param float $lon
     * @return static
     */
    public static function fromRadians(float $lat, float $lon)
    {
        return new static(rad2deg($lat), rad2deg($lon));
    }

    /**
     * Unité utilisée par la suisse
     *
     * @param float $e
     * @param float $n
     * @return static
     */
    public static function fromMN95(float $e, float $n)
    {
        // Convertir les coordonnées de projection E (coordonnée est)
        // et N (coordonnée nord) en MN95
        // dans le système civil (Berne = 0 / 0)
        // et exprimer dans l'unité [1000 km]
        $y = ($e - 2600000) / 1000000;
        $x = ($n - 1200000) / 1000000;

        // Calculer la longitude et la latitude dans l'unité [10000"]
        $lon_i = 2.6779094;
        $lon_i += 4.728982 * $y;
        $lon_i += +0.791484 * $y * $x;
        $lon_i += 0.1306 * $y * $x * $x;
        $lon_i -= 0.0436 * $y * $y * $y;

        $lat_i = 16.9023892;
        $lat_i += 3.238272 * $x;
        $lat_i -= 0.270978 * $y * $y;
        $lat_i -= 0.002528 * $x * $x;
        $lat_i -= 0.0447 * $y * $y * $x;
        $lat_i -= 0.0140 * $x * $x * $x;

        // Convertir la longitude et la latitude dans l'unité [°]
        $lon = $lon_i * 100 / 36;
        $lat = $lat_i * 100 / 36;

        return new static($lat, $lon);
    }

    /**
     * Transform gps data from exif_read_data() to decimal degrees (dd)
     *
     * @param array $exif_gps
     * @return static
     */
    public static function fromExifGPS(array $exif_gps)
    {
        [$lat_degrees, $lat_minutes, $lat_seconds, $lon_degrees, $lon_minutes, $lon_seconds] =
            array_map(function ($value) {
                $value = explode('/', $value);
                return $value[0] / $value[1];
            }, array_merge($exif_gps['GPSLatitude'], $exif_gps['GPSLongitude']));

        $dms = [
            'lat_ref' => $exif_gps['GPSLatitudeRef'],
            'lat_degrees' => $lat_degrees,
            'lat_minutes' => $lat_minutes,
            'lat_seconds' => $lat_seconds,
            'lon_ref' => $exif_gps['GPSLongitudeRef'],
            'lon_degrees' => $lon_degrees,
            'lon_minutes' => $lon_minutes,
            'lon_seconds' => $lon_seconds
        ];

        return self::fromDMS($dms);
    }

    /**
     * Transform degrees, minutes, seconds (DMS) coordinates to decimal degrees (dd)
     * Transform dd in negative value if lat_ref S or lon_ref W
     *
     * @param array $dms with properties lat_ref, lat_degrees, lat_minutes, lat_seconds, lon_ref, lon_degrees, lon_minutes, lon_seconds.
     * @return static
     */
    public static function fromDMS(array $dms)
    {
        $dms_ok = isset(
            $dms['lat_ref'],
            $dms['lat_degrees'],
            $dms['lat_minutes'],
            $dms['lat_seconds'],
            $dms['lon_ref'],
            $dms['lon_degrees'],
            $dms['lon_minutes'],
            $dms['lon_seconds']
        );

        if (!$dms_ok) return;

        extract($dms);

        $lat = (float) $lat_degrees + ((($lat_minutes * 60) + ($lat_seconds)) / 3600);
        $lon = (float) $lon_degrees + ((($lon_minutes * 60) + ($lon_seconds)) / 3600);
        $lat = number_format($lat, 7);
        $lon = number_format($lon, 7);

        // If the latitude is South, make it negative.
        $lat = $lat_ref === 'S' ? $lat * -1 : $lat;
        // If the longitude is West, make it negative
        $lon = $lon_ref === 'W' ? $lon * -1 : $lon;

        return new static($lat, $lon);
    }

    // -------------------------------------------------------------------------
    // Getter
    // -------------------------------------------------------------------------

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLon(): float
    {
        return $this->lon;
    }

    /**
     * @return float
     */
    public function getRadLat(): float
    {
        return deg2rad($this->lat);
    }

    /**
     * @return float
     */
    public function getRadLon(): float
    {
        return deg2rad($this->lon);
    }

    /**
     * @return array
     */
    public function coordinates(): array
    {
        return [$this->lat, $this->lon];
    }

    /**
     * @return array
     */
    public function latlon(): array
    {
        return ["lat" => $this->lat, "lon" => $this->lon];
    }

    /**
     * @return array
     */
    public function latlng(): array
    {
        return ["lat" => $this->lat, "lng" => $this->lon];
    }

    /**
     * @return array
     */
    public function geojson(): array
    {
        return ["type" => "Point", "coordinates" => [$this->lon, $this->lat]];
    }

    /**
     * Distance between two points
     *
     * @param App\Entities\Point $this
     * @param App\Entities\Point $point
     * @return float
     */
    public function distanceTo(Point $point)
    {
        $dlo = ($point->getRadLon() - $this->getRadLon()) / 2;
        $dla = ($point->getRadLat() - $this->getRadLat()) / 2;
        $haversine = (sin($dla) * sin($dla)) + cos($this->getRadLat()) * cos($point->getRadLat()) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($haversine), sqrt(1 - $haversine));

        return $d * self::EARTH_RADIUS;
    }

    /**
     * @param float $distance
     * @return Point[]
     */
    public function bounds(float $distance)
    {
        $MINLAT = pi() * -1 / 2;
        $MAXLAT = pi() / 2;
        $MINLON = pi() * -1;
        $MAXLON = pi();

        $angle = $distance / self::EARTH_RADIUS;
        $rad_lat = $this->getRadLat();
        $rad_lon = $this->getRadLon();

        $min_lat = $rad_lat - $angle;
        $max_lat = $rad_lat + $angle;

        $min_lon = 0;
        $max_lon = 0;
        if ($min_lat > $MINLAT && $max_lat < $MAXLAT) {
            $delta_lon = asin(sin($angle) / cos($rad_lat));
            $min_lon = $rad_lon - $delta_lon;
            if ($min_lon < $MINLON) $min_lon += 2 * pi();
            $max_lon = $rad_lon + $delta_lon;
            if ($max_lon > $MAXLON) $max_lon -= 2 * pi();
        } else {
            $min_lat = max($min_lat, $MINLAT);
            $max_lat = max($max_lat, $MAXLAT);
            $min_lon = $MINLON;
            $max_lon = $MAXLON;
        }

        return [
            self::fromRadians($min_lat, $min_lon), self::fromRadians($max_lat, $max_lon)
        ];
    }

    // -------------------------------------------------------------------------
    // Setter
    // -------------------------------------------------------------------------

    /**
     * @param float $lat
     * @return static
     */
    public function setLat(float $lat)
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @param float $lon
     * @return static
     */
    public function setLon(float $lon)
    {
        $this->lon = $lon;
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * @param string $str
     * @return \App\Entities\Point[]
     */
    public static function toPoints(string $str)
    {
        $points = [];

        foreach (preg_split('/\s/', $str, -1, PREG_SPLIT_NO_EMPTY) as $row) {
            $cols = explode(',', trim($row));
            if (count($cols) === 2) {
                $points[] = new static((float) $row[0], (float) $row[1]);
            }
        }

        return $points;
    }

    /**
     * @param object $path coordonnées lat, lon
     * @return bool
     */
    public function insidePath($path)
    {
        $inside = false;

        if (!$path) {
            return $inside;
        }

        if (isset($path->outer) && $path->outer) {
            foreach ($path->outer as $poly) {
                if (self::insideVertices($this->lat, $this->lon, $poly)) {
                    $inside = true;
                    break;
                }
            }
        }

        if ($inside && isset($path->inner) && $path->inner) {
            foreach ($path->inner as $poly) {
                if (self::insideVertices($this->lat, $this->lon, $poly)) {
                    $inside = false;
                    break;
                }
            }
        }

        return $inside;
    }

    // -------------------------------------------------------------------------

    /**
     * @param array $coords coordonnées lat, lon)
     * @return bool
     */
    public function insidePoly(array $coords)
    {
        return self::insideVertices($this->lat, $this->lon, $coords);
    }

    // -------------------------------------------------------------------------

    /**
     * @param array $geojson coordonnées lon, lat
     * @return bool
     */
    public function insideGeoJson(array $geojson)
    {
        $type = $geojson['type'] ?? '';
        $coords = $geojson['coordinates'] ?? '';

        if (!$coords) return false;

        if ($type === 'Polygon') {
            return $this->insideGeoCoordinates($coords);
        } elseif ($type === 'MultiPolygon') {
            foreach ($coords as $c) {
                if ($this->insideGeoCoordinates($c)) {
                    return true;
                }
            }
        }


        return false;
    }

    /**
     * @param array $coords coordonnées lon, lat
     * @return bool
     */
    private function insideGeoCoordinates(array $coords)
    {
        if (!$coords) return false;
        $poly = array_shift($coords);
        $inside = self::insideVertices($this->lon, $this->lat, $poly);

        // Holes
        if ($inside && $coords) {
            foreach ($coords as $hole) {
                if (self::insideVertices($this->lon, $this->lat, $hole)) {
                    return false;
                }
            }
        }

        return $inside;
    }

    /**
     * @param float $x
     * @param float $y
     * @param array $vertices
     * @return bool
     */
    public static function insideVertices($x, $y, array $vertices)
    {
        $inside = false;

        $l = count($vertices);
        $i = 0;
        $j = $l - 1;

        for (; $i < $l; $j = $i++) {

            $xi = $vertices[$i][0];
            $yi = $vertices[$i][1];
            $xj = $vertices[$j][0];
            $yj = $vertices[$j][1];

            $intersect = (($yi > $y) !== ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);

            if ($intersect) {
                $inside = !$inside;
            }
        }

        return $inside;
    }


    // -------------------------------------------------------------------------
}
