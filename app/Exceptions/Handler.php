<?php
namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Gestion des erreurs de validation
        if ($exception instanceof ValidationException) {
            return response()->json([
                'status_code' => 422,
                'status_message' => 'Erreur de validation.',
                'errors' => $exception->errors(), // Retourne les erreurs détaillées
            ], 422);
        }

        // Gestion des ressources introuvables (404)
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            return response()->json([
                'status_code' => 404,
                'status_message' => 'La ressource demandée est introuvable.',
            ], 404);
        }

        // Gestion des erreurs HTTP
        if ($exception instanceof HttpException) {
            return response()->json([
                'status_code' => $exception->getStatusCode(),
                'status_message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }

        // Gestion des erreurs internes du serveur
        return response()->json([
            'status_code' => 500,
            'status_message' => 'Une erreur interne est survenue.',
            'error_details' => config('app.debug') ? $exception->getMessage() : 'Contactez l’administrateur.',
        ], 500);
    }
}