<?php

namespace App\Http\Controllers\Administrative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\TargetSheetImport;
use App\Jobs\CsvDataImport;
use App\Jobs\TestJob;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\SubUnit;

class TargetImportController extends Controller
{
    public function index()
    {
        return view('administrative.target.import.index');
    }

    public function import(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        // Handle file upload here and get the file path.
        // $filePath = $request->file('file')->storeAs('csv', 'data.csv', 'public');


        // $files =  public_path('csv/data.csv');

        // $this->handle($files);

        // Dispatch the job to process the CSV data.
        //TestJob::dispatch($filePath);

        try {
            Excel::import(new TargetSheetImport, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Handle validation errors
            $failures = $e->failures();

            return back()
                ->with('error', 'The uploaded file contains column mismatch errors.')
                ->withErrors($failures)
                ->withInput();
        }

        return redirect()->back()->with('success', 'CSV file imported successfully');
    }


    public function handle($files)
    {

        $batchSize = 5000;
        if (file_exists($files)) {

            $file = fopen($files, 'r');
            $header = fgetcsv($file); // Read and skip the header row.

            $data = [];
            while (($row = fgetcsv($file)) !== false) {
                $data[] = array_combine($header, $row);

                if (count($data) >= $batchSize) {
                    $this->insertBatch($data);
                    $data = [];
                }
            }

            fclose($file);

            if (!empty($data)) {
                $this->insertBatch($data);
            }
        }
    }


    private function insertBatch($data)
    {
        $batchSize = 5000; // Adjust the batch size as needed
        $dataBatches = array_chunk($data, $batchSize);

        foreach ($dataBatches as $batch) {
            $unitNames = array_column($batch, 'unit');

            // Eager load SubUnit and User data for all unit names in the batch
            $subUnits = SubUnit::whereIn('name', $unitNames)->get();
            $unitIdMapping = $subUnits->pluck('id', 'name')->all();

            $insertData = [];

            foreach ($batch as $record) {
                $unitName = $record['unit'];
                $unitId = $unitIdMapping[$unitName] ?? null;
                $insertData[] = [
                    'unit' => $unitId,
                    'unit_name' => $unitName,
                    'ord_no' => $record['ord_no'],
                    'cutomer_name' => $record['customer_name'],
                    'cutomer_id' => $record['customer'],
                    'terms' => $record['terms'],
                    'inst_id' => $record['inst_id'],
                    'delivery_date' => $record['delivery_date'],
                    'inst_due_date' => $record['inst_due_date'],
                    'part_no' => $record['part_no'],
                    'registration_no' => $record['registration_no'],
                    'resold' => $record['resold'],
                    'seize' => $record['seize'],
                    'vtd_status' => $record['vtd_status'],
                    'market_receivabl' => $record['market_receivable'],
                    'penalty_target' => $record['penalty_target'],
                    'target_arrear' => $record['target_arrear'],
                    'target_total' => $record['target_total'],
                    'profit_customer' => $record['profit_customer'],
                    'created_at' => now(),
                ];
            }

            DB::table('target_sheets')->insert($insertData);
        }
    }
}
