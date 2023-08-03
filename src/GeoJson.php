<?php

namespace Pebble\Helpers;

class GeoJson
{
    const EARTH_RADIUS = 6378137;

    public static function coords($geojson)
    {
        return $geojson['coordinates'] ?? ($geojson ?: []);
    }

    public static function point(array $coords)
    {
        return ["type" => "Point", "coordinates" => $coords];
    }

    /**
     * Valeurs en degrés
     *
     * @param float $lat
     * @param float $lon
     * @return static
     */
    public static function pointFromDegrees(float $lat, float $lon)
    {
        return self::point([$lon, $lat]);
    }

    /**
     * Valeurs en radians
     *
     * @param float $lat
     * @param float $lon
     * @return static
     */
    public static function pointFromRadians(float $lat, float $lon)
    {
        return self::point([rad2deg($lon), rad2deg($lat)]);
    }

    /**
     * Unité utilisée par la suisse
     *
     * @param float $e
     * @param float $n
     * @return static
     */
    public static function pointFromMN95(float $e, float $n)
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

        return self::point([$lon, $lat]);
    }

    /**
     * Transform gps data from exif_read_data() to decimal degrees (dd)
     *
     * @param array $exif_gps
     * @return static
     */
    public static function pointFromExifGPS(array $exif_gps)
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

        return self::pointFromDMS($dms);
    }

    /**
     * Transform degrees, minutes, seconds (DMS) coordinates to decimal degrees (dd)
     * Transform dd in negative value if lat_ref S or lon_ref W
     *
     * @param array $dms with properties lat_ref, lat_degrees, lat_minutes, lat_seconds, lon_ref, lon_degrees, lon_minutes, lon_seconds.
     * @return static
     */
    public static function pointFromDMS(array $dms)
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

        return self::point([$lon, $lat]);
    }

    public static function inside($point, $polygon)
    {
        $poly = self::coords($polygon);
        $type = $polygon['type'] ?? null;

        if (!$poly) {
            return false;
        }

        // Search inside each polygon
        if ($type === 'MultiPolygon') {
            foreach ($poly as $p) {
                if (self::inside($point, $p)) {
                    return true;
                }
            }

            return false;
        }

        // Search inside polygon
        $outline = array_shift($poly);
        $inside = self::insideVertices($point, $outline);

        // Polygon can contain holes
        if ($inside && $poly) {
            foreach ($poly as $hole) {
                // The point is inside the hole
                // then the point is not inside the polygon
                if (self::insideVertices($point, $hole)) {
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
    public static function insideVertices($point, array $vertices)
    {
        $inside = false;

        if (!$vertices) {
            return $inside;
        }

        $pt = self::coords($point);
        $x = $pt[0];
        $y = $pt[1];

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

    public static function distance($point1, $point2)
    {
        $pt1 = self::coords($point1);
        $pt2 = self::coords($point2);

        $x1 = deg2rad($pt1[0]);
        $y1 = deg2rad($pt1[1]);
        $x2 = deg2rad($pt2[0]);
        $y2 = deg2rad($pt2[1]);

        return acos(sin($y1) * sin($y2) +
            cos($y1) * cos($y2) *
            cos($x1 - $x2)) * self::EARTH_RADIUS;
    }

    public static function bounds($point, $distance)
    {
        $pt = self::coords($point);

        $MINLAT = pi() * -1 / 2;
        $MAXLAT = pi() / 2;
        $MINLON = pi() * -1;
        $MAXLON = pi();

        $angle = $distance / self::EARTH_RADIUS;
        $lon = deg2rad($pt[0]);
        $lat = deg2rad($pt[1]);

        $min_lat = $lat - $angle;
        $max_lat = $lat + $angle;

        $min_lon = 0;
        $max_lon = 0;
        if ($min_lat > $MINLAT && $max_lat < $MAXLAT) {
            $delta_lon = asin(sin($angle) / cos($lat));
            $min_lon = $lon - $delta_lon;
            if ($min_lon < $MINLON) {
                $min_lon += 2 * pi();
            }
            $max_lon = $lon + $delta_lon;
            if ($max_lon > $MAXLON) {
                $max_lon -= 2 * pi();
            }
        } else {
            $min_lat = max($min_lat, $MINLAT);
            $max_lat = max($max_lat, $MAXLAT);
            $min_lon = $MINLON;
            $max_lon = $MAXLON;
        }

        return [
            self::pointFromRadians($min_lat, $min_lon),
            self::pointFromRadians($max_lat, $max_lon)
        ];
    }

    public static function bbox($polygon)
    {
        $polys = self::coords($polygon);

        if ($polygon['type'] === 'Polygon') {
            $polys = [$polys];
        }

        $min_lat = 90;
        $min_lon = 180;
        $max_lat = -90;
        $max_lon = -180;

        foreach ($polys as $poly) {
            foreach ($poly as $part) {
                foreach ($part as $pt) {
                    if ($pt[0] < $min_lon) $min_lon = $pt[0];
                    if ($pt[0] > $max_lon) $max_lon = $pt[0];
                    if ($pt[1] < $min_lat) $min_lat = $pt[1];
                    if ($pt[1] > $max_lat) $max_lat = $pt[1];
                }
            }
        }

        return [
            self::point([$min_lon, $min_lat]),
            self::point([$max_lon, $max_lat]),
        ];
    }

    public static function center($polygon)
    {
        $bbox = self::bbox($polygon);
        $min = self::coords($bbox[0]);
        $max = self::coords($bbox[1]);

        $x = ($min[0] + $max[0]) / 2;
        $y = ($min[1] + $max[1]) / 2;

        return self::point([$x, $y]);
    }

    public static function centroid($polygon)
    {
        $polys = self::coords($polygon);

        if ($polygon['type'] === 'Polygon') {
            $polys = [$polys];
        }

        $xSum = 0;
        $ySum = 0;
        $len = 0;

        foreach ($polys as $poly) {
            foreach ($poly as $part) {
                $partLen = count($part);
                for ($i = 0; $i < $partLen - 1; $i++) {
                    $xSum += $part[$i][0];
                    $ySum += $part[$i][1];
                    $len++;
                }
            }
        }

        $y = $ySum / $len;
        $x = $xSum / $len;

        return self::point([$x, $y]);
    }

    public static function visualCenter($polygon)
    {
        $center = self::center($polygon);

        if (self::inside($center, $polygon)) {
            return $center;
        }

        $centroid = self::centroid($polygon);
        if (self::inside($centroid, $polygon)) {
            return $centroid;
        }

        $polys = self::coords($polygon);

        if ($polygon['type'] === 'Polygon') {
            $polys = [$polys];
        }

        $min  = null;
        $point = null;

        foreach ($polys as $poly) {
            foreach ($poly as $part) {
                foreach ($part as $pt) {

                    $d = self::distance($center, $pt);

                    if ($min === null || $min > $d) {
                        $min  = $d;
                        $point = $pt;
                    }
                }
            }
        }

        return self::point($point);
    }
}
