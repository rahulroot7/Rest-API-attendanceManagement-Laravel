<?php

namespace App\Services;

use App\Exceptions\DatabaseException;
use App\Exceptions\GlobalException;
use App\Models\City;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Holiday;
use App\Models\State;
use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

Class MasterService{

    function departmentUpdateOrCreate($request, $id = ''){
        try {
            DB::beginTransaction();
            Department::updateOrCreate(
               [
                   'id' => $id
               ],
               [
                   'name' => ucwords($request->name)
                ]
           );
            DB::commit();
        }catch(PDOException $e){
            DB::rollback();
            throw new DatabaseException($e->getMessage());
        }
        catch(Exception $e){
            DB::rollback();
            throw new GlobalException($e->getMessage());
        }
    }

    function designationUpdateOrCreate($request, $id = ''){

        try {
            DB::beginTransaction();
            $data = Designation::updateOrCreate(
                [
                    'id' => $id
                ],
                [
                    'name' => ucwords($request->name)
                ]
            );
            DB::commit();
        }catch(PDOException $e){
            DB::rollback();
            throw new DatabaseException($e->getMessage());
        }
        catch(Exception $e){
            DB::rollback();
            throw new GlobalException($e->getMessage());
        }
    }

    function stateUpdateOrCreate($request, $id = ''){
        try {
            DB::beginTransaction();
            State::updateOrCreate(
                [
                    'id' => $id
                ],
                [
                    'name' => ucwords($request->name)
                ]
            );
            DB::commit();
        }
        catch(PDOException $e){
            DB::rollback();
            throw new DatabaseException($e->getMessage());
        }
        catch(Exception $e){
            DB::rollback();
            throw new GlobalException($e->getMessage());
        }
    }

    function cityUpdateOrCreate($request, $id = ''){
        try {
            DB::beginTransaction();
            $data = City::updateOrCreate(
                [
                    'id' => $id
                ],
                [
                    'name' => ucwords($request->name),
                    'state_id' => $request->state
                ]
            );
            DB::commit();
            $statusCode = 200;
            $status = 'success';
            $message = "Created Successfully";
        }catch(PDOException $e){
            DB::rollback();
            throw new DatabaseException($e->getMessage());
        }
        catch(Exception $e){
            DB::rollback();
            throw new GlobalException($e->getMessage());
        }
    }

    function holidayUpdateOrCreate($request, $id = ''){
        try {
            DB::beginTransaction();
            $data = Holiday::updateOrCreate(
                [
                    'id' => $id
                ],
                [
                    'name' => ucwords($request->name),
                    'state_id' => $request->state,
                    'year' => $request->year,
                    'date' => $request->date
                ]
            );
            DB::commit();
        }catch(PDOException $e){
            DB::rollback();
            throw new DatabaseException($e->getMessage());
        }
        catch(Exception $e){
            DB::rollback();
            throw new GlobalException($e->getMessage());
        }
    }

}
