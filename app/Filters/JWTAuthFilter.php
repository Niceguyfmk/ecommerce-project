<?php

namespace App\Filters;

use App\Models\TokenBlacklisted;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
class JWTAuthFilter implements FilterInterface
{
    
    public function before(RequestInterface $request, $arguments = null)
    {
        // First, try to get the Authorization header from the request.
        $AuthorizationHeader = $request->getServer("HTTP_AUTHORIZATION");
    
        // If no Authorization header is found, check the session for the token
        if (!$AuthorizationHeader) {
            
            $token = session()->get('jwtToken');  // Get token from session
            
            if (!$token) {
                // If no token is found in session, return an unauthorized response
                return $this->unauthorizedResponse();
            }
    
            try {
                
                // Decode the token using JWT::decode()
                $decodedData = JWT::decode($token, new Key(getenv("JWT_KEY"), "HS256"));
    
                // Store the token and user data in the request
                $request->jwtToken = $token;
                $user = (array) $decodedData->user;
                $request->userData = $user;
            } catch (ExpiredException $e) {
                //if token has expired remove it from session
                session()->remove('jwtToken'); 
                return $this->failedTokenValidateResponse('Token has expired');
            } catch (\Exception $ex) {
                return $this->failedTokenValidateResponse($ex->getMessage());
            }
        } else {
            // If the Authorization header is present, validate it as usual
            $AuthorizationHeaderStringArr = explode(" ", $AuthorizationHeader); // ["Bearer", "eyJ0eXAi..."]
    
            if (count($AuthorizationHeaderStringArr) !== 2 || $AuthorizationHeaderStringArr[0] != "Bearer") {
                return $this->unauthorizedResponse();
            };
    
            // Validate the token value
            try {
                // Check if the token is blacklisted
                $blacklistedObject = new TokenBlacklisted();
                $tokenData = $blacklistedObject->where("token", $AuthorizationHeaderStringArr[1])->first(); 
    
                if ($tokenData) {
                    return $this->unauthorizedResponse();
                }
    
                // Decode the JWT token
                $decodedData = JWT::decode($AuthorizationHeaderStringArr[1], new Key(getenv("JWT_KEY"), "HS256"));
    
                // Store the token and user data in the request
                $request->jwtToken = $AuthorizationHeaderStringArr[1];
                $request->userData = (array) $decodedData;
            } catch (ExpiredException $e) {
                return $this->failedTokenValidateResponse('Token has expired');
            } catch (\Exception $ex) {
                return $this->failedTokenValidateResponse($ex->getMessage());
            }
        }
    }
    

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }

    private function unauthorizedResponse()
    {
        $response = service('response');
        $response->setStatusCode(401); // Set the HTTP status code to 401 Unauthorized
        $response->setContentType('application/json');
        $response->setBody(json_encode([
            "status" => false,
            "message" => "Unauthorized Access"
        ]));

        return $response;
    }

    private function failedTokenValidateResponse($errorMessage)
    {
        $response = service('response');
        $response->setStatusCode(500); // Set the HTTP status code to 500 Unauthorized
        $response->setContentType('application/json');
        $response->setBody(json_encode([
            "status" => false,
            "message" => "Failed to Validate the token",
            "error_message" => $errorMessage
        ]));

        return $response;
    }
}
