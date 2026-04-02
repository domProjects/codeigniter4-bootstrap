<?php

namespace domProjects\CodeIgniterBootstrap\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use domProjects\CodeIgniterBootstrap\Publishers\BootstrapPublisher;
use Throwable;

class PublishBootstrap extends BaseCommand
{
    protected $group = 'Bootstrap';
    protected $name = 'assets:publish-bootstrap';
    protected $description = 'Publishes Bootstrap 5 assets to public/assets/bootstrap.';
    protected $usage = 'assets:publish-bootstrap [--force]';
    protected $options = [
        '--force' => 'Overwrite existing files.',
        '-f'      => 'Alias of --force.',
    ];

    public function run(array $params)
    {
        $force = array_key_exists('force', $params)
            || array_key_exists('f', $params)
            || CLI::getOption('force') !== null
            || CLI::getOption('f') !== null;

        $publisher = (new BootstrapPublisher())->setReplace($force);

        CLI::write(
            'Publishing Bootstrap assets to ' . $publisher->getDestination() . ($force ? ' with overwrite...' : '...'),
            'yellow'
        );

        try {
            if (! $publisher->publish()) {
                CLI::error('Bootstrap publish failed.');

                foreach ($publisher->getErrors() as $file => $exception) {
                    CLI::write($file);
                    CLI::error($exception->getMessage());
                    CLI::newLine();
                }

                return EXIT_ERROR;
            }
        } catch (Throwable $e) {
            $this->showError($e);

            return EXIT_ERROR;
        }

        CLI::write('Bootstrap assets published successfully.', 'green');

        return EXIT_SUCCESS;
    }
}
