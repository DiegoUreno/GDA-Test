<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Commune;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Response;

class CustomerController extends Controller
{

    public function register(Request $request)
    {
        Log::channel('custom')->info('Entrada de información: ' . json_encode($request->all()));


        $validatedData = $request->only(['dni', 'id_reg', 'id_com', 'email', 'name', 'last_name', 'address']);


        $customer = new Customer;
        $customer->fill($validatedData);
        $customer->date_reg = now(); 
        $customer->status = 'A'; 
        $customer->save();
        $response = new Response(['success' => true, 'message' => 'Customer registrado con éxito']);

        if (config('app.debug')) {

            Log::channel('custom')->info('Salida de información: ' . json_encode($response->getContent()));
        }
        
        return $response;
    }


    public function findByDniOrEmail($identifier)
    {


        $customer = Customer::where('status', 'A')
            ->where(function ($query) use ($identifier) {
                $query->where('dni', $identifier)->orWhere('email', $identifier);
            })
            ->first();

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Customer no encontrado']);
        }


        $customerRegion = Region::find($customer->id_reg);
        $customerCommune = Commune::find($customer->id_com);

        $response = new Response([
            'success' => true,
            'customer' => [
                'name' => $customer->name,
                'last_name' => $customer->last_name,
                'address' => $customer->address ?? null,
                'description_region' => $customerRegion->description,
                'description_commune' => $customerCommune->description,
            ],
        ]);

        if (config('app.debug')) {

            Log::channel('custom')->info('Salida de información: ' . json_encode($response->getContent()));
        }
        
        return $response;
    }

    public function softDelete($dni)
    {

        $customer = Customer::whereIn('status', ['A', 'I'])->where('dni', $dni)->first();

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Customer no encontrado o ya eliminado']);
        }


        $customer->status = 'trash';
        $customer->save();

        $response = new Response(['success' => true, 'message' => 'Customer borrado con éxito']);

        if (config('app.debug')) {

            Log::channel('custom')->info('Salida de información: ' . json_encode($response->getContent()));
        }
        
        return $response;
    }
}
