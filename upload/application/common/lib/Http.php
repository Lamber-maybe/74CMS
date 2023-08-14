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
