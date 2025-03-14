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
    <div class="titulo">REPORTE DE NOTIFICACIONES - RESUMEN</div>
    <div class="fh_titulo"> NOTIFICADOS DEL <span>{{$fh_ini}}</span> AL <span>{{$fh_fin}}</span></div>
    <br>
    <div><b>TIPO DOCUMENTO:</b> @foreach($tipodoc as $t) {{$t->nombre  }} @endforeach</div>
    <div><b>NOTIFICADOR:</b> 
    @foreach($notificador as $n)
     {{$n->name  }}        
    @endforeach
    </div>
    <br>
    <p class="titulo_tabla"> Tabla 01 - Fecha Notificación</p>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>cantidad</th>
            </tr>
        </thead>
        <tbody>
                @php
                $total = 0;
                $dias = 0;
                @endphp
                @foreach($notificaciones as $not)
                @php
                $total = $total+$not->cantidad;
                $dias++;
                @endphp
                <tr>
                    <td>{{$not->fecha_notificacion  }}</td> 
                    <td>{{$not->cantidad  }}</td> 
                </tr>       
                @endforeach
                
        </tbody>
        <tfoot>
            <tr>
                <td><b>TOTAL</b></td>
                <td><b>@php echo $total; @endphp</b>
                </td>
            </tr>
        </tfoot>
    </table>
    <p class="titulo_tabla"> Tabla 02 - Tipo de Notificación</p>
    <table>
        <thead>
            <tr>
                <th>Tipo de Notificación</th>
                <th>cantidad</th>
            </tr>
        </thead>
        <tbody>
                @php
                $total = 0;
                @endphp
                @foreach($nottipo as $not)
                @php
                $total = $total+$not->cantidad;
                @endphp
                <tr>
                    <td>{{$not->nombre  }}</td> 
                    <td>{{$not->cantidad  }}</td> 
                </tr>       
                @endforeach
                
        </tbody>
        <tfoot>
            <tr>
                <td><b>TOTAL</b></td>
                <td><b>@php echo $total; @endphp</b>
                </td>
            </tr>
        </tfoot>
    </table>
    <p><b>RESUMEN:</b></p>
    <p><b>Total dias Notificados: </b> @php echo $dias; @endphp</p>
    <p><b>Cantidad Notificado:</b> @php echo $total; @endphp</p>
    <p><b>Promedio por dia: </b> @php if($total>0){echo round($total/$dias,2);} @endphp</p>
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