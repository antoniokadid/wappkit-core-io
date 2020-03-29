<?php

namespace AntonioKadid\WAPPKitCore\IO\Streams;

use AntonioKadid\WAPPKitCore\IO\Exceptions\IOException;

/**
 * Class FileStream
 *
 * @package AntonioKadid\WAPPKitCore\IO\Streams
 */
class FileStream
{
    /** @var resource */
    private $resource;

    /**
     * FileStream constructor.
     *
     * @param string $filename
     * @param string $mode
     *
     * @throws IOException
     */
    public function __construct(string $filename, string $mode)
    {
        $result = fopen($filename, $mode);
        if ($result === FALSE)
            throw new IOException(sprintf('Unable to open "%s".', $filename));

        $this->resource = $result;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        if (!is_resource($this->resource))
            return TRUE;

        return fclose($this->resource);
    }

    /**
     * @return bool
     */
    public function atEnd(): bool
    {
        if (!is_resource($this->resource))
            return TRUE;

        return feof($this->resource);
    }

    /**
     * Gets a line.
     * Reading ends when $length bytes have been read, when the string specified by $ending is found (which is not included in the return value), or on EOF (whichever comes first).
     *
     * @param int         $length The number of bytes to read from the handle.
     * @param string|NULL $ending An optional string delimiter.
     *
     * @return string
     *
     * @throws IOException
     */
    public function getLine(int $length, string $ending = NULL): string
    {
        if (!is_resource($this->resource))
            throw new IOException('Invalid resource.');

        $result = stream_get_line($this->resource, $length, $ending);
        if ($result === FALSE)
            throw new IOException('Unable to read from resource.');

        return $result;
    }

    /**
     * Reads remainder of a stream into a string.
     *
     * @param int|NULL $maxLength The maximum bytes to read. Defaults to -1 (read all the remaining buffer).
     * @param int|NULL $offset    Seek to the specified offset before reading. If this number is negative, no seeking will occur and reading will start from the current position.
     *
     * @return string
     *
     * @throws IOException
     */
    public function getContents(int $maxLength = -1, int $offset = -1): string
    {
        if (!is_resource($this->resource))
            throw new IOException('Invalid resource.');

        $result = stream_get_contents($this->resource, $maxLength, $offset);
        if ($result === FALSE)
            throw new IOException('Unable to read from resource.');

        return $result;
    }

    /**
     * Retrieves header/meta data.
     * The stream must be created by fopen(), fsockopen() and pfsockopen().
     *
     * @return array
     *
     * @throws IOException
     */
    public function getMetaData(): array
    {
        if (!is_resource($this->resource))
            throw new IOException('Invalid resource.');

        return stream_get_meta_data($this->resource);
    }

    /**
     * Returns the current position of read/write pointer.
     *
     * @return int
     *
     * @throws IOException
     */
    public function getCurrentPosition(): int
    {
        if (!is_resource($this->resource))
            throw new IOException('Invalid resource.');

        $result = ftell($this->resource);
        if ($result === FALSE)
            throw new IOException('Unable to retrieve current position for resource.');

        return $result;
    }

    /**
     * Reads up to $length bytes
     *
     * @param int $length
     *
     * @return string
     *
     * @throws IOException
     */
    public function read(int $length): string
    {
        if (!is_resource($this->resource))
            throw new IOException('Invalid resource.');

        $result = fread($this->resource, $length);
        if ($result === FALSE)
            throw new IOException('Unable to read from resource.');

        return $result;
    }

    /**
     * Writes the contents of $string
     *
     * @param string   $string The string that is to be written.
     * @param int|null $length If the length argument is given, writing will stop after length bytes have been written or the end of string is reached, whichever comes first.
     *
     * @return int returns the number of bytes written
     *
     * @throws IOException
     */
    public function write(string $string, ?int $length = NULL): int
    {
        if (!is_resource($this->resource))
            throw new IOException('Invalid resource.');

        $result = ($length == NULL) ? fwrite($this->resource, $string) : fwrite($this->resource, $string, $length);
        if ($result === FALSE)
            throw new IOException('Unable to write to resource.');

        return $result;
    }

    /**
     * Set position equal to $offset bytes.
     *
     * @param int $offset
     *
     * @throws IOException
     */
    public function seekFromBeginning(int $offset): void
    {
        $this->seek($offset, SEEK_SET);
    }

    /**
     * Set position to current location plus $offset.
     *
     * @param int $offset
     *
     * @throws IOException
     */
    public function seekFromCurrentPosition(int $offset): void
    {
        $this->seek($offset, SEEK_CUR);
    }

    /**
     * Set position to end-of-file plus $offset.
     *
     * @param int $offset
     *
     * @throws IOException
     */
    public function seekFromEnd(int $offset): void
    {
        $this->seek($offset, SEEK_END);
    }

    /**
     * @param int $offset
     * @param int $whence
     *
     * @throws IOException
     */
    private function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!is_resource($this->resource))
            throw new IOException('Invalid resource.');

        $result = fseek($this->resource, $offset, $whence);
        if ($result !== 0)
            throw new IOException('Unable to seek.');
    }
}