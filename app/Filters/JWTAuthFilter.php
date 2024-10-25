<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Firebase\JWT\ExpiredException;

class JWTAuthFilter implements FilterInterface
{
    protected $key;

    public function __construct() {
        $this->key = getenv("JWT_KEY"); // Get your JWT key from the environment
    }        

    public function before(RequestInterface $request, $arguments = null)
    {
        // Get the Authorization header
        $authHeader = $request->getHeader('Authorization');

        if ($authHeader) {
            // Extract the token
            list($jwt) = sscanf($authHeader->getValue(), 'Bearer %s');

            if ($jwt) {
                try {
                    // Decode the token
                    $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
                    // Store user information in the request for later use
                    $request->userData = (array) $decoded->user; // Customize as needed
                } catch (ExpiredException $e) {
                    return $this->respondUnauthorized('Token has expired');
                } catch (\Exception $e) {
                    return $this->respondUnauthorized('Invalid token');
                }
            } else {
                return $this->respondUnauthorized('Authorization token not found');
            }
        } else {
            return $this->respondUnauthorized('Authorization header not found');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Actions to take after the request has been processed
    }

    private function respondUnauthorized($message)
    {
        return service('response')->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
            ->setJSON(['status' => false, 'message' => $message]);
    }
}

