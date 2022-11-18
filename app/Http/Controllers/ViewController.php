<?php

namespace App\Http\Controllers;

use App\Models\MstrCompany;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function logo($id)
    {
        try {
            $company = MstrCompany::where('user_id',$id)->first();
            return response()->file('storage/'.$company->logo);
        } catch (\Throwable $th) {
            return response()->file('storage/no-image.png');
        }
    }
}
