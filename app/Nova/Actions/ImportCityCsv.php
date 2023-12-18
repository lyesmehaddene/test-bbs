<?php

namespace App\Nova\Actions;

use App\Models\City;
use App\Models\Department;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\UnavailableStream;

class ImportCityCsv extends Action
{

    use InteractsWithQueue, Queueable;

    public $name = 'Import Csv';

    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        // Retrieve the uploaded file
        $file = $fields->file;
        // Store the uploaded file
        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $allRegions = Department::query()->get();

        collect($csv->getRecords())
            ->map(function($record) use ($allRegions) {
                return [
                    'insee_city_code' => $record['insee_city_code'],
                    'postal_city_name' => $record['postal_city_name'],
                    'postal_code' => $record['postal_code'],
                    'acheminement_name' => $record['acheminement_name'],
                    'line_5' => $record['line_5'],
                    'latitude' => is_numeric($record['latitude']) ? $record['latitude'] : null,
                    'longitude' => is_numeric($record['longitude']) ? $record['longitude'] : null,
                    'city_code' => $record['city_code'],
                    'city_full_name' => $record['city_full_name'],
                    'department_id' => $allRegions->where('department_code', $record['department_code'])->first()->id ?? 1,
                ];
                })
                ->chunk(1000)
                ->each(function ($records){
                    City::query()->upsert($records->toArray(), ['insee_city_code'], ['postal_city_name', 'postal_code', 'acheminement_name', 'line_5', 'latitude', 'longitude', 'city_code', 'city_full_name', 'department_id']);
            });

        return ActionResponse::message('CSV imported successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [
            // Add a file field for CSV upload
            File::make('file')
                ->rules('file')
                ->store(function ($request, $model) {
                    // Store the file in the 'public' disk
                    return 'public';
                }),
        ];
    }
}
