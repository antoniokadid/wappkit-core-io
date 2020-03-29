<?php

namespace AntonioKadid\WAPPKitCore\IO\Streams;

use AntonioKadid\WAPPKitCore\IO\Exceptions\IOException;

/**
 * Class TempStream
 *
 * @package AntonioKadid\WAPPKitCore\IO\Streams
 */
class TempStream extends FileStream
{
    /**
     * TempStream constructor.
     *
     * @param int $sizeInBytes The size of bytes to limit the size if required.
     *
     * @throws IOException
     */
    public function __construct(int $sizeInBytes = 0)
    {
        if ($sizeInBytes === 0)
            parent::__construct('php://temp', 'rb+');
        else
            parent::__construct("php://temp/maxmemory:$sizeInBytes", 'rb+');
    }

    /**
     * @param string $bytes
     *
     * @return TempStream
     *
     * @throws IOException
     */
    public static function fromBytes(string $bytes): TempStream
    {
        $temp = new TempStream();
        $temp->write($bytes);
        $temp->seekFromBeginning(0);

        return $temp;
    }
}
