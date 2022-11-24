<?php

namespace App\Http\Controllers;

use App\Models\MstrEmployee;
use App\Models\MstrSettings;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function logo($id)
    {
        try {
            $employee = MstrEmployee::where('user_id',$id)->first();
            return response()->file('storage/'.$employee->logo);
        } catch (\Throwable $th) {
            return response()->file('storage/no-image.png');
        }
    }

    public function settings($key)
    {
        $type = '';
        try {
            $mstrSettings = MstrSettings::where('key',$key)->first();
            $type = $mstrSettings->type;
            if ($type == 'text') {
                return response()->json($mstrSettings->value);
            } else {
                return response()->file('storage/'.$mstrSettings->value);
            }
        } catch (\Throwable $th) {
            if ($type == 'text') {
                return response()->json('');
            } else {
                return response()->file('storage/no-image.png');
            }
        }
    }
}
