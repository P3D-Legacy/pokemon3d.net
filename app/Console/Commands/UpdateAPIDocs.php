<?php

namespace App\Console\Commands;

use cebe\openapi\exceptions\IOException;
use cebe\openapi\exceptions\TypeErrorException;
use cebe\openapi\exceptions\UnresolvableReferenceException;
use cebe\openapi\Reader;
use cebe\openapi\Writer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UpdateAPIDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the API documentation in JSON format from the OpenAPI documentation in YAML format.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // Generate Scribe API Docs
        Artisan::call('scribe:generate');

        // Get YAML from storage
        $file_path = storage_path('app/scribe/openapi.yaml');
        try {
            $openapi = Reader::readFromYamlFile($file_path);
        } catch (IOException|TypeErrorException|UnresolvableReferenceException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }
        $json = Writer::writeToJson($openapi);
        // Save JSON to storage
        $file_path = storage_path('app/scribe/openapi.json');
        file_put_contents($file_path, $json);
        $this->info('API documentation updated.');

        return Command::SUCCESS;
    }
}
