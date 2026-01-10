<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="text-align: center; width: 5%">Venta</th>
                    <th style="text-align: center; width: 5%">Código Venta</th>
                    <th style="text-align: center; width: 20%">Cliente</th>
                    <th style="text-align: center; width: 10%">Fecha de Venta</th>
                    <th style="text-align: center; width: 30%">Detalles de la Venta</th>
                    <th style="text-align: center; width: 5%">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                <tr>
                    <td style="text-align: center; vertical-align: middle;">
                        <a href="{{ route('sales.show', ['sale' => $sale->id]) }}" target="_blank" title="Ver Venta" class="btn btn-sm btn-warning">
                            <i class="voyager-eye"></i>
                        </a>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        {{ $sale->id }}
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        @if ($sale->person)
                            {{ strtoupper($sale->person->first_name) }} {{ strtoupper($sale->person->paternal_surname) }}
                        @else
                            <span class="text-muted">Sin Cliente</span>
                        @endif
                    </td>
                    <td style="text-align: center; vertical-align: middle;">

                        <i class="fa-solid fa-user"></i> <small>{{\Illuminate\Support\Str::words($sale->register->name, 2, '')}}</small> <br>
                        <b style="font-size: 12px">{{date('d/m/Y h:m:s a', strtotime($sale->created_at))}}</b> <br>
                        <small>{{\Carbon\Carbon::parse($sale->created_at)->diffForHumans()}}</small>
                    </td>
                    <td>
                        <table class="table table-sm" style="margin-bottom: 0px;">
                            <thead>
                                <tr>
                                    <th style="font-size: 12px; padding: 2px 5px; text-align: center">Lote</th>
                                    <th style="font-size: 12px; padding: 2px 5px; text-align: center">Tipo</th>
                                    <th style="font-size: 12px; padding: 2px 5px; text-align: center">Cantidad</th>
                                    <th style="font-size: 12px; padding: 2px 5px; text-align: center">Precio</th>
                                    <th style="font-size: 12px; padding: 2px 5px; text-align: center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->saleDetails as $detail)
                                    <tr>
                                        <td style="font-size: 12px; text-align: center">{{ $detail->itemStock->lote ?? 'N/A' }}</td>
                                        <td style="font-size: 12px; text-align: center">{{ $detail->dispensed == 'Entero' ? 'Entero' : 'Fraccionado' }}</td>
                                        <td style="font-size: 12px; text-align: right">{{ number_format($detail->quantity, 2, ',', '.') }} {{$detail->dispensed == 'Entero'?$detail->itemStock->item->presentation->name:$detail->itemStock->item->fractionPresentation->name}}</td>
                                        <td style="font-size: 12px; text-align: right">{{ number_format($detail->price, 2, ',', '.') }}</td>
                                        <td style="font-size: 12px; text-align: right">{{ number_format($detail->amount, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                    <td style="text-align: right; vertical-align: middle;">
                        <strong>{{ number_format($sale->saleDetails->sum('amount'), 2, ',', '.') }}</strong>
                    </td>                    
                    
                </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <h5 class="text-center" style="margin-top: 20px; margin-bottom: 20px;">
                                <i class="fa-solid fa-box-open" style="font-size: 50px;"></i>
                                <br><br>
                                No hay ventas directas registradas para este producto.
                            </h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">
        @if(count($sales)>0)
            <p class="text-muted">Mostrando del {{$sales->firstItem()}} al {{$sales->lastItem()}} de {{$sales->total()}} registros.</p>
        @endif
    </div>
    <div class="col-md-8" style="overflow-x:auto">
        <nav class="text-right">
            {{ $sales->links() }}
        </nav>
    </div>
</div>

<script>
   var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                listDirectSales(page);
            }
        });
    });
</script>