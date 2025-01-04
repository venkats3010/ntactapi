<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;


/**
 * @OA\Info(title="Braincare Ntact APIs", version="0.1")
 * @OA\Schemes(format="http")
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="Authorization",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="Bearer",
 * ),
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //protected $api_url = "https://api.encloud.app/mydesk/";
    protected $api_url = "https://api.brainymed.com/mydesk/";
    //protected $api_url = "http://127.0.0.1:8000/api/";
	protected $api_key = "encxeyjfqpwapjqedzj";
	
    public function post_file_curl_api($postData, $end_point) {
        //echo gettype($postData);exit;
		//echo $url = $this->api_url.$end_point.'?enc';
		//echo "<br>";
		//print_r($postData);exit;
        $url = $this->api_url.$end_point.'?enc';
        $curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $postData,	  
		));	

		$response = curl_exec($curl);

		curl_close($curl);
        return json_decode($response);
    }

    public function post_curl_api($postData, $end_point) {
		$url = $this->api_url.$end_point;
        $curl = curl_init(); 
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => http_build_query($postData),
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/x-www-form-urlencoded',
				),				
			));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }
    
	public function sendEmail($emailParams)
    {
        $resetUrl = url('create-password/' . $emailParams['token']);

        $mailBody = " <a href='" . $resetUrl . "'>Reset Your Password</a> ";

        $data = [
            "apikey" => $this->api_key,
            "emailsubject" => "Password Reset Request",
            "email" => $emailParams['email'],
            "type" => $emailParams['type'],
            "emailmessage" => $mailBody
        ];

        $response = $this->api_call($data, "notification/email/");
        $mailResponse = json_decode($response->getBody()->getContents(), true);
        return $mailResponse;
    }


    public function commonCurlCall($params, $end_point, $method) {
        $url = $this->api_url.$end_point;
        $headers = array('Content-Type: application/json');
        switch ($method) {
            case 'GET':
                return Http::withHeaders($headers)->get($url);
                break;
            case 'POST':
                return Http::withHeaders($headers)->withBody(json_encode($params), 'application/json')->post($url);
                break;
            case 'PUT':
                return Http::withHeaders($headers)->withBody(json_encode($params), 'application/json')->put($url);
                break;
            case 'DELETE':
                return Http::withHeaders($headers)->delete($url);
                break;  
            default:
                return null;
        }
    }
 

}