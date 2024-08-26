<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Package;

class PhonebookServiceProvider extends ServiceProvider
{

    public static string $name = 'phonebook';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasMigrations(['create_groups_table', 'create_contacts_table', 'create_phone_numbers_table'])
            ->hasViews()
            ->hasTranslations();
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
