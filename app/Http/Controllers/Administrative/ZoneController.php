<?php

namespace App\Http\Controllers\Administrative;

use DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ZoneRequest;
use App\Models\Zone;
use Illuminate\Support\Facades\Gate;

class ZoneController extends Controller
{
  public function index()
  {
    if (!Gate::allows('zone_list')) {
      return abort(401);
    }
    return view('administrative.zone.index');
  }

  public function data()
  {
    if (!Gate::allows('zone_list')) {
      return abort(401);
    }
    return  $this->getAllData();
  }

  public function create()
  {
    if (!Gate::allows('zone_create')) {
      return abort(401);
    }
    return view('administrative.zone.create');
  }

  public function saveOrUpdate(ZoneRequest $request)
  {
    if (!Gate::allows('zone_create')) {
      return abort(401);
    }

    $zone = Zone::updateOrCreate(['id' => $request->id], $request->all());

    if ($zone) {
      return redirect()->route('administrative.zone')->with('success', 'Zone Created Successfully');
    } else {
      return redirect()->back()->withInput()->with('error', 'Something Wrong,Please Try Again');
    }
  }

  public function edit(Zone $zone)
  {
    if (!Gate::allows('zone_edit')) {
      return abort(401);
    }
    $data = $zone;
    return view('administrative.zone.create', compact('data'));
  }

  public function getAllData()
  {
    $data = Zone::query();
    return Datatables::of($data)
      ->addColumn('action', function ($row) {
        $html = '';
        $html .= '
        <ul class="orderDatatable_actions mb-0 d-flex justify-content-between flex-wrap">

                        <li>
                            <a href="' . route('administrative.zone.edit', $row->id) . '" class="edit">
                                <i class="uil uil-edit"></i>
                            </a>
                        </li>

                    </ul>';
        // $html .= '<a href="#" onclick="deleteData(' . $row->id . ');">
        //                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>
        //           </a>';
        return $html;
      })

      ->rawColumns(['action'])
      ->blacklist(['created_at', 'updated_at', 'action'])
      ->addIndexColumn()
      ->toJson();
  }

  public function destroy(Zone $zone)
  {
    if (!Gate::allows('zone_delete')) {
      return abort(401);
    }
    $result = [];
    if (count($zone->area) == 0) {
      $result = $zone->delete();
    }
    if ($result) {
      echo 'success';
    } else {
      echo 'error';
    }
  }
}
