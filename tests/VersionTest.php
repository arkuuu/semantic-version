<?php
declare(strict_types=1);

namespace Arkuuu\SemanticVersion\Tests;

use Arkuuu\SemanticVersion\Version;

/**
 * @coversDefaultClass \Arkuuu\SemanticVersion\Version
 */
class VersionTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @covers ::__construct
     * @covers ::isValidValue
     */
    public function testConstruct() : void
    {
        $version = new Version('1.0.7+61');
        self::assertSame(1, $version->getMajor());
        self::assertSame(0, $version->getMinor());
        self::assertSame(7, $version->getPatch());
        self::assertSame(61, $version->getBuild());

        $version = new Version('1.0.17');
        self::assertSame(1, $version->getMajor());
        self::assertSame(0, $version->getMinor());
        self::assertSame(17, $version->getPatch());
        self::assertSame(null, $version->getBuild());

        $version = new Version('1.2');
        self::assertSame(1, $version->getMajor());
        self::assertSame(2, $version->getMinor());
        self::assertSame(null, $version->getPatch());
        self::assertSame(null, $version->getBuild());

        $version = new Version('0');
        self::assertSame(0, $version->getMajor());
        self::assertSame(null, $version->getMinor());
        self::assertSame(null, $version->getPatch());
        self::assertSame(null, $version->getBuild());
    }


    /**
     * @param $input
     * @covers ::__construct
     *
     * @dataProvider constructFromInvalidStringDataProvider
     * @throws \UnexpectedValueException
     */
    public function testConstructFromInvalidString($input) : void
    {
        self::expectException(\UnexpectedValueException::class);
        new Version($input);
    }


    public function constructFromInvalidStringDataProvider() : array
    {
        return [
            [''],
            ['foo'],
            ['1-0-1+abc'],
            ['1.0.1+abc'],
        ];
    }


    /**
     * @covers ::getMajor
     * @covers ::getMinor
     * @covers ::getPatch
     * @covers ::getBuild
     */
    public function testGetters()
    {
        $version = new Version('1.0.17');
        self::assertSame(1, $version->getMajor());
        self::assertSame(0, $version->getMinor());
        self::assertSame(17, $version->getPatch());
        self::assertSame(null, $version->getBuild());
    }


    /**
     * @covers ::__toString
     */
    public function testToString() : void
    {
        self::assertSame(
            '1.0.17+61',
            (string)(new Version('1.0.17+61'))
        );

        self::assertSame(
            '1.0.17',
            (string)(new Version('1.0.17'))
        );

        self::assertSame(
            '1.0',
            (string)(new Version('1.0'))
        );
    }

    /**
     * @covers ::isSame
     */
    public function testIsSame()
    {
        self::assertTrue((new Version('1.0.0+43'))->isSame(new Version('1.0.0+43')));
        self::assertFalse((new Version('1.0.0+43'))->isSame(new Version('1.0.0+41')));
        self::assertFalse((new Version('1.0.0+43'))->isSame(new Version('1.0.1+43')));
        self::assertFalse((new Version('1.0.0+43'))->isSame(new Version('1.1.0+43')));
        self::assertFalse((new Version('1.0.0+43'))->isSame(new Version('2.0.0+43')));
    }

    /**
     * @param $a
     * @param $b
     * @param $expected
     *
     * @throws \UnexpectedValueException
     * @dataProvider greaterOrEqualDataProvider
     * @covers ::isGreaterOrEqual
     * @covers ::compareTo
     */
    public function testIsGreaterOrEqual($a, $b, $expected) : void
    {
        $versionA = new Version($a);
        $versionB = new Version($b);

        self::assertSame($versionA->isGreaterOrEqual($versionB), $expected);
    }


    public function greaterOrEqualDataProvider() : array
    {
        return [
            ['1.1', '1.0.6', true],
            ['1.0.7', '1.0.6', true],
            ['1.0.6', '1.0.6', true],
            ['1.0.6+25', '1.0.6', true],
            ['1.0.6+25', '1.0.26', false],
            ['1.0.5', '1.0.6', false],
            ['0.19.5', '1.0.0', false],
            ['0', '1.0.7', false],
        ];
    }
}
