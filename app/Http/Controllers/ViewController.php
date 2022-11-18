<?php

namespace App\Http\Controllers;

use App\Models\MstrEmployee;
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
}
