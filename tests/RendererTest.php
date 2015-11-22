<?php

use Symfony\Component\Finder\Finder;

class RendererTest extends TestCase
{
    public function testRender()
    {
        $html = Renderer::render('test.html', ['name' => 'php']);
        $this->assertEquals("lang: php\n", $html);
    }

    public function testRenderer()
    {
        $renderer = Conf::app('renderer', 'twig');
        $this->assertEquals('twig', $renderer);
    }

    public function testTwigCacheDir()
    {
        $dir = Renderer::getCacheDir();
        $this->assertFileExists($dir);
        $this->assertTrue(is_writable($dir));
        $this->assertStringEndsWith('twig', $dir);
    }

    public function testClearCache()
    {
        $dir = Renderer::getCacheDir();

        Renderer::clearCache();

        $finder = new Finder;
        $finder->files()->name('*.php')->in($dir);

        $this->assertEquals(0, $finder->count());
    }

    public function testTwigInstance()
    {
        $obj = Renderer::getTwig();
        $this->assertInstanceOf('\Twig_Environment', $obj);
    }

    public function testAddGlobal()
    {
        $this->assertFalse(isset($globals['test-key']));

        Renderer::addGlobal('test-key', 'test-value');

        $globals = Renderer::getGlobals();
        $this->assertTrue(isset($globals['test-key']));
        $this->assertEquals('test-value', $globals['test-key']);
    }
}
