<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- {{-- font awesome cdn --}} -->
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- {{-- font awesome cdn --}} -->
    <link rel="stylesheet" href="{{URL::asset('plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="erpcss/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link href="erpcss/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="mainjs/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="erpcss/bootstrap.min.css">
    <link rel="stylesheet" href="erpcss/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="erpcss/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="bootstrap_icon/bootstrap-icons.min.css">
    <script src="mainjs/adminlte.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
    #ui-datepicker-div {
        display: none;
    }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance</title>
</head>

<body>
    @section('title', 'Page Title')
    @include('topbar.sidebar')
    <!-- {{-- <div class=" p-3"> --}} -->
    <div class="container-fluid">
        <div class="content-wrapper">

            <form action="" method="" id="attdfrom">
                @csrf
                <div class="container col-sm-12 p-3">

                    <div class="row">
                        {{-- From input --}}
                        <div class="col-md-6">
                            <div class="row p-1">
                                <label for="from" class="col-sm-4 col-form-label">From :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="fromDate" id="fromDate"
                                        value="{{ old('fromDate') }}">
                                </div>
                            </div>
                        </div>


                        {{-- To input --}}
                        <div class="col-md-6">
                            <div class="row p-1">
                                <label for="to" class="col-sm-4 col-form-label">To :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="todate" id="todate"
                                        value="{{ old('todate') }}">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        {{-- In Time input --}}
                        <div class="col-md-6">
                            <div class="row p-1">
                                <label for="in_time" class="col-sm-4 col-form-label">In Time :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="in_time" id="in_time"
                                        value="{{ old('in_time') }}">
                                </div>
                            </div>
                        </div>


                        {{-- Out Times input --}}
                        <div class="col-md-6">
                            <div class="row p-1">
                                <label for="out_time" class="col-sm-4 col-form-label">Out Time :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="out_time" id="out_time"
                                        value="{{ old('out_time') }}">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        {{-- From input --}}
                        <div class="col-md-6">
                            <div class="row p-1">
                                <label for="designation_name" class="col-sm-4 col-form-label">Designation :</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="designation_name" name="designation_name">
                                        {{-- @foreach($empList as $empList)
                                                  <option value="{{$empList->designation_name}}">{{$empList->designation_name}}
                                        </option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- To input --}}
                        <div class="col-md-6">
                            <div class="row p-1">
                                <label for="empno" class="col-sm-4 col-form-label">Employee :</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="empno" name="empno">
                                        @foreach($empList as $empList)
                                        <option value="{{$empList->empno}}">{{$empList->empno}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-2">
                        <button type="button" class="btn btn-success" id="go_btn">Go!</button>
                    </div>

                </div>
            </form>



        <div class="overflow-auto" style="max-width: 100%; max-height: 2200px;">
            <form action="" class="form-group" id="fromsaveData">
                <table id="attn_table" class="table table-bordered table-striped" style="width:100%">
                    <thead class="bg-dark text-light" style="background-color:rgb(94, 21, 94)">
                        <tr>
                            <th>Date</th>
                            <th>NEW EMP ID</th>
                            <th>Emp ID</th>
                            <th>In Time</th>
                            <th>In Time 2</th>
                            <th>Late</th>
                            <th>Out Time</th>
                            <th>Out Time2</th>
                            <th>OT</th>
                            <th>OT2</th>
                            <th>Extra OT</th>
                            <th>Status</th>
                            <th>Status2</th>
                            <th>Late Extra</th>

                        </tr>
                    </thead>

                    <tbody id="table_data">

                    </tbody>

                </table>
                <button class="btn btn-success" type="submit" id="saveData">Save</button>
            </form>
        </div>
    </div>
    </div>
</body>

<script src="dtjs/popper.min.js" crossorigin="anonymous"></script>
<script src="dtjs/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="mainjs/jquery.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }} "></script>
<script type="text/javascript" src="{{ URL::asset('dist/js/adminlte.min.js') }} "></script>
<script src="mainjs/jquery-ui.js"></script>
<script src="mainjs/jquery.timepicker.min.js"></script>
<script src="mainjs/moment.min.js" crossorigin="anonymous"></script>
<script src="mainjs/jquery.dataTables.min.js"></script>
<script src="mainjs/moment-duration-format.js"></script>
<link type="text/css" rel="Stylesheet" href="mainjs/jquery-ui.css" />
<script src="mainjs/moment.min.js" crossorigin="anonymous"></script>
<script src="mainjs/moment-duration-format.js"></script>
<script src="mainjs/select2.min.js"></script>
<link href="erpcss/select2.min.css" rel="stylesheet" />
<script src="mainjs/sweetalert2.all.min.js"></script>
<link href="erpcss/sweetalert2.min.css" rel="stylesheet">

<script>
$(document).ready(function() {
    $('#empno').select2();
});
</script>
<script>
$("#fromDate").datepicker({
    dateFormat: "yy-mm-dd",
    changeMonth: true,
    changeYear: true,
});

$("#todate").datepicker({
    dateFormat: "yy-mm-dd",
    changeMonth: true,
    changeYear: true,
});
</script>
<script>
$("#in_time").timepicker({
    timeFormat: 'h:mm p',
    interval: 60,
    minTime: '8',
    maxTime: '6:00pm',
    // defaultTime: '11',
    startTime: '8:00',
    dynamic: true,
    dropdown: true,
    scrollbar: true,
    showLeadingZero: false
});

$("#out_time").timepicker({
    timeFormat: 'h:mm p',
    interval: 60,
    minTime: '8',
    maxTime: '6:00pm',
    // defaultTime: '11',
    startTime: '8:00',
    dynamic: true,
    dropdown: true,
    scrollbar: true,
    showLeadingZero: false
});
</script>


<script>
$(document).ready(function() {
    $(document).on('click', '#go_btn', function(e) {
        e.preventDefault();
        // var empId = $('#empno').val();

        // const fd = new FormData(this);
        // console.log(fd);
       
        var fromDate = $('#fromDate').val();
        var todate = $('#todate').val();
        var empId = $('#empno').val();
        var inTime = $('#in_time').val();
        var outTime = $('#out_time').val();

        console.log(fromDate);
        console.log(todate);
        console.log(empId);
        console.log(inTime);
        console.log(outTime);

        $.ajax({
            type: 'GET',
            url: 'attData',
            data: {
                'empno': empId,
                'fromDate': fromDate,
                'todate': todate,
                'in_time': inTime,
                'out_time': outTime
            },
            success: function(data) {
                console.log(data);

                $('#table_data').empty().html(data);
                    $('#attn_table').DataTable();
            },
            error: function(response) {
                alert('error');
                console.log(response);
            }
        });
    });
});
</script>

<script>
$("#fromsaveData").submit(function(e) {
    e.preventDefault();

    var arrData = $('#fromsaveData').serializeArray();

    $.ajaxSetup({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    console.log(arrData);
    $.ajax({
        type: 'GET',
        url: 'attendsave',
        data: $('#fromsaveData').serializeArray(),

        dataType: 'json',
        success: function(response) {

            console.log(response); //checking data in controller 

            if (response.status == 200) {

                Swal.fire(
                    'Added!',
                    'Attendance Added Successfully!',
                    'success',

                )

            }
        },
        error: function(response) {
            alert('dd');
            console.log(response);

        }
    });
});
</script>

</html>