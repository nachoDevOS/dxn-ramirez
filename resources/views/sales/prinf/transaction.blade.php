<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 80mm; /* Ancho para impresora de 80mm */
            margin: 0;
            padding: 5px;
        }
        .ticket {
            width: 100%;
            max-width: 80mm;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .restaurant-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .restaurant-info {
            font-size: 10px;
            margin-bottom: 5px;
        }
        .ticket-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 3px 0;
        }
        .items-table td {
            padding: 3px 0;
        }
        .items-table .quantity {
            text-align: center;
            width: 15%;
        }
        .items-table .price {
            text-align: right;
            width: 25%;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
        }
        .qr-container {
            text-align: center;
            margin: 10px 0;
        }
        .qr-code {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }
        .payment-method {
            margin-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <div class="restaurant-name">{{ setting('admin.title') }}</div>
            <div class="restaurant-info">
                Dirección: Av. Beni s/n (Zona Fatima)<br>
                Cel: 76359210<br>
            </div>
        </div>
        
        <div class="ticket-info">
            <div>Codigo Venta: {{$transaction->sale->code}}</div>
        </div>
        
        <div class="ticket-info">
            <div>Fecha: {{date('d/m/Y h:i:s a', strtotime($transaction->created_at))}}</div>
            <div>Atendido por: {{$transaction->register->name}}</div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>TOTAL A PAGAR</th>
                    <th style="text-align: right">Bs. {{ number_format($transaction->sale->amount, 2, ',', '.') }}</th>
                </tr>
                <tr>
                    <th>MONTO PAGADO</th>
                    <th style="text-align: right">Bs. {{ number_format($transaction->amount, 2, ',', '.') }}</th>
                </tr>
            </thead>
            
        </table>
        
  
        

        
        <div class="qr-container">
            <div class="qr-code">
                @php
                    $qrContent = "CODIGO VENTA #{$transaction->sale->code}\n";
                    $qrContent .= "FECHA: " . date('d/m/Y h:i a', strtotime($transaction->created_at)) . "\n";
                    $qrContent .= "PRODUCTOS:\n";
                    foreach ($transaction->sale->saleDetails as $item) {
                        $qrContent .= "- {$item->name} - {$item->quantity} Bs.{$item->price}\n";
                    }
                    $qrContent .= "TOTAL: Bs." . number_format($transaction->sale->amount, 2, ',', '.') . "\n";
                @endphp
                {!! QrCode::size(80)->generate($qrContent) !!}
            </div>
            <div>
                Escanea para verificar <br>
                <small>{{ date('d/M/Y h:i:s a') }}</small>
            </div>
        </div>
        
        <div class="footer">
            ¡Gracias por su visita!<br>
            Vuelva pronto<br>
        </div>
    </div>
</body>
</html>