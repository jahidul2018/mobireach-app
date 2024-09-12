<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DataController extends Controller
{
    /**
     * Handle incoming GET request with data and send it to external URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendData(Request $request)
    {

        // return $request;  
        try {
            // Validate incoming request data if needed
            $request->validate([
                'user_name' => 'required',
                'password' => 'required',
                'sent_from' => 'required',
                'sent_to' => 'required',
                'text' => 'required',
                // Add more validation rules if needed
            ]);

            // Extract parameters from the request
            $username = $request->get('user_name');
            $password = $request->get('password');
            $from = $request->get('sent_from');
            $to = $request->get('sent_to');
            $message = $request->get('text');
            // Extract more parameters if needed

            // // Prepare data to be sent to external URL
            // $dataToSend = [
            //     'Username' => $username,
            //     'Password' => $password,
            //     'From' => $from,
            //     'To' => $to,
            //     'Message' => $message,
            //     // Add more parameters if needed
            // ];

            // // Send data to external URL
            // $response = Http::get('https://api.mobireach.com.bd/SendTextMessage?Username=xxxxxxxx&Password=xxxxxxxxx&From=xxxxxxxxxxxxx&To=xxxxxxxxxxxxx&Message=testmessage');

            // Build the URL with dynamic query parameters
            $url = 'https://api.mobireach.com.bd/SendTextMessage?' . http_build_query([
                'Username' => $username,
                'Password' => $password,
                'From' => $from,
                'To' => $to,
                'Message' => $message,
            ]);

            // Send data to external URL
            $response = Http::get($url);

            // Check if the request was successful
            if ($response->successful()) {
                // Handle successful response from the external URL
                $responseData = $response->body();
                // Process responseData if needed
                return response()->json(['success' => true, 'message' => 'Data sent successfully to external URL', 'data' => $responseData], 200);
            } else {
                // Handle unsuccessful response from the external URL
                $errorResponse = $response->body();
                // Process errorResponse if needed
                return response()->json(['success' => false, 'message' => 'Failed to send data to external URL', 'error' => $errorResponse], $response->status());
            }
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}