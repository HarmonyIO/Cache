<?php declare(strict_types=1);

namespace HarmonyIO\Cache;

class Ttl
{
    public const ONE_MINUTE      = 60;
    public const FIVE_MINUTES    = self::ONE_MINUTE * 5;
    public const FIFTEEN_MINUTES = self::ONE_MINUTE * 15;
    public const HALF_HOUR       = self::ONE_MINUTE * 30;
    public const ONE_HOUR        = self::ONE_MINUTE * 60;
    public const ONE_DAY         = self::ONE_HOUR * 24;
    public const ONE_WEEK        = self::ONE_DAY * 7;
    public const THIRTY_DAYS     = self::ONE_HOUR * 30;
    public const ONE_YEAR        = self::ONE_DAY * 365;

    /** @var int */
    private $ttlInSeconds;

    public function __construct(int $ttlInSeconds)
    {
        if ($ttlInSeconds < 0) {
            throw new InvalidTtl();
        }

        $this->ttlInSeconds = $ttlInSeconds;
    }

    public static function fromDateTime(\DateTimeInterface $expirationDateTime): self
    {
        $now = new \DateTimeImmutable();

        return new self($expirationDateTime->getTimestamp() - $now->getTimestamp());
    }

    public function getTtlInSeconds(): int
    {
        return $this->ttlInSeconds;
    }
}
