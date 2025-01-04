<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Session;
use PDF;

class PDFController extends Controller
{
    public function api_call($params, $end_point){
        return Http::withBody(
            json_encode($params),
            'application/json'
        )->post($this->api_url.'/'.$end_point.'/?'.$this->api_key);
    }

    public function generatePDF(Request $request)
    {
        $data = $request->all();

        $pdf = PDF::loadView('pdf.view', compact('data'));
        return $pdf->download('filename.pdf');
    }

    


}
