<?php
// @codingStandardsIgnoreFile
require_once __DIR__ . '/../autoload.php';

use Qiniu\Auth;

$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');
$testAuth = new Auth($accessKey, $secretKey);

$bucketName = 'phpsdk';
$key = '1503385451.png';
$key2 = '1503385568.png';

$bucketNameBC = 'phpsdk-bc';
$bucketNameNA = 'phpsdk-na';

$dummyAccessKey = 'abcdefghklmnopq';
$dummySecretKey = '1234567890';
$dummyAuth = new Auth($dummyAccessKey, $dummySecretKey);

//cdn
$timestampAntiLeechEncryptKey = getenv('QINIU_TIMESTAMP_ENCRPTKEY');
$customDomain = "http://phpsdk.support2technical.me";

$tid = getenv('TRAVIS_JOB_NUMBER');
if (!empty($tid)) {
    $pid = getmypid();
    $tid = strstr($tid, '.');
    $tid .= '.' . $pid;
}

function qiniuTempFile($size)
{
    $fileName = tempnam(sys_get_temp_dir(), 'qiniu_');
    $file = fopen($fileName, 'wb');
    if ($size > 0) {
        fseek($file, $size - 1);
        fwrite($file, ' ');
    }
    fclose($file);
    return $fileName;
}
