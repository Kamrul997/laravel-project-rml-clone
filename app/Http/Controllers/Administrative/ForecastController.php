<?php

namespace App\Http\Controllers\Administrative;

use DataTables;

use Illuminate\Support\Facades\DB;

use App\Models\Unit;

use App\Models\User;

use Illuminate\Http\Request;

use App\Models\DailyForecast;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;

use App\Models\MonthlyForecast;

class ForecastController extends Controller
{
    public function index(Request $request)
    {

        if (!Gate::allows('forecast_list')) {
            return abort(401);
        }

        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {

            $months = range(1, 12); // Create an array for 12 months (1 to 12)

            $month = MonthlyForecast::select(
                DB::raw('YEAR(month) as year'),
                DB::raw('LPAD(MONTH(month), 2, "0") as month'),
                DB::raw('COALESCE(SUM(amount), 0) as total')
            )
                ->groupBy(DB::raw('YEAR(month)'), DB::raw('LPAD(MONTH(month), 2, "0")'))
                ->orderBy('month', 'ASC')
                ->get();

            if ($request->date_wise == 'date_wise') {
                $from = $request->from;
                $to = $request->to;
                $date = DailyForecast::whereBetween('date', [$from, $to])
                    ->select(
                        DB::raw('DATE(date) as date'),
                        DB::raw('COALESCE(SUM(amount), 0) as total')
                    )
                    ->groupBy(DB::raw('DATE(date)'))
                    ->orderBy('date', 'ASC')
                    ->get();
            } else {
                $date = DailyForecast::select(
                    DB::raw('DATE(date) as date'),
                    DB::raw('COALESCE(SUM(amount), 0) as total')
                )
                    ->groupBy(DB::raw('DATE(date)'))
                    ->orderBy('date', 'ASC')
                    ->get();
            }
        }

        // zonal manager
        elseif (Auth::user()->hasRole('Zone')) {
            $unit_id = Unit::where('units.zone_id', auth()->user()->zone_id)
            ->join('sub_units','sub_units.unit_id','=','units.id')
            ->pluck('sub_units.id')
            ->toArray();
            $months = range(1, 12); // Create an array for 12 months (1 to 12)

            $month = MonthlyForecast::select(
                DB::raw('YEAR(month) as year'),
                DB::raw('LPAD(MONTH(month), 2, "0") as month'),
                DB::raw('COALESCE(SUM(amount), 0) as total')
            )
                ->groupBy(DB::raw('YEAR(month)'), DB::raw('LPAD(MONTH(month), 2, "0")'))
                ->whereIn('unit_id', $unit_id)
                ->orderBy('month', 'ASC')
                ->get();

            if ($request->date_wise == 'date_wise') {
                $from = $request->from;
                $to = $request->to;
                $date = DailyForecast::whereBetween('date', [$from, $to])
                    ->select(
                        DB::raw('DATE(date) as date'),
                        DB::raw('COALESCE(SUM(amount), 0) as total')
                    )
                    ->groupBy(DB::raw('DATE(date)'))
                    ->whereIn('unit_id', $unit_id)
                    ->orderBy('date', 'ASC')
                    ->get();
            } else {
                $date = DailyForecast::select(
                    DB::raw('DATE(date) as date'),
                    DB::raw('COALESCE(SUM(amount), 0) as total')
                )
                    ->groupBy(DB::raw('DATE(date)'))
                    ->whereIn('unit_id', $unit_id)
                    ->orderBy('date', 'ASC')
                    ->get();
            }
        }

        //area manager
        elseif (Auth::user()->hasRole('Area')) {
            $unit_id = Unit::where('units.area_id', auth()->user()->area_id)
            ->join('sub_units','sub_units.unit_id','=','units.id')
            ->pluck('sub_units.id')
            ->toArray();
            $months = range(1, 12); // Create an array for 12 months (1 to 12)

            $month = MonthlyForecast::select(
                DB::raw('YEAR(month) as year'),
                DB::raw('LPAD(MONTH(month), 2, "0") as month'),
                DB::raw('COALESCE(SUM(amount), 0) as total')
            )
                ->groupBy(DB::raw('YEAR(month)'), DB::raw('LPAD(MONTH(month), 2, "0")'))
                ->whereIn('unit_id', $unit_id)
                ->orderBy('month', 'ASC')
                ->get();

            if ($request->date_wise == 'date_wise') {
                $from = $request->from;
                $to = $request->to;
                $date = DailyForecast::whereBetween('date', [$from, $to])
                    ->select(
                        DB::raw('DATE(date) as date'),
                        DB::raw('COALESCE(SUM(amount), 0) as total')
                    )
                    ->groupBy(DB::raw('DATE(date)'))
                    ->whereIn('unit_id', $unit_id)
                    ->orderBy('date', 'ASC')
                    ->get();
            } else {
                $date = DailyForecast::select(
                    DB::raw('DATE(date) as date'),
                    DB::raw('COALESCE(SUM(amount), 0) as total')
                )
                    ->groupBy(DB::raw('DATE(date)'))
                    ->whereIn('unit_id', $unit_id)
                    ->orderBy('date', 'ASC')
                    ->get();
            }
        }

        //Unit Manager
        elseif (Auth::user()->hasRole('Unit')) {
            $unit_id = User::where('unit_id', auth()->user()->unit_id)->pluck('sub_unit_id')->toArray();
            $months = range(1, 12); // Create an array for 12 months (1 to 12)

            $month = MonthlyForecast::select(
                DB::raw('YEAR(month) as year'),
                DB::raw('LPAD(MONTH(month), 2, "0") as month'),
                DB::raw('COALESCE(SUM(amount), 0) as total')
            )
                ->groupBy(DB::raw('YEAR(month)'), DB::raw('LPAD(MONTH(month), 2, "0")'))
                ->whereIn('unit_id', $unit_id)
                ->orderBy('month', 'ASC')
                ->get();

            if ($request->date_wise == 'date_wise') {
                $from = $request->from;
                $to = $request->to;
                $date = DailyForecast::whereBetween('date', [$from, $to])
                    ->select(
                        DB::raw('DATE(date) as date'),
                        DB::raw('COALESCE(SUM(amount), 0) as total')
                    )
                    ->groupBy(DB::raw('DATE(date)'))
                    ->whereIn('unit_id', $unit_id)
                    ->orderBy('date', 'ASC')
                    ->get();
            } else {
                $date = DailyForecast::select(
                    DB::raw('DATE(date) as date'),
                    DB::raw('COALESCE(SUM(amount), 0) as total')
                )
                    ->groupBy(DB::raw('DATE(date)'))
                    ->whereIn('unit_id', $unit_id)
                    ->orderBy('date', 'ASC')
                    ->get();
            }
        }
        //Sub Unit user
        else {
            $unit_id = auth()->user()->sub_unit_id;
            $months = range(1, 12); // Create an array for 12 months (1 to 12)

            $month = MonthlyForecast::select(
                DB::raw('YEAR(month) as year'),
                DB::raw('LPAD(MONTH(month), 2, "0") as month'),
                DB::raw('COALESCE(SUM(amount), 0) as total')
            )
                ->groupBy(DB::raw('YEAR(month)'), DB::raw('LPAD(MONTH(month), 2, "0")'))
                ->where('unit_id',$unit_id)
                ->where('created_by',auth()->user()->id)
                ->orderBy('month', 'ASC')
                ->get();

            if ($request->date_wise == 'date_wise') {
                $from = $request->from;
                $to = $request->to;
                $date = DailyForecast::whereBetween('date', [$from, $to])
                    ->select(
                        DB::raw('DATE(date) as date'),
                        DB::raw('COALESCE(SUM(amount), 0) as total')
                    )
                    ->groupBy(DB::raw('DATE(date)'))
                    ->where('unit_id',$unit_id)
                    ->where('created_by',auth()->user()->id)
                    ->orderBy('date', 'ASC')
                    ->get();
            } else {
                $date = DailyForecast::select(
                    DB::raw('DATE(date) as date'),
                    DB::raw('COALESCE(SUM(amount), 0) as total')
                )
                    ->groupBy(DB::raw('DATE(date)'))
                    ->where('unit_id',$unit_id)
                    ->where('created_by',auth()->user()->id)
                    ->orderBy('date', 'ASC')
                    ->get();
            }
        }

        return view('administrative.forecast.index', compact('month', 'date'));
    }
    public function storeDateWise(Request $request)
    {
        if (!Gate::allows('forecast_create')) {
            return abort(401);
        }
        $startDate = strtotime(date('Y-m-d', strtotime($request->date)));
        $currentDate = strtotime(date('Y-m-d'));
        $date = $request->date." 00:00:00";

        $count = DailyForecast::where('created_by',auth()->user()->id)->where('date',$date)->count();

        if ($startDate < $currentDate) {
            return response()->json(["status" => false, "msg" => 'Not Allowed']);
        }
        if($count>0){
            return response()->json(["status" => false, "msg" => 'Forecast already exists']);
        }
        $data = DailyForecast::updateOrCreate(
            [
                'date' => $request->date,
                'created_by' => Auth::user()->id,
                'unit_id' => Auth::user()->sub_unit_id,
            ],
            [
                'amount' => $request->amount,
            ]
        );
        return response()->json(["status" => true, "msg" => 'Daily Forecasting is Successful', 'amount' => $data->amount]);
    }


    public function getDateData($date)
    {
        $data = DailyForecast::whereDate('date', $date)->where('created_by', Auth::user()->id)->where('unit_id', Auth::user()->sub_unit_id)->first();
        $payload = [
            'code' => 200,
            'user_message' => 'Success',
            'data' => $data ? $data->amount : '',
        ];

        return response()->json($payload, 200);
    }

    public function storeMonthWise(Request $request)
    {

        if (!Gate::allows('forecast_create')) {
            return abort(401);
        }
        $startDate = $request->month . '-01';
        $currentDate = date('Y-m-01');

        if($startDate!=$currentDate){
            return response()->json(["status" => false, "msg" => 'Not Allowed']);
        }

        if ($startDate <= $currentDate) {
            $exist = MonthlyForecast::where([
                'month' => $startDate, 'created_by' => Auth::user()->id,
                'unit_id' => Auth::user()->sub_unit_id,
            ])->first();
            if ($startDate == $currentDate) {
                if (!empty($exist)) {
                    return response()->json(["status" => false, "msg" => 'Not Allowed']);
                }
            } else {
                return response()->json(["status" => false, "msg" => 'Not Allowed']);
            }
        }
        $data = MonthlyForecast::updateOrCreate(
            [
                'month' => $startDate,
                'created_by' => Auth::user()->id,
                'unit_id' => Auth::user()->sub_unit_id,
            ],
            [
                'amount' => $request->amount,

            ]
        );
        return response()->json(["status" => true, "msg" => 'Monthly Forecasting is Successful', 'amount' => $data->amount]);
    }

    public function getMonthData($month)
    {
        $data = MonthlyForecast::whereDate('month', $month . '-01')->where('created_by', Auth::user()->id)->where('unit_id', Auth::user()->sub_unit_id)->first();
        $payload = [
            'code' => 200,
            'user_message' => 'Success',
            'data' => $data ? $data->amount : '',
        ];

        return response()->json($payload, 200);
    }
}
