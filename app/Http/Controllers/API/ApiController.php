<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use App\Models\RaspberryPi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function registerRPI(Request $request)
    {
        $user = Auth::user();  // Get the authenticated user
        $userId = $user->id;  // Getting the logged-in user's ID
    
        Log::info('Attempting to register Raspberry Pi', ['user_id' => $userId]);
    
        // Check if user already has a Raspberry Pi registered
        if (RaspberryPi::where('user_id', $userId)->exists()) {
            Log::warning('User already has a Raspberry Pi registered', ['user_id' => $userId]);
            return redirect('/books')->with('error', 'You already have a Raspberry Pi registered.');
        }
    
        // Check if user already has an API token
        $existingToken = $user->tokens->first();
        if ($existingToken) {
            Log::warning('User already has an API token', ['user_id' => $userId]);
            return redirect('/books')->with('error', 'You already have an API token.');
        }
    
        // Generate a new API token
        $token = $user->createToken('api-token')->plainTextToken;
        Log::info('Generated new API token', ['user_id' => $userId, 'token' => $token]);
    
        // Store the plain-text token securely (e.g., in an environment variable)
        file_put_contents(storage_path('app/api_token.txt'), $token);
    
        // Prepare data to send to the Raspberry Pi
        $payload = [
            'user_id' => $userId,
            'api_token' => $token
        ];
    
        Log::info('Payload to send to Raspberry Pi:', $payload);
    
        // Replace with the actual IP address of your Raspberry Pi
        $rpi_ip_address = 'https://promoted-cicada-notably.ngrok-free.app';
    
        // Send request to the Raspberry Pi Flask server
        try {
            $response = Http::post("$rpi_ip_address/register", $payload);
    
            if ($response->successful()) {
                Log::info('Registration request sent to Raspberry Pi successfully', ['user_id' => $userId]);
    
                // Extract data from the response
                $responseData = $response->json();
    
                // Log the response data
                Log::info('Response data received from Raspberry Pi:', $responseData);
    
                // Validate and sanitize the response data
                $validatedData = [
                    'user_id' => $responseData['user_id'] ?? null,
                    'location_id' => $responseData['location_id'] ?? null,
                    'unique_identifier' => $responseData['unique_identifier'] ?? null,
                    'ip_address' => $responseData['ip_address'] ?? null,
                    'ngrok_url' => $responseData['ngrok_url'] ?? null
                ];
    
                // Ensure all required fields are present
                if (in_array(null, $validatedData, true)) {
                    Log::error('Incomplete response data received from Raspberry Pi', $validatedData);
                    return redirect('/books')->with('error', 'Incomplete response data received from Raspberry Pi.');
                }
    
                // Store the Raspberry Pi information in the database
                RaspberryPi::create($validatedData);
                Log::info('Raspberry Pi registered successfully', $validatedData);
                return redirect('/books')->with('status', 'Registration request sent and Raspberry Pi registered successfully!');
            } else {
                Log::error('Failed to register Raspberry Pi', ['user_id' => $userId, 'response' => $response->body()]);
                return redirect('/books')->with('error', 'Failed to register Raspberry Pi.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to send registration request to Raspberry Pi', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return redirect('/books')->with('error', 'Failed to send registration request to Raspberry Pi: ' . $e->getMessage());
        }
    }
    
    public function showBook(Request $request, $id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);
        $rpi = RaspberryPi::where('user_id', $user->id)->first();
    
        if (!$rpi) {
            return redirect('/books')->with('error', 'No Raspberry Pi registered for this user.');
        }
    
        $rpi_ip_address = $rpi->ngrok_url;
        $unique_identifier = $rpi->unique_identifier;
    
        // Retrieve the user's API token from secure storage
        $api_token = file_get_contents(storage_path('app/api_token.txt'));
    
        Log::info('Sending show book request', [
            'rpi_ip' => $rpi_ip_address, 
            'book_id' => $id, 
            'unique_identifier' => $unique_identifier,
            'api_token' => $api_token
        ]);
    
        try {
            $response = Http::post("{$rpi_ip_address}/control", [
                'place' => $book->place,
                'unique_identifier' => $unique_identifier,
                'api_token' => $api_token
            ]);
    
            Log::info('Show book response', ['status' => $response->status(), 'body' => $response->body()]);
    
            if ($response->successful()) {
                return redirect('/books')->with('status', 'Show book request sent!');
            } else {
                return redirect('/books')->with('error', 'Failed to send show book request.');
            }
        } catch (\Exception $e) {
            return redirect('/books')->with('error', 'Failed to send message: ' . $e->getMessage());
        }
    }
     
}
