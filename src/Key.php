<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

class Key
{
    private const DELIMITER = '_';

    private const APPLICATION_KEY = 'HarmonyIO';

    /** @var string */
    private $type;

    /** @var string */
    private $source;

    /** @var string */
    private $hash;

    public function __construct(string $type, string $source, string $hash)
    {
        $this->type   = $type;
        $this->source = $source;
        $this->hash   = $hash;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function __toString(): string
    {
        // HarmonyIO_HttpRequest_Validation::NotPwnedPassword_hash
        return implode(self::DELIMITER, [
            self::APPLICATION_KEY,
            $this->type,
            $this->source,
            $this->hash,
        ]);
    }
}
