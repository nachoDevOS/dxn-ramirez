<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="text-align: center; width: 5%">Dispensación</th>
                    <th style="text-align: center">Tipo</th>
                    <th style="text-align: center">Mascota</th>
                    <th style="text-align: center">Veterinario</th>
                    <th style="text-align: center; width: 10%">Fecha Dispensación</th>

                    <th style="text-align: center; width: 30%">Detalles de la dispensación</th>
                    <th style="text-align: center; width: 5%">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>        
                        <td style="text-align: center; vertical-align: middle;">                            
                            @if ($item->anamnesisForm_id)
                                <a href="{{ route('voyager.pets.history.show', ['history' => $item->anamnesisForm_id]) }}" target="_blank" title="Ver historial clínico" class="btn btn-sm btn-warning">
                                    <i class="voyager-eye"></i>
                                </a>
                            @else
                                <a href="{{ route('voyager.pets.vaccinationrecords.show', ['vaccine' => $item->vaccinationRecord_id]) }}" target="_blank" title="Ver Vacunación / Desparacitaciones" class="btn btn-sm btn-warning">
                                    <i class="voyager-eye"></i>
                                </a>
                            @endif
                        </td>                
                        <td style="text-align: center">
                            @if ($item->anamnesisForm_id)
                                <span class="label label-primary">Historial Clínico</span>
                            @elseif($item->vaccinationRecord_id)
                                <span class="label label-info">Vacunación <br>Desparacitaciones</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->anamnesisForm)
                                {{ $item->anamnesisForm->pet->name ?? 'Sin Nombre' }} <br>
                                <small>Propietario: {{ optional(optional($item->anamnesisForm->pet)->person)->first_name ?? 'N/A' }} {{ optional(optional($item->anamnesisForm->pet)->person)->paternal_surname ?? '' }}</small>
                            @elseif($item->vaccinationRecord)
                                {{ $item->vaccinationRecord->pet->name ?? 'Sin Nombre' }} <br>
                                <small>Propietario: {{ optional(optional($item->vaccinationRecord->pet)->person)->first_name ?? 'N/A' }} {{ optional(optional($item->vaccinationRecord->pet)->person)->paternal_surname ?? '' }}</small>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if ($item->anamnesisForm)
                                {{ optional($item->anamnesisForm->doctor)->name ?? 'N/A' }}
                            @elseif($item->vaccinationRecord)
                                {{ optional($item->vaccinationRecord->doctor)->name ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td style="text-align: center">
                            {{ date('d/m/Y h:i a', strtotime($item->created_at)) }} <br>
                            <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        </td>
                        <td>
                            <table class="table table-sm" style="margin-bottom: 0px;">
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px; padding: 2px 5px; text-align: center">Lote</th>
                                        <th style="font-size: 12px; padding: 2px 5px; text-align: center">Tipo</th>
                                        <th style="font-size: 12px; padding: 2px 5px; text-align: center">Cant.</th>
                                        <th style="font-size: 12px; padding: 2px 5px; text-align: center">P. Unit.</th>
                                        <th style="font-size: 12px; padding: 2px 5px; text-align: center">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $details = collect();
                                        if($item->anamnesisForm){
                                            $details = $item->anamnesisForm->dispensions->filter(function($d) use ($item_id){
                                                return $d->itemStock && $d->itemStock->item_id == $item_id;
                                            });
                                        } elseif($item->vaccinationRecord){
                                            $details = $item->vaccinationRecord->dispensions->filter(function($d) use ($item_id){
                                                return $d->itemStock && $d->itemStock->item_id == $item_id;
                                            });
                                        }
                                    @endphp
                                    @foreach($details as $detail)
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
                        <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <h5 class="text-center" style="margin-top: 20px; margin-bottom: 20px;">
                                <i class="fa-solid fa-box-open" style="font-size: 50px;"></i>
                                <br><br>
                                No hay dispensaciones registradas para este producto.
                            </h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <div class="col-md-4" style="overflow-x:auto">
            @if(count($data)>0)
                <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
            @endif
        </div>
        <div class="col-md-8" style="overflow-x:auto">
            <nav class="text-right">
                {{ $data->links() }}
            </nav>
        </div>
    </div>
</div>

<script>
    $('.page-link').click(function(e){
        e.preventDefault();
        var link = $(this).attr('href');
        if(link){
            var page = link.split('page=')[1];
            // Asegúrate de tener una función JS llamada listDispensations en tu vista principal
            // que haga la llamada AJAX a esta ruta pasando el número de página.
            listDispensations(page);
        }
    });
</script>