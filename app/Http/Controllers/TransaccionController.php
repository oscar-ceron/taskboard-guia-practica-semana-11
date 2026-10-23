<?php

namespace App\Http\Controllers;

use App\Models\Comercio;
use App\Models\Transaccion;
use Illuminate\View\View;
use App\Http\Requests\GuardarTransaccionRequest;


class TransaccionController extends Controller
{
    public function create(Comercio $comercio): View
    {
        return view('transacciones.create', compact('comercio'));
    }

    public function store(GuardarTransaccionRequest $request)
    {
        $transaccion = Transaccion::create($request->only([
            'comercio_id',
            'cliente_nombre',
            'monto'
        ]));
        return redirect()
            ->route('comercios.show', $transaccion->comercio_id)
            ->with('mensaje', 'Transacción registrada con éxito.');
    }

    public function edit(Transaccion $transaccion)
    {
        $comercios = Comercio::orderBy('nombre_comercio')->get();
        return view(
            'transacciones.edit',
            compact('transaccion', 'comercios')
        );
    }

    public function update(
        GuardarTransaccionRequest $request,
        Transaccion $transaccion
    ) {
        $transaccion->update($request->validated());
        return redirect()
            ->route('comercios.show', $transaccion->comercio_id)
            ->with('mensaje', 'Transacción actualizada correctamente.');
    }

    public function destroy(Transaccion $transaccion)
    {
        $comercioId = $transaccion->comercio_id;
        $clienteNombre = $transaccion->cliente_nombre;
        if ($transaccion->estado === 'Completada') {
            return redirect()
                ->route('comercios.show', $comercioId)
                ->with('error', "No se puede eliminar la transacción de {$clienteNombre} porque está Completada.");
        }else {
        $transaccion->delete();
        return redirect()
            ->route('comercios.show', $comercioId)
            ->with('mensaje', "Transacción de {$clienteNombre} eliminada.");
        }
    }

}
