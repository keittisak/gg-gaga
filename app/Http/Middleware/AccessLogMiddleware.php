<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class AccessLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->insertAccessLog($request->user(), $request);
        return $next($request);
    }

    public function insertAccessLog($user, $request)
    {
        $is_ajax = 0;
        if($request->ajax()){
            $is_ajax = 1;
        }
        
        DB::table('access_log')->insert([
            'url' => $request->url(),
            'query_string' => json_encode($request->all()),
            'method' => $request->method(),
            'user_id' => $user->id,
            'user_name' =>$user->name,
            'is_ajax' => $is_ajax,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
