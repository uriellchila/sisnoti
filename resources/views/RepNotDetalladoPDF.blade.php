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
    <div class="titulo">REPORTE DE NOTIFICACIONES - DETALLADO</div>
    <div class="fh_titulo"> NOTIFICADOS DEL <span>{{$fh_ini}}</span> AL <span>{{$fh_fin}}</span></div>
    <br>
    <div><b>TIPO DOCUMENTO:</b> @foreach($tipodoc as $t) {{$t->nombre  }} @endforeach</div>
    <div><b>NOTIFICADOR:</b> 
    @foreach($notificador as $n)
     {{$n->name  }}        
    @endforeach
    </div>
    
    
    @foreach($fechas as $f)
    <br>
    <p class="titulo_fecha"> {{$f->fecha_notificacion}} - ( {{$f->cantidad}} notificaciones) </p>  
    
    <table>
        <thead>
            <tr>
                <th width="5%">Nro.</th>
                <th>Nro. Doc.</th>
                <th>Codigo.</th>
                <th>Razon Social.</th>
                <th>Tipo Not.</th>
            </tr>
        </thead>
        <tbody>
            <tr>  @php $nro=0; @endphp
                @foreach($notificaciones as $not)
               
                @php if($not->fecha_notificacion==$f->fecha_notificacion) {
                    $nro=$nro+1;
                    @endphp
                    
                <tr>
                <td width="3%">@php echo $nro; @endphp</td>
                <td width="10%">{{$not->numero_doc}}</td>
                <td width="13%">{{$not->codigo}}</td>
                <td width="44%" class="r_z">{{$not->razon_social}}</td>
                <td>{{$not->nombre}}</td>
                </tr>
                @php }@endphp
                @endforeach
            </tr>  
        </tbody>
    </table>  
    @endforeach
    
    
    
    
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