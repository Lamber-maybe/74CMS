<?php
namespace app\common\lib;

class CoordinateTransform
{
    const PI = 3.1415926535897932384626;
    const a = 6378245.0;
    const ee = 0.00669342162296594323;

    /**
     * 度分秒转十进制度
     * @param $deg
     * @return bool|float|int|mixed|string
     */
    public static function degreeMinuteSecondToDecimal($deg)
    {
        $_oldCon = $deg;
        $deg = rtrim(str_replace(['‘’', '’‘', '’', '°', '′', '″', '＇', '“', '”'], '.', $deg), '.');
        $pointCount = substr_count($deg, '.');
        if ($pointCount == 1) { // 形如: 111.23412 需要生成第二个点
            if ($_oldCon == $deg) return $deg;
            [$str1, $str2] = explode('.', $deg);
            if (strlen($str2) > 2) {
                $str2 = substr($str2, 0, 2).'.'.substr($str2, 2);
                $deg = $str1.'.'.$str2;
            }
        } else if ($pointCount == 2) {
            $deg .= '.0';
        }

        try {
            // 形如: 111.23.412 或 111.23.41.2
            [$deg1, $deg2, $deg3] = explode('.', $deg, 3);

            $deg = $deg1 + $deg2/60 + $deg3/3600;
            return $deg;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 百度坐标系 (BD-09) 与 火星坐标系 (GCJ-02)的转换
     * 即 百度 转 谷歌、高德
     * @param $bdLon float|string
     * @param $bdLat float|string
     * @return array
     */
    public static function bd09ToGcj02($bdLon, $bdLat)
    {
        $x = $bdLon - 0.0065;
        $y = $bdLat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * self::PI);
        $theta = atan2($y, $x) - 0.000003 * cos($x * self::PI);
        $gg_lng = $z * cos($theta);
        $gg_lat = $z * sin($theta);
        return [$gg_lng, $gg_lat];
    }

    /**
     * 火星坐标系 (GCJ-02) 与百度坐标系 (BD-09) 的转换
     * 即谷歌、高德 转 百度
     * @param $lng float|string
     * @param $lat float|string
     * @return array
     */
    public static function gcj02ToBd09($lng, $lat)
    {
        $z = sqrt($lng * $lng + $lat * $lat) + 0.00002 * sin($lat * self::PI);
        $theta = atan2($lat, $lng) + 0.000003 * cos($lng * self::PI);
        $bd_lng = $z * cos($theta) + 0.0065;
        $bdLat = $z * sin($theta) + 0.006;
        return [$bd_lng, $bdLat];
    }

    /**
     * WGS84转GCj02
     * @param $lng float|string
     * @param $lat float|string
     * @return array
     */
    public static function wgs84ToGcj02($lng, $lat)
    {
        $dlat = self::transformlat($lng - 105.0, $lat - 35.0);
        $dlng = self::transformlng($lng - 105.0, $lat - 35.0);
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

    /**
     * GCJ02 转换为 WGS84
     * @param $lng float|string
     * @param $lat float|string
     * @return array
     */
    public static function gcj02ToWgs84($lng, $lat)
    {
        $dlat = self::transformlat($lng - 105.0, $lat - 35.0);
        $dlng = self::transformlng($lng - 105.0, $lat - 35.0);
        $radlat = $lat / 180.0 * self::PI;
        $magic = sin($radlat);
        $magic = 1 - self::ee * $magic * $magic;
        $sqrtmagic = sqrt($magic);
        $dlat = ($dlat * 180.0) / ((self::a * (1 - self::ee)) / ($magic * $sqrtmagic) * self::PI);
        $dlng = ($dlng * 180.0) / (self::a / $sqrtmagic * cos($radlat) * self::PI);
        $mglat = $lat + $dlat;
        $mglng = $lng + $dlng;
        return [$mglat, $mglng];
    }

    /**
     * WGS84 转换为 BD-09
     * @param $lng float|string
     * @param $lat float|string
     * @return array
     */
    public static function wgs84ToBd09($lng, $lat)
    {
        //第一次转换
        $dlat = self::transformlat($lng - 105.0, $lat - 35.0);
        $dlng = self::transformlng($lng - 105.0, $lat - 35.0);
        $radlat = $lat / 180.0 * self::PI;
        $magic = sin($radlat);
        $magic = 1 - self::ee * $magic * $magic;
        $sqrtmagic = sqrt($magic);
        $dlat = ($dlat * 180.0) / ((self::a * (1 - self::ee)) / ($magic * $sqrtmagic) * self::PI);
        $dlng = ($dlng * 180.0) / (self::a / $sqrtmagic * cos($radlat) * self::PI);
        $mglat = $lat + $dlat;
        $mglng = $lng + $dlng;

        $x_PI = 3.14159265358979324 * 3000.0 / 180.0;

        //第二次转换
        $z = sqrt($mglng * $mglng + $mglat * $mglat) + 0.00002 * sin($mglat * $x_PI);
        $theta = atan2($mglat, $mglng) + 0.000003 * cos($mglng * $x_PI);
        $bd_lng = $z * cos($theta) + 0.0065;
        $bdLat = $z * sin($theta) + 0.006;
        return [$bd_lng, $bdLat];
    }

    private static function transformlat($lng, $lat)
    {
        $ret = -100.0 + 2.0 * $lng + 3.0 * $lat + 0.2 * $lat * $lat + 0.1 * $lng * $lat + 0.2 * sqrt(abs($lng));
        $ret += (20.0 * sin(6.0 * $lng * self::PI) + 20.0 * sin(2.0 * $lng * self::PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($lat * self::PI) + 40.0 * sin($lat / 3.0 * self::PI)) * 2.0 / 3.0;
        $ret += (160.0 * sin($lat / 12.0 * self::PI) + 320 * sin($lat * self::PI / 30.0)) * 2.0 / 3.0;
        return $ret;
    }

    private static function transformlng($lng, $lat)
    {
        $ret = 300.0 + $lng + 2.0 * $lat + 0.1 * $lng * $lng + 0.1 * $lng * $lat + 0.1 * sqrt(abs($lng));
        $ret += (20.0 * sin(6.0 * $lng * self::PI) + 20.0 * sin(2.0 * $lng * self::PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($lng * self::PI) + 40.0 * sin($lng / 3.0 * self::PI)) * 2.0 / 3.0;
        $ret += (150.0 * sin($lng / 12.0 * self::PI) + 300.0 * sin($lng / 30.0 * self::PI)) * 2.0 / 3.0;
        return $ret;
    }

    /**
     * 百度坐标系(BD-09)转WGS坐标
     *
     * @param float $lng 百度坐标纬度
     * @param float $lat 百度坐标经度
     * @return array WGS84坐标数组
     */
    public static function bd09ToWgs84 ($lng, $lat) {
        $gcj = self::bd09ToGcj02($lng, $lat);
        return self::gcj02ToWgs84($gcj[0], $gcj[1]);
    }
}
