<?php
namespace app\common\lib;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
class Http
{
    protected $client = null;
    protected $error = null;
    protected $verify = false;
    public function __construct()
    {
        $this->client = new Client(['verify' => $this->verify]);
    }
    /**
     * GET请求
     */
    public function get($url)
    {
        $retrun = false;
        try {
            $response = $this->client->request('GET', $url);
            $retrun = $response->getBody()->getContents();
        } catch (ClientException $e) {
            $this->error = $e->getMessage();
        }
        return $retrun;
    }
    public function get2($url)
    {
        $retrun = false;
        try {
            $response = $this->client->request('GET', $url);
            $retrun = $response->getBody()->getContents();
        } catch (ClientException $e) {
            $this->error = $e->getMessage();
        }
        return [
            'headers' => $response->getHeaders(),
            'body' => $retrun
        ];
    }
    function get3($url ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1" );

        curl_setopt( $ch, CURLOPT_URL, $url);

        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );

        curl_setopt( $ch, CURLOPT_ENCODING, "" );

        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );

        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        $content = curl_exec( $ch );
        curl_close ( $ch );
        return $content;
    }
    function request($url, $header, $type, $data, $DataType, $HeaderType = "PC")
    {
        //常用header
        //$header = "user-agent:" . 1 . "\r\n" . "referer:" . 1 . "\r\n" . "AccessToken:" . 1 . "\r\n" . "cookie:" . 1 . "\r\n";
        if (empty($header)) {
            if ($HeaderType == "PC") {
                $header = "user-agent:Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1\r\n";
            } else if ($HeaderType == "PE") {
                $header = "user-agent:Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1 Edg/88.0.4324.150\r\n";
            }
        }
        if (!empty($data)) {
            if ($DataType == 1) {
                $data = http_build_query($data); //数据拼接
            } else if ($DataType == 2) {
                $data = json_encode($data, JSON_UNESCAPED_UNICODE); //数据格式转换
            }
        }
        $options = array(
            'http' => array(
                'method' => $type,
                "header" => $header,
                'content' => $data,
                'timeout' => 30, // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $headers = get_headers($url, true); //获取请求返回的header
        $ReturnArr = array(
            'headers' => $headers,
            'body' => $result
        );
        return $ReturnArr;
    }
    /**
     * POST请求
     */
    public function post($url, $data = [])
    {
        $retrun = false;
        try {
            $response = $this->client->request('POST', $url, ['json' => $data]);
            $retrun = $response->getBody()->getContents();
        } catch (ClientException $e) {
            $this->error = $e->getMessage();
        }
        return $retrun;
    }
    /**
     * 异步GET请求
     */
    public function getAsync($url, $func)
    {
        $promise = $this->client->requestAsync('GET', $url);
        try {
            $promise->then(
                function (ResponseInterface $res) use ($func) {
                    if (is_callable($func)) {
                        return $func($res);
                    }
                },
                function (RequestException $e) {}
            );
            $promise->wait();
        } catch (ClientException $e) {
            $this->error = $e->getMessage();
        }
    }
    /**
     * 异步POST请求
     */
    public function postAsync($url, $data = [], $func)
    {
        $promise = $this->client->requestAsync('POST', $url, ['json' => $data]);
        try {
            $promise->then(
                function (ResponseInterface $res) use ($func) {
                    if (is_callable($func)) {
                        return $func($res);
                    }
                },
                function (RequestException $e) {}
            );
            $promise->wait();
        } catch (ClientException $e) {
            $this->error = $e->getMessage();
        }
    }
    /**
     * 异步并发GET请求
     */
    public function getAsyncConcurrency($urlarr)
    {
        $retrun = [];
        $requests = function ($urlarr) {
            for ($i = 0; $i < count($urlarr); $i++) {
                yield new Request('GET', $urlarr[$i]);
            }
        };

        $pool = new Pool($this->client, $requests($urlarr), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) use (&$retrun) {
                $retrun[] = $response->getBody()->getContents();
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
            }
        ]);

        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();
        return $retrun;
    }
    public function getError()
    {
        return $this->error;
    }
}
