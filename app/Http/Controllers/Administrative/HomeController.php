<?php

namespace App\Http\Controllers\Administrative;

use App\Models\Unit;

use App\Models\SubUnit;

use App\Models\User;

use App\Models\Target;

use App\Models\Collection;

use Illuminate\Http\Request;

use App\Models\DailyForecast;

use Illuminate\Support\Carbon;

use App\Models\MonthlyForecast;

use App\Models\CustomNotification;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {

        $year = date('Y');
        $month = date('m');

        // head of depertment or admin role
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
            $completeCollection = Collection::where('status', 1)
            ->where('hod_approved_status', 2)->count();
            // $pendingCollection = Collection::where('status', 0)->count();
            $pendingCollection = Collection::where('status','!=', 2)
            ->where('hod_approved_status','!=', 2)->count();

            $dailyForcast = DailyForecast::whereDate('date', '=', date('Y-m-d'))->sum('amount');
            $monthlyForcast = MonthlyForecast::whereDate('month', date('Y-m') . '-01')->sum('amount');

            $thisMonth = Collection::
            whereYear('created_at',$year)
            ->whereMonth('created_at',$month)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');

            $lastMonth = Collection::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');
            $today = Collection::whereDate('created_at', '=', date('Y-m-d'))
            ->where('hod_approved_status',2)
            ->sum('collection_amount');
            $lastDay = Collection::whereDate('created_at', '=', date("Y-m-d", strtotime("-1 day")))
            ->where('hod_approved_status',2)
            ->sum('collection_amount');

            $units = SubUnit::all();
        }

        // zonal manager
        elseif (Auth::user()->hasRole('Zone')) {
            $unit_id = Unit::where('units.zone_id', auth()->user()->zone_id)
            ->join('sub_units','sub_units.unit_id','=','units.id')
            ->pluck('sub_units.id')
            ->toArray();

            $completeCollection = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->whereIn('target_sheets.unit', $unit_id)
                ->where('status', 1)
                ->where('hod_approved_status', 2)
                ->count();
            $pendingCollection = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->whereIn('target_sheets.unit', $unit_id)
                ->where('status','!=', 2)
                ->where('hod_approved_status','!=', 2)
                ->count();

            $dailyForcast = DailyForecast::whereDate('date', '=', date('Y-m-d'))->whereIn('unit_id', $unit_id)->sum('amount');
            $monthlyForcast = MonthlyForecast::whereDate('month', date('Y-m') . '-01')->whereIn('unit_id', $unit_id)->sum('amount');

            $thisMonth = Collection::
            whereYear('created_at',$year)
            ->whereMonth('created_at',$month)
            ->where('hod_approved_status',2)
            ->whereIn('unit', $unit_id)->sum('collection_amount');
            $lastMonth = Collection::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereIn('unit', $unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');
            $today = Collection::whereDate('created_at', '=', date('Y-m-d'))
            ->whereIn('unit', $unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');
            $lastDay = Collection::whereDate('created_at', '=', date("Y-m-d", strtotime("-1 day")))
            ->whereIn('unit', $unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');
            $units = SubUnit::where('zone_id', auth()->user()->zone_id)
            ->select('id', 'name')
            ->get();
        }

        //area manager
        elseif (Auth::user()->hasRole('Area')) {
            $unit_id = Unit::where('units.area_id', auth()->user()->area_id)
            ->join('sub_units','sub_units.unit_id','=','units.id')
            ->pluck('sub_units.id')
            ->toArray();
            $completeCollection = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->whereIn('target_sheets.unit', $unit_id)
                ->where('status', 1)
                ->where('hod_approved_status', 2)
                ->count();
            $pendingCollection = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->whereIn('target_sheets.unit', $unit_id)
                ->where('status','!=', 2)
                ->where('hod_approved_status','!=', 2)
                ->count();

            $dailyForcast = DailyForecast::whereDate('date', '=', date('Y-m-d'))->whereIn('unit_id', $unit_id)->sum('amount');
            $monthlyForcast = MonthlyForecast::whereDate('month', date('Y-m') . '-01')->whereIn('unit_id', $unit_id)->sum('amount');

            $thisMonth = Collection::
            whereYear('created_at',$year)
            ->whereMonth('created_at',$month)
            ->where('hod_approved_status',2)
            ->whereIn('unit', $unit_id)->sum('collection_amount');
            $lastMonth = Collection::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereIn('unit', $unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');
            $today = Collection::whereDate('created_at', '=', date('Y-m-d'))
            ->whereIn('unit', $unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');
            $lastDay = Collection::whereDate('created_at', '=', date("Y-m-d", strtotime("-1 day")))
            ->whereIn('unit', $unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');
            $units = SubUnit::where('area_id', auth()->user()->area_id)->select('id', 'name')->get();
        }

        //Unit Manager
        elseif (Auth::user()->hasRole('Unit')) {
            $unit_id = User::where('unit_id', auth()->user()->unit_id)->pluck('sub_unit_id')->toArray();
            $completeCollection = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->whereIn('target_sheets.unit', $unit_id)
                ->where('status', 1)
                ->where('hod_approved_status', 2)
                ->count();

            $pendingCollection = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->whereIn('target_sheets.unit', $unit_id)
                ->where('status','!=', 2)
                ->where('hod_approved_status','!=', 2)
                ->count();

            $dailyForcast = DailyForecast::whereDate('date', '=', date('Y-m-d'))->whereIn('unit_id',  $unit_id)->sum('amount');
            $monthlyForcast = MonthlyForecast::whereDate('month', date('Y-m') . '-01')->whereIn('unit_id',  $unit_id)->sum('amount');

            $thisMonth = Collection::
            whereYear('created_at',$year)
            ->whereMonth('created_at',$month)
            ->where('hod_approved_status',2)
            ->whereIn('unit',  $unit_id)->sum('collection_amount');

            $lastMonth = Collection::whereMonth('created_at', Carbon::now()
            ->subMonth()->month)->whereIn('unit', $unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');

            $today = Collection::whereDate('created_at', '=', date('Y-m-d'))
            ->whereIn('unit',  $unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');

            $lastDay = Collection::whereDate('created_at', '=', date("Y-m-d", strtotime("-1 day")))
            ->whereIn('unit',  $unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');

            $units = SubUnit::where('unit_id', auth()->user()->unit_id)->select('id', 'name')->get();
        }

        //Sub Unit user
        else {

            $completeCollection = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->where('collections.unit', auth()->user()->sub_unit_id)
                ->where('collections.created_by', auth()->user()->id)
                ->where('status', 1)
                ->where('hod_approved_status', 2)
                ->count();

            $pendingCollection = Collection::join('target_sheets', 'target_sheets.id', '=', 'collections.target_sheet_id')
                ->where('collections.unit', auth()->user()->sub_unit_id)
                ->where('collections.created_by', auth()->user()->id)
                ->where('status','!=', 2)
                ->where('hod_approved_status','!=', 2)
                ->count();

            $dailyForcast = DailyForecast::whereDate('date', '=', date('Y-m-d'))->where('created_by', Auth::user()->id)->where('unit_id', Auth::user()->sub_unit_id)->sum('amount');
            $monthlyForcast = MonthlyForecast::whereDate('month', date('Y-m') . '-01')->where('created_by', Auth::user()->id)->where('unit_id', Auth::user()->sub_unit_id)->sum('amount');

            $thisMonth = Collection::
            whereYear('created_at',$year)
            ->whereMonth('created_at',$month)
            ->where('hod_approved_status',2)
            ->where('collections.unit', auth()->user()->sub_unit_id)
            ->where('created_by', Auth::user()->id)->sum('collection_amount');

            $lastMonth = Collection::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->where('created_by', Auth::user()->id)
            ->where('collections.unit', auth()->user()->sub_unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');

            $today = Collection::whereDate('created_at', '=', date('Y-m-d'))
            ->where('created_by', Auth::user()->id)
            ->where('collections.unit', auth()->user()->sub_unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');
            $lastDay = Collection::whereDate('created_at', '=', date("Y-m-d", strtotime("-1 day")))
            ->where('created_by', Auth::user()->id)
            ->where('collections.unit', auth()->user()->sub_unit_id)
            ->where('hod_approved_status',2)
            ->sum('collection_amount');
            $units = SubUnit::where('id', auth()->user()->sub_unit_id)->select('id', 'name')->get();
        }

        return view('administrative.dashboard', compact('completeCollection', 'pendingCollection', 'dailyForcast', 'monthlyForcast', 'thisMonth', 'lastMonth', 'today', 'lastDay', 'units'));
    }
    // Old one
    public function monthlyTargetAchivement(Request $request)
    {
        if ($request->unit == 0) {
            $targetData = Target::whereMonth('target_date', Carbon::now()->month)
                ->leftjoin('units', 'target_sheets.unit', '=', 'units.id')
                ->select([DB::raw("SUM(target_sheets.target_total) as target_total"), 'units.name as unitname', 'target_sheets.unit as unitid'])
                ->groupBy('unit')
                ->get();
            $target = collect($targetData)->map(function ($item) {
                return $item->target_total;
            })->toArray();
            $units = collect($targetData)->map(function ($item) {
                return $item->unitname;
            })->toArray();
            $unitIds = collect($targetData)->map(function ($item) {
                return $item->unitid;
            })->toArray();

            $collectionArray = [];

            $collections = Collection::whereMonth('created_at', Carbon::now()->month)->get();

            foreach ($unitIds as $key => $unit) {
                $collectionArray[$key] = $collections->where('unit', $unit)->sum('collection_amount');
            }

            $payload = [
                'code' => 200,
                'user_message' => 'success',
                'targetArray' => $target,
                'unitArray' => $units,
                'collectionArray' => $collectionArray,
                'target' => '',
                'collection' => '',
            ];

            return response()->json($payload, 200);
        } else {
            if ($request->isSubunit == 0) {
                $target = Target::whereMonth('target_date', Carbon::now()->month)
                    ->where('unit', $request->unit)
                    ->sum('target_total');
                $collections = Collection::whereMonth('created_at', Carbon::now()->month)
                    ->where('unit', $request->unit)
                    ->sum('collection_amount');
                $payload = [
                    'code' => 200,
                    'user_message' => 'success',
                    'targetArray' => [],
                    'unitArray' => [],
                    'collectionArray' => [],
                    'target' => $target,
                    'collection' => $collections,
                ];

                return response()->json($payload, 200);
            }
        }
    }

      //dashboard target vs achievement
      public function targetVsAchievement(Request $request)
      {

          $unit = $request->unit;

          $year = date('Y');
          $month = date('m');

          // head of depertment or admin role
          if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
              if($unit==0){
                  $unit_id = Unit::where('area_id', auth()->user()->area_id)->pluck('id')->toArray();
                  $target = Target::
                  whereYear('target_sheets.created_at',$year)
                  ->whereMonth('target_sheets.created_at',$month)
                  ->leftjoin('sub_units', 'target_sheets.unit', '=', 'sub_units.id')
                  ->select([DB::raw("SUM(target_sheets.target_total) as target_total"), 'sub_units.name as unitname', 'target_sheets.unit as unitid'])
                  ->groupBy('unit')
                  ->get();

                  $targetArray = collect($target)->map(function ($item) {
                      return number_format($item->target_total, 2, '.', '');
                  })->toArray();
                  $unitArray = collect($target)->map(function ($item) {
                      return $item->unitname;
                  })->toArray();
                  $unitIds = collect($target)->map(function ($item) {
                      return $item->unitid;
                  })->toArray();

                  $collectionArray = [];

                  $collections = Collection::
                  whereYear('created_at',$year)
                  ->whereMonth('created_at',$month)
                  ->where('hod_approved_status',2)
                  ->get();

                  foreach ($unitIds as $key => $unit) {
                      $unitCollections = $collections->where('unit', $unit);
                      $collectionAmount = $unitCollections->sum(function ($item) {
                        return intval($item->collection_amount);
                      });
                      $collectionArray[$key] = number_format($collectionAmount, 2, '.', '');
                      
                  }
                  if(empty(collect($unitArray)->first())){
                    array_shift($unitArray);
                    array_shift($targetArray);
                    array_shift($collectionArray);
                  }
                  $payload = [
                      'code' => 200,
                      'user_message' => 'success',
                      'targetArray' => $targetArray,
                      'unitArray' => $unitArray,
                      'collectionArray' => $collectionArray,
                      'target' => 0,
                      'collection' => 0,
                  ];
                  return response()->json($payload, 200);
              }
              else{
                  $target = Target::
                  whereYear('created_at',$year)
                  ->whereMonth('created_at',$month)
                  ->where('unit', $unit)
                  ->sum('target_total');

                  $collection = Collection::
                  whereYear('created_at',$year)
                  ->whereMonth('created_at',$month)
                  ->where('hod_approved_status',2)
                  ->where('unit', $unit)
                  ->sum('collection_amount');

                  $payload = [
                      'code' => 200,
                      'user_message' => 'success',
                      'targetArray' => [],
                      'unitArray' => [],
                      'collectionArray' => [],
                      'target' => number_format($target, 2, '.', ''),
                      'collection' =>  number_format($collection, 2, '.', ''),
                  ];
                  return response()->json($payload, 200);
              }
          }

          // zonal manager
          elseif (Auth::user()->hasRole('Zone')) {
              if($unit==0){
                $unit_id = Unit::where('units.zone_id', auth()->user()->zone_id)
                ->join('sub_units','sub_units.unit_id','=','units.id')
                ->pluck('sub_units.id')
                ->toArray();
                  $target = Target::
                  whereYear('target_sheets.created_at',$year)
                  ->whereMonth('target_sheets.created_at',$month)
                  ->whereIn('unit', $unit_id)
                  ->leftjoin('sub_units', 'target_sheets.unit', '=', 'sub_units.id')
                  ->select([DB::raw("SUM(target_sheets.target_total) as target_total"), 'sub_units.name as unitname', 'target_sheets.unit as unitid'])
                  ->groupBy('unit')
                  ->get();

                  $targetArray = collect($target)->map(function ($item) {
                    return number_format($item->target_total, 2, '.', '');
                  })->toArray();
                  $unitArray = collect($target)->map(function ($item) {
                      return $item->unitname;
                  })->toArray();
                  $unitIds = collect($target)->map(function ($item) {
                      return $item->unitid;
                  })->toArray();

                  $collectionArray = [];

                  $collections = Collection::
                  whereYear('created_at',$year)
                  ->whereMonth('created_at',$month)
                  ->whereIn('unit', $unit_id)
                  ->where('hod_approved_status',2)
                  ->get();

                  foreach ($unitIds as $key => $unit) {
                      $unitCollections = $collections->where('unit', $unit);
                      $collectionAmount = $unitCollections->sum(function ($item) {
                          return intval($item->collection_amount);
                      });
                      $collectionArray[$key] = number_format($collectionAmount, 2, '.', '');
                  }
                  if(empty(collect($unitArray)->first())){
                    array_shift($unitArray);
                    array_shift($targetArray);
                    array_shift($collectionArray);
                  }
                  $payload = [
                      'code' => 200,
                      'user_message' => 'success',
                      'targetArray' => $targetArray,
                      'unitArray' => $unitArray,
                      'collectionArray' => $collectionArray,
                      'target' => 0,
                      'collection' => 0,
                  ];
                  return response()->json($payload, 200);
              }
              else{
                  $target = Target::
                  whereYear('created_at',$year)
                  ->whereMonth('created_at',$month)
                  ->where('unit', $unit)
                  ->sum('target_total');

                  $collection = Collection::
                  whereYear('created_at',$year)
                  ->whereMonth('created_at',$month)
                  ->where('hod_approved_status',2)
                  ->where('unit', $unit)
                  ->sum('collection_amount');

                  $payload = [
                      'code' => 200,
                      'user_message' => 'success',
                      'targetArray' => [],
                      'unitArray' => [],
                      'collectionArray' => [],
                      'target' => number_format($target, 2, '.', ''),
                      'collection' => number_format($collection, 2, '.', ''),
                  ];
                  return response()->json($payload, 200);
              }
          }

          //area manager
          elseif (Auth::user()->hasRole('Area')) {

              if($unit==0){
                $unit_id = Unit::where('units.area_id', auth()->user()->area_id)
                ->join('sub_units','sub_units.unit_id','=','units.id')
                ->pluck('sub_units.id')
                ->toArray();
                  $target = Target::
                  whereYear('target_sheets.created_at',$year)
                  ->whereMonth('target_sheets.created_at',$month)
                  ->whereIn('unit', $unit_id)
                  ->leftjoin('sub_units', 'target_sheets.unit', '=', 'sub_units.id')
                  ->select([DB::raw("SUM(target_sheets.target_total) as target_total"), 'sub_units.name as unitname', 'target_sheets.unit as unitid'])
                  ->groupBy('unit')
                  ->get();

                  $targetArray = collect($target)->map(function ($item) {
                      return number_format($item->target_total, 2, '.', '');
                  })->toArray();
                  $unitArray = collect($target)->map(function ($item) {
                      return $item->unitname;
                  })->toArray();
                  $unitIds = collect($target)->map(function ($item) {
                      return $item->unitid;
                  })->toArray();

                  $collectionArray = [];

                  $collections = Collection::
                  whereYear('created_at',$year)
                  ->whereMonth('created_at',$month)
                  ->where('hod_approved_status',2)
                  ->whereIn('unit', $unit_id)
                  ->get();

                  foreach ($unitIds as $key => $unit) {
                      $unitCollections = $collections->where('unit', $unit);
                      $collectionAmount = $unitCollections->sum(function ($item) {
                          return intval($item->collection_amount);
                      });
                      $collectionArray[$key] = number_format($collectionAmount, 2, '.', '');
                  }
                  if(empty(collect($unitArray)->first())){
                    array_shift($unitArray);
                    array_shift($targetArray);
                    array_shift($collectionArray);
                  }
                  $payload = [
                      'code' => 200,
                      'user_message' => 'success',
                      'targetArray' => $targetArray,
                      'unitArray' => $unitArray,
                      'collectionArray' => $collectionArray,
                      'target' => 0,
                      'collection' => 0,
                  ];
                  return response()->json($payload, 200);
              }
              else{
                  $target = Target::
                  whereYear('created_at',$year)
                  ->whereMonth('created_at',$month)
                  ->where('unit', $unit)
                  ->sum('target_total');

                  $collection = Collection::
                  whereYear('created_at',$year)
                  ->whereMonth('created_at',$month)
                  ->where('hod_approved_status',2)
                  ->where('unit', $unit)
                  ->sum('collection_amount');

                  $payload = [
                      'code' => 200,
                      'user_message' => 'success',
                      'targetArray' => [],
                      'unitArray' => [],
                      'collectionArray' => [],
                      'target' => number_format($target, 2, '.', ''),
                      'collection' =>  number_format($collection, 2, '.', ''),
                  ];
                  return response()->json($payload, 200);
              }

          }

          //Unit Manager
          elseif (Auth::user()->hasRole('Unit')) {
            if($unit==0){
                $unit_id = Unit::where('units.id', auth()->user()->unit_id)
                ->join('sub_units','sub_units.unit_id','=','units.id')
                ->pluck('sub_units.id')
                ->toArray();
                  $target = Target::
                  whereYear('target_sheets.created_at',$year)
                  ->whereMonth('target_sheets.created_at',$month)
                  ->whereIn('unit', $unit_id)
                  ->leftjoin('sub_units', 'target_sheets.unit', '=', 'sub_units.id')
                  ->select([DB::raw("SUM(target_sheets.target_total) as target_total"), 'sub_units.name as unitname', 'target_sheets.unit as unitid'])
                  ->groupBy('unit')
                  ->get();

                  $targetArray = collect($target)->map(function ($item) {
                      return number_format($item->target_total, 2, '.', '');
                  })->toArray();
                  $unitArray = collect($target)->map(function ($item) {
                      return $item->unitname;
                  })->toArray();
                  $unitIds = collect($target)->map(function ($item) {
                      return $item->unitid;
                  })->toArray();

                  $collectionArray = [];

                  $collections = Collection::
                  whereYear('created_at',$year)
                  ->whereMonth('created_at',$month)
                  ->where('hod_approved_status',2)
                  ->whereIn('unit', $unit_id)
                  ->get();

                  foreach ($unitIds as $key => $unit) {
                      $unitCollections = $collections->where('unit', $unit);
                      $collectionAmount = $unitCollections->sum(function ($item) {
                          return intval($item->collection_amount);
                      });
                      $collectionArray[$key] = number_format($collectionAmount, 2, '.', '');
                  }
                  if(empty(collect($unitArray)->first())){
                    array_shift($unitArray);
                    array_shift($targetArray);
                    array_shift($collectionArray);
                  }
                  $payload = [
                      'code' => 200,
                      'user_message' => 'success',
                      'targetArray' => $targetArray,
                      'unitArray' => $unitArray,
                      'collectionArray' => $collectionArray,
                      'target' => 0,
                      'collection' => 0,
                  ];
                  return response()->json($payload, 200);
              }
              else{
            // $unit_id = User::where('unit_id', $request->unit)->pluck('sub_unit_id')->toArray();
              $target = Target::
              whereYear('created_at',$year)
                  ->whereMonth('created_at',$month)
              ->where('unit',  $request->unit)
              ->sum('target_total');

              $collection = Collection::
              whereYear('created_at',$year)
              ->whereMonth('created_at',$month)
              ->where('unit',  $request->unit)
              ->where('hod_approved_status',2)
              ->sum('collection_amount');

              $payload = [
                  'code' => 200,
                  'user_message' => 'success',
                  'targetArray' => [],
                  'unitArray' => [],
                  'collectionArray' => [],
                  'target' => number_format($target, 2, '.', ''),
                  'collection' => number_format($collection, 2, '.', ''),
              ];
              return response()->json($payload, 200);
          }
        }

          //Sub Unit user
          else {

              $target = Target::
              whereYear('created_at',$year)
              ->whereMonth('created_at',$month)
              //->where('employee_id', Auth::user()->employee_id)
              ->where('unit', Auth::user()->sub_unit_id)
              ->sum('target_total');

              $collection = Collection::
              whereYear('created_at',$year)
              ->whereMonth('created_at',$month)
              ->where('hod_approved_status',2)
              ->where('created_by', Auth::user()->id)
              ->where('unit', Auth::user()->sub_unit_id)
              ->sum('collection_amount');

              $payload = [
                  'code' => 200,
                  'user_message' => 'success',
                  'targetArray' => [],
                  'unitArray' => [],
                  'collectionArray' => [],
                  'target' => number_format($target, 2, '.', ''),
                  'collection' => number_format($collection, 2, '.', ''),
              ];
              return response()->json($payload, 200);
          }

      }

    //dashboard forecast vs achievement
    public function forecastVsAchievement(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
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

            $type = $request->type;

            $date = date('Y-m-d');

            $startOfWeek = Carbon::now()->startOfWeek();

            $endOfWeek = Carbon::now()->endOfWeek();

            $year = date('Y');

            $month = date('m');

            // head of depertment or admin role
            if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
                if ($type == 'daily') {
                    $forecast = DailyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'daily_forecasts.unit_id')
                        ->select(
                            'daily_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->where('date', $date)
                        ->groupBy('daily_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereDate('collections.created_at', $date)
                        ->where('hod_approved_status',2)
                        ->groupBy('unit')
                        ->get();
                }

                if ($type == 'weekly') {

                    $forecast = DailyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'daily_forecasts.unit_id')
                        ->select(
                            'daily_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->whereBetween('date', [$startOfWeek, $endOfWeek])
                        ->groupBy('daily_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereBetween('collections.created_at', [$startOfWeek, $endOfWeek])
                        ->where('hod_approved_status',2)
                        ->groupBy('unit')
                        ->get();
                }

                if ($type == 'monthly') {

                    $forecast = MonthlyForecast::leftjoin('sub_units','sub_units.id', '=', 'monthly_forecasts.unit_id')
                        ->select(
                            'monthly_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->whereYear('month', $year)
                        ->whereMonth('month', $month)
                        ->groupBy('monthly_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereYear('collections.created_at',$year)
                        ->whereMonth('collections.created_at',$month)
                        ->where('hod_approved_status',2)
                        ->groupBy('unit')
                        ->get();
                }
            }

            // zonal manager
            elseif ($request->user()->hasRole('Zone')) {
                $unit_id = Unit::where('units.zone_id', auth()->user()->zone_id)
                ->join('sub_units','sub_units.unit_id','=','units.id')
                ->pluck('sub_units.id')
                ->toArray();
                if ($type == 'daily') {
                    $forecast = DailyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'daily_forecasts.unit_id')
                        ->select(
                            'daily_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->where('date', $date)
                        ->whereIn('daily_forecasts.unit_id', $unit_id)
                        ->groupBy('daily_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereDate('collections.created_at', $date)
                        ->where('hod_approved_status',2)
                        ->whereIn('unit', $unit_id)
                        ->groupBy('unit')
                        ->get();
                }

                if ($type == 'weekly') {

                    $forecast = DailyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'daily_forecasts.unit_id')
                        ->select(
                            'daily_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->whereBetween('date', [$startOfWeek, $endOfWeek])
                        ->whereIn('daily_forecasts.unit_id', $unit_id)
                        ->groupBy('daily_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereBetween('collections.created_at', [$startOfWeek, $endOfWeek])
                        ->where('hod_approved_status',2)
                        ->whereIn('unit', $unit_id)
                        ->groupBy('unit')
                        ->get();
                }

                if ($type == 'monthly') {

                    $forecast = MonthlyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'monthly_forecasts.unit_id')
                        ->select(
                            'monthly_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->whereYear('month', $year)
                        ->whereMonth('month', $month)
                        ->whereIn('monthly_forecasts.unit_id', $unit_id)
                        ->groupBy('monthly_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereYear('collections.created_at', $year)
                        ->whereMonth('collections.created_at', $month)
                        ->where('hod_approved_status',2)
                        ->whereIn('unit', $unit_id)
                        ->groupBy('unit')
                        ->get();
                }
            }

            //area manager
            elseif ($request->user()->hasRole('Area')) {
                $unit_id = Unit::where('units.area_id', auth()->user()->area_id)
                ->join('sub_units','sub_units.unit_id','=','units.id')
                ->pluck('sub_units.id')
                ->toArray();

                if ($type == 'daily') {
                    $forecast = DailyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'daily_forecasts.unit_id')
                        ->select(
                            'daily_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->where('date', $date)
                        ->whereIn('daily_forecasts.unit_id', $unit_id)
                        ->groupBy('daily_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereDate('collections.created_at', $date)
                        ->where('hod_approved_status',2)
                        ->whereIn('unit', $unit_id)
                        ->groupBy('unit')
                        ->get();
                }

                if ($type == 'weekly') {

                    $forecast = DailyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'daily_forecasts.unit_id')
                        ->select(
                            'daily_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->whereBetween('date', [$startOfWeek, $endOfWeek])
                        ->whereIn('daily_forecasts.unit_id', $unit_id)
                        ->groupBy('daily_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereBetween('collections.created_at', [$startOfWeek, $endOfWeek])
                        ->where('hod_approved_status',2)
                        ->whereIn('unit', $unit_id)
                        ->groupBy('unit')
                        ->get();
                }

                if ($type == 'monthly') {

                    $forecast = MonthlyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'monthly_forecasts.unit_id')
                        ->select(
                            'monthly_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->whereYear('month', $year)
                        ->whereMonth('month', $month)
                        ->whereIn('monthly_forecasts.unit_id', $unit_id)
                        ->groupBy('monthly_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereYear('collections.created_at', $year)
                        ->whereMonth('collections.created_at', $month)
                        ->where('hod_approved_status',2)
                        ->whereIn('unit', $unit_id)
                        ->groupBy('unit')
                        ->get();
                }
            }

            //Unit Manager
            elseif ($request->user()->hasRole('Unit')) {
                $unit_id = User::where('unit_id', auth()->user()->unit_id)->pluck('sub_unit_id')->toArray();
                if ($type == 'daily') {
                    $forecast = DailyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'daily_forecasts.unit_id')
                        ->select(
                            'daily_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->where('date', $date)
                        ->whereIn('daily_forecasts.unit_id', $unit_id)
                        ->groupBy('daily_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )

                        ->whereDate('collections.created_at', $date)
                        ->where('hod_approved_status',2)
                        ->whereIn('unit', $unit_id)
                        ->groupBy('unit')
                        ->get();
                }

                if ($type == 'weekly') {
                    $forecast = DailyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'daily_forecasts.unit_id')
                        ->select(
                            'daily_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->whereBetween('date', [$startOfWeek, $endOfWeek])
                        ->whereIn('daily_forecasts.unit_id',$unit_id)
                        ->groupBy('daily_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereBetween('collections.created_at', [$startOfWeek, $endOfWeek])
                        ->where('hod_approved_status',2)
                        ->whereIn('unit',$unit_id)
                        ->groupBy('unit')
                        ->get();
                }

                if ($type == 'monthly') {

                    $forecast = MonthlyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'monthly_forecasts.unit_id')
                        ->select(
                            'monthly_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->whereYear('month', $year)
                        ->whereMonth('month', $month)
                        ->whereIn('monthly_forecasts.unit_id',$unit_id)
                        ->groupBy('monthly_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereYear('collections.created_at', $year)
                        ->whereMonth('collections.created_at', $month)
                        ->where('hod_approved_status',2)
                        ->whereIn('unit',$unit_id)
                        ->groupBy('unit')
                        ->get();
                }
            }

            //sub unit user
            elseif (Auth::user()->hasRole('Subunit')) {
                if ($type == 'daily') {
                    $forecast = DailyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'daily_forecasts.unit_id')
                        ->select(
                            'daily_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->where('date', $date)
                        ->where('daily_forecasts.unit_id', $request->user()->sub_unit_id)
                        ->groupBy('unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereDate('collections.created_at', $date)
                        ->where('hod_approved_status',2)
                        ->where('unit', $request->user()->sub_unit_id)
                        ->groupBy('unit')
                        ->get();
                }

                if ($type == 'weekly') {
                    $forecast = DailyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'daily_forecasts.unit_id')
                        ->select(
                            'daily_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->whereBetween('date', [$startOfWeek, $endOfWeek])
                        ->where('daily_forecasts.unit_id', $request->user()->sub_unit_id)
                        ->groupBy('unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereBetween('collections.created_at', [$startOfWeek, $endOfWeek])
                        ->where('hod_approved_status',2)
                        ->where('unit', $request->user()->sub_unit_id)
                        ->groupBy('unit')
                        ->get();
                }

                if ($type == 'monthly') {

                    $forecast = MonthlyForecast::leftjoin('sub_units', 'sub_units.id', '=', 'monthly_forecasts.unit_id')
                        ->select(
                            'monthly_forecasts.unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(amount) as total_amount')
                        )
                        ->whereYear('month', $year)
                        ->whereMonth('month', $month)
                        ->where('monthly_forecasts.unit_id', $request->user()->sub_unit_id)
                        ->groupBy('monthly_forecasts.unit_id')
                        ->get();

                    $achievement = Collection::leftjoin('sub_units', 'sub_units.id', '=', 'collections.unit')
                        ->select(
                            'collections.unit as unit_id',
                            'sub_units.name as unit_name',
                            DB::raw('SUM(collection_amount) as total_amount')
                        )
                        ->whereYear('collections.created_at', $year)
                        ->whereMonth('collections.created_at', $month)
                        ->where('hod_approved_status',2)
                        ->where('unit', $request->user()->sub_unit_id)
                        ->groupBy('unit')
                        ->get();
                }
            }

            // Initialize units array
            $units = [];

            // head of depertment or admin role
            if ($request->user()->hasRole('Admin') || $request->user()->hasRole('National') || $request->user()->hasRole('Accounts')) {
                $units = SubUnit::select('id', 'name')->get();
            }

            // zonal manager
            elseif ($request->user()->hasRole('Zone')) {
                $units = SubUnit::where('zone_id', $request->user()->zone_id)->select('id', 'name')->get();
            }

            //area manager
            elseif ($request->user()->hasRole('Area')) {
                $units = SubUnit::where('area_id', $request->user()->area_id)->select('id', 'name')->get();
            }

            //Unit Manager
            elseif ($request->user()->hasRole('Unit') || $request->user()->hasRole('Subunit')) {
                $units = SubUnit::where('unit_id', $request->user()->unit_id)->select('id', 'name')->get();
            }

            $payload = [
                'code' => 200,
                'user_message' => 'success',
                'app_message' => 'success',
                'forecast' => $forecast,
                'achievement' => $achievement,
                'units' => $units,
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

    public function getNotificationCount($id)
    {
        $data = User::find($id);

        $count = count($data->notifications->where('is_read', '0'));

        return response()->json($count, 200);
    }

    public function getNotification(Request $request)
    {
        $count = count(User::find($request->id)->notifications->where('is_read', '0'));

        $data = User::find($request->id);
        $result = $data->notifications;
        $result = $result->sortByDesc('created_at');

        return View('administrative.notification', compact('result'))->render();
    }

    public function changeStatus(Request $request)
    {
        CustomNotification::find($request->id)->update(['is_read' => 1, 'read_datetime' => now()]);
        return response()->json(['data' => ''], 200);
    }
}
