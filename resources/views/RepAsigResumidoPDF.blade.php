<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        *{
           
            font-family:Arial; 
            font-size:12px;
            
        }
        .titulo{ text-align:center; font-weight:bold; font-size:24px !important; text-decoration: underline; margin-bottom:10px;}
        table {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #ddd;
        font-size:11px !important;
        }
        th,
        td {
        padding: 5x 2px;
        border: 1px solid #ddd;
        text-align: center;
        }
        .titulo_tabla{
            text-align:center;
            
        }
        .firma{text-align:center;}
        .r_z{text-align:left; font-size:10px !important;}
        .titulo_fecha{
            font-weight:700;
            font-size: 14px;
        }
        .fh_titulo{
            font-size: 16px !important;
            text-align:center;
            font-weight:700;
        }
        .fh_titulo span{
            font-size:16px;
            font-weight:700;

        }
    </style>
</head>
<body>
    <div class="container mx-auto ">
    <div class="titulo">REPORTE DOCUMENTOS ASIGNADOS - RESUMEN</div>
    <div class="fh_titulo"> ASIGNADOS DEL <span>{{$fh_ini}}</span> AL <span>{{$fh_fin}}</span></div>
    <br>
    <div><b>TIPO DOCUMENTO:</b> @foreach($tipodoc as $t) {{$t->nombre  }} @endforeach</div>
    <div><b>NOTIFICADOR:</b> 
    @foreach($notificador as $n)
     {{$n->name  }}        
    @endforeach
    <br><br>
    </div>
    <p class="titulo_tabla"> Tabla 01 - Estado de Asignados</p>
    <table>
        <thead>
            <tr>
                <th>Fecha Asignada</th>
                <th>Cant. Asignado</th>
                <th>Notificados</th>
                <th>Devueltos</th>
                <th style="color:red;">Pendientes</th>
            </tr>
        </thead>
        <tbody>
                @php
                $total = 0;
                $dias = 0;
                $notificados = 0;
                $devueltos = 0;
                $pendientes=$total-($notificados+$devueltos);
                @endphp
                @foreach($notificaciones as $not)
                @php
                $total = $total+$not->cantidad;
                $notificados = $notificados+$not->notificados;
                $devueltos = $devueltos+$not->devueltos;
                if($not->cantidad - ($not->notificados+$not->devueltos)>0){
                $pendientes = $pendientes + ($not->cantidad - ($not->notificados+$not->devueltos));
                }
                $dias++;
                @endphp
                <tr>
                    <td>{{$not->fecha_para  }}</td> 
                    <td>{{$not->cantidad  }}</td> 
                    <td>{{$not->notificados  }}</td> 
                    <td>{{$not->devueltos  }}</td> 
                    
                    @if($not->cantidad - ($not->notificados + $not->devueltos) > 0)         
                    <td style="color:red;">{{$not->cantidad - ($not->notificados + $not->devueltos)  }}</td>         
                    @else
                        <td>-</td>        
                    @endif
                     
                </tr>       
                @endforeach
                
        </tbody>
        <tfoot>
            <tr>
                <td><b>TOTAL</b></td>
                <td><b>@php echo $total; @endphp</b></td>
                <td><b>@php echo $notificados; @endphp</b></td>
                <td><b>@php echo $devueltos; @endphp</b></td>
                <td style="color:red;"><b>@php echo $pendientes; @endphp</b></td>
                
            </tr>
        </tfoot>
    </table>

    <p class="titulo_tabla"> Tabla 02 - <b>Notificados </b> (Por Tipo)</p>
    <table>
        <thead>
            <tr>
                <th>Fecha Asignada</th>
                <th>Por Const. Admin. (Recepcion)</th>
                <th>Por Cedulon</th>
                <th>Por Correo</th>
                <th>Negativa de recepcion</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
                @php
                $totalrecep = 0;
                $totalcedulon = 0;
                $totalcorreo = 0;
                $totalnegatividad = 0;
                $totalnotis = 0;
                @endphp
                @foreach($notificacionesxtipo as $notxtipo)
                @php
                $totalrecep = $totalrecep + $notxtipo->recepcion;
                $totalcedulon = $totalcedulon + $notxtipo->cedulon;
                $totalcorreo = $totalcorreo + $notxtipo->correo;
                $totalnegatividad = $totalnegatividad + $notxtipo->negatividad;
                $totalnotis = $totalnotis + $notxtipo->notificados;
                @endphp
                <tr>
                    <td>{{$notxtipo->fecha_para  }}</td> 
                    <td>{{$notxtipo->recepcion  }}</td> 
                    <td>{{$notxtipo->cedulon  }}</td>                    
                    <td>{{$notxtipo->correo  }}</td>                    
                    <td>{{$notxtipo->negatividad  }}</td>                    
                    <td>{{$notxtipo->notificados  }}</td>                    
                </tr>       
                @endforeach
                
        </tbody>
        <tfoot>
            <tr>
                <td><b>TOTAL</b></td>
                <td><b>@php echo $totalrecep; @endphp</b></td>
                <td><b>@php echo $totalcedulon; @endphp</b></td>
                <td><b>@php echo $totalcorreo; @endphp</b></td>
                <td><b>@php echo $totalnegatividad; @endphp</b></td>              
                <td><b>@php echo $totalnotis; @endphp</b></td>                         
            </tr>
        </tfoot>
    </table>

    <p><b>RESUMEN:</b></p>
    <p><b>Cantidad Asignado:</b> @php echo $total; @endphp</p>
    <p><b>Cantidad Notificados:</b> @php echo $notificados; @endphp</p>
    <p><b>Cantidad Devueltos:</b> @php echo $devueltos; @endphp</p>
    <p style="color:red;"><b>Cantidad Pendientes:</b> @php echo $pendientes; @endphp</p>
  <br><br><br><br><br>
    <p class="firma">
    ___________________________________________<br>    
    @foreach($notificador as $n)
     {{$n->name  }}        
    @endforeach <br>
NOTIFICADOR</p>
    </div>
    
</body>
</html>