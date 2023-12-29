<?php

namespace app\common\lib;

class CoordinateTransform
{
    const PI = 3.1415926535897932384626;
    const a = 6378245.0;
    const ee = 0.00669342162296594323;

    /**
     * 天地图 转换为 百度
     * WGS84 转换为 BD-09
     * @param $lng float|string
     * @param $lat float|string
     * @return array
     */
    public function wgs84ToBd09($lng, $lat)
    {
        // 先将WGS84转为高德腾讯，再转为BD09
        $tempGeom = $this->wgs84ToGcj02($lng, $lat);
        // GCJ-02 转 BD09
        return $this->gcj02ToBd09($tempGeom[0], $tempGeom[1]);
    }

    /**
     * 百度 转换为 天地图
     * BD-09 转换为 WGS84
     * @param $lng float|string
     * @param $lat float|string
     * @return array
     */
    public function bd09ToWgs84($lng, $lat)
    {
        // 先将BD09转为高德腾讯，再转为WGS84
        $tempGeom = $this->bd09ToGcj02($lng, $lat);
        // GCJ-02 转 WGS84
        return $this->gcj02ToWgs84($tempGeom[0], $tempGeom[1]);
    }

    /**
     * WGS84转GCj02
     * @param $lng float|string
     * @param $lat float|string
     * @return array
     */
    public function wgs84ToGcj02($lng, $lat)
    {
        if ($this->outOfChina($lng, $lat)) {
            return [$lng, $lat];
        } else {
            $dlat = $this->transformLat($lng - 105.0, $lat - 35.0);
            $dlng = $this->transformLng($lng - 105.0, $lat - 35.0);
            $radlat = $lat / 180.0 * self::PI;
            $magic = sin($radlat);
            $magic = 1 - self::ee * $magic * $magic;
            $sqrtmagic = sqrt($magic);
            $dlat = ($dlat * 180.0) / ((self::a * (1 - self::ee)) / ($magic * $sqrtmagic) * self::PI);
            $dlng = ($dlng * 180.0) / (self::a / $sqrtmagic * cos($radlat) * self::PI);
            $mglat = $lat + $dlat;
            $mglng = $lng + $dlng;
            return [$mglng, $mglat];
        }
    }

    /**
     * 火星坐标系 (GCJ-02) 与百度坐标系 (BD-09) 的转换
     * 即谷歌、高德 转 百度
     * @param $lng float|string
     * @param $lat float|string
     * @return array
     */
    public function gcj02ToBd09($lng, $lat)
    {
        $x_PI = 3.14159265358979324 * 3000.0 / 180.0;
        $z = sqrt($lng * $lng + $lat * $lat) + 0.00002 * sin($lat * $x_PI);
        $theta = atan2($lat, $lng) + 0.000003 * cos($lng * $x_PI);
        $bd_lng = $z * cos($theta) + 0.0065;
        $bd_lat = $z * sin($theta) + 0.006;
        return [$bd_lng, $bd_lat];
    }

    /**
     * 百度坐标系 (BD-09) 与 火星坐标系 (GCJ-02)的转换
     * 即 百度 转 谷歌、高德
     * @param $lng float|string
     * @param $lat float|string
     * @return array
     */
    public function bd09ToGcj02($lng, $lat)
    {
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $gg_lng = $z * cos($theta);
        $gg_lat = $z * sin($theta);
        return [$gg_lng, $gg_lat];
    }

    /**
     * GCJ02 转换为 WGS84
     * @param $lng float|string
     * @param $lat float|string
     * @return array
     */
    public function gcj02ToWgs84($lng, $lat)
    {
        if ($this->outOfChina($lng, $lat)) {
            return [$lng, $lat];
        } else {
            $dlat = $this->transformLat($lng - 105.0, $lat - 35.0);
            $dlng = $this->transformLng($lng - 105.0, $lat - 35.0);
            $radlat = $lat / 180.0 * self::PI;
            $magic = sin($radlat);
            $magic = 1 - self::ee * $magic * $magic;
            $sqrtmagic = sqrt($magic);
            $dlat = ($dlat * 180.0) / ((self::a * (1 - self::ee)) / ($magic * $sqrtmagic) * self::PI);
            $dlng = ($dlng * 180.0) / (self::a / $sqrtmagic * cos($radlat) * self::PI);
            $mglat = $lat + $dlat;
            $mglng = $lng + $dlng;
            return [$lng * 2 - $mglng, $lat * 2 - $mglat];
        }
    }

    /**
     * 判断是否在国内，不在国内则不做偏移
     * @param $lng float|string
     * @param $lat float|string
     * @return bool
     */
    private function outOfChina($lng, $lat)
    {
        return ($lng < 72.004 || $lng > 137.8347) || (($lat < 0.8293 || $lat > 55.8271) || false);
    }

    /**
     * 转换经度
     * @param $lng float|string
     * @param $lat float|string
     * @return float|string
     */
    private function transformLng($lng, $lat)
    {
        $ret = 300.0 + $lng + 2.0 * $lat + 0.1 * $lng * $lng + 0.1 * $lng * $lat + 0.1 * sqrt(abs($lng));
        $ret += (20.0 * sin(6.0 * $lng * self::PI) + 20.0 * sin(2.0 * $lng * self::PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($lng * self::PI) + 40.0 * sin($lng / 3.0 * self::PI)) * 2.0 / 3.0;
        $ret += (150.0 * sin($lng / 12.0 * self::PI) + 300.0 * sin($lng / 30.0 * self::PI)) * 2.0 / 3.0;
        return $ret;
    }

    /**
     * 转换纬度
     * @param $lng float|string
     * @param $lat float|string
     * @return float|string
     */
    private function transformLat($lng, $lat)
    {
        $ret = -100.0 + 2.0 * $lng + 3.0 * $lat + 0.2 * $lat * $lat + 0.1 * $lng * $lat + 0.2 * sqrt(abs($lng));
        $ret += (20.0 * sin(6.0 * $lng * self::PI) + 20.0 * sin(2.0 * $lng * self::PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($lat * self::PI) + 40.0 * sin($lat / 3.0 * self::PI)) * 2.0 / 3.0;
        $ret += (160.0 * sin($lat / 12.0 * self::PI) + 320 * sin($lat * self::PI / 30.0)) * 2.0 / 3.0;
        return $ret;
    }

}
