<?php

namespace App\Stadium\Console\Commands;

use App\Stadium\Data\StadiumCsvProcessor;
use App\Stadium\StadiumData;
use App\Stadium\StadiumService;
use App\Stadium\Validation\StadiumCsvValidator;
use Illuminate\Console\Command;

/**
 * Usage example: `php artisan stadium:ingest my-csv-file.csv`
 */
class StadiumIngest extends Command
{
    const STATUS_SUCCESS = 0;
    const STATUS_ERROR = 1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stadium:ingest {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ingest stadium data from a CSV file.';

    /**
     * Execute the console command.
     */
    public function handle(StadiumService $stadiumService)
    {
        $filepath = $this->argument('filepath');
        if (!file_exists($filepath)) {
            $this->error("No such file or path exists: `{$filepath}`");
            return self::STATUS_ERROR;
        }

        $contents = file_get_contents($filepath);
        if (false === $contents) {
            $this->error("Could not read contents from file `{$filepath}`");
            return self::STATUS_ERROR;
        }

        $validated = StadiumCsvValidator::validate($contents);
        if (!$validated->success) {
            $this->error("Given file is malformed: " . $this->fmtValidationError($filepath, $validated->failReason));
            return self::STATUS_ERROR;
        }

        $this->line('Loading stadium data into database...');
        StadiumCsvProcessor::process($contents, function (StadiumData $data) use ($stadiumService) {
            // We use updateOrCreate() so as to not create duplicates if the same stadium is ingested more than once
            $stadiumService->updateOrCreate($data);
        });
        $this->line('Done');

        return self::STATUS_SUCCESS;
    }

    /**
     * Substitutes ":attribute" (from validation error) to the filename that generated the error.
     */
    #[Pure]
    private function fmtValidationError(string $filepath, string $reason): string
    {
        $filename = basename($filepath);
        return str_replace(':attribute', $filename, $reason);
    }
}
