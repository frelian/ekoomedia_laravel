<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        /*
        return $next($request)
            //Url a la que se le dará acceso en las peticiones
            ->header("Access-Control-Allow-Origin", "*")
            //Métodos que a los que se da acceso
            ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE")
            //Headers de la petición
            ->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, Authorization");
        */

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers',' Origin, Content-Type, Accept, Authorization, X-Request-With')
            ->header('Access-Control-Allow-Credentials',' true');
    }
}
