<?php

namespace App\Libraries;

use CodeIgniter\Debug\ExceptionHandlerInterface;
use Throwable;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CustomExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * Handle the exception and generate a response.
     *
     * @param Throwable $exception
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param int $statusCode
     * @param int $exitCode
     *
     * @return void
     */
    public function handle(Throwable $exception, RequestInterface $request, ResponseInterface $response, int $statusCode, int $exitCode): void
    {
        // Custom handler for 404 errors
        if ($statusCode === 404) {
            // Set the page title or other variables as needed
            $pageTitle = '404 Error';

            // Output the custom 404 page
            echo view('shop-Include/header', ['pageTitle' => $pageTitle])
                . view('shop/404')
                . view('shop-Include/footer');

            // End the script to prevent further processing
            exit;
        }

        // For other errors, pass to default handler
        echo view('errors/exception', ['exception' => $exception]);
        exit;
    }
}
