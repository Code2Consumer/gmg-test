<?php


namespace App\Http\Services;


use App\Http\Interfaces\ResponseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class JsonResponseService implements ResponseInterface
{
    /**
     * @param Collection|array $data
     * @param Collection|array $errors
     * @return JsonResponse
     */
    public function response($data, $errors = []): JsonResponse
    {
        $success = empty($errors);

        $response = [
            'success' => $success,
            'data' => $data,
            'errors' => $errors,
        ];

        return new JsonResponse($response);
    }
}
