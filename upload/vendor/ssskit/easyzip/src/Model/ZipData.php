<?php

namespace EasyZip\Model;

use EasyZip\Exception\ZipException;

/**
 * Interface ZipData.
 */
interface ZipData
{
    /**
     * @return string returns data as string
     */
    public function getDataAsString();

    /**
     * @return resource returns stream data
     */
    public function getDataAsStream();

    /**
     * @param resource $outStream
     *
     * @throws ZipException
     */
    public function copyDataToStream($outStream);
}
