<?php

namespace App\Http\Interfaces;


use Illuminate\Http\JsonResponse;

interface ResponseInterface
{
    public function response($data, $errors = []): JsonResponse;
}
