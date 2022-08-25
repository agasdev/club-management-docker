<?php

namespace App\Packages\Common\Infrastructure\EntryPoint\Api\Responses;

use App\Packages\Common\Infrastructure\EntryPoint\Api\Responses\Elements\Error;
use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorJsonResponse extends JsonResponse
{
    /**
     * @param Error[] $errors
     * @param int   $status
     * @param array $headers
     */
    public function __construct(array $errors, int $status, array $headers = [])
    {
        $data = [
            "errors" => array_map(function($error) {
                return $error->toArray();
            }, $errors)
        ];

        parent::__construct($data, $status, $headers, false);
    }
}