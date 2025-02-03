<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteNotificacionesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/{id}/{fh_ini}/{fh_fin}/rrpdf', [ReporteNotificacionesController::class, 'reporte_resumido'])->name('notificaciones.rrpdf.reporte_resumido');
Route::get('/{id}/{fh_ini}/{fh_fin}/rdpdf', [ReporteNotificacionesController::class, 'reporte_detallado'])->name('notificaciones.rdpdf.reporte_detallado');
Route::get('/{id}/{fh_ini}/{fh_fin}/rdevueltospdf', [ReporteNotificacionesController::class, 'reporte_devueltos'])->name('notificaciones.rdevueltospdf.reporte_devueltos');
Route::get('/{id}/{fh_ini}/{fh_fin}/rasigdetalladopdf', [ReporteNotificacionesController::class, 'reporte_asig_detallado'])->name('notificaciones.rasigdetalladopdf.reporte_asig_detallado');
Route::get('/{id}/{fh_ini}/{fh_fin}/rasigresumidopdf', [ReporteNotificacionesController::class, 'reporte_asig_resumido'])->name('notificaciones.rasigresumidopdf.reporte_asig_resumido');
Route::get('/{id}/{fh_ini}/{fh_fin}/rgeneralpdf', [ReporteNotificacionesController::class, 'reporte_general'])->name('notificaciones.rgeneralpdf.reporte_general');
Route::get('/{id}/{fh_ini}/{fh_fin}/rgeneralnotpdf', [ReporteNotificacionesController::class, 'reporte_general_not'])->name('notificaciones.rgeneralpdfnot.reporte_general_not');
Route::get('/{id}/rpagospdf', [ReporteNotificacionesController::class, 'reporte_pagos'])->name('notificaciones.rpagospdf.reporte_pagos');
