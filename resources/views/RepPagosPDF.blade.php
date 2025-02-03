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
        .titulo{ text-align:center; font-weight:bold; font-size:20px !important;}
        table {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #ddd;
        font-size:10px !important;
        }
        th,
        td {
        padding: 5x 5px;
        border: 1px solid #ddd;
        text-align: center;
        }
        .titulo_tabla{
            text-align:center;
            
        }
        .firma{text-align:center;}
    </style>
</head>
<body>
    <div class="container mx-auto ">
    <div class="titulo">PAGOS IMPUESTO PREDIAL 2024 </div>
    <div class="titulo">RECUPERADOS A PARTIR DE NOTIFICACIONES</div>
    <div class="titulo">A FECHA 04/04/2024</div>
    <br>
    <div><b>TIPO DOCUMENTO:</b> @foreach($tipodoc as $t) {{$t->nombre  }} @endforeach</div>
    <div><b>Impreso por:</b> 
    @php setlocale(LC_MONETARY, 'es_PE'); @endphp
    @foreach($notificador as $n)
     {{$n->name  }}        
    @endforeach
    <br><br>
    </div>
    
    <p><b>RESUMEN:</b></p>
    <p><b>Monto Notificado:</b> @foreach($montonotificado as $n) @php echo number_format($n->notificados, 2);  @endphp @endforeach</p>
    <p><b>Monto Recaudado:</b> @foreach($recaudacion_notificaciones as $n) @php echo number_format($n->monto_recuperado, 2);  @endphp @endforeach</p>
    
    <br>
    <p class="titulo_tabla"> Tabla 01 - Detalle de Pagos Pricos</p>
    <table>
        <thead>
            <tr>
                <th>Nro.</th>
                <th>Nro. Doc.</th>
                <th>Codigo</th>
                <th>Contribuyente</th>
                <th>Notificador</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>  
                @php 
                    $nro=0;
                    $totalprico=0;
                @endphp
                @foreach($detallepagos_prico as $p)
                @php  
                    $nro=$nro+1;
                    $totalprico=$totalprico+$p->monto_recuperado;
                @endphp
                <tr>
                <td width="5%" style="text-align:center; font-size:10px;">@php  echo $nro; @endphp  </td>      
                <td width="10%" style="text-align:center; font-size:10px;">{{$p->numero_doc  }}  </td>      
                <td width="10%" style="text-align:center; font-size:10px;">{{$p->codigo  }}  </td>
                <td width="50%" style="text-align:left; font-size:9px;">{{$p->razon_social  }}  </td>      
                <td width="15%" style="text-align:left; font-size:9px;">{{$p->nombre  }}  </td>      
                <td width="10%" style="text-align:right; font-size:10px;">@php echo number_format($p->monto_recuperado, 2);  @endphp</td>
                </tr>         
                @endforeach
                       
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>@php echo number_format($totalprico, 2);  @endphp</td>
            </tr>
        </tfoot>
    </table>
    <br>
    <p class="titulo_tabla"> Tabla 02 - Detalle de Pagos Montos Menores</p>
    <table>
        <thead>
            <tr>
                <th>Nro.</th>
                <th>Nro. Doc.</th>
                <th>Codigo</th>
                <th>Contribuyente</th>
                <th>Notificador</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>  
                @php 
                    $nro=0;
                    $totaln=0;
                @endphp
                @foreach($detallepagos as $p)
                @php  
                    $nro=$nro+1;
                    $totaln=$totaln+$p->monto_recuperado;
                @endphp
                <tr>
                <td width="5%" style="text-align:center; font-size:10px;">@php  echo $nro; @endphp  </td>      
                <td width="10%" style="text-align:center; font-size:10px;">{{$p->numero_doc  }}  </td>      
                <td width="10%" style="text-align:center; font-size:10px;">{{$p->codigo  }}  </td>
                <td width="50%" style="text-align:left; font-size:9px;">{{$p->razon_social  }}  </td>      
                <td width="15%" style="text-align:left; font-size:9px;">{{$p->nombre  }}  </td>      
                <td width="10%" style="text-align:right; font-size:10px;">@php echo number_format($p->monto_recuperado, 2);  @endphp</td>
                </tr>         
                @endforeach
                       
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>@php echo number_format($totaln, 2);  @endphp</td>
            </tr>
        </tfoot>
    </table>
    
    <br><br><br><br><br><br>
    <p class="firma">
    ___________________________________________<br>    
    @foreach($notificador as $n)
     {{$n->name  }}        
    @endforeach <br>
ENCARGADO/SUPERVISOR</p>
    </div>
    
    
</body>
</html>