<?php

namespace App\Http\Controllers\Administrative;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;

use App\Models\Unit;

use App\Models\SubUnit;

use App\Models\Area;

use App\Models\Zone;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National') || Auth::user()->hasRole('Accounts')) {
            $data = User::where('id', auth()->user()->id)->first();
            $unit = null;
        }
        elseif (Auth::user()->hasRole('Zone')) {
            $data = User::where('id', auth()->user()->id)->first();
            $unit = Zone::where('id', auth()->user()->zone_id)->first();
        } elseif (Auth::user()->hasRole('Area')) {
            $data = User::where('id', auth()->user()->id)->first();
            $unit = Area::where('id', auth()->user()->area_id)->first();
        } elseif (Auth::user()->hasRole('Unit')) {
            $data = User::where('id', auth()->user()->id)->first();
            $unit = Unit::where('id', auth()->user()->unit_id)->first();
        } else {
            $data = User::where('id', auth()->user()->id)->first();
            $unit = SubUnit::where('id', auth()->user()->sub_unit_id)->first();
        }

        return view('administrative.profile.index', compact('data', 'unit'));
    }

    public function resetPassword()
    {
        return view('administrative.profile.reset-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'The current password is incorrect.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
