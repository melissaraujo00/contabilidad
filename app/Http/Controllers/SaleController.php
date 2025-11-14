<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SaleDetails;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'documentType'])->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        return view('sales.create', [
            'customers'     => Customer::all(),
            'documentTypes' => DocumentType::all(),
            'products'      => Product::all()
        ]);
    }


    public function store(StoreSaleRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $customer = Customer::findOrFail($request->customer_id);

            $subtotal_general = 0;
            $iva_general = 0;

            $sale = Sale::create([
                'numero_documento' => 'FAC-'.time(),
                'fecha'            => now(),
                'subtotal'         => 0,
                'iva'              => 0,
                'customer_id'      => $data['customer_id'],
                'documentType_id'  => $data['documentType_id']
            ]);

            foreach ($data['products'] as $item) {

                $product = Product::find($item['product_id']);
                $cantidad = $item['cantidad'];
                $precio = $product->precio;

                if ($customer->tipo_cliente === 'consumidor') {
                    $precio_sin_iva = $precio;
                    $iva_unitario = 0;

                } elseif ($customer->tipo_cliente === 'juridico') {
                    $precio_sin_iva = $precio / 1.13;
                    $iva_unitario = $precio - $precio_sin_iva;

                } else {
                    $precio_sin_iva = $precio;
                    $iva_unitario = 0;
                }

                $subtotal = $precio_sin_iva * $cantidad;
                $iva_total = $iva_unitario * $cantidad;

                $subtotal_general += $subtotal;
                $iva_general += $iva_total;

                SaleDetail::create([
                    'precio'     => $precio,
                    'subtotal'   => $subtotal,
                    'iva'        => $iva_total,
                    'monto'      => $subtotal + $iva_total,
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id
                ]);
            }

            $sale->update([
                'subtotal' => round($subtotal_general, 2),
                'iva'      => round($iva_general, 2)
            ]);

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Venta registrada con éxito');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar la venta: ' . $e->getMessage());
        }
    }
}
