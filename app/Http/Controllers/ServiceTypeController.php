<?php

namespace App\Http\Controllers;

use App\Models\MstrEmployee;
use App\Models\MstrServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pages.service_type.index');
    }

    public function getAll()
    {
        $employee = MstrEmployee::where('user_id',auth()->user()->id)->first();

        $mstrServiceType = MstrServiceType::where('employee_id',$employee->employee_id)
        ->orderBy('created_at','ASC')
        ->get();

        $data = [];
        $i = 0;

        foreach ($mstrServiceType as $key => $value) {
            $data[$i]['no'] = $i + 1;
            $data[$i]['service_type_id'] = $value->service_type_id;
            $data[$i]['name'] = $value->name;
            $data[$i]['description'] = $value->description;
            $i++;
        }
        return response()->json(['data' => $data, 'recordsTotal' => count($data), 'recordsFiltered' => count($data)]);
    }

    public function store(Request $request)
    {
        try {
            $name = $request->name;
            $description = $request->description;

            $employee = MstrEmployee::where('user_id',auth()->user()->id)->first();

            MstrServiceType::create([
                'employee_id' => $employee->employee_id,
                'name' => $name,
                'description' => $description,
                'created_by' => auth()->user()->email
            ]);

            return response()->json([
                'code' => 200,
                'message' => 'Sukses disimpan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $name = $request->name;
            $description = $request->description;

            MstrServiceType::where('service_type_id',$id)
            ->update([
                'name' => $name,
                'description' => $description,
                'updated_by' => auth()->user()->email
            ]);

            return response()->json([
                'code' => 200,
                'message' => 'Sukses diubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            MstrServiceType::where('service_type_id',$id)->delete();

            return response()->json([
                'code' => 200,
                'message' => 'Sukses dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
}
