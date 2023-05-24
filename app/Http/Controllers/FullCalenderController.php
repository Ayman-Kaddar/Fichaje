<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class FullCalenderController extends Controller
{
    public function index(Request $request)
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Treballador")) {
            return redirect()->route("fitxadmin");
        }

        if ($request->ajax()) {

            $data = Event::whereDate('start', '>=', $request->start)
                ->whereDate('end', '<=', $request->end)->where("user_id", Auth::user()->id)
                ->get(['id', 'title', 'start', 'end']);

            return response()->json($data);
        }

        return view('cliente.fullcalender');
    }

    public function ajax(Request $request)
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Treballador")) {
            return redirect()->route("fitxadmin");
        }

        switch ($request->type) {
            case 'add':
                $event = Event::create([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                    'user_id' => Auth::user()->id
                ]);

                return response()->json($event);
                break;

            case 'update':
                $event = Event::find($request->id)->update([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                ]);

                return response()->json($event);
                break;

            case 'delete':
                $event = Event::find($request->id)->delete();

                return response()->json($event);
                break;

            default:
                # code...
                break;
        }
    }
}
