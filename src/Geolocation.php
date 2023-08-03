<?php

namespace Pebble\Helpers;

/**
 * Geolocation
 *
 * Resources :
 * http://en.wikipedia.org/wiki/Haversine_formula
 * http://www.movable-type.co.uk/scripts/latlong.html
 *
 * @author Mathieu
 */
class Geolocation
{
    const DELTA_ZOOM_GOOGLE = 0;
    const DELTA_ZOOM_OSM = 8;
    const EARTH_RADIUS = 6378137;

    /**
     * latitude in radians
     * @var float
     */
    private $radLat;

    /**
     * longitude in radians
     * @var float
     */
    private $radLng;

    /**
     * latitude in degrees
     * @var float
     */
    private $degLat;

    /**
     * longitude in degrees
     * @var float
     */
    private $degLng;

    /**
     * angular radius
     * @var float
     */
    private $angular;

    /* Bounds */
    protected $minLat;  // -PI/2
    protected $maxLat;  //  PI/2
    protected $minLng;  // -PI
    protected $maxLng;  //  PI

    // -------------------------------------------------------------------------

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->minLat = deg2rad(-90);   // -PI/2
        $this->maxLat = deg2rad(90);    //  PI/2
        $this->minLng = deg2rad(-180);  // -PI
        $this->maxLng = deg2rad(180);   //  PI
    }

    // -------------------------------------------------------------------------

    /**
     * @param float $latitude the latitude, in degrees.
     * @param float $longitude the longitude, in degrees.
     * @return \Pebble\Helpers\GeoLocation
     */
    public static function fromDegrees($latitude, $longitude)
    {
        if (abs($latitude) > 90 || abs($longitude) > 180) {
            $latitude  = self::toFloat($latitude);
            $longitude = self::toFloat($longitude);
        }

        $location         = new static();
        $location->radLat = deg2rad($latitude);
        $location->radLng = deg2rad($longitude);
        $location->degLat = $latitude;
        $location->degLng = $longitude;
        $location->checkBounds();
        return $location;
    }

    // -------------------------------------------------------------------------

    /**
     * @param float $latitude the latitude, in radians.
     * @param float $longitude the longitude, in radians.
     * @return \Pebble\Helpers\GeoLocation
     */
    public static function fromRadians($latitude, $longitude)
    {
        $location         = new static();
        $location->radLat = $latitude;
        $location->radLng = $longitude;
        $location->degLat = rad2deg($latitude);
        $location->degLng = rad2deg($longitude);
        $location->checkBounds();
        return $location;
    }

    // -------------------------------------------------------------------------

    /**
     * Check arguments
     *
     * @throws \Exception
     */
    protected function checkBounds()
    {
        if ($this->radLat < $this->minLat || $this->radLat > $this->maxLat || $this->radLng < $this->minLng || $this->radLng > $this->maxLng) {
            throw new \Exception("Invalid Argument");
        }
    }

    // -------------------------------------------------------------------------

    /**
     * @return float the latitude, in degrees
     */
    public function getLatDeg()
    {
        return $this->degLat;
    }

    // -------------------------------------------------------------------------

    /**
     * @return float the longitude, in degrees
     */
    public function getLngDeg()
    {
        return $this->degLng;
    }

    // -------------------------------------------------------------------------

    /**
     * @return float the latitude, in radians
     */
    public function getLatRad()
    {
        return $this->radLat;
    }

    // -------------------------------------------------------------------------

    /**
     * @return float the longitude, in radians
     */
    public function getLngRad()
    {
        return $this->radLng;
    }

    // -------------------------------------------------------------------------

    /**
     * @return float angular radius
     */
    public function getAngular()
    {
        return $this->angular;
    }

    // -------------------------------------------------------------------------

    /**
     * @return array coordinates in degrees
     */
    public function exportDeg()
    {
        return [
            'lat' => $this->getLatDeg(),
            'lng' => $this->getLngDeg(),
        ];
    }

    // -------------------------------------------------------------------------

    /**
     * @return array coordinates in radians
     */
    public function exportRad()
    {
        return [
            'lat' => $this->getLatRad(),
            'lng' => $this->getLngRad(),
        ];
    }

    // -------------------------------------------------------------------------

    /**
     * @return string
     */
    public function __toString()
    {
        return "(" . $this->degLat . ", " . $this->degLng . ") = (" .
            $this->radLat . " rad, " . $this->radLng . " rad";
    }

    // -------------------------------------------------------------------------

    /**
     * Computes the great circle distance between this GeoLocation instance
     * and the location argument.
     *
     * @param GeoLocation $location
     * @return float the distance
     */
    public function distanceTo(Geolocation $location)
    {
        $radius = $this->getEarthsRadius();

        return acos(sin($this->radLat) * sin($location->radLat) +
            cos($this->radLat) * cos($location->radLat) *
            cos($this->radLng - $location->radLng)) * $radius;
    }

    // -------------------------------------------------------------------------

    /**
     *
     * @param type $distance
     * @param type $unit_of_measurement
     * @return \Pebble\Helpers\GeoLocation[]
     * @throws \Exception
     */
    public function boundingCoordinates($distance)
    {

        $radius = $this->getEarthsRadius();

        if ($distance < 0) {
            throw new \Exception('Arguments must be greater than 0.');
        }

        // angular distance in radians on a great circle
        $this->angular = $distance / $radius;
        $minLat        = $this->radLat - $this->angular;
        $maxLat        = $this->radLat + $this->angular;
        $minLon        = 0;
        $maxLon        = 0;
        if ($minLat > $this->minLat && $maxLat < $this->maxLat) {
            $deltaLon = asin(sin($this->angular) / cos($this->radLat));
            $minLon   = $this->radLng - $deltaLon;
            if ($minLon < $this->minLng) {
                $minLon += 2 * pi();
            }
            $maxLon = $this->radLng + $deltaLon;
            if ($maxLon > $this->maxLng) {
                $maxLon -= 2 * pi();
            }
        } else {
            // a pole is within the distance
            $minLat = max($minLat, $this->minLat);
            $maxLat = min($maxLat, $this->maxLat);
            $minLon = $this->minLng;
            $maxLon = $this->maxLng;
        }
        return array(
            GeoLocation::fromRadians($minLat, $minLon),
            GeoLocation::fromRadians($maxLat, $maxLon)
        );
    }

    // -------------------------------------------------------------------------

    /**
     * Get earth radius
     *
     * @return float
     */
    protected function getEarthsRadius()
    {
        return self::EARTH_RADIUS;
    }

    // -------------------------------------------------------------------------
    // OLD
    // -------------------------------------------------------------------------

    /**
     * Distance beetween 2 points using the haversine formula
     *
     * @param float $lat1 Latitude of point 1
     * @param float $lng1 Longitude of point 1
     * @param float $lat2 Latitude of point 2
     * @param float $lng2 Longitude of point 2
     * @return float Distance
     */
    public static function distance($lat1, $lng1, $lat2, $lng2)
    {

        $p1 = self::fromDegrees($lat1, $lng1);
        $p2 = self::fromDegrees($lat2, $lng2);

        return $p1->distanceTo($p2);
    }

    // -------------------------------------------------------------------------

    /**
     * Get the bounding box of a point and a distance
     *
     * @param float $lat
     * @param float $lng
     * @param float $distance (meter)
     * @return array latMin, latMax, lngMin, lngMax
     */
    public static function boundingBox($lat, $lng, $distance)
    {

        $p = self::fromDegrees($lat, $lng);
        $b = $p->boundingCoordinates($distance);

        return array($b[0]->getLatDeg(), $b[1]->getLatDeg(), $b[0]->getLngDeg(), $b[1]->getLngDeg());
    }

    // -------------------------------------------------------------------------

    public static function toInt($var, $precision = 1000000)
    {
        return floor($var * $precision);
    }

    // -------------------------------------------------------------------------

    public static function toFloat($var, $precision = 1000000)
    {
        return $var * 1.0 / $precision;
    }

    // -------------------------------------------------------------------------

    /**
     * Calculate the number of meters per pixel on a map
     *
     * @param float $meters
     * @param float $lat
     * @param integer $zoom
     * @return float
     */
    public static function metersPerPixel(float $lat, int $zoom, int $delta_zoom = self::DELTA_ZOOM_GOOGLE): float
    {
        return 2 * M_PI * self::EARTH_RADIUS
            * cos(deg2rad($lat))
            / pow(2, $zoom + $delta_zoom);
    }

    // -------------------------------------------------------------------------
}

/* End of file */
