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
        $AuthorizationHeader = $request->getServer("HTTP_AUTHORIZATION");

        //Check Header Authorization is available
        if(!$AuthorizationHeader){
            return $this->unauthorizedResponse();
        };

        //Check Header Pattern
        $AuthorizationHeaderStringArr = explode(" ", $AuthorizationHeader); // ["Bearer", "eyJ0eXAi..."]

        if(count($AuthorizationHeaderStringArr) !== 2 || $AuthorizationHeaderStringArr[0] != "Bearer"){
            return $this->unauthorizedResponse();
        };

        //Validate the token value
        try{
            $blacklistedObject = new TokenBlacklisted();

            $tokenData = $blacklistedObject->where("token", $AuthorizationHeaderStringArr[1])->first();

            if($tokenData){
                return $this->unauthorizedResponse();
            }

            $decodedData = JWT::decode($AuthorizationHeaderStringArr[1], new Key(getenv("JWT_Key"), "HS256"));

            $request->jwtToken = $AuthorizationHeaderStringArr[1];
            $request->userData = (array) $decodedData;
        } catch (ExpiredException $e) {
            return $this->failedTokenValidateResponse('Token has expired');
        }catch(\Exception $ex){
            return $this->failedTokenValidateResponse($ex->getMessage());
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
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
