<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *  version="1.0",
 *  title="Documentación de la API",
 *  description="Documentación de la API de la aplicación",
 * )
 * @OA\SecurityScheme(
 *  securityScheme="bearerAuth",
 *  in="header",
 *  name="Authorization",
 *  type="http",
 *  scheme="bearer",
 *  bearerFormat="JWT",
 * )
 */
abstract class Controller
{
    //
}
