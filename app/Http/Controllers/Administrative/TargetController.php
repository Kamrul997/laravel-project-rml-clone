<?php

namespace App\Http\Controllers\Administrative;

use DataTables;
use App\Models\Unit;
use App\Models\SubUnit;
use App\Models\User;
use App\Models\Target;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Exports\TargetExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class TargetController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('target_list')) {
            return abort(401);
        }

        if ($request->ajax()) {

            // head of depertment or admin role
            if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
                $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                    ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                    ->select('target_sheets.*', 'sub_units.name', 'users.employee_id', 'users.name as employee_name');
            }

            // zonal manager
            elseif (Auth::user()->hasRole('Zone')) {
                $unit_id = Unit::where('units.zone_id', auth()->user()->zone_id)
                    ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                    ->pluck('sub_units.id')
                    ->toArray();
                $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                    ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                    ->select('target_sheets.*', 'sub_units.name', 'users.employee_id', 'users.name as employee_name')
                    ->whereIn('unit', $unit_id);
            }

            //area manager
            elseif (Auth::user()->hasRole('Area')) {
                $unit_id = Unit::where('units.area_id', auth()->user()->area_id)
                    ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                    ->pluck('sub_units.id')
                    ->toArray();
                $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                    ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                    ->select('target_sheets.*', 'sub_units.name', 'users.employee_id', 'users.name as employee_name')
                    ->whereIn('unit', $unit_id);
            }

            //Unit Manager
            elseif (Auth::user()->hasRole('Unit')) {
                $unit_id = User::where('unit_id', auth()->user()->unit_id)->pluck('sub_unit_id')->toArray();
                $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                    ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                    ->select('target_sheets.*', 'sub_units.name', 'users.employee_id', 'users.name as employee_name')
                    ->whereIn('unit', $unit_id);
            }

            //Sub Unit user
            else {
                $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                    ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                    ->select('target_sheets.*', 'sub_units.name', 'users.employee_id', 'users.name as employee_name')
                    ->where('target_sheets.unit', auth()->user()->sub_unit_id);
            }

            $data =  $data->whereYear('target_sheets.created_at', date('Y'))
                ->whereMonth('target_sheets.created_at', date('m'))
                ->orderBy('target_sheets.id', 'DESC');


            return Datatables::of($data)
                ->filterColumn('ord_no', function ($query, $keyword) {
                    $sql = "ord_no  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('cutomer_name', function ($query, $keyword) {
                    $sql = "cutomer_name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })

                ->filterColumn('employee_id', function ($query, $keyword) {
                    $sql = "users.employee_id  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })

                ->filterColumn('employee_name', function ($query, $keyword) {
                    $sql = "users.name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })

                ->addIndexColumn()

                ->addColumn('cust_info', function ($row) {
                    $html = '';
                    $html .= '<a href="' . route('customer.show', $row->ord_no) . '" >
                                        <button class="btn btn-primary btn-sm">View</button></a>';
                    return $html;
                })

                ->addColumn('target_show', function ($row) {
                    $html = '';
                    $html .= '<a href="' . route('administrative.target_show', $row->id) . '" >
                                        <button class="btn btn-primary btn-sm">View</button></a>';
                    return $html;
                })

                ->addColumn('ledger', function ($row) {
                    $html = '';
                    $html .= '<a href="#">
                    <button class="btn btn-primary btn-sm">View</button></a>';
                    return $html;
                })
                ->editColumn('market_receivabl', function ($row) {
                    if (is_numeric($row->market_receivabl)) {
                        $formattednumber = number_format($row->market_receivabl);
                    } else {
                        $formattednumber = 0;
                    }
                    return $formattednumber;
                })
                ->editColumn('target_arrear', function ($row) {
                    if (is_numeric($row->target_arrear)) {
                        $formattednumber = number_format($row->target_arrear);
                    } else {
                        $formattednumber = 0;
                    }
                    return $formattednumber;
                })
                ->editColumn('target_current', function ($row) {
                    if (is_numeric($row->target_current)) {
                        $formattednumber = number_format($row->target_current);
                    } else {
                        $formattednumber = 0;
                    }
                    return $formattednumber;
                })
                ->editColumn('penalty_target', function ($row) {
                    if (is_numeric($row->penalty_target)) {
                        $formattednumber = number_format($row->penalty_target);
                    } else {
                        $formattednumber = 0;
                    }
                    return $formattednumber;
                })
                ->editColumn('target_total', function ($row) {
                    if (is_numeric($row->target_total)) {
                        $formattednumber = number_format($row->target_total);
                    } else {
                        $formattednumber = 0;
                    }
                    return $formattednumber;
                })

                ->rawColumns(['cust_info', 'target_show', 'ledger', 'market_receivabl', 'target_arrear', 'target_current', 'penalty_target', 'target_total'])
                ->make(true);
        }

        return view('administrative.target.index');
    }

    public function data(Request $request)
    {
        if (!Gate::allows('target_list')) {
            return abort(401);
        }
        return $this->getAllData($request);
    }

    public function getAllData(Request $request)
    {

        // head of depertment or admin role
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
            $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select('target_sheets.*', 'sub_units.name', 'users.employee_id', 'users.name as employee_name');
        }

        // zonal manager
        elseif (Auth::user()->hasRole('Zone')) {
            $unit_id = Unit::where('units.zone_id', auth()->user()->zone_id)
                ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                ->pluck('sub_units.id')
                ->toArray();
            $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->whereIn('unit', $unit_id)
                ->select('target_sheets.*', 'sub_units.name', 'users.employee_id', 'users.name as employee_name');
        }

        //area manager
        elseif (Auth::user()->hasRole('Area')) {
            $unit_id = Unit::where('units.area_id', auth()->user()->area_id)
                ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                ->pluck('sub_units.id')
                ->toArray();
            $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->whereIn('unit', $unit_id)
                ->select('target_sheets.*', 'sub_units.name', 'users.employee_id', 'users.name as employee_name');
        }

        //Unit Manager
        elseif (Auth::user()->hasRole('Unit')) {
            $unit_id = User::where('unit_id', auth()->user()->unit_id)->pluck('sub_unit_id')->toArray();
            $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->whereIn('unit', $unit_id)
                ->select('target_sheets.*', 'sub_units.name', 'users.employee_id', 'users.name as employee_name');
        }

        //Sub Unit user
        else {
            $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->where('employee_id', auth()->user()->employee_id)
                ->where('target_sheets.unit', auth()->user()->sub_unit_id)
                ->select('target_sheets.*', 'sub_units.name', 'users.employee_id', 'users.name as employee_name');
        }

        $requestData = $request->all();
        if ($request->has('query') && !empty($requestData['query'])) {
            $data = $data->where('cutomer_name', 'LIKE', '%' . $requestData['query'] . '%')
                ->orWhere('employee_name', 'LIKE', '%' . $requestData['query'] . '%')
                ->orWhere('mobile_no', 'LIKE', '%' . $requestData['query'] . '%')
                ->orWhere('employee_id', 'LIKE', '%' . $requestData['query'] . '%');
        }
        $data = $data->whereYear('target_sheets.created_at', date('Y'))
            ->whereMonth('target_sheets.created_at', date('m'))
            ->get();
    }


    public function showData($id)
    {

        $data = Target::findOrFail($id);
        $unit = SubUnit::find($data->unit);

        return view('administrative.target.show', compact('data', 'unit'));
    }

    public function getBranch(Request $request)
    {
        $data = Branch::where('bank_id', $request->id)->get();
        return response()->json($data);
    }

    public function download()
    {
        if (!Gate::allows('target_list')) {
            return abort(401);
        }

        // head of depertment or admin role
        if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('National') || auth()->user()->hasRole('Accounts')) {
            $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'users.employee_id as Employee Id',
                    'users.name as Employee Name',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Arrear Collection',
                    'target_current as Current Collection',
                    'market_receivabl as Market Receiveable',
                    'target_total as Total Collection',
                    'sub_units.name as Unit Name'
                )
                ->whereYear('target_sheets.created_at', date('Y'))
                ->whereMonth('target_sheets.created_at', date('m'));
        }

        // zonal manager
        elseif (auth()->user()->hasRole('Zone')) {
            $unit_id = Unit::where('units.zone_id', auth()->user()->zone_id)
                ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                ->pluck('sub_units.id')
                ->toArray();
            $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'users.employee_id as Employee Id',
                    'users.name as Employee Name',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Arrear Collection',
                    'target_current as Current Collection',
                    'market_receivabl as Market Receiveable',
                    'target_total as Total Collection',
                    'sub_units.name as Unit Name'
                )
                ->whereIn('unit', $unit_id)

                ->whereYear('target_sheets.created_at', date('Y'))
                ->whereMonth('target_sheets.created_at', date('m'));
        }

        //area manager
        elseif (auth()->user()->hasRole('Area')) {
            $unit_id = Unit::where('units.area_id', auth()->user()->area_id)
                ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                ->pluck('sub_units.id')
                ->toArray();
            $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'users.employee_id as Employee Id',
                    'users.name as Employee Name',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Arrear Collection',
                    'target_current as Current Collection',
                    'market_receivabl as Market Receiveable',
                    'target_total as Total Collection',
                    'sub_units.name as Unit Name'
                )
                ->whereIn('unit', $unit_id)

                ->whereYear('target_sheets.created_at', date('Y'))
                ->whereMonth('target_sheets.created_at', date('m'));
        }

        //Unit Manager
        elseif (auth()->user()->hasRole('Unit')) {
            $unit_id = User::where('unit_id', auth()->user()->unit_id)->pluck('sub_unit_id')->toArray();
            $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'users.employee_id as Employee Id',
                    'users.name as Employee Name',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Arrear Collection',
                    'target_current as Current Collection',
                    'market_receivabl as Market Receiveable',
                    'target_total as Total Collection',
                    'sub_units.name as Unit Name'
                )
                ->whereIn('unit', $unit_id)

                ->whereYear('target_sheets.created_at', date('Y'))
                ->whereMonth('target_sheets.created_at', date('m'));
        }

        //Sub Unit user
        else {
            $data = Target::leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->select(
                    'users.employee_id as Employee Id',
                    'users.name as Employee Name',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Arrear Collection',
                    'target_current as Current Collection',
                    'market_receivabl as Market Receiveable',
                    'target_total as Total Collection',
                    'sub_units.name as Unit Name'
                )
                ->where('unit', auth()->user()->sub_unit_id)
                ->whereYear('target_sheets.created_at', date('Y'))
                ->whereMonth('target_sheets.created_at', date('m'));
        }

        $results = $data->get();

        $date_str = date('Y_m_d_H_i_s');

        return Excel::download(new TargetExport($results), $date_str . 'target_sheet.xlsx');
    }
}
