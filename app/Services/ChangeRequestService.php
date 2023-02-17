<?php

namespace App\Services;

use App\Exceptions\DatabaseException;
use App\Exceptions\GlobalException;
use App\Models\ChangeRequest;
use App\Models\FieldReport;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChangeRequestService{

    /**
     * @throws GlobalException
     * @throws DatabaseException
     */
    public function filter($fromDate, $toDate, $userId = ''){
        try {

            $startDate = Carbon::createFromFormat('Y-m-d', $fromDate);
            $endDate = Carbon::createFromFormat('Y-m-d', $toDate);

            if(strtotime($startDate) !== strtotime($endDate)){
                $query = ChangeRequest::with('user')->whereBetween('date', [$startDate, $endDate]);
            }else{
                $query = ChangeRequest::with('user')->where('date', $fromDate);
            }

            if(isset($userId) && $userId !== ""){
                $query = $query->where(['user_id' => $userId]);
            }

            $user = Auth::user();

//            if($user->role != '0'){ //Admin
//                $teamUserId = $user->team()->pluck('id');
//                $query = $query->whereIn('user_id', $teamUserId);
//            }

            return $query->paginate(10);
        }catch(\PDOException $e){
            DB::rollback();
            throw new DatabaseException($e->getMessage());
        }
        catch(Exception $e){
            DB::rollback();
            throw new GlobalException($e->getMessage());
        }
    }


}
