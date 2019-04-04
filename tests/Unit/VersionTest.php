<?php

namespace Tests\Unit;

use App\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VersionTest extends TestCase
{
    /** @test */
    public function it_retuns_major_and_minor_version_of_a_version_string()
    {
        $this->assertEquals('v1.0', Version::minorVersion('v1.0.1'));
    }
}
