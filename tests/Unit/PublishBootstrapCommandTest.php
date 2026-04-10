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

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class PublishBootstrapCommandTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->deletePath(FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'bootstrap');
    }

    protected function tearDown(): void
    {
        $this->deletePath(FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'bootstrap');

        parent::tearDown();
    }

    public function testCommandPublishesBootstrapAssets(): void
    {
        command('assets:publish-bootstrap');

        $this->assertFileExists(FCPATH . 'assets/bootstrap/css/bootstrap.min.css');
        $this->assertFileExists(FCPATH . 'assets/bootstrap/js/bootstrap.bundle.min.js');
    }

    public function testCommandForceOptionOverwritesExistingFiles(): void
    {
        $destinationFile = FCPATH . 'assets/bootstrap/css/bootstrap.min.css';
        $destinationDir  = dirname($destinationFile);

        if (! is_dir($destinationDir)) {
            mkdir($destinationDir, 0775, true);
        }

        file_put_contents($destinationFile, 'modified-css');

        command('assets:publish-bootstrap --force');
        $source = file_get_contents(VENDORPATH . 'twbs/bootstrap/dist/css/bootstrap.min.css');

        $this->assertSame($source, file_get_contents($destinationFile));
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
