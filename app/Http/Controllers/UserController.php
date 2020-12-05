<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use PHPUnit\Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $content = $request->all();

        // En caso que halla la peticion pero sin ningun dato enviado a la API
        if (empty($content)) {
            return response()->json(
                [
                    "status"  => false,
                    "result"  => null,
                    "message" => "Sin datos enviados..."
                ],
                204 // 204 No Content
            );
        }

        $isValid = true;
        foreach ($content as $key => $value) {
            if (empty($value)) {
                $isValid = false;
            }
        }

        // Retorno mensaje de error si hay algun campo vacio
        if (!$isValid) {
            return response()->json(
                [
                    "status"  => false,
                    "result"  => $content,
                    "message" => "Existen campos vacios."
                ],
                500 // 500 Internal Server Error
            );
        }

        // Si llegaron todos los datos creo nickname y registro los datos en BD
        $email  = $request->input('email');
        $content['nickname'] = "";
        $saved = false;

        if (strpos($email, '@') != false) {

            $limitEmail = strpos($email, '@');
            $nickname   = substr($email, 0, $limitEmail);
            $content['nickname'] = $nickname;

            /*
                Nota: 
                Podria validar solo el email si existe en la BD, si es así entonces el nickname también, 
                pero si se modifican los damos manualmente se saltaría la validación.
            */

            // Verifico que el email no exista
            $findEmail = User::where('email', $email)->first(['id', 'email']);

            // Verifico que el nickname no exista
            $findNick = User::where('nickname', $nickname)->first(['id', 'nickname']);

            $exists = false;
            $existFields = "";
            if ($findEmail) {
                $existFields = "El email ya existe en la base de datos. ";
            }

            if ($findNick) {
                $existFields = !empty($existFields) ? "El email y nickname ya existen en la base de datos." : "El nickname ya existe en la base de datos.";
            }

            if ($existFields) {
                return response()->json(
                    [
                        "status"  => false,
                        "result"  => $content,
                        "message" => $existFields
                    ],
                    406 // 406 Not Acceptable
                );
            }

            // Guardo en BD
            $saved = User::create($content);

        }

        // Peticion satisfactoria
        return response()->json(
            [
                "status"  => true,
                "result"  => $content,
                "message" => "Se ha registrado el nuevo usuario correctamente.",
            ],
            201 // 201 Created
        );
    }
}
