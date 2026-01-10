<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Historial N&deg;</th>
                    <th>Mascota</th>
                    <th>Lote</th>
                    <th class="text-right">Cantidad</th>
                    <th>Veterinario</th>
                    {{-- <th>Registrado por</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($sale->anamnesisForm->date)->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('voyager.pets.history.show', ['history' => $sale->anamnesisForm->id]) }}" target="_blank">
                            {{ str_pad($sale->anamnesisForm->id, 6, '0', STR_PAD_LEFT) }}
                        </a>
                    </td>
                    <td>{{ $sale->anamnesisForm->pet->name ?? 'N/A' }}</td>
                    <td>{{ $sale->itemStock->lote ?? 'N/A' }}</td>
                    <td class="text-right">{{ $sale->quantity }}</td>
                    <td>{{ $sale->anamnesisForm->doctor->name ?? 'N/A' }}</td>
                    {{-- <td>{{ $sale->registerUser->name ?? 'N/A' }}</td> --}}
                </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <h5 class="text-center" style="margin-top: 20px; margin-bottom: 20px;">
                                <i class="fa-solid fa-box-open" style="font-size: 50px;"></i>
                                <br><br>
                                No se encontraron dispensaciones.
                            </h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">@if(count($sales)>0) <p class="text-muted">Mostrando del {{$sales->firstItem()}} al {{$sales->lastItem()}} de {{$sales->total()}} registros.</p> @endif</div>
    <div class="col-md-8" style="overflow-x:auto"><nav class="text-right">{{ $sales->links() }}</nav></div>
</div>

<script>
    $('.page-link').click(function(e){
        e.preventDefault();
        let link = $(this).attr('href');
        let page = link.split('page=')[1];
        listSales(page);
    });
</script>