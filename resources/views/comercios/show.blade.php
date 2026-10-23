{{-- Semana 7 · Blade --}}
{{-- resources/views/comercios/show.blade.php --}}
@extends('layouts.app')

@section('titulo', $comercio->nombre_comercio)

@section('contenido')

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
    {{ session('error') }}
</div>
@endif

<p><a href="{{ route('comercios.index') }}">&larr; Volver a comercios</a></p>

<h1>{{ $comercio->nombre_comercio }}</h1>

<a href="{{ route('transacciones.create', $comercio) }}">
    + Nueva transacción
</a>

<p class="meta">
    Rubro: {{ $comercio->rubro }}
    &middot;
    Teléfono: {{ $comercio->telefono ?? 'Sin teléfono registrado' }}
</p>

<h2>Transacciones</h2>

@if ($comercio->transacciones->count() === 0)
<p>Este comercio es nuevo, aún no registra actividad.</p>
@elseif ($comercio->transacciones->count() === 1)
<p>Este comercio tiene su primera transacción registrada.</p>
@else
<p>Este comercio tiene un historial de {{ $comercio->transacciones->count() }} transacciones.</p>
@endif

@forelse ($comercio->transacciones as $t)
<div class="transaccion">
    <strong>${{ $t->monto }}</strong> {{ $t->cliente_nombre }}
    <x-badge-estado :estado="$t->estado" />
    <a href="{{ route('transacciones.edit', $t) }}">Editar</a>
    <form action="{{ route('transacciones.destroy', $t) }}"
        method="POST"
        onsubmit="return confirm('¿Eliminar esta transacción? Esta acción no se puede deshacer.')">
        @csrf
        @method('DELETE')
        <button type="submit">Eliminar</button>
    </form>
    
    @if ($t->estado === 'Iniciada')
    <form action="{{ route('transacciones.mover', [$t, 'Completada']) }}"
        method="POST" style="display:inline">
        @csrf @method('PATCH')
        <button type="submit">Marcar Completada</button>
    </form>
    <form action="{{ route('transacciones.mover', [$t, 'Fallida']) }}"
        method="POST" style="display:inline">
        @csrf @method('PATCH')
        <button type="submit">Marcar Fallida</button>
    </form>
    @endif
</div>
@empty
<p>Sin transacciones</p>
@endforelse


@endsection