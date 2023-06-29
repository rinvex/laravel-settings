<?php

declare(strict_types=1);

namespace Rinvex\Settings\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'rinvex:rollback:settings')]
class RollbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rinvex:rollback:settings {--f|force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback Rinvex Settings Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $path = config('rinvex.settings.autoload_migrations') ?
            'vendor/rinvex/laravel-settings/database/migrations' :
            'database/migrations/rinvex/laravel-settings';

        if (file_exists($path)) {
            $this->call('migrate:reset', [
                '--path' => $path,
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->warn('No migrations found! Consider publish them first: <fg=green>php artisan rinvex:publish:settings</>');
        }

        $this->line('');
    }
}
