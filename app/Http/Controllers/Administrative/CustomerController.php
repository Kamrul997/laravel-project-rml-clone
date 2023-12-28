<?php

namespace App\Http\Controllers\Administrative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\CollectionStatement;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomerImport;
use App\Imports\BankImport;
use Illuminate\Support\Facades\Validator;
use DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
                $data = Customer::
                orderBy('customers.id','DESC');

            return Datatables::of($data)
            ->filterColumn('order_no', function ($query, $keyword) {
                $sql = "order_no  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('customer_name', function ($query, $keyword) {
                $sql = "customer_name  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('mobile_no', function ($query, $keyword) {
                $sql = "mobile_no  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
                ->addIndexColumn()

                ->addColumn('details', function ($row) {
                    $html = '';
                    $html .= '<a href="' . route('customer.show', $row->id) . '" >
                                            <button class="btn btn-primary btn-sm">View</button></a>';
                    return $html;
                })
                ->rawColumns(['status', 'details'])
                ->make(true);
        }
        return view('administrative.customer.index');
    }

    public function importIndex(){
        return view('administrative.customer.import.index');
    }

    public function import(Request $request){

        // $validator = Validator::make($request->all(), [
        //     'file' => 'required|mimes:csv',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withInput()
        //         ->withErrors($validator);
        // }

        try {
            Excel::import(new CustomerImport, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return back()
                ->with('error', 'The uploaded file contains column mismatch errors.')
                ->withErrors($failures)
                ->withInput();
        }
        return redirect()->back()->with('success', 'CSV file imported successfully');
    }

    public function importBank(){
        return view('administrative.customer.import.bank');
    }

    public function importBankSheet(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        try {
            Excel::import(new BankImport, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return back()
                ->with('error', 'The uploaded file contains column mismatch errors.')
                ->withErrors($failures)
                ->withInput();
        }
        return redirect()->back()->with('success', 'CSV file imported successfully');
    }

    public function download()
    {
        $data = Customer::
        orderBy('customers.id','DESC')
        ->get();

        $results = $data;

        $date_str = date('Y_m_d_H_i_s');

        return Excel::download(new CustomerExport($results),   $date_str.'customer.xlsx');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Customer::findOrFail($id);
        return view('administrative.customer.show',compact('data'));
    }

    public function custInfo($id){
        $data = Customer::where('order_no',$id)->first();
        if(isset($data)){
            return view('administrative.customer.show',compact('data'));
        }else{
            return redirect()->back()->with('error','Order id not found');
        }

    }

    public function statement()
    {
        $data = CollectionStatement::first();
        $unit = Unit::find($data->unit_id);
        return view('administrative.customer.statement',compact('data','unit'));
    }

    public function paymentSchedule(){

        return view('administrative.customer.payment-schedule');
    }
}
