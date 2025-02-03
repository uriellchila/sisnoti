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
    <div class="titulo">REPORTE GENERAL - NOTIFICADOS</div>
    <div class="fh_titulo"> NOTIFICADOS DEL <span>{{$fh_ini}}</span> AL <span>{{$fh_fin}}</span></div>
    <br>
    <div><b>TIPO DOCUMENTO:</b> @foreach($tipodoc as $t) {{$t->nombre  }} @endforeach</div>
    
    <div><b>Impreso por:</b> 
    @foreach($notificador as $n)
     {{$n->name  }}        
    @endforeach
    <br><br>
    </div>
    <p class="titulo_tabla"> Tabla 01 - <b>Estadistica Notificados </b> (dias)</p>
    <table>
        <thead>
            <tr>
                <th>Notificador</th>
                <th>Cant. Notificados</th>
                <th>Dias Notificados</th>
                <th>Promedio por Dia</th>
            </tr>
        </thead>
        <tbody>
                @php
                $total = 0;
                $dias = 0;
                
                @endphp
                @foreach($notis as $not)
                @php
                $total = $total+$not->cantidad;
                $dias = $dias+$not->dias;
                @endphp
                <tr>
                    <td width="40%" style="text-align:left;">{{$not->name  }}</td> 
                    <td>{{$not->cantidad  }}</td>  
                    <td>{{$not->dias  }}</td>  
                    <td>@php echo round($not->promedio,1); @endphp</td>  
                      
                </tr>       
                @endforeach
                
        </tbody>
        <tfoot>
            <tr>
                <td><b>TOTAL</b></td>
                <td><b>@php echo $total; @endphp</b></td>
                <td><b>@php echo $dias; @endphp</b></td>
                <td><b></b></td>
                
                
            </tr>
        </tfoot>
    </table>
    <p class="titulo_tabla"> Tabla 02 - <b>Notificados </b> (Por Tipo)</p>
    <table>
        <thead>
            <tr>
                <th>Notificador</th>
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
                @foreach($notisxtipo as $notxtipo)
                @php
                $totalrecep = $totalrecep + $notxtipo->recepcion;
                $totalcedulon = $totalcedulon + $notxtipo->cedulon;
                $totalcorreo = $totalcorreo + $notxtipo->correo;
                $totalnegatividad = $totalnegatividad + $notxtipo->negatividad;
                $totalnotis = $totalnotis + $notxtipo->notificados;
                @endphp
                <tr>
                    <td width="40%" style="text-align:left;">{{$notxtipo->name  }}</td> 
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
    <br><br><br><br><br><br><br><br>
    <p class="firma">
    ___________________________________________<br>    
    @foreach($notificador as $n)
     {{$n->name  }}        
    @endforeach <br>
ENCARGADO/SUPERVISOR</p>
</body>
</html>