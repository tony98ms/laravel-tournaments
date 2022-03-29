<?php

namespace Tonystore\LaravelTournaments;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class LaravelTournamentProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-tournaments.php', 'laravel-tournaments');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-tournaments.php' => config_path('laravel-tournaments.php'),
            ], 'config-tournaments');
            $this->publishes([
                __DIR__ . '/../database/migrations/create_divisions_table.php.stub' => $this->getMigrationFileName('create_divisions_table.php', 1),
                __DIR__ . '/../database/migrations/create_tournaments_table.php.stub' => $this->getMigrationFileName('create_tournaments_table.php', 2),
                __DIR__ . '/../database/migrations/create_teams_table.php.stub' => $this->getMigrationFileName('create_teams_table.php', 3),
                __DIR__ . '/../database/migrations/create_fixtures_table.php.stub' => $this->getMigrationFileName('create_fixtures_table.php', 4),
                __DIR__ . '/../database/migrations/create_results_table.php.stub' => $this->getMigrationFileName('create_results_table.php', 5),
            ], 'migrations-tournaments');
        }
    }

    protected function getMigrationFileName($migrationFileName, $number)
    {
        $timestamp = now()->addSecond('2')->format('Y_m_d_His') . $number;

        $filesystem = $this->app->make(Filesystem::class);
        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path . '*_' . $migrationFileName);
            })
            ->push($this->app->databasePath() . "/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
