<?php

namespace domProjects\CodeIgniterBootstrap\Publishers;

use CodeIgniter\Publisher\Publisher;
use RuntimeException;

class BootstrapPublisher extends Publisher
{
    protected $source = VENDORPATH . 'twbs/bootstrap/dist';
    protected $destination = FCPATH . 'assets/bootstrap';
    protected bool $replace = false;

    public function __construct(?string $source = null, ?string $destination = null)
    {
        $destination ??= $this->destination;

        if (! is_dir($destination) && ! mkdir($destination, 0775, true) && ! is_dir($destination)) {
            throw new RuntimeException('Unable to create Bootstrap asset destination: ' . $destination);
        }

        parent::__construct($source ?? $this->source, $destination);
    }

    public function setReplace(bool $replace): self
    {
        $this->replace = $replace;

        return $this;
    }

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
