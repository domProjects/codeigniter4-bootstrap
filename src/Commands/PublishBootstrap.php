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

namespace domProjects\CodeIgniterBootstrap\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use domProjects\CodeIgniterBootstrap\Publishers\BootstrapPublisher;
use Throwable;

/**
 * Spark command used to publish Bootstrap distribution assets.
 */
class PublishBootstrap extends BaseCommand
{
    /**
     * Command group shown in the Spark command list.
     *
     * @var string
     */
    protected $group = 'Bootstrap';

    /**
     * Command name used from the CLI.
     *
     * @var string
     */
    protected $name = 'assets:publish-bootstrap';

    /**
     * Short description displayed in Spark help.
     *
     * @var string
     */
    protected $description = 'Publishes Bootstrap 5 assets to public/assets/bootstrap.';

    /**
     * Usage string displayed in Spark help.
     *
     * @var string
     */
    protected $usage = 'assets:publish-bootstrap [--force]';

    /**
     * Supported command options.
     *
     * @var array<string, string>
     */
    protected $options = [
        '--force' => 'Overwrite existing files.',
        '-f'      => 'Alias of --force.',
    ];

    /**
     * Publishes the Bootstrap assets to the configured public destination.
     *
     * When `--force` is provided, existing files are overwritten.
     *
     * @param array<int|string, string|null> $params
     *
     * @return int CLI exit status code.
     */
    public function run(array $params): int
    {
        $force = array_key_exists('force', $params)
            || array_key_exists('f', $params)
            || CLI::getOption('force') !== null
            || CLI::getOption('f') !== null;

        $publisher = (new BootstrapPublisher())->setReplace($force);

        CLI::write(
            'Publishing Bootstrap assets to ' . $publisher->getDestination() . ($force ? ' with overwrite...' : '...'),
            'yellow',
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
