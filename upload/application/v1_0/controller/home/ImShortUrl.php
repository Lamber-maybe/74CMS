<?php
/**
 * IM短链接
 */

namespace app\v1_0\controller\home;

use app\v1_0\controller\common\Base;
use app\common\model\ImShortUrl as ImShortUrlModel;

class ImShortUrl extends Base
{
    public function index($code)
    {
        $m = new ImShortUrlModel();
        $row = $m->getValidByCode($code);
        if (!$row) {
            $this->error('链接已失效或不存在', '/');
        }
        $m->where(['id' => $row['id']])->setInc('pv');
        $this->redirect($row['url']);
    }

}
