<?php

namespace App\Http\Controllers;

use App\Models\MstrEmployee;
use App\Models\TrService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pages.service.index');
    }

    public function getAll()
    {
        $user = auth()->user();
        if ($user->roles[0]->name == 'superadmin') {
            $trService = TrService::with('mstrServiceType')
            ->orderBy('created_at','ASC')
            ->get();
        } else {
            $employee = MstrEmployee::where('user_id',$user->id)->first();

            $trService = TrService::with('mstrServiceType')
            ->where('employee_id',$employee->employee_id)
            ->orderBy('created_at','ASC')
            ->get();
        }

        $data = [];
        $i = 0;

        foreach ($trService as $key => $value) {
            $data[$i]['no'] = $i + 1;
            $data[$i]['service_id'] = $value->service_id;
            $data[$i]['nik'] = $value->nik;
            $data[$i]['name'] = $value->name;
            $data[$i]['service_type_id'] = $value->service_type_id;
            $data[$i]['service_type'] = $value->mstrServiceType->name;
            $data[$i]['letter_number'] = $value->letter_number;
            $data[$i]['serviced_by'] = $value->serviced_by;
            $data[$i]['notes'] = $value->notes;
            $i++;
        }
        return response()->json(['data' => $data, 'recordsTotal' => count($data), 'recordsFiltered' => count($data)]);
    }

    public function store(Request $request)
    {
        try {
            $nik = $request->nik;
            $name = $request->name;
            $serviceTypeId = $request->service_type_id;
            $letterNumber = $request->letter_number;
            // $servicedBy = $request->serviced_by;
            $notes = $request->notes;

            $employee = MstrEmployee::where('user_id',auth()->user()->id)->first();

            TrService::create([
                'employee_id' => $employee->employee_id,
                'nik' => $nik,
                'name' => $name,
                'service_type_id' => $serviceTypeId,
                'letter_number' => $letterNumber,
                'serviced_by' => auth()->user()->name,
                'notes' => $notes,
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
            $nik = $request->nik;
            $name = $request->name;
            $serviceTypeId = $request->service_type_id;
            $letterNumber = $request->letter_number;
            // $servicedBy = $request->serviced_by;
            $notes = $request->notes;

            TrService::where('service_id',$id)
            ->update([
                'nik' => $nik,
                'name' => $name,
                'service_type_id' => $serviceTypeId,
                'letter_number' => $letterNumber,
                'serviced_by' => auth()->user()->name,
                'notes' => $notes,
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
            TrService::where('service_id',$id)->delete();

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
