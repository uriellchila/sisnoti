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
    <div class="titulo">REPORTE DE DEVUELTOS</div>
    <div class="fh_titulo"> ASIGNADOS DEL <span>{{$fh_ini}}</span> AL <span>{{$fh_fin}}</span></div>
    <br>
    <div><b>TIPO DOCUMENTO:</b> @foreach($tipodoc as $t) {{$t->nombre  }} @endforeach</div>
    
    <div><b>NOTIFICADOR:</b> 
    @foreach($notificador as $n)
     {{$n->name  }}        
    @endforeach
    </div>
    <br>
    
    @foreach($fechas as $f)
    <br>
    <p class="titulo_fecha"> {{$f->fecha_para}} - ( {{$f->cantidad}} documentos) </p>  
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>N° Doc.</th>
                <th>Codigo.</th>
                <th>Razon Social.</th>
                <th>Motivo - Observaciones </th>
            </tr>
        </thead>
        <tbody>
            <tr>  @php $nro=0; @endphp
                
                @foreach($devueltos as $not)
                
                @php if($not->fecha_para==$f->fecha_para) {
                    $nro=$nro+1;
                    @endphp
                    
                <tr>
                <td width="2%">@php echo $nro; @endphp</td>
                <td width="8%">{{$not->numero_doc}}</td>
                <td width="10%">{{$not->codigo}}</td>
                <td width="30%" class="r_z">{{$not->razon_social}}</td>
                <td>{{$not->nombre}} - <span style="font-size:11px">{{$not->observaciones}}</span></td>
                </tr>
                @php }@endphp
                @endforeach
            </tr>  
        </tbody>
    </table>  
    @endforeach
    <div style="page-break-after:always;"></div>
    <p><b>RESUMEN:</b></p>
    <p class="titulo_tabla"> Tabla - Motivo Devolucion</p>
    <table>
        <thead>
            <tr>
                <th>Motivo Devolucion</th>
                <th>cantidad</th>
            </tr>
        </thead>
        <tbody>
                @php
                $total = 0;
                @endphp
                @foreach($devtipo as $not)
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
    
    
    
  <br><br><br><br><br><br>
    <p class="firma">
    ___________________________________________<br>    
    @foreach($notificador as $n)
     {{$n->name  }}        
    @endforeach <br>
NOTIFICADOR</p>
    </div>
    
</body>
</html>