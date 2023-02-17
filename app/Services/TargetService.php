<?php

namespace App\Services;

use App\Models\Target;
use Illuminate\Support\Facades\DB;

Trait TargetService{

    function getEmpTarget($id){
        try {
            $target = Target::find($id);
            return response()->json(['status' =>'success', 'message' => 'Target fetch successfully', 'data' => $target], 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    function createOrUpdateEmpTarget($request, $id = ""){

        try {
            $year = $request->year;
            $month = $request->month;

            $data = Target::updateOrCreate(
                [
                    'id' => $id
                ],
                [
                'user_id' => $request->user_id,
                'year' => $year,
                'month' => $month,
                'target' => $request->target
            ]);
            $statusCode = 200;
            $status = 'success';
            $message = "Created Successfully";
        } catch (\PDOException $e) {
            DB::rollBack();
            $statusCode = 400;
            $status = 'error';
            $message = 'Database Error: Target could not be saved.';
        } catch (\Exception $e) {
            DB::rollBack();
            $statusCode = 500;
            $status = 'error';
            $message = 'Error code 500: internal server error.';
        }

        return response()->json(['status' => $status, 'message' => $message, 'data' => $data ?? []], $statusCode);
    }

    function deleteEmpTarget($id){
        try {
            $target = Target::find($id);
            Target::destroy($id);
            return response()->json(['status' =>'success', 'message' => 'Target deleted successfully', 'data' => $target], 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * @empTargetStatus
     * Employee particular month target status
     *
     * @param $year
     * @param $month
     * @param $userId
     * @return void
     */
    function empTarget($year, $month, $userId){
        try {

            $target = Target::where(['year' => $year, 'month' => $month, 'user_id' => $userId])->first();
            $data['year'] = $year;
            $data['month'] = $month;
            $data['target_record']['target'] = $target->target ?? 0;
            $data['target_record']['achieve_target'] = $target->achieve_target ?? 0;

            return response()->json(['status' =>'success', 'message' => 'Target fetch successfully', 'data' => $data], 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

}
