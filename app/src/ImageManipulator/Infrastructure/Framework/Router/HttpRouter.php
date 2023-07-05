<?php

namespace App\ImageManipulator\Infrastructure\Framework\Router;

use App\ImageManipulator\Infrastructure\Framework\Exception\BadRequestException;
use App\ImageManipulator\Infrastructure\Framework\Exception\HttpNotFoundException;
use App\ImageManipulator\Infrastructure\Framework\Exception\NotAllowedException;
use App\ImageManipulator\Infrastructure\Http\Controller\DisplayImageController;
use App\ImageManipulator\Infrastructure\Http\Controller\ModifyImageController;
use Psr\Container\ContainerInterface;

class HttpRouter
{
    private string $requestMethod = '';
    private array $path = [];

    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function parseRoute(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=UTF-8');
        header('Access-Control-Allow-Methods: GET'); // OPTIONS,GET,POST,PUT,DELETE
        header('Access-Control-Max-Age: 3600');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');

        $uri = trim(htmlentities(strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))), '/');

        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->path = explode('/', $uri);
    }

    public function processRequest(): array
    {
        $response['status_code'] = 'HTTP/1.1 200 OK';
        $response['content_type'] = '';
        $response['body'] = null;

        $controller = $this->resolveController($this->path);

        if (empty($controller)) {
            return $this->invalidRequest('Invalid request. Please try /dog.jpg/resize:200,400');
        }

        try {
            switch ($this->requestMethod) {
                case 'GET':
                    $controller = $this->container->get($controller);
                    $response = $controller($this->path);
                    break;

                default:
                    throw new NotAllowedException('Method not allowed!');
            }
        } catch (HttpNotFoundException $e) {
            return $this->notFound($e->getMessage());
        } catch (NotAllowedException $e) {
            return $this->notAllowed($e->getMessage());
        } catch (BadRequestException|\RuntimeException $e) {
            return $this->invalidRequest($e->getMessage());
        }

        return $response;
    }

    public function createResponse(array $response): void
    {
        if (!empty($response['location'])) {
            header($response['location'], true, 303);
            exit;
        }

        header($response['status_code']);
        header($response['content_type']);

        if (isset($response['body'])) {
            echo $response['body'];
        }
    }

    private function invalidRequest(string $message = ''): array
    {
        $response['status_code'] = 'HTTP/1.1 400 Bad Request';
        $response['content_type'] = '';
        $response['body'] = $message;

        return $response;
    }

    private function notFound(string $message = ''): array
    {
        $response['status_code'] = 'HTTP/1.1 404 Not Found';
        $response['content_type'] = '';
        $response['body'] = $message;

        return $response;
    }

    private function notAllowed(string $message = ''): array
    {
        $response['status_code'] = 'HTTP/1.1 405 Not Allowed';
        $response['content_type'] = '';
        $response['body'] = $message;

        return $response;
    }

    private function resolveController(array $path): string
    {
        if (!count($path) || empty($path[0])) {
            return '';
        }

        return isset($path[1]) && !empty($path[1]) ? ModifyImageController::class : DisplayImageController::class;
    }
}
