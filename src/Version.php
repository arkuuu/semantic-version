<?php
declare(strict_types=1);

namespace Arkuuu\SemanticVersion;

class Version
{

    /**
     * @var int
     */
    private $major;

    /**
     * @var int|null
     */
    private $minor;

    /**
     * @var int|null
     */
    private $patch;

    /**
     * @var int|null
     */
    private $build;


    /**
     * @param string $version
     *
     * @throws \UnexpectedValueException If unable to parse version string
     */
    public function __construct(string $version)
    {
        list($number, $build) = array_pad(explode('+', $version), 2, null);
        list($major, $minor, $patch) = array_pad(explode('.', $number), 3, null);

        if (!is_numeric($major)
            || !self::isValidValue($minor)
            || !self::isValidValue($patch)
            || !self::isValidValue($build)
        ) {
            throw new \UnexpectedValueException(
                'Unable to parse version string ' . $version
            );
        }

        $this->major = (int)$major;
        $this->minor = isset($minor) ? (int)$minor : null;
        $this->patch = isset($patch) ? (int)$patch : null;
        $this->build = isset($build) ? (int)$build : null;
    }


    private static function isValidValue($number) : bool
    {
        if ($number === null
            || $number === 0
            || $number === '0'
        ) {
            return true;
        }

        return is_numeric($number);
    }


    public function getMajor() : int
    {
        return $this->major;
    }


    public function getMinor() : ?int
    {
        return $this->minor;
    }


    public function getPatch() : ?int
    {
        return $this->patch;
    }


    public function getBuild() : ?int
    {
        return $this->build;
    }


    public function __toString() : string
    {
        $version = $this->major;

        if (isset($this->minor)) {
            $version .= '.' . $this->minor;
        }
        if (isset($this->patch)) {
            $version .= '.' . $this->patch;
        }
        if (isset($this->build)) {
            $version .= '+' . $this->build;
        }

        return $version;
    }


    public function isSame(Version $that) : bool
    {
        return $this->major === $that->major
            && $this->minor === $that->minor
            && $this->patch === $that->patch
            && $this->build === $that->build;
    }


    public function isGreaterOrEqual(Version $that) : bool
    {
        return $this->compareTo($that) >= 0;
    }


    public function compareTo(Version $that) : int
    {
        $thisParts = [$this->major, $this->minor, $this->patch, $this->build];
        $thatParts = [$that->major, $that->minor, $that->patch, $that->build];

        for ($i = 0; $i < count($thisParts); $i++) {
            $thisPart = $thisParts[$i] ?? 0;
            $thatPart = $thatParts[$i] ?? 0;
            if ($thisPart < $thatPart) {
                return -1;
            }
            if ($thisPart > $thatPart) {
                return 1;
            }
        }

        return 0;
    }
}
