<?php

namespace Tests\Unit;

use App\Version;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    /** @test */
    public function it_retuns_major_and_minor_version_of_a_version_string()
    {
        $this->assertEquals('v1.0', Version::minorVersion('v1.0.1'));
    }

    /** @test */
    public function it_returns_6_0_for_a_newer_laravel_version()
    {
        $this->assertEquals(true, Version::isLaravelVersion('v6.15.0'));

        $this->assertEquals('v6.0', Version::minorVersion('v6.15.0'));

        $this->assertEquals('v7.0', Version::minorVersion('v7.10.0'));
    }
}
