<?php

namespace Pebble\Helpers;

/**
 * Points
 *
 * @author mathieu
 */
class Points
{


    const EARTH_RADIUS = 6378137;

    /**
     * @var \Pebble\Helpers\Point[]
     */
    private $points = [];

    /**
     * @param \Pebble\Helpers\Point $point
     * @return static
     */
    public function add(Point $point)
    {
        $this->points[] = $point;
        return $this;
    }

    /**
     * @return \Pebble\Helpers\Point[]
     */
    public function all()
    {
        return $this->points;
    }

    /**
     * @return \Pebble\Helpers\Point
     */
    public function center()
    {
        return static::getCenter($this->points);
    }

    /**
     * @return \Pebble\Helpers\Point
     */
    public function closer()
    {
        return static::getCloser($this->center(), $this->points);
    }

    /**
     * Give the point closer from another point
     *
     * @param Point $point
     * @param \Pebble\Helpers\Point[] $points
     * @return \Pebble\Helpers\Point
     */
    public static function getCloser(Point $point, array $points)
    {
        if (!$points) {
            return null;
        }

        $min  = null;
        $item = null;

        foreach ($points as $p) {

            $d = $point->distanceTo($p);

            if ($min === null || $min > $d) {
                $min  = $d;
                $item = $p;
            }
        }

        return $item;
    }

    /**
     * @param \Pebble\Helpers\Point[] $points
     * @return \Pebble\Helpers\Point
     */
    public static function getCenter(array $points)
    {
        $maxlat = 0;
        $minlat = 0;
        $maxlon = 0;
        $minlon = 0;
        $i      = 0;

        foreach ($points as $point) {
            if ($i === 0) {
                $maxlat = $point->getLat();
                $minlat = $point->getLat();
                $maxlon = $point->getLon();
                $minlon = $point->getLon();
            } else {
                if ($maxlat < $point->getLat()) {
                    $maxlat = $point->getLat();
                }
                if ($minlat > $point->getLat()) {
                    $minlat = $point->getLat();
                }
                if ($maxlon < $point->getLon()) {
                    $maxlon = $point->getLon();
                }
                if ($minlon > $point->getLon()) {
                    $minlon = $point->getLon();
                }
            }

            $i++;
        }

        return new Point(($minlat + $maxlat) / 2, ($minlon + $maxlon) / 2);
    }
}
