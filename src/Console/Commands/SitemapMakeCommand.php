<?php

namespace Cher4geo35\Sitemap\Console\Commands;


use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;

class SitemapMakeCommand extends BaseConfigModelCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sitemap
                    {--all : Run all}
                    {--controllers : Export controllers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $vendorName = 'Cher4geo35';
    protected $packageName = "Sitemap";

    protected $controllers = [
        'Site' => ['SitemapXmlController'],
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $all = $this->option("all");

        if ($this->option("controllers") || $all) {
            $this->exportControllers("Site");
        }
    }

}
