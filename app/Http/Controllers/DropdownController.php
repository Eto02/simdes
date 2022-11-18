<?php

namespace App\Http\Controllers;

use App\Models\MstrEmployee;
use App\Models\MstrFileType;
use App\Models\MstrServiceType;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    public function serviceType()
    {
        $employee = MstrEmployee::where('user_id',auth()->user()->id)->first();
        
        $mstrServiceType = MstrServiceType::where('employee_id',$employee->employee_id)
        ->orderBy('created_at','ASC')
        ->get();

        $data = [];
        $i = 0;

        foreach ($mstrServiceType as $key => $value) {
            $data[$i]['value'] = $value->service_type_id;
            $data[$i]['text'] = $value->name;
            $i++;
        }
        return $data;
    }

    public function fileType()
    {
        $employee = MstrEmployee::where('user_id',auth()->user()->id)->first();
        
        $mstrFileType = MstrFileType::where('employee_id',$employee->employee_id)
        ->orderBy('created_at','ASC')
        ->get();

        $data = [];
        $i = 0;

        foreach ($mstrFileType as $key => $value) {
            $data[$i]['value'] = $value->file_type_id;
            $data[$i]['text'] = $value->name;
            $i++;
        }
        return $data;
    }
}
