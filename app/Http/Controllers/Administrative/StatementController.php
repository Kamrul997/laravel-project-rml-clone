<?php

namespace App\Http\Controllers\Administrative;

use DataTables;

use App\Models\Unit;

use App\Imports\BankImport;

use Illuminate\Http\Request;

use App\Models\DailyForecast;

use App\Models\TrackImporter;

use App\Jobs\CustAccStatement;

use App\Models\MonthlyForecast;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AccountStatementImport;
use App\Models\AccountStatement;
use App\Models\Customer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class StatementController extends Controller
{
    public function account(Request $request)
    {
        return view('administrative.statement.account');
    }
    public function final(Request $request)
    {
        return view('administrative.statement.final');
    }

    public function accountStatement($id)
    {
        $data['accountStatement'] = AccountStatement::where('order_id', $id)->orderBy('vch_date', 'asc')->get();
        $data['customer'] = Customer::where('order_no', $id)->first();
        if ($data['accountStatement']->count() <= 0) {
            return redirect()->back()->with('error', 'Data not found!');
        }
        if ($data['customer']->count() <= 0) {
            return redirect()->back()->with('error', 'Data not found!');
        }
        $data['debit'] = $data['accountStatement']->map(function ($item) {
            return (float)$item->debit_amount;
        })->sum();
        $data['credit'] = $data['accountStatement']->map(function ($item) {
            return (float)$item->credit_amount;
        })->sum();

        $pdf = PDF::loadView('administrative.statement.cust_statement_pdf', $data);

        return $pdf->download('statement.pdf');
        // return $pdf->stream('news_letter.pdf', 'D');
    }

    public function finalStatement(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response(
                    [
                        'message' => 'Validation errors',
                        'errors' => $validator->errors(),
                        'status' => false
                    ],
                    422
                );
            }

            $payload = [
                'code' => 200,
                'app_message' => 'success',
                'user_message' => 'success',
                'download_path' => "https://rml.singularitybd.co/excels/customers/statement.pdf",
            ];

            return response()->json($payload, 200);
        } catch (\Throwable $th) {
            $payload = [
                'code' => 500,
                'app_message' => 'Something went wrong.',
                'user_message' => 'Something went wrong.',
                'data' => $th->getMessage()
            ];
            return response()->json($payload, 500);
        }
    }

    public function importIndex()
    {
        $file = DB::table('track_importers')->where('type', 'account_statement')->orderBy('id', 'desc')->first();

        if (($file && $file->status == 'successful')) {
            $fileToDelete = $file->file_url;

            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }
        }

        return view('administrative.customer.import.account-statement', compact('file'));
    }

    public function importAccountStatement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        $filename = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file('file')->move(public_path('temp'), $filename);


        $id = DB::table('track_importers')
            ->insertGetId(
                [
                    'user_id' => auth()->user()->id,
                    'type' => 'account_statement',
                    'file_url' => 'temp/' . $filename,
                    'file_original_name' => $request->file->getClientOriginalName(),
                    'status' => 'pending',
                    'created_at' => now(),
                ]
            );

        // CustAccStatement::dispatch($id);
        // CustAccStatement::dispatch($id)->unique('account-statement-'.$id);
        Artisan::call('import:cust-acc-statement', ['id' => $id]);

        return redirect()->back()->with('success', 'CSV file imported soon');
    }
}
