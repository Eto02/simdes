<?php

namespace App\Http\Controllers;

use App\Models\TrServiceFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->serviceFilePath = 'company/service_file/';
    }

    public function getAll(Request $request)
    {
        $serviceId = $request->service_id;

        $trServiceFile = TrServiceFile::with('mstrFileType')
        ->where('service_id',$serviceId)
        ->orderBy('created_at','ASC')
        ->get();

        $data = [];
        $i = 0;

        foreach ($trServiceFile as $key => $value) {
            $data[$i]['no'] = $i + 1;
            $data[$i]['service_file_id'] = $value->service_file_id;
            $data[$i]['file_type_id'] = $value->file_type_id;
            $data[$i]['file_type'] = $value->mstrFileType->name;
            $data[$i]['file_path'] = 'storage/'.$value->file_name;
            $data[$i]['file_location'] = $value->file_location;
            $i++;
        }
        return response()->json(['data' => $data, 'recordsTotal' => count($data), 'recordsFiltered' => count($data)]);
    }

    public function store(Request $request)
    {
        try {
            $serviceId = $request->service_id;
            $fileTypeId = $request->file_type_id;
            $fileName = $request->file_name;
            $fileLocation = $request->file_location;

            TrServiceFile::create([
                'service_id' => $serviceId,
                'file_type_id' => $fileTypeId,
                'file_name' => $fileName,
                'file_location' => $fileLocation,
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
            $fileTypeId = $request->file_type_id;
            $fileName = $request->file_name;
            $fileLocation = $request->file_location;

            $serviceFileUpdateField = [
                'file_type_id' => $fileTypeId,
                'file_location' => $fileLocation,
                'updated_by' => auth()->user()->email
            ];

            // check file exist
            if ($fileName) {
                // remove old file
                $trServiceFile = TrServiceFile::find($id);
                Storage::disk('public')->delete($trServiceFile->file_name);
                $serviceFileUpdateField = array_merge($serviceFileUpdateField,['file_name' => $fileName]);
            }

            TrServiceFile::where('service_file_id',$id)
            ->update($serviceFileUpdateField);

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
            TrServiceFile::where('service_file_id',$id)->delete();

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

    public function upload(Request $request)
    {
        try {
            $file = $request->file('file_name');

            $fileName = date('YmdHis').'_'.$file->getClientOriginalName();
            $file->storeAs('public/'.$this->serviceFilePath, $fileName);

            return response()->json([
                'file_name' => $this->serviceFilePath.$fileName
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'file_name' => null
            ]);
        }
    }
}
