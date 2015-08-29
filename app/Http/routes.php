<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/', 'WelcomeController@index');

Route::controllers([
    'auth'              => 'Auth\AuthController',
    'password'          => 'Auth\PasswordController',
    'tipo-articulos'    => 'TipoArticulosController',
    'articulos'         => 'ArticulosController',
    'personas'          => 'PersonasController',
    'presupuestos'      => 'PresupuestosController',
    'configuraciones'   => 'ConfiguracionesController',
    'usuarios'          => 'UsuariosController',
    'pago-proveedores'  => 'PagoProveedoresController',
    'detalle-articulos' => 'DetalleArticulosController',
]);
