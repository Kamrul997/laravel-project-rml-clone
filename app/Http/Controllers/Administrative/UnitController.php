<?php

namespace App\Http\Controllers\Administrative;

use DataTables;
use App\Models\Area;
use App\Models\Unit;
use App\Models\Zone;
use App\Models\SubUnit;
use Illuminate\Http\Request;
use App\Http\Requests\AreaRequest;
use App\Http\Requests\UnitRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Traits\SendNotificationTrait;

class UnitController extends Controller
{
  public function index()
  {
    if (!Gate::allows('unit_list')) {
      return abort(401);
    }
    return view('administrative.unit.index');
  }

  public function data()
  {
    if (!Gate::allows('unit_list')) {
      return abort(401);
    }
    return  $this->getAllData();
  }

  public function create()
  {
    if (!Gate::allows('unit_create')) {
      return abort(401);
    }
    $zones = Zone::all();
    $areas = Area::all();
    return view('administrative.unit.create', compact('zones', 'areas'));
  }

  public function saveOrUpdate(UnitRequest $request)
  {
    if (!Gate::allows('unit_create')) {
      return abort(401);
    }

    $unit = Unit::updateOrCreate(['id' => $request->id], $request->all());
    if ($unit) {
      return redirect()->route('administrative.unit')->with('success', 'Unit Created Successfully');
    } else {
      return redirect()->back()->withInput()->with('error', 'Something Wrong,Please Try Again');
    }
  }

  public function edit(Unit $unit)
  {
    if (!Gate::allows('unit_edit')) {
      return abort(401);
    }
    $data = $unit;
    $zones = Zone::all();
    $areas = Area::all();
    return view('administrative.unit.create', compact('data', 'zones', 'areas'));
  }

  public function getAllData()
  {
    $data = Unit::query();
    return Datatables::of($data)
      ->addColumn('action', function ($row) {
        $html = '';
        $html .= '<ul class="orderDatatable_actions mb-0 d-flex justify-content-between flex-wrap">

        <li>
            <a href="' . route('administrative.unit.edit', $row->id) . '" class="edit">
                <i class="uil uil-edit"></i>
            </a>
        </li>

    </ul>';
        // $html .= '<a href="#" onclick="deleteData(' . $row->id . ');">
        //                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>
        //           </a>';
        return $html;
      })
      ->editColumn('zone', function ($data) {
        return $data->zone ? $data->zone->name : '';
      })
      ->editColumn('area', function ($data) {
        return $data->area ? $data->area->name : '';
      })

      ->rawColumns(['zone', 'area', 'action'])
      ->blacklist(['created_at', 'updated_at', 'action'])
      ->addIndexColumn()
      ->toJson();
  }

  public function destroy(Unit $unit)
  {
    if (!Gate::allows('unit_delete')) {
      return abort(401);
    }
    $result = [];
    if (count($unit->subUnit) == 0) {
      $result = $unit->delete();
    }
    if ($result) {
      echo 'success';
    } else {
      echo 'error';
    }
  }

  public function getArea(Request $request)
  {
    $data = Area::where('zone_id', $request->id)->pluck('id', 'name');
    return response()->json(['data' => $data]);
  }

  public function getSubUnit(Request $request)
  {
    $data = SubUnit::where('unit_id', $request->id)->pluck('id', 'name');
    return response()->json(['data' => $data]);
  }
}
