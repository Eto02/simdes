<?php

namespace App\Http\Controllers;

use App\Models\MstrServiceType;
use App\Models\MstrSettings;
use App\Models\TrService;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pages.report.index');
    }

    public function getData(Request $request)
    {
        try {
            $date = $request->date;

            if ($date) {
                $mstrServiceType = MstrServiceType::with(['trService' => function($query) use ($date) {
                    $dateExp = explode('-', date('Y-m', strtotime($date)));
                    $query->whereMonth('created_at',$dateExp[1]);
                    $query->whereYear('created_at',$dateExp[0]);
                }])
                ->get();
            } else {
                $mstrServiceType = MstrServiceType::with('trService')->get();
            }
            $array = [
                'labels' => [],
                'data' => [],
                'colors' => []
            ];

            foreach ($mstrServiceType as $key => $value) {
                array_push($array['labels'], $value->name);
                array_push($array['data'], count($value->trService));
            }

            return response()->json([
                'code' => 200,
                'message' => 'Success',
                'data' => $array
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => $th->getMessage(),
                'data' => []
            ]);
        }
    }

    public function print(Request $request)
    {
        $date = $request->date;
        $pie = $request->pie;
        $bar = $request->bar;

        if ($date) {
            $mstrServiceType = MstrServiceType::with(['trService' => function($query) use ($date) {
                $dateExp = explode('-', date('Y-m', strtotime($date)));
                $query->whereMonth('created_at',$dateExp[1]);
                $query->whereYear('created_at',$dateExp[0]);
            }])
            ->get();
        } else {
            $mstrServiceType = MstrServiceType::with('trService')->get();
        }
        
        $service = [];
        $i = 0;
        foreach ($mstrServiceType as $key => $value) {
            $service[$i]['service'] = $value->name;
            $service[$i]['amount'] = count($value->trService);
            $i++;
        }

        if ($date) {
            $serviceHistory = TrService::with('mstrServiceType')
            ->whereHas(function($query) use ($date) {
                $dateExp = explode('-', date('Y-m', strtotime($date)));
                $query->whereMonth('created_at',$dateExp[1]);
                $query->whereYear('created_at',$dateExp[0]);
            })
            ->get();
        } else {
            $serviceHistory = TrService::with('mstrServiceType')->get();
        }

        // $viewController = new ViewController();
        $mstrSettings = MstrSettings::where('key','cop')->first();
        $image_path = 'storage/'.$mstrSettings->value;
        $image_type = pathinfo($image_path, PATHINFO_EXTENSION);
        $data = file_get_contents($image_path);

        $data = [
            'title' => 'Laporan_'.date('YmdHis'),
            'date' => date('d-m-Y H:i:s'),
            'service' => $service,
            'pie_chart' => $this->base64url_decode($pie),
            'bar_chart' => $this->base64url_decode($bar),
            'service_history' => $serviceHistory,
            'cop' => 'data:image:/' . $image_type . ';base64,' . base64_encode($data)
        ];

        // return view('pages.report.template', $data);
        
        $pdf = PDF::loadView('pages.report.template', $data);
    
        return $pdf->download('Laporan_'.date('YmdHis').'.pdf');
    }

    function base64url_encode(Request $request) {
        // array
        $data = $request->data;
        $array = [];
        foreach ($data as $key => $value) {
            array_push($array, rtrim(strtr(base64_encode($value), '+/', '-_'), '='));
        }
        return $array;
    }
    
    function base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
