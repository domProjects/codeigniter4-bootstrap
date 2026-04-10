<?php

declare(strict_types=1);

/**
 * This file is part of domprojects/codeigniter4-bootstrap.
 *
 * (c) domProjects
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace domProjects\CodeIgniterBootstrap\Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use domProjects\CodeIgniterBootstrap\Publishers\BootstrapPublisher;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BootstrapPublisherTest extends CIUnitTestCase
{
    /**
     * @var list<string>
     */
    private array $cleanupPaths = [];

    protected function tearDown(): void
    {
        foreach (array_reverse($this->cleanupPaths) as $path) {
            $this->deletePath($path);
        }

        $this->cleanupPaths = [];

        parent::tearDown();
    }

    public function testConstructorCreatesDestinationDirectory(): void
    {
        $source      = $this->createSourceDist();
        $destination = FCPATH . 'publisher-create-' . bin2hex(random_bytes(4));

        $this->cleanupPaths[] = $destination;

        $this->assertDirectoryDoesNotExist($destination);

        $publisher = new BootstrapPublisher($source, $destination);

        $this->assertSame($destination . DIRECTORY_SEPARATOR, $publisher->getDestination());
        $this->assertDirectoryExists($destination);
    }

    public function testPublishCopiesBootstrapFiles(): void
    {
        $source      = $this->createSourceDist();
        $destination = $this->createDestinationDirectory('publisher-copy');
        $publisher   = new BootstrapPublisher($source, $destination);

        $this->assertTrue($publisher->publish());
        $this->assertSame([], $publisher->getErrors());
        $this->assertCount(4, $publisher->getPublished());
        $this->assertFileExists($destination . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'bootstrap.min.css');
        $this->assertFileExists($destination . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'bootstrap.min.css.map');
        $this->assertFileExists($destination . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'bootstrap.bundle.min.js');
        $this->assertFileExists($destination . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'bootstrap.bundle.min.js.map');
    }

    public function testPublishDoesNotOverwriteExistingFilesByDefault(): void
    {
        $source      = $this->createSourceDist();
        $destination = $this->createDestinationDirectory('publisher-no-replace');

        $existingCss = $destination . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'bootstrap.min.css';
        $existingDir = dirname($existingCss);

        if (! is_dir($existingDir)) {
            mkdir($existingDir, 0775, true);
        }

        file_put_contents($existingCss, 'old-css');

        $publisher = new BootstrapPublisher($source, $destination);

        $this->assertTrue($publisher->publish());
        $this->assertSame('old-css', file_get_contents($existingCss));
    }

    public function testPublishOverwritesExistingFilesWhenReplaceIsEnabled(): void
    {
        $source      = $this->createSourceDist();
        $destination = $this->createDestinationDirectory('publisher-replace');

        $existingCss = $destination . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'bootstrap.min.css';
        $existingDir = dirname($existingCss);

        if (! is_dir($existingDir)) {
            mkdir($existingDir, 0775, true);
        }

        file_put_contents($existingCss, 'old-css');

        $publisher = (new BootstrapPublisher($source, $destination))->setReplace(true);

        $this->assertTrue($publisher->publish());
        $this->assertSame('bootstrap-css', file_get_contents($existingCss));
    }

    private function createSourceDist(): string
    {
        $root = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'test-runtime' . DIRECTORY_SEPARATOR . 'source-' . bin2hex(random_bytes(4));

        $cssDir = $root . DIRECTORY_SEPARATOR . 'css';
        $jsDir  = $root . DIRECTORY_SEPARATOR . 'js';

        mkdir($cssDir, 0775, true);
        mkdir($jsDir, 0775, true);

        file_put_contents($cssDir . DIRECTORY_SEPARATOR . 'bootstrap.min.css', 'bootstrap-css');
        file_put_contents($cssDir . DIRECTORY_SEPARATOR . 'bootstrap.min.css.map', '{"version":3}');
        file_put_contents($jsDir . DIRECTORY_SEPARATOR . 'bootstrap.bundle.min.js', 'bootstrap-js');
        file_put_contents($jsDir . DIRECTORY_SEPARATOR . 'bootstrap.bundle.min.js.map', '{"version":3}');

        $this->cleanupPaths[] = $root;

        return $root;
    }

    private function createDestinationDirectory(string $prefix): string
    {
        $destination = FCPATH . $prefix . '-' . bin2hex(random_bytes(4));

        mkdir($destination, 0775, true);
        $this->cleanupPaths[] = $destination;

        return $destination;
    }

    private function deletePath(string $path): void
    {
        if (is_file($path) || is_link($path)) {
            @unlink($path);

            return;
        }

        if (! is_dir($path)) {
            return;
        }

        $items = scandir($path);

        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $this->deletePath($path . DIRECTORY_SEPARATOR . $item);
        }

        @rmdir($path);
    }
}
