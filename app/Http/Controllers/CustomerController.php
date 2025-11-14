<?php

namespace App\Http\Controllers;

use App\Enums\CustomerType;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Mostrar listado.
     */
    public function index()
    {
        $customers = Customer::paginate(20);
        return view('customers.index', compact('customers'));
    }

    /**
     * Formulario de crear cliente.
     */
    public function create()
    {
        $tipos = CustomerType::cases(); // consumidor, juridico, proveedor
        return view('customers.create', compact('tipos'));
    }

    /**
     * Guardar cliente.
     */
    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validate());

        return redirect()->route('customers.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Formulario de edición.
     */
    public function edit(Customer $customer)
    {
        $tipos = CustomerType::cases();
        return view('customers.edit', compact('customer', 'tipos'));
    }

    /**
     * Actualizar cliente.
     */
    public function update( UpdateCustomerRequest $request, Customer $customer)
    {
        $request->validate([
            'nombre'       => 'required|string|max:50',
            'apellido'     => 'required|string|max:50',
            'telefono'     => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:80',
            'nit'          => 'nullable|string|max:20',
            'direccion'    => 'nullable|string|max:150',
            'tipo_cliente' => ['required', 'in:consumidor,juridico,proveedor'],
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Eliminar cliente.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
