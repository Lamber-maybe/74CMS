<?php

/*
    // composer require lcobucci/jwt 3.3
    $t = JwtAuth::getToken($tokenstr);
    dump($t->verify('123456'));  // 验证签名
    dump($t->isExpired());  // 验证是否过期
    dump($t->getData());  // iat=生效时间戳 exp=过期时间戳 cid
    dump($t->getString());  // 获取Ticket字符串
 */

namespace app\common\lib;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

class JwtAuth
{
    /**
     * @var Token
     */
    private $token;

    /**
     * Ticket constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @param string $token_str
     * @return self
     */
    public static function getToken($token_str)
    {
        $token = (new Parser())->parse($token_str);
        return new self($token);
    }

    /**
     * 生成Token
     * @param string $sign   签名
     * @param int    $expire 过期时间
     * @param array  $data   数据
     * @return self
     */
    public static function mkToken($sign, $expire, $data = [])
    {
        $signer = new Sha256();
        $time = time();
        $token = (new Builder())->issuedAt($time)->expiresAt($time + $expire);
        foreach ($data as $key => $val) {
            $token->withClaim($key, $val);
        }
        $token = $token->getToken($signer, new Key($sign));
        return new self($token);
    }

    /**
     * 判断是否过期
     * @return bool
     */
    public function isExpired()
    {
        return $this->token->isExpired();
    }

    /**
     * 验证签名
     * @param string $sign
     * @return bool
     */
    public function verify($sign)
    {
        return $this->token->verify(new Sha256(), $sign);
    }

    /**
     * 获取数据
     * @param string|null $key
     * @return array|mixed
     */
    public function getData($key = null)
    {
        if (!isset($key)) {
            return $this->token->getClaims();
        }
        return $this->token->getClaim($key);
    }

    /**
     * 获取Ticket字符串
     * @return string
     */
    public function getString()
    {
        return (string)$this->token;
    }
}