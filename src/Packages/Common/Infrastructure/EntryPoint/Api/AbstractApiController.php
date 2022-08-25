<?php

declare(strict_types=1);

namespace App\Packages\Common\Infrastructure\EntryPoint\Api;

use App\Packages\Common\Infrastructure\EntryPoint\Api\Responses\Elements\Error;
use App\Packages\Common\Infrastructure\EntryPoint\Api\Responses\ErrorJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractController
{
    protected function sendError(string $message, int $code): ErrorJsonResponse {
        return new ErrorJsonResponse([new Error($code, $message)], $code);
    }
}