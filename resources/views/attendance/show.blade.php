@extends('layouts.app')

@push('style-scripts')
    <!-- INTERNAL Data table css -->
    <link href="{{asset('css/fullcalendar.min.css')}}" rel="stylesheet" />

@endpush

@section('style')
    <style>
    </style>
@endsection

@section('php')
    @php
        $yearCondition = Date('Y');
        if(app('request')->input('year') != ''){
            $yearCondition = app('request')->input('year');
        }

        $monthCondition = Date('m');
        if(app('request')->input('month') != ''){
            $monthCondition = app('request')->input('month');
        }
    @endphp
@endsection

@section('content')

    <div class="app-content">
        <div class="side-app">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <!-- Row -->

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filter</h3>
                        </div>
                    </div>

                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title">Attendances</h3>
                        </div>

                        <!-- show success and unsuccess message -->
                        @include('layouts.error_display')
                        <!-- End show success and unsuccess message -->

                        <div class="card-body">
                            <!-- Main content -->
                            <section class="content">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-primary">
                                            <form id="employee_Attendance" method="GET">
                                                <div class="row select-detail-below">

                                                    <input type="hidden" name="id" value="{{@$user->id}}">

                                                    <div class="col-md-2 attendance-column1">
                                                        <label>Year<sup class="ast">*</sup></label>
                                                        <select class="form-control input-sm basic-detail-input-style" id="year" name="year">
                                                            <option value="" selected disabled>Please select Year</option>
                                                            @for($year = date("Y"); $year >=2020; $year--)
                                                                <option value="{{ $year }}">{{ $year }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2 attendance-column2">
                                                        <label>Month<sup class="ast">*</sup></label>
                                                        <select class="form-control input-sm basic-detail-input-style" id="month" name="month">
                                                            <option value="" selected disabled>Please select Month</option>
                                                            <option value="1">Dec-Jan</option>
                                                            <option value="2">Jan-Feb</option>
                                                            <option value="3">Feb-March</option>
                                                            <option value="4">Mar-Apr</option>
                                                            <option value="5">Apr-May</option>
                                                            <option value="6">May-June</option>
                                                            <option value="7">June-Jul</option>
                                                            <option value="8">Jul-Aug</option>
                                                            <option value="9">aug-Sep</option>
                                                            <option value="10">sep-Oct</option>
                                                            <option value="11">Oct-Nov</option>
                                                            <option value="12">Nov-Dec</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2 attendance-column4">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn searchbtn-attendance">Search <i class="fa fa-search"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                            </form>

                                            <?php
                                            $monthNames = Array('1' => "January", '2' => "February", '3' => "March", '4' => "April", '5' => "May", '6' => "June", '7' => "July",
                                                '8' => "August", '9' => "September", '10' => "October", '11' => "November", '12' => "December");

                                            if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
                                            if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");

                                            $cMonth = $_REQUEST["month"];
                                            $cYear = $_REQUEST["year"];

                                            $prev_year = $cYear;
                                            $next_year = $cYear;
                                            $prev_month = $cMonth-1;
                                            $next_month = $cMonth+1;

                                            if ($prev_month == 0 ) {
                                                $prev_month = 12;
                                                $prev_year = $cYear - 1;
                                            }
                                            if ($next_month == 13 ) {
                                                $next_month = 1;
                                                $next_year = $cYear + 1;
                                            }

                                            $date1 = $prev_year.'-'.$prev_month.'-'.'26';
                                            $date2 = $cYear.'-'.$cMonth.'-'.'25';

                                            ?>
                                            <hr class="attendance-hr">
                                            <!-- Attendance guide section starts here -->
                                            <div class="emp-name-and-color">
                                                <div class="attendance-guide">
                                                    <ul>
                                                        <li class="attendance-rectangle holiday-only">Holiday</li>
                                                        <li class="attendance-rectangle present-only">Present</li>
                                                        <li class="attendance-rectangle absent-only">Absent</li>
                                                        <li class="attendance-rectangle check-in-only">Check-In</li>

                                                        <li class="attendance-rectangle ">Balance Casual : @if(isset($leaveDetail)){{ $leaveDetail->accumalated_casual_leave }} @else 0 @endif</li>
                                                        <li class="attendance-rectangle ">Balance Sick : @if(isset($leaveDetail)){{ $leaveDetail->accumalated_sick_leave }} @else 0 @endif</li>

                                                    </ul>
                                                </div>
                                                <div class="a-last-absent">
                                                    <h3 class="a-employe-name">{{@$user->employee->fullname}}</h3>
                                                    <!-- <span class="a-last-absent-span1">bsenLast At:</span> -->
                                                    <!-- <span class="a-last-absent-span2">25 Days Ago</span> -->
                                                </div>
                                            </div>
                                            <!-- Attendance guide section ends here -->



                                            <!-- Calender starts here -->
                                            <div class="box">
                                                <div class="box-body no-padding">
                                                    <!-- THE CALENDAR -->
                                                    <div id="calendar" class="fc fc-unthemed fc-ltr">
                                                        <div class="fc-toolbar fc-header-toolbar">
                                                            <div class="fc-left">
                                                                <div class="fc-button-group">
                                                                    <a href="<?php echo url()->current() . "?month=". $prev_month . "&year=" . $prev_year. "&id=". @$user->id; ?>"><button type="button" class="fc-prev-button fc-button fc-state-default fc-corner-left" aria-label="prev"><span class="fc-icon fc-icon-left-single-arrow"></span></button></a>
                                                                    <a href="<?php echo url()->current() . "?month=". $next_month . "&year=" . $next_year. "&id=". @$user->id; ?>"><button type="button" class="fc-next-button fc-button fc-state-default fc-corner-right" aria-label="next"><span class="fc-icon fc-icon-right-single-arrow"></span></button></a>
                                                                </div>
                                                                <!-- <button type="button" class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right fc-state-disabled" disabled="">today</button> -->
                                                            </div>


                                                            <div class="fc-center">
                                                                <h2>
                                                                    <?php
                                                                    echo $monthNames[$prev_month];
                                                                    echo "-";
                                                                    echo $monthNames[$cMonth];
                                                                    echo " ".$cYear;
                                                                    ?>
                                                                </h2>
                                                            </div>


                                                            <!--&& @$verify['verifier'] != 0 -->
                                                            <!--if(strtotime($on_date) < strtotime(date("Y-m-d")) && @$verify['isverified'] == 0  ) unverified  -->


                                                            @if($_REQUEST["month"] == date('n'))
                                                                @if(@$verify['isverified'] == 0 )
                                                                    @if(@$verify['verifier']!=@$user->id)

                                                                        <button type="button" class="btn btn-primary verify-btn-calender verifyMonthAttendance" data-userid="{{@$user->id}}"
                                                                                data-managerid="{{@$verify['verifier']}}" data-ondate="{{$cYear.'-'.$cMonth.'-'.'25'}}">Verify Attendance</button>
                                                                    @endif
                                                                @elseif(@$verify['isverified'] == 1)
                                                                    <span class="verify-btn-calender attendance-verified label-success">Verified</span>
                                                                @endif
                                                            @else
                                                                @if(@$verify['isverified'] == 0)
                                                                    <span  class="label label-info">Attendance not Verified.</span>
                                                                @else
                                                                    <span class="verify-btn-calender attendance-verified label-success">Verified</span>
                                                                @endif
                                                            @endif
                                                            <div class="fc-clear"></div>
                                                        </div>
                                                        {{--                                    <form method="post" action="{{ url('attendances/verify-month-attendance') }}">--}}
                                                        {{--                                        @csrf--}}
                                                        {{--                                        <input type="hidden" name="user_id" value="{{@$user->id}}">--}}
                                                        {{--                                        <input type="hidden" name="manager_id" value="{{ Auth::id() }}">--}}
                                                        {{--                                        <input type="hidden" name="on_date" value="2021-07-25">--}}
                                                        {{--                                        <input type="submit">--}}
                                                        {{--                                    </form>--}}


                                                        <div class="fc-view-container" style="">
                                                            <div class="fc-view fc-month-view fc-basic-view" style="">
                                                                <table class="">
                                                                    <thead class="fc-head">
                                                                    <tr>
                                                                        <td class="fc-head-container fc-widget-header">
                                                                            <div class="fc-row fc-widget-header">
                                                                                <table class="">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th class="fc-day-header fc-widget-header fc-sun"><span>Sun</span></th>
                                                                                        <th class="fc-day-header fc-widget-header fc-mon"><span>Mon</span></th>
                                                                                        <th class="fc-day-header fc-widget-header fc-tue"><span>Tue</span></th>
                                                                                        <th class="fc-day-header fc-widget-header fc-wed"><span>Wed</span></th>
                                                                                        <th class="fc-day-header fc-widget-header fc-thu"><span>Thu</span></th>
                                                                                        <th class="fc-day-header fc-widget-header fc-fri"><span>Fri</span></th>
                                                                                        <th class="fc-day-header fc-widget-header fc-sat"><span>Sat</span></th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="fc-body">
                                                                    <tr>
                                                                        <td class="fc-widget-content">
                                                                            <div class="fc-scroller fc-day-grid-container" style="overflow: hidden; height: 666px;">
                                                                                <div class="fc-day-grid fc-unselectable">
                                                                                    <div class="fc-row fc-week fc-widget-content" style="height: 96px;">

                                                                                        <div class="fc-bg">
                                                                                            <table>
                                                                                                <thead>

                                                                                                <?php

                                                                                                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                                                                                                $thismonth = getdate ($timestamp);

                                                                                                $startday = $thismonth['wday'];

                                                                                                $timestamp = strtotime($date1);
                                                                                                $thismonth = getdate ($timestamp);
                                                                                                $startday = $thismonth['wday'];


                                                                                                $allowAttendanceVerification = 1;
                                                                                                $j=array();
                                                                                                $i=0;
                                                                                                while (strtotime($date1) <= strtotime($date2)) {
                                                                                                $day = $i - $startday + 1;
                                                                                                $cDayTime = $cYear.'-'.$cMonth.'-'.$day;

                                                                                                $attendanceArray = \App\Helpers\Helper::getAttendanceInfo($date1, @$user->id);

                                                                                                $status = $attendanceArray['status'];

                                                                                                $mapUrl = url('attendances/view-map').'?id='.@$user->id.'&date='.$date1;


                                                                                                if(($i % 7) == 0 ){

                                                                                                ?>
                                                                                                <tr>

                                                                                                    <?php }if($i < $startday) { ?>
                                                                                                    <td class="fc-day-top fc-sun fc-other-month fc-past" data-date=""></td>

                                                                                                    <td class="fc-day-top fc-sun fc-future attendance-tds" data-date="2019-09-01">
                                                                                                        <?php $date_month = Date('d M',strtotime($date1)); ?>
                                                                                                        <span class="fc-day-number"><?php echo $date_month; ?></span>
                                                                                                        <div class="three-icon-box">
                                                                                                            <div class="edit-tooltip" data-date="{{$date1}}" data-remarks="{{$attendanceArray['remarks']}}" data-userid="{{@$user->id}}">
                                                                                                                <i class="fa @if(!empty($attendanceArray['remarks'])){{'fa-edit a-icon4'}}@endif"></i>
                                                                                                                <span class="edit-tooltiptext">@if(!empty($attendanceArray['remarks'])){{'View Remarks'}}@endif</span>
                                                                                                            </div>

                                                                                                        </div>

                                                                                                        <?php if($status) { ?>
                                                                                                        <div class="leave-type-onnly">
                                                                                                            <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable @if($status == 'Present'){{'calender-day-present'}}@elseif($status == 'Holiday'){{'calender-day-holiday'}}@elseif($status == 'Absent' || $status == 'Week-Off'){{'calender-day-absent'}}@endif">
                                                                                                                <span><?php echo  $status; ?></span>
                                                                                                            </a>
                                                                                                            <div>
                                                                                                                <a><span class="label label-warning full-short-half">></span></a>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div style="padding-left: 5px !important; padding-right: 5px !important;">
                                                                                                            <a><span class="label label-warning full-short-half">{{  $attendanceArray['leave_type'] }}</span></a>
                                                                                                        </div>
                                                                                                        <?php } ?>

                                                                                                    </td>

                                                                                                    <?php
                                                                                                    $date1 = date ("Y-m-d", strtotime("+1 days", strtotime($date1)));
                                                                                                    } if(($i % 7) == 6 ){

                                                                                                    ?>

                                                                                                </tr>

                                                                                                <?php
                                                                                                }

                                                                                                $i++;
                                                                                                }
                                                                                                ?>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <!-- Calender ends here -->

                                                    </div>
                                                </div>
                                                <!-- /.box-body -->
                                            </div>
                                            <!-- Calender starts here -->
                                        </div>
                                    </div>
                                </div>

                            </section>
                            <!-- End Main content -->
                        </div>
                    </div>
                </div>

                <!-- End Row -->
            </div>
            <!-- PAGE-HEADER END -->
        </div>
    </div>
@endsection

@push('js-scripts')

    <!-- INTERNAL Data tables js-->
    <script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>

    <script src="{{asset('assets/js/form-js.js')}}"></script>

@endpush

@section('script')
    <script>
        $(document).ready(function() {
            dataTablePattern('.data-table');
        });
    </script>
@endsection
