<?php

namespace App\Console\Commands;

use App\Models\Reference;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class TempAwsUrlCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tempawsurl:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(): int
    {
        /*call resource MODEL*/
        $resource_model = new Resource();
        $files_names = $resource_model->getFileNames();
        $aws_path = config('app.aws_path');

        // initially remove all unlinked resources for both Themes and Statements system. Delete dormant files from AWS

        $resource_model->delete_unlinked_Resources();
        Log::info('Unused resources deleted at '.Carbon::now());

        /*
           Database logic to store temp S3 URLs in resource table;
        */

        // For Resources Files
        foreach ($files_names as $files_name) {
            $file = $aws_path.'/documents/resources/'.$files_name->file_name;
            $temporarySignedUrl = Storage::disk('s3')->temporaryUrl($file, now()->addMinutes(1490)); //1490

            // 24 hours temporary url for AWS S3 file download
            $resource_model->updateResourceTempUrls($files_name->file_name, $temporarySignedUrl);

            Log::info('TEMP RESOURCES AWS URL '.$temporarySignedUrl.' CREATED!: '.'Filename: '.$files_name->file_name.Carbon::now());
        }

        /*call reference MODEL*/
        $reference_model = new Reference();
        $files_names = $reference_model->getFileNames();
        $aws_path = config('app.aws_path');

        // For References Files
        foreach ($files_names as $files_name) {
            $file = $aws_path.'/documents/references/'.$files_name->file_name;
            $temporarySignedUrl = Storage::disk('s3')->temporaryUrl($file, now()->addMinutes(1490));

            // 24 hours temporary url for AWS S3 file download
            $reference_model->updateReferenceTempUrls($files_name->file_name, $temporarySignedUrl);

            Log::info('TEMP REFERENCES AWS URL '.$temporarySignedUrl.' CREATED!: '.'Filename: '.$files_name->file_name.Carbon::now());
        }

        // Log::info("TEMP REFERENCES AWS URL CREATED!: ".  Carbon::now());

        return CommandAlias::SUCCESS;
    }
}
