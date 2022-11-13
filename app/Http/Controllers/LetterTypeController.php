<?php

namespace App\Http\Controllers;

use App\Models\MstrLetterType;
use Illuminate\Http\Request;

class LetterTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pages.letter_type.index');
    }

    public function getAll(Request $request)
    {
        $mstrLetterType = MstrLetterType::all();
        return response()->json($mstrLetterType);
    }

    public function store(Request $request)
    {
        try {
            $name = $request->name;
            $description = $request->description;

            MstrLetterType::create([
                'company_id' => auth()->user()->company->company_id,
                'name' => $name,
                'description' => $description,
                'modified_by' => auth()->user()->email
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

            MstrLetterType::where('letter_type_id',$id)
            ->update([
                'name' => $name,
                'description' => $description,
                'modified_by' => auth()->user()->email
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
            MstrLetterType::where('letter_type_id',$id)->delete();

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
