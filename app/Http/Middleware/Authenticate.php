<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $jwt = $request->header('Authorization') ?? $request->header('authorization');

        if (!$jwt)
            return response()->json([
                'success' => false,
                'message' => 'JWT tidak ada.'
            ], 403);

        $jwt = str_replace("Bearer ", "", $jwt);

        $user = null;
        try {
            $user = JWT::decode($jwt, env('JWT_SECRET'), ['HS256']);
        } catch (BeforeValidException $bve) {
            return response()->json([
                'success' => false,
                'message' => 'JWT error: ' . $bve->getMessage()
            ], 401);
        } catch (ExpiredException $ee) {
            return response()->json([
                'success' => false,
                'message' => 'JWT error: ' . $ee->getMessage()
            ], 401);
        } catch (SignatureInvalidException $sie) {
            return response()->json([
                'success' => false,
                'message' => 'JWT error: ' . $sie->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Servernya lagi ngambek nih. :('
            ], 500);
        }

        $request->auth = $user->data;

        return $next($request);
    }
}
