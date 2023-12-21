<?php

namespace App\Http\Controllers\Administrative;

use DataTables;
use App\Models\Area;
use App\Models\Zone;
use App\Http\Requests\AreaRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class AreaController extends Controller
{
  public function index()
  {
    if (!Gate::allows('area_list')) {
      return abort(401);
    }
    return view('administrative.area.index');
  }

  public function data()
  {
    if (!Gate::allows('area_list')) {
      return abort(401);
    }
    return  $this->getAllData();
  }

  public function create()
  {
    if (!Gate::allows('area_create')) {
      return abort(401);
    }
    $zones = Zone::all();
    return view('administrative.area.create', compact('zones'));
  }

  public function saveOrUpdate(AreaRequest $request)
  {
    if (!Gate::allows('area_create')) {
      return abort(401);
    }

    $area = Area::updateOrCreate(['id' => $request->id], $request->all());

    if ($area) {
      return redirect()->route('administrative.area')->with('success', 'Area Created Successfully');
    } else {
      return redirect()->back()->withInput()->with('error', 'Something Wrong,Please Try Again');
    }
  }

  public function edit(Area $area)
  {
    if (!Gate::allows('area_edit')) {
      return abort(401);
    }
    $data = $area;
    $zones = Zone::all();
    return view('administrative.area.create', compact('data', 'zones'));
  }

  public function getAllData()
  {
    $data = Area::query();
    return Datatables::of($data)
      ->addColumn('action', function ($row) {
        $html = '';
        $html .= '<a href="' . route('administrative.area.edit', $row->id) . '" >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    </a>';
        // $html .= '<a href="#" onclick="deleteData(' . $row->id . ');">
        //                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>
        //           </a>';
        return $html;
      })
      ->editColumn('zone', function ($data) {
        return $data->zone ? $data->zone->name : '';
      })

      ->rawColumns(['zone', 'action'])
      ->blacklist(['created_at', 'updated_at', 'action'])
      ->addIndexColumn()
      ->toJson();
  }

  public function destroy(Area $area)
  {
    if (!Gate::allows('area_delete')) {
      return abort(401);
    }
    $result = [];
    if (count($area->unit) == 0) {
      $result = $area->delete();
    }
    if ($result) {
      echo 'success';
    } else {
      echo 'error';
    }
  }
}
