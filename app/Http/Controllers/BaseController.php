<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Success JSON response
     */
    protected function jsonSuccess(mixed $data = null, string $message = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Error JSON response
     */
    protected function jsonError(string $message = null, int $statusCode = 400, mixed $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message ?? 'An error occurred',
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Redirect back with success message
     */
    protected function redirectSuccess(string $message, string $route = null): RedirectResponse
    {
        return redirect($route ?? back()->getTargetUrl())
            ->with('success', $message);
    }

    /**
     * Redirect back with error message
     */
    protected function redirectError(string $message, string $route = null): RedirectResponse
    {
        return redirect($route ?? back()->getTargetUrl())
            ->with('error', $message)
            ->withInput();
    }

    /**
     * Get authenticated user with clearance level
     */
    protected function authenticatedUser()
    {
        return auth()->user();
    }

    /**
     * Check if user has required clearance level
     */
    protected function hasClearance(int $level): bool
    {
        $user = $this->authenticatedUser();
        return $user && ($user->clearance_level ?? 1) >= $level;
    }

    /**
     * Paginate query with custom per page
     */
    protected function paginate($query, int $perPage = 15, int $maxPerPage = 100)
    {
        $requestedPerPage = request()->integer('per_page', $perPage);

        // Limit per page to prevent abuse
        $perPage = min(max($requestedPerPage, 1), $maxPerPage);

        return $query->paginate($perPage);
    }
}
