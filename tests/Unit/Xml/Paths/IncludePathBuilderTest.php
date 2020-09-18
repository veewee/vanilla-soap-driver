<?php

declare(strict_types=1);

namespace WsdlTools\Test\Unit\Xml\Paths;

use PHPUnit\Framework\TestCase;
use WsdlTools\Xml\Paths\IncludePathBuilder;

/**
 * @covers \WsdlTools\Xml\Paths\IncludePathBuilder
 */
class IncludePathBuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideBuildPaths
     */
    public function it_can_build_include_paths(string $relativePath, string $fromFile, string $expected): void
    {
        $builder = new IncludePathBuilder();

        self::assertSame($expected, $builder($relativePath, $fromFile));
    }

    public function provideBuildPaths()
    {
        yield 'same-dir-file' => [
            'relativePath' => 'otherfile.xml',
            'fromFile' => 'somedir/somefile.xml',
            'expected' => 'somedir/otherfile.xml',
        ];
        yield 'child-dir-file' => [
            'relativePath' => '../otherfile.xml',
            'fromFile' => 'somedir/child/somefile.xml',
            'expected' => 'somedir/otherfile.xml',
        ];
        yield 'http-file' => [
            'relativePath' => 'otherfile.xml',
            'fromFile' => 'http://localhost/somedir/somefile.xml',
            'expected' => 'http://localhost/somedir/otherfile.xml',
        ];
        yield 'http-dir-file' => [
            'relativePath' => '../otherfile.xml',
            'fromFile' => 'http://localhost/somedir/child/somefile.xml',
            'expected' => 'http://localhost/somedir/otherfile.xml',
        ];

    }
}
