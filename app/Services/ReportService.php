<?php

namespace App\Services;

use App\Exceptions\DatabaseException;
use App\Exceptions\GlobalException;
use App\Models\FieldReport;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use PDOException;

Class ReportService{

    public function fieldReport($fromDate, $toDate, $userId = '', $route = 'web'){
        try {

            $startDate = Carbon::createFromFormat('Y-m-d', $fromDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('Y-m-d', $toDate)->format('Y-m-d');

            if(strtotime($startDate) !== strtotime($endDate)){
                $query = FieldReport::with('user')->whereBetween('date', [$startDate.'00:00:00', $endDate]);
            }else{
                $query = FieldReport::with('user')->where('date', $fromDate);
            }

            if(isset($userId) && $userId !== "" && $userId !== "all"){
                $query = $query->where(['user_id' => $userId]);
            }

            if($route == 'web') {
                    $user = Auth::user();

                    if ($user->role != '0' && !isset($userId) && $userId == "") { //Admin
                        $teamUserId = $user->team()->pluck('id');
                        $query = $query->whereIn('user_id', $teamUserId);
                    }
                return $query->get();
            }else{
                return $query->paginate(10);
            }

        } catch(PDOException $e){
            throw new DatabaseException($e->getMessage(), 'api');
        }
        catch(Exception $e){
            throw new GlobalException($e->getMessage());
        }
    }
}
