<?php

namespace App\Http\Controllers\Administrative;

use DataTables;

use App\Models\Unit;

use App\Models\User;

use App\Models\Collection;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Exports\PreviousNotApproved;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Auth;

class PreviousNotApprovedController extends Controller
{
    public function index(Request $request)
    {
        return view('administrative.report.previous-not-approved-collection');
    }

    public function data(Request $request)
    {
        // head of depertment or admin role
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'collections.id',
                    'deposit_types.deposit_type as deposit_name',
                    'employee_id',
                    'users.name as employee_name',
                    'collections.status',
                    'users.phone as mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'hod_approved_status',
                    'deposit_date'
                )
                ->where('collections.status', 2)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }


        // zonal manager
        elseif (Auth::user()->hasRole('Zone')) {
            $unit_id = Unit::where('units.zone_id', auth()->user()->zone_id)
                ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                ->pluck('sub_units.id')
                ->toArray();
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'collections.id',
                    'deposit_types.deposit_type as deposit_name',
                    'employee_id',
                    'users.name as employee_name',
                    'collections.status',
                    'users.phone as mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'hod_approved_status',
                    'deposit_date'
                )
                ->where('collections.status', 2)
                ->whereIn('target_sheets.unit', $unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }


        //area manager
        elseif (Auth::user()->hasRole('Area')) {
            $unit_id = Unit::where('units.area_id', auth()->user()->area_id)
                ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                ->pluck('sub_units.id')
                ->toArray();
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'collections.id',
                    'deposit_types.deposit_type as deposit_name',
                    'employee_id',
                    'users.name as employee_name',
                    'collections.status',
                    'users.phone as mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'hod_approved_status',
                    'deposit_date'
                )
                ->where('collections.status', 2)
                ->whereIn('target_sheets.unit', $unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }


        //Unit Manager
        elseif (Auth::user()->hasRole('Unit')) {
            $unit_id = User::where('unit_id', auth()->user()->unit_id)->pluck('sub_unit_id')->toArray();
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'collections.id',
                    'deposit_types.deposit_type as deposit_name',
                    'employee_id',
                    'users.name as employee_name',
                    'collections.status',
                    'users.phone as mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'hod_approved_status',
                    'deposit_date'
                )
                ->where('collections.status', 2)
                ->whereIn('target_sheets.unit', $unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }

        //Sub Unit user
        else {
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'collections.id',
                    'deposit_types.deposit_type as deposit_name',
                    'employee_id',
                    'users.name as employee_name',
                    'collections.status',
                    'users.phone as mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'hod_approved_status',
                    'deposit_date'
                )
                ->where('collections.status', 2)

                ->where('collections.created_by', auth()->user()->id)
                ->where('collections.unit', auth()->user()->sub_unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }

        return Datatables::of($data)

            ->addIndexColumn()

            ->addColumn('details', function ($row) {
                $html = '';
                $html .= '<a href="' . route('collection.show', $row->id) . '" >
                                            <button class="btn btn-primary btn-sm">View</button></a>   ';
                return $html;
            })
            ->editColumn('target_arrear', function ($row) {
                return is_numeric($row->target_arrear) ? number_format($row->target_arrear) : 0;
            })
            ->editColumn('target_current', function ($row) {
                return is_numeric($row->target_current) ? number_format($row->target_current) : 0;
            })
            ->editColumn('advanced_collection', function ($row) {
                return is_numeric($row->advanced_collection) ? number_format($row->advanced_collection) : 0;
            })
            ->editColumn('collection_amount', function ($row) {
                return is_numeric($row->collection_amount) ? number_format($row->collection_amount) : 0;
            })
            ->rawColumns(['status', 'details', 'action', 'target_arrear', 'target_current', 'advanced_collection', 'collection_amount'])
            ->make(true);
    }

    public function download(Request $request)
    {
        // head of depertment or admin role
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'employee_id as Employee Id',
                    'users.name as Employee Name',
                    'users.phone as Mobile No',
                    'sub_units.name as Unit',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Target Arrear',
                    'target_current as Target Current',
                    'advanced_collection as Advanced Collection',
                    'deposit_types.deposit_type as Deposit Name',
                    'collection_amount as Collection Amount',
                )
                ->where('collections.status', 2)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }


        // zonal manager
        elseif (Auth::user()->hasRole('Zone')) {
            $unit_id = Unit::where('units.zone_id', auth()->user()->zone_id)
                ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                ->pluck('sub_units.id')
                ->toArray();
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'employee_id as Employee Id',
                    'users.name as Employee Name',
                    'users.phone as Mobile No',
                    'sub_units.name as Unit',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Target Arrear',
                    'target_current as Target Current',
                    'advanced_collection as Advanced Collection',
                    'deposit_types.deposit_type as Deposit Name',
                    'collection_amount as Collection Amount',
                )
                ->where('collections.status', 2)
                ->whereIn('target_sheets.unit', $unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }


        //area manager
        elseif (Auth::user()->hasRole('Area')) {
            $unit_id = Unit::where('units.area_id', auth()->user()->area_id)
                ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                ->pluck('sub_units.id')
                ->toArray();
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'employee_id as Employee Id',
                    'users.name as Employee Name',
                    'users.phone as Mobile No',
                    'sub_units.name as Unit',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Target Arrear',
                    'target_current as Target Current',
                    'advanced_collection as Advanced Collection',
                    'deposit_types.deposit_type as Deposit Name',
                    'collection_amount as Collection Amount',
                )
                ->where('collections.status', 2)
                ->whereIn('target_sheets.unit', $unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }


        //Unit Manager
        elseif (Auth::user()->hasRole('Unit')) {
            $unit_id = User::where('unit_id', auth()->user()->unit_id)->pluck('sub_unit_id')->toArray();
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'employee_id as Employee Id',
                    'users.name as Employee Name',
                    'users.phone as Mobile No',
                    'sub_units.name as Unit',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Target Arrear',
                    'target_current as Target Current',
                    'advanced_collection as Advanced Collection',
                    'deposit_types.deposit_type as Deposit Name',
                    'collection_amount as Collection Amount',
                )
                ->where('collections.status', 2)
                ->whereIn('target_sheets.unit', $unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }

        //Sub Unit user
        else {
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'employee_id as Employee Id',
                    'users.name as Employee Name',
                    'users.phone as Mobile No',
                    'sub_units.name as Unit',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Target Arrear',
                    'target_current as Target Current',
                    'advanced_collection as Advanced Collection',
                    'deposit_types.deposit_type as Deposit Name',
                    'collection_amount as Collection Amount',
                )
                ->where('collections.status', 2)

                ->where('collections.created_by', auth()->user()->id)
                ->where('collections.unit', auth()->user()->sub_unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }

        $results = $data;

        $date_str = date('Y_m_d_H_i_s');

        return Excel::download(new PreviousNotApproved($results), $date_str . 'previous_not_approved.xlsx');
    }
}
