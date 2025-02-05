<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Documento;
use Illuminate\Http\Request;
use App\Models\TipoDocumento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\DevolucionDocumento;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificacionDocumento;

class ReporteNotificacionesController extends Controller
{
   public function reporte_resumido($id,$fh_ini,$fh_fin){
    $documentos = NotificacionDocumento::select('*')
    ->join('documentos as d', 'd.id', '=', 'documento_id')
    //->join('conductors', 'vales.conductor_id', '=', 'conductors.id')
    ->where('notificacion_documentos.user_id',$id)
    ->where('fecha_notificacion', '>=',$fh_ini)
    ->where('fecha_notificacion', '<=',$fh_fin)
    ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
    ->get();
    $notificaciones=NotificacionDocumento::select("fecha_notificacion", DB::raw('count(*) as cantidad') 
   /* DB::raw('(select count(*) from notificacion_documentos where user_id='.$id.' and tipo_notificacion_id = 1 and deleted_at is null and fecha_notificacion = fecha_notificacion) as recepcionados'),
    DB::raw('(select count(*) from notificacion_documentos where user_id='.$id.' and tipo_notificacion_id = 2 and deleted_at is null) as cedulon'),
    DB::raw('(select count(*) from notificacion_documentos where user_id='.$id.' and tipo_notificacion_id = 3 and deleted_at is null) as correo'),
    DB::raw('(select count(*) from notificacion_documentos where user_id='.$id.' and tipo_notificacion_id = 4 and deleted_at is null) as negativa')
    */)  
    ->where('user_id',$id)
    ->where('tipo_documento_id', '=',Auth::user()->tipo_documento_id)
    ->where('fecha_notificacion', '>=',$fh_ini)
    ->where('fecha_notificacion', '<=',$fh_fin)
    ->groupBy('fecha_notificacion')
    ->orderBy('fecha_notificacion','asc')
    ->get();
    $nottipo=NotificacionDocumento::select("tipo_notificacion_id", 'tn.nombre', DB::raw('count(*) as cantidad') )
    ->join('tipo_notificacions as tn', 'tn.id', 'tipo_notificacion_id' )  
    ->where('user_id',$id)
    ->where('notificacion_documentos.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
    ->where('fecha_notificacion', '>=',$fh_ini)
    ->where('fecha_notificacion', '<=',$fh_fin)
    ->groupBy('tipo_notificacion_id','nombre')
    ->orderBy('cantidad','desc')
    ->get();

    $notificador =User::where('id',$id)->get();
    $tipodoc=TipoDocumento::where('id', '=',Auth::user()->tipo_documento_id)->get();
    //dd($notificaciones);
    $pdf = Pdf::setPaper('a4', 'potrait')->loadView('RepNotResumidoPDF', compact(['notificaciones','notificador', 'nottipo','tipodoc','fh_ini','fh_fin']))->setOptions(['defaultFont' => 'sans-serif']);
    return $pdf->stream('reporte_not_resumido.pdf');//('vehiculos.pdf');
    }


    public function reporte_detallado($id,$fh_ini,$fh_fin){
        $fechas=NotificacionDocumento::select("fecha_notificacion", DB::raw('count(*) as cantidad') 
       )  
        ->where('user_id',$id)
        ->where('tipo_documento_id', '=',Auth::user()->tipo_documento_id)
        ->where('fecha_notificacion', '>=',$fh_ini)
        ->where('fecha_notificacion', '<=',$fh_fin)
        ->groupBy('fecha_notificacion')
        ->orderBy('fecha_notificacion','asc')
        ->get();
        $notificaciones=NotificacionDocumento::select('fecha_notificacion','d.numero_doc','d.codigo','d.razon_social','tn.nombre')
        ->join('tipo_notificacions as tn', 'tn.id', 'tipo_notificacion_id' )  
        ->join('documentos as d', 'd.id', 'documento_id' )  
        ->where('d.user_id',$id)
        ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
        ->where('fecha_notificacion', '>=',$fh_ini)
        ->where('fecha_notificacion', '<=',$fh_fin)
        //->groupBy('tipo_notificacion_id','nombre')
        ->orderBy('numero_doc','asc')
        ->get();
    
        $notificador =User::where('id',$id)->get();
        $tipodoc=TipoDocumento::where('id', '=',Auth::user()->tipo_documento_id)->get();
        //dd($notificaciones);
        $pdf = Pdf::setPaper('a4', 'potrait')->loadView('RepNotDetalladoPDF', compact(['fechas','notificador', 'notificaciones','tipodoc','fh_ini','fh_fin']))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream('reporte_not_detallado.pdf');//('vehiculos.pdf');
        }
    
    public function reporte_devueltos($id,$fh_ini,$fh_fin){
            $fechas=DevolucionDocumento::select("d.fecha_para", DB::raw('count(*) as cantidad') 
           ) 
            ->join('documentos as d', 'd.id', 'documento_id' ) 
            ->where('d.user_id',$id)
            ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
            ->where('d.fecha_para', '>=',$fh_ini)
            ->where('d.fecha_para', '<=',$fh_fin)
            ->groupBy('d.fecha_para')
            ->orderBy('d.fecha_para','asc')
            ->get();
            $devueltos=DevolucionDocumento::select('d.fecha_para','d.numero_doc','d.codigo','d.razon_social','md.nombre','observaciones')
            ->join('motivo_devolucions as md', 'md.id', 'motivo_devolucion_id' )  
            ->join('documentos as d', 'd.id', 'documento_id' )  
            ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
            ->where('d.fecha_para', '>=',$fh_ini)
            ->where('d.fecha_para', '<=',$fh_fin)
            ->where('d.user_id',$id)
            //->groupBy('tipo_notificacion_id','nombre')
            ->orderBy('numero_doc','asc')
            ->get();
        
            $notificador =User::where('id',$id)->get();
            $tipodoc=TipoDocumento::where('id', '=',Auth::user()->tipo_documento_id)->get();
            
            //dd($notificaciones);
            $devtipo=DevolucionDocumento::select("motivo_devolucion_id", 'md.nombre', DB::raw('count(*) as cantidad') )
            ->join('motivo_devolucions as md', 'md.id', 'motivo_devolucion_id' ) 
            ->join('documentos as d', 'd.id', 'documento_id' )  
            ->where('devolucion_documentos.user_id',$id)
            ->where('devolucion_documentos.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
            ->where('d.fecha_para', '>=',$fh_ini)
            ->where('d.fecha_para', '<=',$fh_fin)
            ->groupBy('motivo_devolucion_id', 'md.nombre')
            ->orderBy('cantidad','desc')
            ->get();
            $pdf = Pdf::setPaper('a4', 'potrait')->loadView('RepDevueltosPDF', compact(['fechas','notificador', 'devueltos','devtipo','tipodoc','fh_ini','fh_fin']))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->stream('reporte_devueltos.pdf');//('vehiculos.pdf');
    }


    public function reporte_asig_detallado($id,$fh_ini,$fh_fin){
        $fechas=Documento::select("fecha_para", DB::raw('count(*) as cantidad') 
       )  
        ->where('user_id',$id)
        ->where('tipo_documento_id', '=',Auth::user()->tipo_documento_id)
        ->where('fecha_para', '>=',$fh_ini)
        ->where('fecha_para', '<=',$fh_fin)
        //->where('fecha_para', '>','2024-03-22')
        ->groupBy('fecha_para')
        ->orderBy('fecha_para','asc')
        ->get();
        $notificaciones=Documento::select('fecha_para','numero_doc','codigo','razon_social',/* 'nd.fecha_notificacion',*/
        DB::raw('(select documento_id from notificacion_documentos where user_id=documentos.user_id and deleted_at is null and documentos.id = documento_id LIMIT 1) as notificado'),
        DB::raw('(select documento_id from devolucion_documentos where user_id=documentos.user_id and deleted_at is null and documentos.id = documento_id LIMIT 1) as devuelto')
        )
        /*->join('notificacion_documentos as nd', 'nd.documento_id', 'documentos.id' )
        ->join('devolucion_documentos as dd', 'dd.documento_id', 'documentos.id' )*/
        ->where('documentos.user_id',$id)
        //->where('fecha_para', '>','2024-03-22')
        ->where('tipo_documento_id', '=',Auth::user()->tipo_documento_id)
        ->where('fecha_para', '>=',$fh_ini)
        ->where('fecha_para', '<=',$fh_fin)
        
        /*->where('nd.deleted_at','=',null)
        ->where('dd.deleted_at','=',null)*/
        ->orderBy('numero_doc','asc')
        ->get();
       /* $notificaciones=Documento::select('fecha_para','numero_doc','codigo','razon_social', 
        DB::raw('(select documento_id from notificacion_documentos where user_id=documentos.user_id and deleted_at is null and documentos.id = documento_id LIMIT 1) as notificado'),
        DB::raw('(select documento_id from devolucion_documentos where user_id=documentos.user_id and deleted_at is null and documentos.id = documento_id LIMIT 1) as devuelto')
        )
        ->where('documentos.user_id',$id)
        ->orderBy('numero_doc','asc')
        ->get();*/
    
        $notificador =User::where('id',$id)->get();
        $tipodoc=TipoDocumento::where('id', '=',Auth::user()->tipo_documento_id)->get();

        //dd($notificaciones);
        $pdf = Pdf::setPaper('a4', 'potrait')->loadView('RepAsigDetalladoPDF', compact(['fechas','notificador', 'notificaciones','tipodoc','fh_ini','fh_fin']))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream('reporte_asig_detallado.pdf');//('vehiculos.pdf');
        }
    

    public function reporte_asig_resumido($id,$fh_ini,$fh_fin){
            $documentos = NotificacionDocumento::select('*')
            ->join('documentos as d', 'd.id', '=', 'documento_id')
           // ->where('d.fecha_para', '>','2024-03-22')
            ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
            ->where('d.fecha_para', '>=',$fh_ini)
            ->where('d.fecha_para', '<=',$fh_fin)

            //->join('conductors', 'vales.conductor_id', '=', 'conductors.id')
            ->where('notificacion_documentos.user_id',$id)
            ->get();
            $notificaciones=Documento::select("fecha_para", DB::raw('count(*) as cantidad'),
            DB::raw(
                '(select count(*) from notificacion_documentos as nd
                inner join documentos as d on d.id = nd.documento_id
                where nd.user_id='.$id.' and nd.deleted_at is null and d.fecha_para = documentos.fecha_para) as notificados'),
            DB::raw(
                '(select count(*) from devolucion_documentos as nd
                inner join documentos as d on d.id = nd.documento_id
                where nd.user_id='.$id.' and nd.deleted_at is null and documentos.fecha_para = d.fecha_para) as devueltos')
            )  
            ->where('user_id',$id)
            ->where('fecha_para', '>=',$fh_ini)
            ->where('fecha_para', '<=',$fh_fin)
            ->where('tipo_documento_id', '=',Auth::user()->tipo_documento_id)
           // ->where('fecha_para', '>','2024-03-22')

            ->groupBy('fecha_para')
            ->orderBy('fecha_para','asc')
            ->get();
            $notificacionesxtipo=Documento::select("fecha_para", DB::raw('count(*) as cantidad'),
            DB::raw(
                '(select count(*) from notificacion_documentos as nd
                inner join documentos as d on d.id = nd.documento_id
                where nd.user_id='.$id.' and nd.deleted_at is null and d.fecha_para = documentos.fecha_para) as notificados'),
            DB::raw(
                    '(select count(*) from notificacion_documentos as nd
                    inner join documentos as d on d.id = nd.documento_id
                    inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
                    where nd.user_id='.$id.' and nd.deleted_at is null and d.fecha_para = documentos.fecha_para and nd.tipo_notificacion_id = 1) as recepcion'),
            DB::raw(
                '(select count(*) from notificacion_documentos as nd
                 inner join documentos as d on d.id = nd.documento_id
                inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
                where nd.user_id='.$id.' and nd.deleted_at is null and d.fecha_para = documentos.fecha_para and nd.tipo_notificacion_id = 2) as cedulon'),
            DB::raw(
                '(select count(*) from notificacion_documentos as nd
                 inner join documentos as d on d.id = nd.documento_id
                inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
                where nd.user_id='.$id.' and nd.deleted_at is null and d.fecha_para = documentos.fecha_para and nd.tipo_notificacion_id = 3) as correo'),
            DB::raw(
                '(select count(*) from notificacion_documentos as nd
                 inner join documentos as d on d.id = nd.documento_id
                inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
                where nd.user_id='.$id.' and nd.deleted_at is null and d.fecha_para = documentos.fecha_para and nd.tipo_notificacion_id = 4) as negatividad'),
            )  
            
            ->where('user_id',$id)
            ->where('fecha_para', '>=',$fh_ini)
            ->where('fecha_para', '<=',$fh_fin)
            ->where('tipo_documento_id', '=',Auth::user()->tipo_documento_id)
           // ->where('fecha_para', '>','2024-03-22')

            ->groupBy('fecha_para')
            ->orderBy('fecha_para','asc')
            ->get();
            $nottipo=NotificacionDocumento::select("tipo_notificacion_id", 'tn.nombre', DB::raw('count(*) as cantidad') )
            ->join('tipo_notificacions as tn', 'tn.id', 'tipo_notificacion_id' ) 
            ->join('documentos as d', 'd.id', '=', 'documento_id') 
            ->where('d.user_id',$id)
            ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
            ->where('d.fecha_para', '>=',$fh_ini)
            ->where('d.fecha_para', '<=',$fh_fin)
            ->groupBy('tipo_notificacion_id','nombre')
            ->orderBy('cantidad','desc')
            ->get();
        
            $notificador =User::where('id',$id)->get();
            $tipodoc=TipoDocumento::where('id', '=',Auth::user()->tipo_documento_id)->get();

            //dd($notificaciones);
            $pdf = Pdf::setPaper('a4', 'potrait')->loadView('RepAsigResumidoPDF', compact(['notificaciones','notificador', 'nottipo','tipodoc','notificacionesxtipo','fh_ini','fh_fin']))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->stream('reporte_asig_resum.pdf');//('vehiculos.pdf');
    }

    public function reporte_general($id,$fh_ini,$fh_fin){
        
        $nfh_ini = date("d/m/Y", strtotime($fh_ini));
        $nfh_fin = date("d/m/Y", strtotime($fh_fin));
        $notificaciones=Documento::select("name", DB::raw('count(*) as cantidad'),
        DB::raw(
            "(select count(*) from notificacion_documentos as nd
            left join documentos as d on d.id=nd.documento_id
            where nd.deleted_at is null and u.id = d.user_id and d.fecha_para >= '".$fh_ini."' and d.fecha_para <= '".$fh_fin."') as notificados"),
        DB::raw(
            "(select count(*) from devolucion_documentos as nd
            left join documentos as d on d.id=nd.documento_id
            where nd.deleted_at is null and u.id = d.user_id and d.fecha_para >= '".$fh_ini."' and d.fecha_para <= '".$fh_fin."') as devueltos"),
        )  
        ->join('users as u','u.id','user_id')
        
        //->where('user_id',$id)
        ->where('documentos.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
        ->where('fecha_para', '>=',$fh_ini)
        ->where('fecha_para', '<=',$fh_fin)
        ->groupBy('u.id','name')
        ->orderBy('cantidad','desc')
        ->get();
        
        $notificacionesxtipo=Documento::select("name", DB::raw('count(*) as cantidad'),
        DB::raw(
            "(select count(*) from notificacion_documentos as nd
            left join documentos as d on d.id=nd.documento_id
            where nd.deleted_at is null and u.id = d.user_id and d.fecha_para >= '".$fh_ini."' and d.fecha_para <= '".$fh_fin."') as notificados"),
        DB::raw(
            "(select count(*) from notificacion_documentos as nd
            left join documentos as d on d.id=nd.documento_id
            inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
            where nd.deleted_at is null and u.id = d.user_id and d.fecha_para >= '".$fh_ini."' and d.fecha_para <= '".$fh_fin."' and  nd.tipo_notificacion_id = 1 ) as recepcion"),
        DB::raw(
            "(select count(*) from notificacion_documentos as nd
            left join documentos as d on d.id=nd.documento_id
            inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
            where nd.deleted_at is null and u.id = d.user_id and d.fecha_para >= '".$fh_ini."' and d.fecha_para <= '".$fh_fin."' and  nd.tipo_notificacion_id = 2 ) as cedulon"),
        DB::raw(
            "(select count(*) from notificacion_documentos as nd
            left join documentos as d on d.id=nd.documento_id
            inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
            where nd.deleted_at is null and u.id = d.user_id and d.fecha_para >= '".$fh_ini."' and d.fecha_para <= '".$fh_fin."' and  nd.tipo_notificacion_id = 3 ) as correo"),
        DB::raw(
            "(select count(*) from notificacion_documentos as nd
            left join documentos as d on d.id=nd.documento_id
            inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
            where nd.deleted_at is null and u.id = d.user_id and d.fecha_para >= '".$fh_ini."' and d.fecha_para <= '".$fh_fin."' and  nd.tipo_notificacion_id = 4 ) as negatividad"),
        )  
        ->join('users as u','u.id','user_id')
        
        //->where('user_id',$id)
        ->where('documentos.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
        ->where('fecha_para', '>=',$fh_ini)
        ->where('fecha_para', '<=',$fh_fin)
        ->groupBy('u.id','name')
        ->orderBy('cantidad','desc')
        ->get();

        $notis=NotificacionDocumento::select("name", 
        DB::raw('count(distinct numero_doc) as cantidad'), 
        DB::raw('count(distinct fecha_notificacion) as dias'), 
        DB::raw('count(*)/count(distinct fecha_notificacion)::DOUBLE PRECISION as promedio')
      /* DB::raw(
            '(select count(*) from notificacion_documentos as nd
            where nd.deleted_at is null and u.id = nd.user_id) as dias'),*/
        /*DB::raw(
            '(select count(*) from devolucion_documentos as nd
            inner join documentos as d on d.id=nd.documento_id
            where nd.deleted_at is null and u.id = d.user_id) as devueltos'),*/
        )  
        ->join('users as u','u.id','user_id')
        ->join('documentos as d', 'd.id', '=', 'documento_id')
        ->where('d.fecha_para', '>=',$fh_ini)
        ->where('d.fecha_para', '<=',$fh_fin)
        ->where('notificacion_documentos.deleted_at','=', null)
        ->where('notificacion_documentos.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
        ->groupBy('u.id','name')
        ->orderBy('promedio','desc')
        ->get();

        $notisxtipo=NotificacionDocumento::select("name", 
        DB::raw('count(distinct numero_doc) as notificados'), 
        DB::raw(
            "(select count(*) from notificacion_documentos as nd
            left join documentos as d on d.id=nd.documento_id
            inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
            where nd.deleted_at is null and u.id = d.user_id and nd.fecha_notificacion >= '".$fh_ini."' and nd.fecha_notificacion <= '".$fh_fin."' and  nd.tipo_notificacion_id = 1 ) as recepcion"),
        DB::raw(
            "(select count(*) from notificacion_documentos as nd
            left join documentos as d on d.id=nd.documento_id
            inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
            where nd.deleted_at is null and u.id = d.user_id and nd.fecha_notificacion >= '".$fh_ini."' and nd.fecha_notificacion <= '".$fh_fin."' and  nd.tipo_notificacion_id = 2 ) as cedulon"),
        DB::raw(
            "(select count(*) from notificacion_documentos as nd
            left join documentos as d on d.id=nd.documento_id
            inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
            where nd.deleted_at is null and u.id = d.user_id and nd.fecha_notificacion >= '".$fh_ini."' and nd.fecha_notificacion <= '".$fh_fin."' and  nd.tipo_notificacion_id = 3 ) as correo"),
        DB::raw(
            "(select count(*) from notificacion_documentos as nd
            left join documentos as d on d.id=nd.documento_id
            inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
            where nd.deleted_at is null and u.id = d.user_id and nd.fecha_notificacion >= '".$fh_ini."' and nd.fecha_notificacion <= '".$fh_fin."' and  nd.tipo_notificacion_id = 4 ) as negatividad"), 
        DB::raw('count(distinct fecha_notificacion) as dias'), 
        DB::raw('count(*)/count(distinct fecha_notificacion)::DOUBLE PRECISION as promedio')
      /* DB::raw(
            '(select count(*) from notificacion_documentos as nd
            where nd.deleted_at is null and u.id = nd.user_id) as dias'),*/
        /*DB::raw(
            '(select count(*) from devolucion_documentos as nd
            inner join documentos as d on d.id=nd.documento_id
            where nd.deleted_at is null and u.id = d.user_id) as devueltos'),*/
        )  
        ->join('users as u','u.id','user_id')
        ->join('documentos as d', 'd.id', '=', 'documento_id')
        ->where('fecha_notificacion', '>=',$fh_ini)
        ->where('fecha_notificacion', '<=',$fh_fin)
        ->where('notificacion_documentos.deleted_at','=', null)
        ->where('notificacion_documentos.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
        ->groupBy('u.id','name')
        ->orderBy('promedio','desc')
        ->get();
    
        $notificador =User::where('id',$id)->get();
        $tipodoc=TipoDocumento::where('id', '=',Auth::user()->tipo_documento_id)->get();

        //dd($notificaciones);
        $pdf = Pdf::setPaper('a4', 'potrait')->loadView('RepGeneralPDF', compact(['notificaciones','notificador','notis','tipodoc','notificacionesxtipo','notisxtipo','fh_ini', 'fh_fin']))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream('reporte_general.pdf');//('vehiculos.pdf');
        }
    public function reporte_general_not($id,$fh_ini,$fh_fin){
        
            $nfh_ini = date("d/m/Y", strtotime($fh_ini));
            $nfh_fin = date("d/m/Y", strtotime($fh_fin));
            $notis=NotificacionDocumento::select("name", 
            DB::raw('count(distinct numero_doc) as cantidad'), 
            DB::raw('count(distinct fecha_notificacion) as dias'), 
            //DB::raw('count(*)/count(distinct fecha_notificacion)::DOUBLE as promedio')
            DB::raw('count(*)/count(distinct fecha_notificacion) as promedio')
          /* DB::raw(
                '(select count(*) from notificacion_documentos as nd
                where nd.deleted_at is null and u.id = nd.user_id) as dias'),*/
            /*DB::raw(
                '(select count(*) from devolucion_documentos as nd
                inner join documentos as d on d.id=nd.documento_id
                where nd.deleted_at is null and u.id = d.user_id) as devueltos'),*/
            )  
            ->join('users as u','u.id','user_id')
            ->join('documentos as d', 'd.id', '=', 'documento_id')
            ->where('fecha_notificacion', '>=',$fh_ini)
            ->where('fecha_notificacion', '<=',$fh_fin)
            ->where('notificacion_documentos.deleted_at','=', null)
            ->where('notificacion_documentos.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
            ->groupBy('u.id','name')
            ->orderBy('promedio','desc')
            ->get();
    
            $notisxtipo=NotificacionDocumento::select("name", 
            DB::raw('count(distinct numero_doc) as notificados'), 
            DB::raw(
                "(select count(*) from notificacion_documentos as nd
                left join documentos as d on d.id=nd.documento_id
                inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
                where nd.deleted_at is null and u.id = d.user_id and nd.fecha_notificacion >= '".$fh_ini."' and nd.fecha_notificacion <= '".$fh_fin."' and  nd.tipo_notificacion_id = 1 ) as recepcion"),
            DB::raw(
                "(select count(*) from notificacion_documentos as nd
                left join documentos as d on d.id=nd.documento_id
                inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
                where nd.deleted_at is null and u.id = d.user_id and nd.fecha_notificacion >= '".$fh_ini."' and nd.fecha_notificacion <= '".$fh_fin."' and  nd.tipo_notificacion_id = 2 ) as cedulon"),
            DB::raw(
                "(select count(*) from notificacion_documentos as nd
                left join documentos as d on d.id=nd.documento_id
                inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
                where nd.deleted_at is null and u.id = d.user_id and nd.fecha_notificacion >= '".$fh_ini."' and nd.fecha_notificacion <= '".$fh_fin."' and  nd.tipo_notificacion_id = 3 ) as correo"),
            DB::raw(
                "(select count(*) from notificacion_documentos as nd
                left join documentos as d on d.id=nd.documento_id
                inner join tipo_notificacions as tn on tn.id = nd.tipo_notificacion_id
                where nd.deleted_at is null and u.id = d.user_id and nd.fecha_notificacion >= '".$fh_ini."' and nd.fecha_notificacion <= '".$fh_fin."' and  nd.tipo_notificacion_id = 4 ) as negatividad"), 
            DB::raw('count(distinct fecha_notificacion) as dias'), 
            //DB::raw('count(*)/count(distinct fecha_notificacion)::DOUBLE PRECISION as promedio')
            DB::raw('count(*)/count(distinct fecha_notificacion) as promedio')
          /* DB::raw(
                '(select count(*) from notificacion_documentos as nd
                where nd.deleted_at is null and u.id = nd.user_id) as dias'),*/
            /*DB::raw(
                '(select count(*) from devolucion_documentos as nd
                inner join documentos as d on d.id=nd.documento_id
                where nd.deleted_at is null and u.id = d.user_id) as devueltos'),*/
            )  
            ->join('users as u','u.id','user_id')
            ->join('documentos as d', 'd.id', '=', 'documento_id')
            ->where('fecha_notificacion', '>=',$fh_ini)
            ->where('fecha_notificacion', '<=',$fh_fin)
            ->where('notificacion_documentos.deleted_at','=', null)
            ->where('notificacion_documentos.tipo_documento_id', '=',Auth::user()->tipo_documento_id)
            ->groupBy('u.id','name')
            ->orderBy('promedio','desc')
            ->get();
        
            $notificador =User::where('id',$id)->get();
            $tipodoc=TipoDocumento::where('id', '=',Auth::user()->tipo_documento_id)->get();
    
            //dd($notificaciones);
            $pdf = Pdf::setPaper('a4', 'potrait')->loadView('RepGeneralNotPDF', compact(['notificador','notis','tipodoc','notisxtipo','fh_ini', 'fh_fin']))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->stream('reporte_general.pdf');//('vehiculos.pdf');
            }
        
    public function reporte_pagos($id){
        $notificador =User::where('id',$id)->get();
        $tipodoc=TipoDocumento::where('id', '=',Auth::user()->tipo_documento_id)->get();

            $montonotificado=NotificacionDocumento::select(DB::raw("sum(d.deuda_ip) as notificados"))
            ->join('documentos as d', 'd.id', '=', 'notificacion_documentos.documento_id')
            ->where('tipo_notificacion_id', '!=', null)
            ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)        
            ->where('notificacion_documentos.deleted_at', '=', null)        
            //->groupBy('fecha_notificacion')
            //->orderBy('fecha_notificacion','asc')
            ->get();
            $recaudacion_notificaciones=NotificacionDocumento::select(DB::raw("sum(p.monto_ip) as monto_recuperado"))
            ->join('documentos as d', 'd.id', '=', 'notificacion_documentos.documento_id')
            ->join('pagos as p', 'p.recepcion', '=', 'd.numero_doc')
            ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)        
            ->where('tipo_notificacion_id', '!=', null)        
            ->where('notificacion_documentos.deleted_at', '=', null)        
            //->groupBy('fecha_notificacion')
            //->orderBy('fecha_notificacion','asc')
            ->get();
            $detallepagos_prico=NotificacionDocumento::select(DB::raw("SPLIT_PART(u.name, ' ', 1) as nombre"),'d.numero_doc','d.codigo','d.razon_social',DB::raw("sum(p.monto_ip) as monto_recuperado"))
            ->join('documentos as d', 'd.id', '=', 'notificacion_documentos.documento_id')
            ->join('pagos as p', 'p.recepcion', '=', 'd.numero_doc')
            ->join('users as u','u.id','notificacion_documentos.user_id')
            ->where('tipo_notificacion_id', '!=', null) 
            ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)        
            ->where('notificacion_documentos.deleted_at', '=', null)        
            ->where('monto_ip', '>', 4000)        
            ->groupBy('d.numero_doc','d.codigo','d.razon_social','u.name')
            ->orderBy('monto_recuperado','desc')
            ->get();
            $detallepagos=NotificacionDocumento::select(DB::raw("SPLIT_PART(u.name, ' ', 1) as nombre"),'d.numero_doc','d.codigo','d.razon_social',DB::raw("sum(p.monto_ip) as monto_recuperado"))
            ->join('documentos as d', 'd.id', '=', 'notificacion_documentos.documento_id')
            ->join('pagos as p', 'p.recepcion', '=', 'd.numero_doc')
            ->join('users as u','u.id','notificacion_documentos.user_id')
            ->where('tipo_notificacion_id', '!=', null)  
            ->where('d.tipo_documento_id', '=',Auth::user()->tipo_documento_id)        
            ->where('notificacion_documentos.deleted_at', '=', null)  
            ->where('monto_ip', '<', 4000)       
            ->groupBy('d.numero_doc','d.codigo','d.razon_social','u.name')
            ->orderBy('monto_recuperado','desc')
            ->get();
            $pdf = Pdf::setPaper('a4', 'potrait')->loadView('RepPagosPDF', compact(['notificador','montonotificado','recaudacion_notificaciones','detallepagos','detallepagos_prico','tipodoc']))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->stream('reporte_general.pdf');//('vehiculos.pdf');
            }
}
