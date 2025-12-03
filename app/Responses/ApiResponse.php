<?php

namespace App\Responses;

use App\Enums\ErrorCodeEnum;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;

final readonly class ApiResponse implements Responsable
{
    public function __construct(
        private int         $status,
        private ?string     $message = null,
        private ?MessageBag $validationErrors = null,
        private mixed       $data = null,
    )
    {
    }

    public function toResponse($request): JsonResponse
    {
        $responseData = match (true) {
            ($this->status == 200 && $this->message), ($this->status == 201) => [
                'message' => $this->message,
            ],
            ($this->status == 200 && !$this->message) => [
                'data' => $this->data,
            ],
            ($this->status == 400) => [
                'errorCode' => ErrorCodeEnum::getMessage($this->status),
                'errors' => $this->validationErrors,
            ],
            ($this->status > 400 && $this->status <= 500) => [
                'errorCode' => ErrorCodeEnum::getMessage($this->status),
                'message' => $this->message,
            ],
            default => [
                'errorCode' => ErrorCodeEnum::getMessage(500),
                'message' => 'Unexpected error',
            ]
        };

        return new JsonResponse(
            data: $responseData,
            status: $this->status
        );
    }
}
