<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KhachHangMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('sanctum')->user();
        if($user && $user instanceof \App\Models\KhachHang){
            return $next($request);
        }else{
                return response()->json([
                    'message'    => "Bạn phải đăng nhập để thực hiện hành động này!",
                ]);
        }

    }
}
