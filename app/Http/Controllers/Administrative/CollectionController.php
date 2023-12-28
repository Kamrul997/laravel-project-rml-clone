<?php

namespace App\Http\Controllers\Administrative;

use Collator;

use DataTables;

use App\Models\Bank;

use App\Models\Unit;

use App\Models\User;

use App\Models\Branch;

use App\Models\Target;

use App\Models\Collection;

use App\Models\DepositType;

use App\Models\MakePayment;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

use App\Exports\PaymentExport;

use App\Exports\CustomerExport;

use App\Exports\CollectionExport;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\CollectionAttachment;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Traits\MakePaymentTrait;
use App\Exports\PendingCollectionExport;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\SendNotificationTrait;
use App\Exports\DepositDatewiseCollectionExport;
use App\Models\DisapprovedNote;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use SendNotificationTrait, MakePaymentTrait;

    public function paymentIndex(Request $request)
    {
        return view('administrative.collection.make-payment');
    }

    public function paymentData(Request $request)
    {

        // head of depertment or admin role
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
            $data = MakePayment::select('make_payments.*');
        }

        $data =  $data->whereYear('make_payments.created_at', date('Y'))
            ->whereMonth('make_payments.created_at', date('m'))
            ->orderBy('make_payments.id', 'DESC');

        $requestData = $request->all();

        if (!empty($requestData['start_date']) && empty($requestData['end_date'])) {
            $data = $data->whereDate('make_payments.created_at', $requestData['start_date']);
        }

        if (!empty($requestData['start_date']) && !empty($requestData['end_date'])) {
            $data = $data->whereBetween('make_payments.created_at', [$requestData['start_date'], $requestData['end_date']]);
        }

        return Datatables::of($data)
            ->filterColumn('order_no', function ($query, $keyword) {
                $sql = "order_no  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('cutomer_name', function ($query, $keyword) {
                $sql = "cutomer_name  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('deposit_date', function ($query, $keyword) {
                $sql = "deposit_date  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('gl_code', function ($query, $keyword) {
                $sql = "gl_code  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('bank_name', function ($query, $keyword) {
                $sql = "bank_name  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('branch_name', function ($query, $keyword) {
                $sql = "branch_name  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('deposit_type', function ($query, $keyword) {
                $sql = "deposit_type  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->editColumn('total', function ($row) {
                return is_numeric($row->total) ? number_format($row->total) : 0;
            })
            ->rawColumns(['created_at', 'total'])
            ->addIndexColumn()
            ->make(true);
    }

    public function paymentDownload(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        // head of depertment or admin role
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
            $data = MakePayment::select(
                'make_payments.order_no',
                'make_payments.customer_name',
                'make_payments.gl_code',
                'make_payments.total',
                'make_payments.deposit_date',
                'make_payments.deposit_type',
                'make_payments.bank_name',
                'make_payments.branch_name',
                'make_payments.naration',
                'make_payments.created_at',
            );
        }

        $data =  $data->whereYear('make_payments.created_at', date('Y'))
            ->whereMonth('make_payments.created_at', date('m'))
            ->orderBy('make_payments.id', 'DESC');

        // if (!empty($requestData['start_date']) && empty($requestData['end_date'])) {
        //     $data = $data->whereDate('make_payments.created_at', $requestData['start_date']);
        // }

        if (!empty($start) && !empty($end)) {
            $data = $data->whereBetween('make_payments.created_at', [$start, $end]);
        }

        $data = $data->get();

        $results = $data;

        $date_str = date('Y_m_d_H_i_s');

        return Excel::download(new PaymentExport($results), $date_str . 'payment.xlsx');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // head of depertment or admin role
            if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
                $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                    ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                    ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                    ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                    ->select(
                        'collections.id',
                        'deposit_types.deposit_type as deposit_name',
                        'employee_id',
                        'users.name as employee_name',
                        'collections.status',
                        'mobile_no',
                        'target_sheets.unit',
                        'sub_units.name as unit_id',
                        'ord_no',
                        'cutomer_name',
                        'target_arrear',
                        'target_current',
                        'advanced_collection',
                        'collection_amount',
                        'hod_approved_status'
                    )
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
                    ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                    ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                    ->select(
                        'collections.id',
                        'deposit_types.deposit_type as deposit_name',
                        'employee_id',
                        'users.name as employee_name',
                        'collections.status',
                        'mobile_no',
                        'target_sheets.unit',
                        'sub_units.name as unit_id',
                        'ord_no',
                        'cutomer_name',
                        'target_arrear',
                        'target_current',
                        'advanced_collection',
                        'collection_amount',
                        'hod_approved_status'
                    )
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
                        'mobile_no',
                        'target_sheets.unit',
                        'sub_units.name as unit_id',
                        'ord_no',
                        'cutomer_name',
                        'target_arrear',
                        'target_current',
                        'advanced_collection',
                        'collection_amount',
                        'hod_approved_status'
                    )
                    ->whereIn('target_sheets.unit', $unit_id)
                    ->orderBy('collections.id', 'DESC')
                    ->get();
            }


            //Unit Manager
            elseif (Auth::user()->hasRole('Unit')) {
                $unit_id = User::where('unit_id', auth()->user()->unit_id)->pluck('sub_unit_id')->toArray();
                $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                    ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                    ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                    ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                    ->select(
                        'collections.id',
                        'deposit_types.deposit_type as deposit_name',
                        'employee_id',
                        'users.name as employee_name',
                        'collections.status',
                        'mobile_no',
                        'target_sheets.unit',
                        'sub_units.name as unit_id',
                        'ord_no',
                        'cutomer_name',
                        'target_arrear',
                        'target_current',
                        'advanced_collection',
                        'collection_amount',
                        'hod_approved_status'
                    )
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
                        'mobile_no',
                        'target_sheets.unit',
                        'sub_units.name as unit_id',
                        'ord_no',
                        'cutomer_name',
                        'target_arrear',
                        'target_current',
                        'advanced_collection',
                        'collection_amount',
                        'hod_approved_status'
                    )
                    ->where('collections.created_by', auth()->user()->id)
                    ->where('collections.unit', auth()->user()->sub_unit_id)
                    ->orderBy('collections.id', 'DESC')
                    ->get();
            }

            return Datatables::of($data)

                ->addIndexColumn()

                ->addColumn('status', function ($row) {
                    if ($row->status == 0) {
                        return '<span class="badge badge-warning">Pending</span>';
                    } elseif ($row->status == 1 && $row->hod_approved_status == 2) {
                        return '<span class="badge badge-success">Approved</span>';
                    } elseif ($row->status == 1 && $row->hod_approved_status == 1) {
                        return '<span class="badge badge-success">Waiting for accounts approval</span>';
                    } elseif ($row->status == 1 && $row->hod_approved_status == 0) {
                        return '<span class="badge badge-success">Pending for final Approval</span>';
                    } else {
                        return '<span class="badge badge-danger">Disapproved</span>';
                    }
                })

                ->addColumn('details', function ($row) {
                    $html = '';
                    $html .= '
                <a href="' . route('collection.show', $row->id) . '" class="btn btn-xs btn-primary btn-icon-text">

                View
            </a>';
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

                ->rawColumns(['status', 'details', 'target_arrear', 'target_current', 'advanced_collection', 'collection_amount'])
                ->make(true);
        }

        if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('National') || auth()->user()->hasRole('Accounts')) {
            $total_order = Collection::count();
            $total_amount = Collection::sum('collection_amount');
            $total_approved_order = Collection::where('status', 1)->where('hod_approved_status', 2)->count();
            $total_approved_amount = Collection::where('status', 1)->where('hod_approved_status', 2)->sum('collection_amount');
            $total_disapproved_order = Collection::where('status', 2)->count();
            $total_disapproved_amount = Collection::where('status', 2)->sum('collection_amount');
        } elseif (auth()->user()->hasRole('Zone')) {
            $unit_id = Unit::where('units.zone_id', auth()->user()->zone_id)
                ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                ->pluck('sub_units.id')
                ->toArray();
            $total_order = Collection::whereIn('unit', $unit_id)->count();
            $total_amount = Collection::whereIn('unit', $unit_id)->sum('collection_amount');
            $total_approved_order = Collection::whereIn('unit', $unit_id)->where('status', 1)->where('hod_approved_status', 2)->count();
            $total_approved_amount = Collection::whereIn('unit', $unit_id)->where('status', 1)->where('hod_approved_status', 2)->sum('collection_amount');
            $total_disapproved_order = Collection::whereIn('unit', $unit_id)->where('status', 2)->count();
            $total_disapproved_amount = Collection::whereIn('unit', $unit_id)->where('status', 2)->sum('collection_amount');
        } elseif (auth()->user()->hasRole('Area')) {
            $unit_id = Unit::where('units.area_id', auth()->user()->area_id)
                ->join('sub_units', 'sub_units.unit_id', '=', 'units.id')
                ->pluck('sub_units.id')
                ->toArray();
            $total_order = Collection::whereIn('unit', $unit_id)->count();
            $total_amount = Collection::whereIn('unit', $unit_id)->sum('collection_amount');
            $total_approved_order = Collection::whereIn('unit', $unit_id)->where('status', 1)->where('hod_approved_status', 2)->count();
            $total_approved_amount = Collection::whereIn('unit', $unit_id)->where('status', 1)->where('hod_approved_status', 2)->sum('collection_amount');
            $total_disapproved_order = Collection::whereIn('unit', $unit_id)->where('status', 2)->count();
            $total_disapproved_amount = Collection::whereIn('unit', $unit_id)->where('status', 2)->sum('collection_amount');
        } elseif (auth()->user()->hasRole('Unit')) {
            $unit_id = User::where('unit_id', auth()->user()->unit_id)->pluck('sub_unit_id')->toArray();
            $total_order = Collection::whereIn('unit', $unit_id)->count();
            $total_amount = Collection::whereIn('unit', $unit_id)->sum('collection_amount');
            $total_approved_order = Collection::whereIn('unit', $unit_id)->where('status', 1)->where('hod_approved_status', 2)->count();
            $total_approved_amount = Collection::whereIn('unit', $unit_id)->where('status', 1)->where('hod_approved_status', 2)->sum('collection_amount');
            $total_disapproved_order = Collection::whereIn('unit', $unit_id)->where('status', 2)->count();
            $total_disapproved_amount = Collection::whereIn('unit', $unit_id)->where('status', 2)->sum('collection_amount');
        } else {
            $unit_id = auth()->user()->sub_unit_id;
            $total_order = Collection::where('unit', $unit_id)->where('created_by', auth()->user()->id)->count();
            $total_amount = Collection::where('unit', $unit_id)->where('created_by', auth()->user()->id)->sum('collection_amount');
            $total_approved_order = Collection::where('unit', $unit_id)->where('created_by', auth()->user()->id)->where('status', 1)->where('hod_approved_status', 2)->count();
            $total_approved_amount = Collection::where('unit', $unit_id)->where('created_by', auth()->user()->id)->where('status', 1)->where('hod_approved_status', 2)->sum('collection_amount');
            $total_disapproved_order = Collection::where('unit', $unit_id)->where('created_by', auth()->user()->id)->where('status', 2)->count();
            $total_disapproved_amount = Collection::where('unit', $unit_id)->where('created_by', auth()->user()->id)->where('status', 2)->sum('collection_amount');
        }

        return view('administrative.collection.index', compact(
            'total_order',
            'total_amount',
            'total_approved_order',
            'total_approved_amount',
            'total_disapproved_order',
            'total_disapproved_amount'
        ));
    }

    public function depositSlipReport(Request $request)
    {
        if ($request->ajax()) {
            // head of depertment or admin role
            if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
                $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                    ->leftjoin('units', 'units.id', '=', 'target_sheets.unit')
                    ->leftjoin('banks', 'banks.id', '=', 'collections.bank_name')
                    ->leftjoin('branches', 'branches.id', '=', 'collections.branch_name')
                    ->select(
                        'banks.bank_name',
                        'branches.branch_name',
                        'collections.id',
                        'collections.created_at',
                        'collections.approved_date',
                        'collections.status',
                        'mobile_no',
                        'target_sheets.unit',
                        'units.name as unit_id',
                        'ord_no',
                        'note',
                        'collection_amount',
                        'hod_approved_status',
                        'deposit_date'
                    )
                    ->orderBy('collections.id', 'DESC');
            }


            // zonal manager
            elseif (Auth::user()->hasRole('Zone')) {
                $unit_id = Unit::where('zone_id', auth()->user()->zone_id)->pluck('id')->toArray();
                $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                    ->leftjoin('units', 'units.id', '=', 'target_sheets.unit')
                    ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                    ->select(
                        'banks.bank_name',
                        'branches.branch_name',
                        'collections.id',
                        'collections.created_at',
                        'collections.approved_date',
                        'collections.status',
                        'mobile_no',
                        'target_sheets.unit',
                        'units.name as unit_id',
                        'ord_no',
                        'note',
                        'collection_amount',
                        'hod_approved_status',
                        'deposit_date'
                    )
                    ->whereIn('target_sheets.unit', $unit_id)
                    ->orderBy('collections.id', 'DESC');
            }


            //area manager
            elseif (Auth::user()->hasRole('Area')) {
                $unit_id = Unit::where('area_id', auth()->user()->area_id)->pluck('id')->toArray();
                $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                    ->leftjoin('units', 'units.id', '=', 'target_sheets.unit')
                    ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                    ->select(
                        'banks.bank_name',
                        'branches.branch_name',
                        'collections.id',
                        'collections.created_at',
                        'collections.approved_date',
                        'collections.status',
                        'mobile_no',
                        'target_sheets.unit',
                        'units.name as unit_id',
                        'ord_no',
                        'note',
                        'collection_amount',
                        'hod_approved_status',
                        'deposit_date'
                    )
                    ->whereIn('target_sheets.unit', $unit_id)
                    ->orderBy('collections.id', 'DESC');
            }


            //Unit Manager
            elseif (Auth::user()->hasRole('Unit')) {
                $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                    ->leftjoin('units', 'units.id', '=', 'target_sheets.unit')
                    ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                    ->select(
                        'banks.bank_name',
                        'branches.branch_name',
                        'collections.id',
                        'collections.created_at',
                        'collections.approved_date',
                        'collections.status',
                        'mobile_no',
                        'target_sheets.unit',
                        'units.name as unit_id',
                        'ord_no',
                        'note',
                        'collection_amount',
                        'hod_approved_status',
                        'deposit_date'
                    )
                    ->where('target_sheets.unit', auth()->user()->unit_id)
                    ->orderBy('collections.id', 'DESC');
            }

            //Sub Unit user
            else {
                $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                    ->leftjoin('units', 'units.id', '=', 'target_sheets.unit')
                    ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                    ->select(
                        'banks.bank_name',
                        'branches.branch_name',
                        'collections.id',
                        'collections.created_at',
                        'collections.approved_date',
                        'collections.status',
                        'mobile_no',
                        'target_sheets.unit',
                        'units.name as unit_id',
                        'ord_no',
                        'note',
                        'collection_amount',
                        'hod_approved_status',
                        'deposit_date'
                    )
                    ->where('collections.created_by', auth()->user()->id)
                    ->where('collections.unit', auth()->user()->unit_id)
                    ->orderBy('collections.id', 'DESC');
            }

            // Apply date range filter if provided
            $from = $request->input('from');
            $to = $request->input('to');
            if (!empty($from) && !empty($to)) {
                $data->whereBetween('collections.deposit_date', [$from, $to]);
            }

            $data = $data->get();

            return Datatables::of($data)

                ->addIndexColumn()

                ->addColumn('status', function ($row) {
                    $html = '';
                    $html .= '<a href="' . route('collection.show', $row->id) . '" >
                                            <button class="btn btn-primary btn-xs">View</button></a>   ';
                    return $html;
                })
                ->rawColumns(['status'])
                ->make(true);
        }
        return view('administrative.report.deposit-slip-wise-report');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'target_sheet_id' => 'required',
            // 'areer_amount' => 'required',
            // 'current_amount' => 'required',
            //'vts_charge' => 'required',
            //'name_transfer_fees' => 'required',
            'collection_amount' => 'required',
            // 'advanced_collection' => 'required',
            //'ownership_charge' => 'required',
            //'rotl' => 'required',
            //'brta_charge' => 'required',
            //'others' => 'required',
            'deposit_type' => 'required',
            'bank_name' => 'required',
            // 'branch_name' => 'required',
            // 'ft_code' => 'required',
            'deposit_date' => 'required|date',
            'attachment.*' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->route('collect_entry', $request->target_sheet_id)
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'target_sheet_id' => $request->target_sheet_id,
            // 'areer_amount' => $request->areer_amount,
            // 'current_amount' => $request->current_amount,
            'vts_charge' => $request->vts_charge,
            'name_transfer_fees' => $request->name_transfer_fees,
            'unit' => $request->unit,
            'collection_amount' => $request->collection_amount,
            //'advanced_collection' => $request->advanced_collection,
            'ownership_charge' => $request->ownership_charge,
            'rotl' => $request->rotl,
            'brta_charge' => $request->brta_charge,
            'others' => $request->others,
            'deposit_type' => $request->deposit_type,
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'ft_code' => $request->ft_code,
            'deposit_date' =>  date('Y-m-d H:i:s', strtotime($request->deposit_date)),
            'created_by' => auth()->user()->id,
            'status' => 0
        ];
        $collection = Collection::create($data);

        $user = User::where('unit_id', $collection->unit)->where('id', '!=', auth()->user()->id)->select('id')->get();
        foreach ($user as $users) {
            $user = $users->id;
            $message = auth()->user()->name . ' -' . auth()->user()->employee_id . ' has been create a collection';
            $action = '';
            $this->sendNotification($user, $message, $action);
        }

        //attachment
        if ($request->hasFile('attachment')) {
            foreach ($request->file('attachment') as $attachment) {

                $filename = Str::random(40) . '.' . $attachment->getClientOriginalExtension();

                $attachment->move('collection_attachment/', $filename);

                // Create a new CollectionAttachment record
                CollectionAttachment::create([
                    'collection_id' => $collection->id,
                    'attachment' => $filename,
                ]);
            }
        }

        return redirect()->route('collection.index')->with('success', 'Collection has been inserted');
    }

    public function approvedCollection($id)
    {

        $data = Collection::where('id', $id)->first();

        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National')) {
            Collection::where('id', $id)->update([
                'status' => 1,
                'hod_approved_status' => 1,
                'approved_by' => auth()->user()->id,
                'approved_date' => date('Y-m-d H:i:s'),
                'reject_by' => null,
                'reject_date' => null,
            ]);

            $roleNames = ['Accounts'];

            $userIdsWithRoles = User::whereHas('roles', function ($query) use ($roleNames) {
                $query->whereIn('name', $roleNames);
            })->pluck('id')->toArray();

            foreach ($userIdsWithRoles as $userid) {
                $user = $userid; // Replace with your user ID
                $message = 'Collection has been approved by ' . auth()->user()->name . ' -' . auth()->user()->employee_id;
                $action = '';
                $this->sendNotification($user, $message, $action);
            }
            return redirect()->route('pending_collection')->with('success', 'Status has been updated');
        } elseif (Auth::user()->hasRole('Accounts')) {
            Collection::where('id', $id)->update([
                'status' => 1,
                'hod_approved_status' => 2,
                'approved_by' => auth()->user()->id,
                'approved_date' => date('Y-m-d H:i:s'),
                'reject_by' => null,
                'reject_date' => null,
            ]);

            $this->storePayment($id);

            $user = $data->created_by; // Replace with your user ID
            $message = 'Collection has been approved by ' . auth()->user()->name . ' -' . auth()->user()->employee_id;
            $action = '';
            $this->sendNotification($user, $message, $action);


            return redirect()->route('pending_collection')->with('success', 'Collection has been approved');
        } else {

            $roleNames = ['Admin', 'National'];

            $userIdsWithRoles = User::whereHas('roles', function ($query) use ($roleNames) {
                $query->whereIn('name', $roleNames);
            })->pluck('id')->toArray();

            foreach ($userIdsWithRoles as $userid) {
                $user = $userid; // Replace with your user ID
                $message = 'Collection has been approved by ' . auth()->user()->name . ' -' . auth()->user()->employee_id;
                $action = '';
                $this->sendNotification($user, $message, $action);
            }

            Collection::where('id', $id)->update([
                'status' => 1,
                'hod_approved_status' => 0,
                'approved_by' => auth()->user()->id,
                'approved_date' => date('Y-m-d H:i:s'),
                'reject_by' => null,
                'reject_date' => null,
            ]);

            return redirect()->route('pending_collection')->with('success', 'Status has been updated');
        }
    }

    public function multiApprovedCollection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'collection_ids' => 'required|array|min:1',
        ]);
        if ($validator->fails()) {
            return response(
                [
                    'message' => 'Validation errors',
                    'errors' => $validator->errors(),
                    'status' => 'error'
                ],
                422
            );
        }
        $collection_ids = $request->collection_ids;

        if (Auth::user()->hasRole('Accounts')) {
            $this->storeArrayPayment($collection_ids);
        }

        if (count($collection_ids) > 0) {

            foreach ($collection_ids as $id) {
                $data = Collection::where('id', $id)->first();

                if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National')) {
                    Collection::where('id', $id)->update([
                        'status' => 1,
                        'hod_approved_status' => 1,
                        'approved_by' => auth()->user()->id,
                        'approved_date' => date('Y-m-d H:i:s'),
                        'reject_by' => null,
                        'reject_date' => null,
                    ]);

                    $roleNames = ['Accounts'];

                    $userIdsWithRoles = User::whereHas('roles', function ($query) use ($roleNames) {
                        $query->whereIn('name', $roleNames);
                    })->pluck('id')->toArray();

                    foreach ($userIdsWithRoles as $userid) {
                        $user = $userid; // Replace with your user ID
                        $message = 'Collection has been approved by ' . auth()->user()->name . ' -' . auth()->user()->employee_id;
                        $action = '';
                        $this->sendNotification($user, $message, $action);
                    }
                } elseif (Auth::user()->hasRole('Accounts')) {
                    Collection::where('id', $id)->update([
                        'status' => 1,
                        'hod_approved_status' => 2,
                        'approved_by' => auth()->user()->id,
                        'approved_date' => date('Y-m-d H:i:s'),
                        'reject_by' => null,
                        'reject_date' => null,
                    ]);

                    $user = $data->created_by; // Replace with your user ID
                    $message = 'Collection has been approved by ' . auth()->user()->name . ' -' . auth()->user()->employee_id;
                    $action = '';
                    $this->sendNotification($user, $message, $action);
                } else {

                    $roleNames = ['Admin', 'National'];

                    $userIdsWithRoles = User::whereHas('roles', function ($query) use ($roleNames) {
                        $query->whereIn('name', $roleNames);
                    })->pluck('id')->toArray();

                    foreach ($userIdsWithRoles as $userid) {
                        $user = $userid; // Replace with your user ID
                        $message = 'Collection has been approved by ' . auth()->user()->name . ' -' . auth()->user()->employee_id;
                        $action = '';
                        $this->sendNotification($user, $message, $action);
                    }

                    Collection::where('id', $id)->update([
                        'status' => 1,
                        'hod_approved_status' => 0,
                        'approved_by' => auth()->user()->id,
                        'approved_date' => date('Y-m-d H:i:s'),
                        'reject_by' => null,
                        'reject_date' => null,
                    ]);
                }
            }
            return \response()->json(['status' => 'success', 'message' => 'Status has been updated successfully'], 200);
        }
        return \response()->json(['status' => 'error', 'message' => 'Failed to update status'], 500);
    }

    public function disapprovedCollection(Request $request)
    {
        $result = Collection::where('id', $request->collection_id)->update([
            'status' => 2,
            'reject_by' => auth()->user()->id,
            'reject_date' => date('Y-m-d H:i:s'),
            'disapproved_note_id' => $request->disapproved_note_id,
        ]);
        if ($result) {
            echo 'success';
        } else {
            echo 'error';
        }
    }

    // organogram
    public function organogram(Request $request)
    {
        if ($request->ajax()) {
            $data = User::leftjoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('roles.name', 'Subunit')
                ->orderBy('users.id', 'DESC')
                ->get();

            $map = array_map(function ($item) {

                $unit_manager = User::leftjoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                    ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->leftjoin('units', 'units.id', '=', 'users.unit_id')
                    ->where('roles.name', 'Unit')
                    ->where('unit_id', $item['unit_id'])
                    ->select('units.area_id', 'units.id as unit_id', 'units.name as unit_name', 'users.name as unit_manager_name', 'users.id as user_id', 'employee_id')
                    ->first();

                if (isset($unit_manager)) {
                    $area_manager = User::leftjoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                        ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->leftjoin('areas', 'areas.id', '=', 'users.area_id')
                        ->where('roles.name', 'Area')
                        ->where('users.area_id', $unit_manager->area_id)
                        ->select('users.zone_id', 'areas.id as area_id', 'areas.name as area_name', 'users.name as area_manager_name', 'users.id as user_id', 'employee_id')
                        ->first();
                }

                if (isset($area_manager)) {
                    $zonal_manager = User::leftjoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                        ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->leftjoin('zones', 'zones.id', '=', 'users.zone_id')
                        ->where('roles.name', 'Zone')
                        ->where('users.zone_id', $area_manager->zone_id)
                        ->select('zones.id as zone_id', 'zones.name as zone_name', 'users.name as zone_manager_name', 'users.id as user_id', 'employee_id')
                        ->first();
                }

                $national_user = User::leftjoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                    ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->where('roles.name', 'National')
                    ->select('users.name as national_name', 'users.id as user_id', 'employee_id')
                    ->first();

                $item['area_manager'] = $area_manager->area_manager_name ?? "";
                $item['area_manager_emp_id'] = $area_manager->employee_id ?? "";

                $item['zone_manager'] = $zonal_manager->zone_manager_name ?? "";
                $item['zone_manager_emp_id'] = $zonal_manager->employee_id ?? "";

                $item['unit_manager'] = $unit_manager->unit_manager_name ?? "";
                $item['unit_manager_emp_id'] = $unit_manager->employee_id ?? "";

                $item['national'] = $national_user->national_name ?? "";
                $item['national_emp_id'] = $national_user->employee_id ?? "";

                $item['sub_unit'] = $item['name'] ?? "";
                $item['sub_unit_emp_id'] = $item['employee_id'] ?? "";

                $item['area'] = $area_manager->area_name ?? "";
                $item['zone'] = $zonal_manager->zone_name ?? "";
                $item['unit'] = $unit_manager->unit_name ?? "";

                return $item;
            }, $data->toArray());

            return Datatables::of($map)

                ->addIndexColumn()

                ->make(true);
        }
        return view('administrative.organogram.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {

        $bank = Bank::find($collection->bank_name);
        $branch = Branch::find($collection->branch_name);
        $deposit = DepositType::find($collection->deposit_type);
        $createdBy = $collection->userName($collection->created_by);
        $approvedBy = $collection->userName($collection->approved_by);
        $rejectBy = $collection->userName($collection->reject_by);
        $amount = Target::find($collection->target_sheet_id);
        $attachment = CollectionAttachment::where('collection_id', $collection->id)->get();
        $disapprovedNotes = DisapprovedNote::all();
        return view('administrative.collection.show', compact('disapprovedNotes', 'collection', 'bank', 'branch', 'approvedBy', 'rejectBy', 'attachment', 'deposit', 'amount', 'createdBy'));
    }

    public function entryCollection($id)
    {
        //check collection
        $count = Collection::where('target_sheet_id', $id)->count();
        // if ($count > 0) {
        //     return redirect()->back()->with('error', 'Collection already exists');
        // }
        $depositType = DepositType::where('status', 1)->get();
        $bank = Bank::where('status', 1)->get();
        $data = Target::findOrFail($id);
        return view('administrative.collection.create', compact('depositType', 'bank', 'data'));
    }
    public function pendingCollectionIndex()
    {
        return view('administrative.collection.pending');
    }

    public function pendingCollection(Request $request)
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
                    'collections.status as c_status',
                    'collections.hod_approved_status',
                    'mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'collections.created_at',
                    'collections.approved_date',
                    'collections.deposit_date',
                )
                ->where('collections.status', '!=', 2)
                ->where('collections.hod_approved_status', '!=', 2)
                ->orderBy('collections.id', 'DESC');
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
                    'collections.status as c_status',
                    'collections.hod_approved_status',
                    'mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'collections.created_at',
                    'collections.approved_date',
                    'collections.deposit_date',
                )
                ->whereIn('target_sheets.unit', $unit_id)
                ->where('collections.status', '!=', 2)
                ->where('collections.hod_approved_status', '!=', 2)
                ->orderBy('collections.id', 'DESC');
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
                    'collections.status as c_status',
                    'collections.hod_approved_status',
                    'mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'collections.created_at',
                    'collections.approved_date',
                    'collections.deposit_date',
                )
                ->whereIn('target_sheets.unit', $unit_id)
                ->where('collections.status', '!=', 2)
                ->where('collections.hod_approved_status', '!=', 2)
                ->orderBy('collections.id', 'DESC');
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
                    'collections.status as c_status',
                    'collections.hod_approved_status',
                    'mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'collections.created_at',
                    'collections.approved_date',
                    'collections.deposit_date',
                )
                ->whereIn('target_sheets.unit', $unit_id)
                ->where('collections.status', '!=', 2)
                ->where('collections.hod_approved_status', '!=', 2)
                ->orderBy('collections.id', 'DESC');
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
                    'collections.status as c_status',
                    'collections.hod_approved_status',
                    'mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'collections.created_at',
                    'collections.approved_date',
                    'collections.deposit_date',
                )
                ->where('collections.created_by', auth()->user()->id)
                ->where('collections.unit', auth()->user()->sub_unit_id)
                ->where('collections.status', '!=', 2)
                ->where('collections.hod_approved_status', '!=', 2)
                ->orderBy('collections.id', 'DESC');
        }

        $requestData = $request->all();

        if (!empty($requestData['start_date']) && empty($requestData['end_date'])) {
            $data = $data->whereDate('deposit_date', $requestData['start_date']);
        }

        if (!empty($requestData['start_date']) && !empty($requestData['end_date'])) {
            $data = $data->whereBetween('deposit_date', [$requestData['start_date'], $requestData['end_date']]);
        }
        $data = $data->get();

        return Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('status', function ($row) {
                if ($row->c_status == 0) {
                    return '<span class="badge badge-warning">Pending</span>';
                } elseif ($row->c_status == 1 && $row->hod_approved_status == 2) {
                    return '<span class="badge badge-success">Approved</span>';
                } elseif ($row->c_status == 1 && $row->hod_approved_status == 1) {
                    return '<span class="badge badge-success">Waiting for accounts approval</span>';
                } elseif ($row->c_status == 1 && $row->hod_approved_status == 0) {
                    return '<span class="badge badge-success">Pending for final Approval</span>';
                } else {
                    return '<span class="badge badge-danger">Disapproved</span>';
                }
            })

            ->addColumn('action', function ($row) {

                if (Auth::user()->hasRole('Unit')) {

                    if ($row->c_status == 0) {
                        $html = '';
                        $html .= '
                            <a href="' . route('collection.edit', $row->id) . '" >
                            <button class="btn btn-warning btn-xs">Edit</button></a>
                            <a href="' . route('approved_collection', $row->id) . '" >
                                            <button class="btn btn-success btn-xs">Approved</button></a>
                                             <a href="' . route('disapproved_collection', $row->id) . '" >
                                            <button class="btn btn-danger btn-xs">Disapproved</button></a>
                                            ';
                        return $html;
                    }
                }

                if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National')) {

                    if ($row->hod_approved_status == 0) {
                        $html = '';
                        $html .= '
                            <a href="' . route('collection.edit', $row->id) . '" >
                                            <button class="btn btn-warning btn-xs">Edit</button></a>
                            <a href="' . route('approved_collection', $row->id) . '" >
                                            <button class="btn btn-success btn-xs">Approved</button></a>
                                             <a href="' . route('disapproved_collection', $row->id) . '" >
                                            <button class="btn btn-danger btn-xs">Disapproved</button></a>

                                            ';
                        return $html;
                    }
                }

                if (Auth::user()->hasRole('Accounts')) {
                    if ($row->c_status == 1 && $row->hod_approved_status == 1) {
                        $html = '';
                        $html .= '<a href="' . route('approved_collection', $row->id) . '" >
                                            <button class="btn btn-success btn-xs">Approved</button></a>
                                            <a href="' . route('disapproved_collection', $row->id) . '" >
                                            <button class="btn btn-danger btn-xs">Disapproved</button></a>';
                        return $html;
                    }
                }
            })
            ->addColumn('sid', function ($row) {
                $id = $row->id . "b";
                if (Auth::user()->hasRole('Unit')) {

                    if ($row->c_status == 0) {
                        $id = $row->id . "a";
                    }
                }

                if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National')) {

                    if ($row->hod_approved_status == 0) {
                        $id = $row->id . "a";
                    }
                }

                if (Auth::user()->hasRole('Accounts')) {

                    if ($row->c_status == 1 && $row->hod_approved_status == 1) {
                        $id = $row->id . "a";
                    }
                }
                return $id;
            })
            ->addColumn('details', function ($row) {
                $html = '';
                $html .= '<a href="' . route('collection.show', $row->id) . '" >
                                            <button class="btn btn-primary btn-xs">View</button></a>';
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
            ->editColumn('deposit_date', function ($row) {
                return $row->deposit_date ? date('Y-m-d h:i:s a', strtotime($row->deposit_date)) : '';
            })

            ->rawColumns(['deposit_date', 'status', 'action', 'details', 'sid', 'target_arrear', 'target_current', 'advanced_collection', 'collection_amount'])
            ->addIndexColumn()
            ->escapeColumns()
            ->toJson();
    }

    public function pendingCollectionDownload(Request $request)
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
                    'users.phone as Mobile',
                    'sub_units.name as Unit',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Target Arrear',
                    'target_current as Target Current',
                    'advanced_collection as Advanced Collection',
                    'deposit_types.deposit_type as Deposit Type',
                    'collection_amount as Collection Amount'
                )
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
                    'users.phone as Mobile',
                    'sub_units.name as Unit',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Target Arrear',
                    'target_current as Target Current',
                    'advanced_collection as Advanced Collection',
                    'deposit_types.deposit_type as Deposit Type',
                    'collection_amount as Collection Amount'
                )
                ->whereIn('target_sheets.unit', $unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }


        //area managers
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
                    'users.phone as Mobile',
                    'sub_units.name as Unit',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Target Arrear',
                    'target_current as Target Current',
                    'advanced_collection as Advanced Collection',
                    'deposit_types.deposit_type as Deposit Type',
                    'collection_amount as Collection Amount'
                )
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
                    'users.phone as Mobile',
                    'sub_units.name as Unit',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Target Arrear',
                    'target_current as Target Current',
                    'advanced_collection as Advanced Collection',
                    'deposit_types.deposit_type as Deposit Type',
                    'collection_amount as Collection Amount'
                )
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
                    'users.phone as Mobile',
                    'sub_units.name as Unit',
                    'ord_no as Order No',
                    'cutomer_name as Customer Name',
                    'target_arrear as Target Arrear',
                    'target_current as Target Current',
                    'advanced_collection as Advanced Collection',
                    'deposit_types.deposit_type as Deposit Type',
                    'collection_amount as Collection Amount'
                )
                ->where('collections.created_by', auth()->user()->id)
                ->where('collections.unit', auth()->user()->sub_unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }

        $results = $data;

        $date_str = date('Y_m_d_H_i_s');

        return Excel::download(new PendingCollectionExport($results), $date_str . 'collection_approval.xlsx');
    }

    public function rejectCollection(Request $request)
    {

        if ($request->ajax()) {
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
                        'mobile_no',
                        'target_sheets.unit',
                        'sub_units.name as unit_id',
                        'ord_no',
                        'cutomer_name',
                        'target_arrear',
                        'target_current',
                        'advanced_collection',
                        'collection_amount'
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
                        'mobile_no',
                        'target_sheets.unit',
                        'sub_units.name as unit_id',
                        'ord_no',
                        'cutomer_name',
                        'target_arrear',
                        'target_current',
                        'advanced_collection',
                        'collection_amount'
                    )
                    ->whereIn('target_sheets.unit', $unit_id)
                    ->where('collections.status', 2)
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
                        'mobile_no',
                        'target_sheets.unit',
                        'sub_units.name as unit_id',
                        'ord_no',
                        'cutomer_name',
                        'target_arrear',
                        'target_current',
                        'advanced_collection',
                        'collection_amount'
                    )
                    ->whereIn('target_sheets.unit', $unit_id)
                    ->where('collections.status', 2)
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
                        'mobile_no',
                        'target_sheets.unit',
                        'sub_units.name as unit_id',
                        'ord_no',
                        'cutomer_name',
                        'target_arrear',
                        'target_current',
                        'advanced_collection',
                        'collection_amount'
                    )
                    ->whereIn('target_sheets.unit', $unit_id)
                    ->where('collections.status', 2)
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
                        'mobile_no',
                        'target_sheets.unit',
                        'sub_units.name as unit_id',
                        'ord_no',
                        'cutomer_name',
                        'target_arrear',
                        'target_current',
                        'advanced_collection',
                        'collection_amount'
                    )
                    ->where('collections.created_by', auth()->user()->id)
                    ->where('collections.unit', auth()->user()->sub_unit_id)
                    ->where('collections.status', 2)
                    ->orderBy('collections.id', 'DESC')
                    ->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('status', function ($row) {
                    return '<span class="badge badge-danger">Disapproved</span>';
                })
                ->addColumn('details', function ($row) {
                    $html = '';
                    $html .= '<a href="' . route('collection.show', $row->id) . '" >
                                 <button class="btn btn-primary btn-xs">View</button></a>';
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
                ->rawColumns(['status', 'details', 'target_arrear', 'target_current', 'advanced_collection', 'collection_amount'])
                ->make(true);
        }
        return view('administrative.collection.reject');
    }

    public function viewStatement()
    {
        return view('administrative.customer.view-statement');
    }

    public function viewPayment()
    {
        return view('administrative.customer.view-payment');
    }

    public function edit($id)
    {
        $collection = Collection::findOrFail($id);
        $depositType = DepositType::where('status', 1)->get();
        $bank = Bank::where('status', 1)->get();
        $branch = Branch::where('id', $collection->branch_name)->first();
        $attachment = CollectionAttachment::where('collection_id', $id)->get();

        $data = [
            'depositType' => $depositType,
            'bank' => $bank,
            'collection' => $collection,
            'branch' => $branch,
            'attachment' => $attachment,
        ];
        return view('administrative.collection.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            //'vts_charge' => 'required',
            //'name_transfer_fees' => 'required',
            //'advanced_collection' => 'required',
            'collection_amount' => 'required',
            //'ownership_charge' => 'required',
            //'rotl' => 'required',
            //'brta_charge' => 'required',
            //'others' => 'required',
            'deposit_type' => 'required',
            'bank_name' => 'required',
            // 'branch_name' => 'required',
            'ft_code' => 'required',
            'deposit_date' => 'required|date',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $collection = Collection::findOrFail($id);

        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National')) {
            $approve_date = date('Y-m-d H:i:s', strtotime($request->approved_date));
            $approve_by = auth()->user()->id;
        } else {
            $approve_date = null;
            $approve_by = auth()->user()->id;
        }

        $data = [
            'target_sheet_id' => $collection->target_sheet_id,
            //'advanced_collection' => $request->advanced_collection,
            'vts_charge' => $request->vts_charge,
            'name_transfer_fees' => $request->name_transfer_fees,
            'collection_amount' => $request->collection_amount,
            'ownership_charge' => $request->ownership_charge,
            'rotl' => $request->rotl,
            'brta_charge' => $request->brta_charge,
            'others' => $request->others,
            'deposit_type' => $request->deposit_type,
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'ft_code' => $request->ft_code,
            'deposit_date' => date('Y-m-d H:i:s', strtotime($request->deposit_date)),
            'approved_date' => $approve_date,
            'approved_by' =>  $approve_by,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $collection = Collection::where('id', $id)->update($data);

        //attachment
        if ($request->hasFile('attachment')) {
            CollectionAttachment::where('collection_id', $id)->delete();
            foreach ($request->file('attachment') as $attachment) {

                $filename = Str::random(40) . '.' . $attachment->getClientOriginalExtension();

                $attachment->move('collection_attachment/', $filename);

                // Create a new CollectionAttachment record
                CollectionAttachment::create([
                    'collection_id' => $id,
                    'attachment' => $filename,
                ]);
            }
        }

        return redirect()->route('pending_collection')->with('success', 'Collection has been updated');
    }

    public function download()
    {
        // head of depertment or admin role
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->select(
                    'collections.id',
                    'deposit_types.deposit_type as deposit_name',
                    'employee_id',
                    'users.name as employee_name',
                    'collections.status',
                    'mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'hod_approved_status'
                )
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
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->select(
                    'collections.id',
                    'deposit_types.deposit_type as deposit_name',
                    'employee_id',
                    'users.name as employee_name',
                    'collections.status',
                    'mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'hod_approved_status'
                )
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
                    'mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'hod_approved_status'
                )
                ->whereIn('target_sheets.unit', $unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }


        //Unit Manager
        elseif (Auth::user()->hasRole('Unit')) {
            $unit_id = User::where('unit_id', auth()->user()->unit_id)->pluck('sub_unit_id')->toArray();
            $data = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->leftjoin('sub_units', 'sub_units.id', '=', 'target_sheets.unit')
                ->leftjoin('users', 'users.sub_unit_id', '=', 'target_sheets.unit')
                ->leftjoin('deposit_types', 'deposit_types.id', '=', 'collections.deposit_type')
                ->select(
                    'collections.id',
                    'deposit_types.deposit_type as deposit_name',
                    'employee_id',
                    'users.name as employee_name',
                    'collections.status',
                    'mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'hod_approved_status'
                )
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
                    'mobile_no',
                    'target_sheets.unit',
                    'sub_units.name as unit_id',
                    'ord_no',
                    'cutomer_name',
                    'target_arrear',
                    'target_current',
                    'advanced_collection',
                    'collection_amount',
                    'hod_approved_status'
                )
                ->where('collections.created_by', auth()->user()->id)
                ->where('collections.unit', auth()->user()->sub_unit_id)
                ->orderBy('collections.id', 'DESC')
                ->get();
        }

        $results = $data;

        $date_str = date('Y_m_d_H_i_s');

        return Excel::download(new CollectionExport($results),   $date_str . 'collection.xlsx');
    }
}
