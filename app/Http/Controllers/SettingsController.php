<?php

namespace App\Http\Controllers;

use App\Models\MstrSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->settingsFilePath = 'settings/';
    }

    public function index()
    {
        return view('pages.settings.index');
    }

    public function getAll()
    {
        $mstrSettings = MstrSettings::all();

        $data = [];
        $i = 0;

        foreach ($mstrSettings as $key => $value) {
            $data[$i]['no'] = $i + 1;
            $data[$i]['key'] = $value->key;
            $data[$i]['value'] = $value->value;
            $data[$i]['show_value'] = $value->type == 'text' ? $value->value : 'storage/'.$value->value;
            $data[$i]['description'] = $value->description;
            $data[$i]['type'] = $value->type;
            $i++;
        }
        return response()->json(['data' => $data, 'recordsTotal' => count($data), 'recordsFiltered' => count($data)]);
    }

    public function update(Request $request, $key)
    {
        try {
            $value = $request->value;
            $description = $request->description;

            $mstrSettings = MstrSettings::where('key',$key)->first();
            if ($value != null && $mstrSettings->value != $value) {
                // remove old file
                Storage::disk('public')->delete($mstrSettings->value);
            }

            $settingsUpdateField = [
                'description' => $description,
                'updated_by' => auth()->user()->email
            ];

            if ($value) {
                $settingsUpdateField = array_merge($settingsUpdateField,['value' => $value]);
            }

            MstrSettings::where('key',$key)
            ->update($settingsUpdateField);

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

    public function upload(Request $request, $key)
    {
        try {
            $file = $request->file('value');

            $exp = explode(".",$file->getClientOriginalName());
            $fileName = $key.'.'.$exp[count($exp)-1];
            $file->storeAs('public/'.$this->settingsFilePath, $fileName);

            return response()->json([
                'value' => $this->settingsFilePath.$fileName
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'value' => null
            ]);
        }
    }
}
