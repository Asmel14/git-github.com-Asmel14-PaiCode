<?php

declare(strict_types=1);

abstract class BaseController
{
    protected function jsonResponse(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function success(array $data = [], string $message = 'OK', int $statusCode = 200): void
    {
        $this->jsonResponse([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function error(string $message, int $statusCode = 400, array $errors = []): void
    {
        $this->jsonResponse([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    protected function readJsonBody(): array
    {
        $raw = file_get_contents('php://input');
        if ($raw === false || trim($raw) === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            throw new InvalidArgumentException('El cuerpo JSON no es valido.');
        }

        return $decoded;
    }

    protected function parseCriteria(array $input): array
    {
        $criteria = $input['criteria'] ?? [];
        if (!is_array($criteria) || $criteria === []) {
            throw new InvalidArgumentException('Debe enviar criteria como objeto no vacio.');
        }

        return $criteria;
    }

    protected function parseData(array $input): array
    {
        $data = $input['data'] ?? [];
        if (!is_array($data) || $data === []) {
            throw new InvalidArgumentException('Debe enviar data como objeto no vacio.');
        }

        return $data;
    }
}
