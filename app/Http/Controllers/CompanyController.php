<?php

namespace App\Http\Controllers;

use App\Models\MstrEmployee;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->logoPath = 'employee/logo/';
        $this->loginBgPath = 'employee/login_background/';
    }

    public function index()
    {
        return view('pages.employee.index');
    }

    public function getAll()
    {
        $users = Users::with('mstrEmployee','modelHasRoles.roles')
        ->whereHas('modelHasRoles.roles', function ($query) {
            $query->where('name','employee');
        })
        ->get();

        $data = [];
        $i = 0;

        foreach ($users as $key => $value) {
            $data[$i]['no'] = $i + 1;
            $data[$i]['name'] = $value->name;
            $data[$i]['email'] = $value->email;
            $data[$i]['employee_id'] = $value->mstrEmployee->employee_id;
            $data[$i]['employee_name'] = $value->mstrEmployee->name;
            $data[$i]['address'] = $value->mstrEmployee->address;
            $data[$i]['phone_number'] = $value->mstrEmployee->phone_number;
            $data[$i]['logo'] = 'storage/'.$value->mstrEmployee->logo;
            $data[$i]['login_background'] = 'storage/'.$value->mstrEmployee->login_background;
            $i++;
        }

        return response()->json(['data' => $data, 'recordsTotal' => count($data), 'recordsFiltered' => count($data)]);
    }

    public function store(Request $request)
    {
        try {
            // For table users
            $name = $request->name;
            $email = $request->email;
            $password = $request->password;

            // For table mstr_employee
            $employeeName = $request->employee_name;
            $address = $request->address;
            $phoneNumber = $request->phone_number;
            $logo = $request->file('logo');
            $loginBackground = $request->file('login_background');

            // Insert users
            Users::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Insert mstr_employee
            $logoFilename = date('YmdHis').'_'.$logo->getClientOriginalName();
            $logo->storeAs('public/'.$this->logoPath, $logoFilename);

            $loginBackgroundFilename = date('YmdHis').'_'.$loginBackground->getClientOriginalName();
            $loginBackground->storeAs('public/'.$this->loginBgPath, $loginBackgroundFilename);

            MstrEmployee::create([
                'name' => $employeeName,
                'address' => $address,
                'phone_number' => $phoneNumber,
                'logo' => $logoFilename,
                'login_background' => $loginBackgroundFilename,
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
            // For table users
            $name = $request->name;
            $email = $request->email;
            $password = $request->password;

            // For table mstr_employee
            $employeeName = $request->employee_name;
            $address = $request->address;
            $phoneNumber = $request->phone_number;
            $logo = $request->file('logo');
            $loginBackground = $request->file('login_background');

            // Update users
            $usersFieldUpdate = [
                'name' => $name,
                'email' => $email,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            if ($password) {
                $usersFieldUpdate = array_merge($usersFieldUpdate,['password' => Hash::make($password)]);
            }
            Users::where('id',$id)
            ->update($usersFieldUpdate);

            // Update mstr_employee
            $mstrEmployeeFieldUpdate = [
                'name' => $employeeName,
                'address' => $address,
                'phone_number' => $phoneNumber,
                'updated_by' => auth()->user()->email
            ];
            if ($logo) {
                $logoFilename = date('YmdHis').'_'.$logo->getClientOriginalName();
                $logo->storeAs('public/'.$this->logoPath, $logoFilename);

                $mstrEmployeeFieldUpdate = array_merge($mstrEmployeeFieldUpdate,['logo' => $logoFilename]);
            }
            if ($loginBackground) {
                $loginBackgroundFilename = date('YmdHis').'_'.$loginBackground->getClientOriginalName();
                $loginBackground->storeAs('public/'.$this->loginBgPath, $loginBackgroundFilename);

                $mstrEmployeeFieldUpdate = array_merge($mstrEmployeeFieldUpdate,['login_background' => $loginBackgroundFilename]);
            }

            MstrEmployee::where('user_id',$id)
            ->update($mstrEmployeeFieldUpdate);

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
            // Delete mstr_employee
            MstrEmployee::where('user_id',$id)->delete();
            // Delete users
            Users::where('id',$id)->delete();

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
