<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');

/**
 * Convierte "usuarios" o "usuario_roles" a "Usuarios" y "UsuarioRoles".
 */
function toPascalCase(string $value): string
{
	$normalized = preg_replace('/[^a-zA-Z0-9]+/', ' ', $value) ?? '';
	$parts = preg_split('/\s+/', trim($normalized)) ?: [];
	$parts = array_map(static fn (string $part): string => ucfirst(strtolower($part)), $parts);

	return implode('', $parts);
}

function jsonResponse(array $payload, int $statusCode = 200): void
{
	http_response_code($statusCode);
	echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function decodeInputJson(): array
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

function readArrayParam(array $input, string $key): array
{
	if (isset($input[$key])) {
		if (!is_array($input[$key]) || $input[$key] === []) {
			throw new InvalidArgumentException("El parametro {$key} debe ser un objeto no vacio.");
		}

		return $input[$key];
	}

	$queryValue = $_GET[$key] ?? null;
	if ($queryValue === null || trim((string) $queryValue) === '') {
		throw new InvalidArgumentException("Falta el parametro requerido: {$key}.");
	}

	$decoded = json_decode((string) $queryValue, true);
	if (!is_array($decoded) || $decoded === []) {
		throw new InvalidArgumentException("El parametro {$key} en query string debe ser JSON valido y no vacio.");
	}

	return $decoded;
}

try {
	$resource = trim((string) ($_GET['resource'] ?? ''));
	if ($resource === '') {
		$connection = Database::getConnection();
		$stmt = $connection->query('SELECT 1 AS ok');
		$result = $stmt->fetch();

		if (($result['ok'] ?? null) !== 1) {
			throw new RuntimeException('La consulta de prueba no devolvio el resultado esperado.');
		}

		jsonResponse([
			'success' => true,
			'message' => 'API operativa.',
			'usage' => [
				'resource' => 'Nombre del recurso. Ejemplo: usuarios, roles, pagos_nomina',
				'action' => 'index|show|store|update|destroy (opcional, se infiere por metodo HTTP)',
			],
		]);
		return;
	}

	$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
	$action = strtolower((string) ($_GET['action'] ?? ''));
	if ($action === '') {
		$action = match ($method) {
			'GET' => 'index',
			'POST' => 'store',
			'PUT', 'PATCH' => 'update',
			'DELETE' => 'destroy',
			default => '',
		};
	}

	if ($action === '') {
		throw new InvalidArgumentException('No se pudo determinar la accion para este metodo HTTP.');
	}

	$controllerClass = toPascalCase($resource) . 'Controller';
	$controllerFile = dirname(__DIR__) . '/app/Controllers/' . $controllerClass . '.php';
	if (!file_exists($controllerFile)) {
		jsonResponse([
			'success' => false,
			'message' => 'Recurso no encontrado.',
			'resource' => $resource,
		], 404);
		return;
	}

	require_once $controllerFile;

	if (!class_exists($controllerClass)) {
		throw new RuntimeException('No se pudo cargar la clase del controller: ' . $controllerClass);
	}

	$controller = new $controllerClass();
	if (!method_exists($controller, $action)) {
		jsonResponse([
			'success' => false,
			'message' => 'Accion no soportada para este recurso.',
			'resource' => $resource,
			'action' => $action,
		], 405);
		return;
	}

	$input = decodeInputJson();

	switch ($action) {
		case 'index':
			$limit = (int) ($_GET['limit'] ?? 100);
			$offset = (int) ($_GET['offset'] ?? 0);
			$controller->index(max(1, $limit), max(0, $offset));
			break;

		case 'show':
			$criteria = readArrayParam($input, 'criteria');
			$controller->show($criteria);
			break;

		case 'store':
			$data = readArrayParam($input, 'data');
			$controller->store($data);
			break;

		case 'update':
			$criteria = readArrayParam($input, 'criteria');
			$data = readArrayParam($input, 'data');
			$controller->update($criteria, $data);
			break;

		case 'destroy':
			$criteria = readArrayParam($input, 'criteria');
			$controller->destroy($criteria);
			break;

		default:
			jsonResponse([
				'success' => false,
				'message' => 'Accion no implementada.',
				'action' => $action,
			], 405);
			break;
	}
} catch (InvalidArgumentException $exception) {
	jsonResponse([
		'success' => false,
		'message' => $exception->getMessage(),
	], 400);
} catch (Throwable $exception) {
	jsonResponse([
		'success' => false,
		'message' => 'Error interno en el enrutador.',
		'error' => $exception->getMessage(),
	], 500);
}
