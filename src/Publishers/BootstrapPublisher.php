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

namespace domProjects\CodeIgniterBootstrap\Publishers;

use CodeIgniter\Publisher\Publisher;
use RuntimeException;

/**
 * Publishes the Bootstrap runtime assets from the Composer package to `public/`.
 */
final class BootstrapPublisher extends Publisher
{
    /**
     * Default source directory inside the Bootstrap Composer package.
     *
     * @var string
     */
    protected $source = VENDORPATH . 'twbs/bootstrap/dist';

    /**
     * Default public destination for the published Bootstrap assets.
     *
     * @var string
     */
    protected $destination = FCPATH . 'assets/bootstrap';

    /**
     * Whether existing destination files should be overwritten.
     */
    protected bool $replace = false;

    /**
     * @param string|null $source      Optional custom Bootstrap dist source directory.
     * @param string|null $destination Optional custom destination directory under the public path.
     */
    public function __construct(?string $source = null, ?string $destination = null)
    {
        $destination ??= $this->destination;

        if (! is_dir($destination) && ! mkdir($destination, 0775, true) && ! is_dir($destination)) {
            throw new RuntimeException('Unable to create Bootstrap asset destination: ' . $destination);
        }

        parent::__construct($source ?? $this->source, $destination);
    }

    /**
     * Enables or disables overwriting existing files during publication.
     */
    public function setReplace(bool $replace): self
    {
        $this->replace = $replace;

        return $this;
    }

    /**
     * Publishes the production Bootstrap CSS and JavaScript files.
     */
    public function publish(): bool
    {
        return $this->addPaths([
            'css/bootstrap.min.css',
            'css/bootstrap.min.css.map',
            'js/bootstrap.bundle.min.js',
            'js/bootstrap.bundle.min.js.map',
        ])->merge($this->replace);
    }
}
