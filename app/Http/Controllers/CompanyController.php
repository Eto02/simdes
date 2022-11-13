<?php

namespace App\Http\Controllers;

use App\Models\MstrCompany;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->logoPath = 'company/logo/';
        $this->loginBgPath = 'company/login_background/';
    }

    public function index()
    {
        // dd(Hash::make('company123'));
        return view('pages.company.index');
    }

    public function getAll()
    {
        $users = Users::with('mstrCompany','modelHasRoles.roles')
        ->whereHas('modelHasRoles.roles', function ($query) {
            $query->where('name','company');
        })
        ->get();

        $data = [];
        $i = 0;

        foreach ($users as $key => $value) {
            $data[$i]['no'] = $i + 1;
            $data[$i]['name'] = $value->name;
            $data[$i]['email'] = $value->email;
            $data[$i]['company'] = $value->mstrCompany['name'];
            $data[$i]['address'] = $value->mstrCompany['address'];
            $data[$i]['phone_number'] = $value->mstrCompany['phone_number'];
            $data[$i]['logo'] = $value->mstrCompany['logo'];
            $data[$i]['login_background'] = $value->mstrCompany['login_background'];
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

            // For table mstr_company
            $companyName = $request->company_name;
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

            // Insert mstr_company
            $logoFilename = date('YmdHis').'_'.$logo->getClientOriginalName();
            $logo->storeAs('public/'.$this->logoPath, $logoFilename);

            $loginBackgroundFilename = date('YmdHis').'_'.$loginBackground->getClientOriginalName();
            $loginBackground->storeAs('public/'.$this->loginBgPath, $loginBackgroundFilename);

            MstrCompany::create([
                'name' => $companyName,
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

            // For table mstr_company
            $companyName = $request->company_name;
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

            // Update mstr_company
            $mstrCompanyFieldUpdate = [
                'name' => $companyName,
                'address' => $address,
                'phone_number' => $phoneNumber,
                'updated_by' => auth()->user()->email
            ];
            if ($logo) {
                $logoFilename = date('YmdHis').'_'.$logo->getClientOriginalName();
                $logo->storeAs('public/'.$this->logoPath, $logoFilename);

                $mstrCompanyFieldUpdate = array_merge($mstrCompanyFieldUpdate,['logo' => $logoFilename]);
            }
            if ($loginBackground) {
                $loginBackgroundFilename = date('YmdHis').'_'.$loginBackground->getClientOriginalName();
                $loginBackground->storeAs('public/'.$this->loginBgPath, $loginBackgroundFilename);

                $mstrCompanyFieldUpdate = array_merge($mstrCompanyFieldUpdate,['login_background' => $loginBackgroundFilename]);
            }

            MstrCompany::where('user_id',$id)
            ->update($mstrCompanyFieldUpdate);

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
            // Delete mstr_company
            MstrCompany::where('user_id',$id)->delete();
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
