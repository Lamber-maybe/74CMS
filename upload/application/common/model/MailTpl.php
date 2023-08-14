<?php
namespace app\common\model;

class MailTpl extends \app\common\model\BaseModel
{
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_mail_tpl', null);
        });
        self::event('after_delete', function () {
            cache('cache_mail_tpl', null);
        });
    }
    public function getCache($alias = '')
    {
        if (false === ($data = cache('cache_mail_tpl'))) {
            $data = $this->where('status', 1)->column(
                'alias,title,value',
                'alias'
            );
            cache('cache_mail_tpl', $data);
        }
        if ($alias != '') {
            return $data[$alias];
        }
        return $data;
    }
    public function labelReplace($templates, $replac)
    {
        $replac['sitename'] = config('global_config.sitename');
        $replac['sitedomain'] =
        config('global_config.sitedomain') .
        config('global_config.sitedir');
        $replac['logo'] = config('global_config.logo')
        ? make_file_url(config('global_config.logo'))
        : make_file_url('resource/logo.gif');
        $replac['bootom_tel'] = config('global_config.contact_tel');
        $replac['contact_email'] = config('global_config.contact_email');
        $replac['qrcode'] = config('global_config.wechat_qrcode')
        ? make_file_url(config('global_config.wechat_qrcode'))
        : make_file_url('resource/weixin_img.jpg');
        $replac['url_membercenter'] =
        config('global_config.sitedomain') .
        config('global_config.sitedir');
        $replac['url_help'] =
        config('global_config.sitedomain') .
        config('global_config.sitedir');
        if (!empty($replac)) {
            foreach ($replac as $key => $val) {
                $replac['{' . $key . '}'] = $val;
            }
        }
        return $templates = strtr($templates, $replac);
    }
    public function readFromHtml($alias)
    {
        $html = file_get_contents(
            API_LIB_PATH . 'mail' . DS . 'mailtpl' . DS . $alias . '.html'
        );
        $this->save(['value' => $html], ['alias' => $alias]);
        return true;
    }
}
