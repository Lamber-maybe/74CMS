<?php
/**
 * 极光推送
 *
 * @author
 */

namespace app\common\lib;

class Jpush
{
    protected $client;
    protected $push;
    public function __construct()
    {
        $this->client = new \JPush\Client(config('global_config.jpush_appkey'), config('global_config.jpush_appsecret'),RUNTIME_PATH.'jpush.log');
        $this->push = $this->client->push();
    }
    public function notification($uid,$message,$page,$id=0)
    {   
        $uidarr = [];
        foreach ($uid as $key => $value) {
            $uidarr[] = $value.'';
        }
        $platform = ['ios', 'android'];
        $ios_notification = [
            'content-available' => true,
            'extras' => [
                'page' => $page
            ]
        ];
        $android_notification = [
            'extras' => [
                'page' => $page,
                'id'=>$id
            ]
        ];
        try {
            $response = $this->push->setPlatform($platform)
            ->addAlias($uidarr)
            ->iosNotification($message, $ios_notification)
            ->androidNotification($message, $android_notification)
            ->send();
        } 
        catch (\JPush\Exceptions\APIConnectionException $e) 
        {

        } 
        catch (\JPush\Exceptions\APIRequestException $e) 
        {
            
        }
        
    }
}
