<?php

namespace App\Nova\Actions;

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

class ImportDepartmentCsv extends Action
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
        $csv->setDelimiter(',');
        $csv->setHeaderOffset(0);

        collect($csv->getRecords())
            ->map(function ($record){
                return [
                    'department_code' => $record['department_code'],
                    'department_name' => $record['department_name'],
                    'region_code' => $record['region_code'],
                    'region_name' => $record['region_name'],
                ];
            })
            ->chunk(100)
            ->each(function ($records){
                Department::query()->upsert($records->toArray(), ['department_code'], ['department_name', 'region_code', 'region_name']);
            });

//        foreach ($csv->getRecords() as $record) {
//            Department::query()->updateOrCreate(['department_code' => $record['department_code']], [
//                'department_name' => $record['department_name'],
//                'region_code' => $record['region_code'],
//                'region_name' => $record['region_name'],
//                // Add more fields as needed
//            ]);
//        }
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
                ->rules('file'),
        ];
    }
}
