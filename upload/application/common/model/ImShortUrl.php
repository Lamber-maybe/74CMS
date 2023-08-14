<?php
/**
 * IM短链接
 */

namespace app\common\model;

class ImShortUrl extends BaseModel
{
    public function genCode($len = 2)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';

        $string = time();

        for (; $len >= 1; $len--) {
            $position = rand() % strlen($chars);
            $position2 = rand() % strlen($string);
            $string = substr_replace($string, substr($chars, $position, 1), $position2, 0);
        }

        return $string;
    }

    public function gen($url, $uid)
    {
        $data = $this->where(['url' => $url, 'uid' => $uid])->find();
        if (!$data || !isset($data['code'])) {
            $code = $this->genCode();
            $data = [
                'url' => $url,
                'code' => $code,
                'uid' => $uid,
                'addtime' => time()
            ];
            $this->insert($data);
        }
        return rtrim(config('global_config.sitedomain'), '/') . '/ims/' . $data['code'];
    }


    public function getValidByCode($code)
    {
        $row = $this->where('code', $code)->find();
        if ($row === null) {
            return null;
        }
        return $row;
    }


}
