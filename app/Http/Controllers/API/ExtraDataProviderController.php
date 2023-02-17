<?php

namespace App\Http\Controllers\API;


use App\Services\DataService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExtraDataProviderController extends Controller
{
    use DataService;

    function holidayList(Request $request){
        $validator = Validator::make($request->all(),[
//            'state_id' => 'required|numeric',
            'year' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'validation_error'=>$validator->errors()], 400);
        }

        try {
            $user = auth('sanctum')->user();
            $empStateId = $user->employee->state_id;
            $data = $this->holidays($empStateId, $request->year);
            return response()->json(['status' => 'success', 'message' => 'Holidays record fetch successfully', 'data' => $data], 200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    function employeesList(Request $request){
        $data = $this->users();
        return response()->json(['status' => 'success', 'message' => 'Employees record fetch successfully', 'data' => $data], 200);
    }
}
