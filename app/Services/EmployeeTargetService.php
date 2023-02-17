<?php

namespace App\Services;

use App\Models\EmployeeTarget;
use App\Models\Target;
use Illuminate\Support\Facades\DB;

Trait EmployeeTargetService{

    /**
     * @getTarget
     * Get single target data
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function getTarget($id){
        try {
            $target = EmployeeTarget::find($id);
            return response()->json(['status' =>'success', 'message' => 'Target fetch successfully', 'data' => $target], 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * @createOrUpdateTarget
     * Create or update employee daily target
     *
     * @param $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function createOrUpdateTarget($request, $id = ""){

        try {
        $targetDate = date('Y-m-d', strtotime($request->target_date));
        $year = date('Y', strtotime($request->target_date));
        $month = date('m', strtotime($request->target_date));

        $data = EmployeeTarget::updateOrCreate(
            [
                'id' => $id
            ],
            [
                'user_id' => $request->user_id,
                'date' => $targetDate,
                'year' => $year,
                'month' => $month,
                'target' => $request->target
            ]);

        $monthTarget = Target::where(['year' => $year, 'month' => $month, 'user_id' => $request->user_id])->first();
        $monthTarget->achieve_target += $request->target;
        if($monthTarget->achieve_target == $monthTarget->target){
            $monthTarget->status = '1';
        }
        $monthTarget->save();

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

    /**
     * @filterMonthlyTargets
     * Filter monthly target
     *
     * @param $year
     * @param $month
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    function filterMonthlyTargets($year, $month, $userId = ""){
        try {
            $query = Target::has('user')->with('user')->where(['year' => $year, 'month' => $month]);
            if(isset($userId) && $userId !== ''){
                $query = $query->where(['user_id' => $userId]);
            }
            $targets = $query->paginate(10);

            $targets->map(function($target){
                $remaining = 0;
                if(isset($target) && $target->target > $target->achieve_target){
                    $remaining = $target->target-$target->achieve_target;
                }
                $target->remaining = $remaining;
            });

            $data['targets'] = $targets ?? [];
            return response()->json(['status' =>'success', 'message' => 'Targets fetch successfully', 'data' => $data], 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * @filterDailyTarget
     * Filter daily targets
     *
     * @param $year
     * @param $month
     * @param string $userId
     * @return \Illuminate\Http\JsonResponse
     */
    function filterDailyTargets($year, $month, string $userId = ""){
        try {
            $target = Target::where(['year' => $year, 'month' => $month, 'user_id' => $userId])->first();
            $data['monthly_target'] = $target->target ?? 0;
            $data['achieve_target'] = $target->achieve_target ?? 0;
            $remaining = 0;
            if(isset($target) && $target->target > $target->achieve_target){
                $remaining = $target->target-$target->achieve_target;
            }

            $data['remaining_target'] = $remaining;

            $query = EmployeeTarget::with('user')->where(['year' => $year, 'month' => $month]);
            if(isset($userId) && $userId !== ''){
                $query = $query->where(['user_id' => $userId]);
            }
            $targets = $query->paginate(10);

            $targets->map(function($target){
                $target->can_edit = false;
                return $target;
            });

            $data['targets'] = $targets ?? [];

            return response()->json(['status' =>'success', 'message' => 'Targets fetch successfully', 'data' => $data], 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

}
