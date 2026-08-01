<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- font awesome cdn --}}
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- font awesome cdn --}}
    <link rel="stylesheet" href="{{URL::asset('plugins/fontawesome-free/css/all.min.css')}}">

    <link rel="stylesheet" href="erpcss/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link href="erpcss/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="mainjs/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="erpcss/bootstrap.min.css">
    <link rel="stylesheet" href="erpcss/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="erpcss/buttons.bootstrap5.min.css">

    <link rel="stylesheet" href="bootstrap_icon/bootstrap-icons.min.css">


    <script src="mainjs/adminlte.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style type="text/css">

    </style>
    <style type="text/css">
    .text-style:hover {
        text-decoration: underline;
    }

    label {
        font-size: 10px;

    }

    @media only screen and (max-width: 400px) {
        body {
            font-size: 50px;

        }
    }




    #ui-datepicker-div {
        display: none;
    }

    .select2-selection__rendered {
        line-height: 28px !important;
    }

    .select2-container .select2-selection--single {
        height: 38px !important;
    }

    .select2-selection__arrow {
        height: 35px !important;
    }
    </style>


    <style type="text/css">
    @font-face {
        font-family: SutonnyMJ;
        src: url('/fonts/SutonnyMJ.ttf');
        src: url('/fonts/SutonnyMJ.eot');
        src: url('/fonts/SutonnyMJ.eot?#iefix') format('embedded-opentype'),
            url('/fonts/SutonnyMJ.ttf') format('truetype'),
            url('/fonts/SutonnyMJ.svg#FortFoundry') format('svg');
    }

    #b_name {
        font-family: 'SutonnyMJ' !important;
    }
    </style>


    <title>Leave Entry</title>
</head>

<body>
    @section('title', 'Page Title')
    @include('topbar.sidebar')

    <div class="container-fluid">
        <div class="content-wrapper">


            <form action="" method="" id="leavEntryMaster">
                <div class="container-fluid">

                    {{-- Company Name input --}}
                    <div class="row p-1">

                        <hr />
                        <div class="col-md-4">
                            <label for="company_id" class="form-label">Company Name</label>
                            <select class="form-select" id="company_id" name="company_id" required>
                                <option selected value="">Choose...</option>
                                @foreach($companyList As $companyList)
                                <option value="{{$companyList->company_id}}">{{$companyList->company_name}}</option>

                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="year" class="form-label">Year</label>

                            <select class="form-control" id="year" name="year" required>
                                <option selected value="">Choose...</option>
                                @foreach($yaerList As $yaerList)
                                <option value="{{$yaerList->year}}">{{$yaerList->year}}</option>
                                @endforeach
                            </select>



                        </div>
                        <div class="col-md-4">
                            <label for="empno" class="form-label">Employee Id</label>
                            <select class="form-control" id="empno" name="empno" required>
                                <option selected value="">Choose...</option>
                                @foreach($empnoList As $empnoList)
                                <option value="{{$empnoList->empno}}">{{$empnoList->empno}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="emp_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="emp_name" name="emp_name" required>

                        </div>
                        <div class="col-md-4">
                            <label for="designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" name="designation" required>



                        </div>
                        <div class="col-md-4">
                            <label for="lv_cat_id" class="form-label">Leave Category</label>
                            <input type="text" class="form-control" id="lv_cat_id" name="lv_cat_id" required>

                        </div>

                    </div>
            </form>

            <hr>



            <div class="container-fluid">

                <div class="overflow-auto" style="max-width: 2000px; max-height: 250px;">
                    <table width="2000">
                        <thead class="bg-dark text-light">
                            <tr class="w-100">
                                <th style="text-align:center">Leave Name</th>
                                <th style="text-align:center">Max</th>
                                <th style="text-align:center">Pre Bal</th>
                                <th style="text-align:center">Submitted</th>
                                <th style="text-align:center">Approved</th>
                                <th style="text-align:center">L. From</th>
                                <th style="text-align:center">L. To</th>
                                <th style="text-align:center">Days</th>
                                <th style="text-align:center">New Bal</th>
                                <th style="text-align:center">Approved By</th>
                                <th style="text-align:center">Information</th>
                                <th style="text-align:center">Remarks</th>
                            </tr>
                        </thead>
                        <form action="" id="leaveEntry">


                            <tbody>
                                <tr>
                                    <input type="text" class="form-control" id="lv_cat_id_det" name="lv_cat_id_det" hidden>
                                    <input type="text" class="form-control" id="company_d_det" name="company_d_det" hidden>
                                    <input type="text" class="form-control" id="emp_no_det" name="emp_no_det" hidden>
                                    <input type="text" class="form-control" id="year_det" name="year_det" hidden>

                                    <td colspan="1"><select class="form-control w-60" name="leave_name" id="leave_name"
                                            value="{{ old('leave_name') }}" placeholder="">
                                            <option selected value="">Select One</option>
                                            @foreach($leaveCatt As $leaveCatt)
                                            <option value="{{$leaveCatt->leave_id}}">{{$leaveCatt->leave_name}}</option>

                                            @endforeach

                                    </td>
                                    <td colspan="1"><input type="text" class="form-control w-10" name="max_days"
                                            id="max_days" required minlength="1" maxlength="5"
                                            value="{{ old('max_days') }}" placeholder=""></td>
                                    <td rowspan="2"><input type="number" class="form-control" name="pre_balance"
                                            id="pre_balance" value="{{ old('pre_balance') }}" placeholder=""></td>
                                    <td rowspan="2"><input type="text" class="form-control" name="submitted"
                                            id="submitted" value="{{ old('submitted') }}" placeholder=""></td>
                                    <td rowspan="2"><input type="text" class="form-control" name="approve_date"
                                            id="approve_date" value="{{ old('approve_date') }}" placeholder=""></td>
                                    <td rowspan="2"><input type="text" class="form-control" name="lv_from" id="lv_from"
                                            value="{{ old('lv_from') }}" placeholder=""></td>
                                    <td rowspan="2"><input type="text" class="form-control" name="lv_to" id="lv_to"
                                            value="{{ old('lv_to') }}" placeholder=""></td>
                                    <td rowspan="2"><input type="number" class="form-control" name="approve_days"
                                            id="approve_days" value="{{ old('approve_days') }}" placeholder=""></td>
                                    <td rowspan="2"><input type="text" class="form-control" name="new_balance"
                                            id="new_balance" value="{{ old('new_balance') }}" placeholder=""></td>
                                    <td rowspan="2"><input type="text" class="form-control" name="approve_by"
                                            id="approve_by" value="{{ old('approve_by') }}" placeholder=""></td>
                                    <td rowspan="2"><input type="text" class="form-control" name="information"
                                            id="information" value="{{ old('information') }}" placeholder=""></td>
                                    <td rowspan="2"><input type="text" class="form-control" name="remarks" id="remarks"
                                            value="{{ old('remarks') }}" placeholder=""></td>


                                </tr>

                            </tbody>



                    </table>

                </div>
                <div class="row-md-6 m-3 text-center">
                    <button class="btn btn-info" type="submit" id="save_btn">Submit</button>

                    </form>



                </div>



            </div>
        </div>
        <hr>
        <div class="overflow-auto" style="max-width: 100%; max-height: 2200px;">
            <table id="leave_list" class="table table-bordered p-3" style="width:100%">
                <thead class="bg-dark text-light">
                    <tr>
                        <th style="text-align:center">Leave Name</th>
                        <th style="text-align:center">Max</th>
                        <th style="text-align:center">Pre Bal</th>
                        <th style="text-align:center">Submitted</th>
                        <th style="text-align:center">Approved</th>
                        <th style="text-align:center">Approved Days</th>
                        <th style="text-align:center">L. From</th>
                        <th style="text-align:center">L. To</th>

                        <th style="text-align:center">Approved By</th>
                        <th style="text-align:center">Information</th>
                        <th style="text-align:center">Remarks</th>
                        <th style="text-align:center">Action</th>
                    </tr>
                </thead>
                <tbody id="list_value">

                </tbody>
            </table>
        </div>
</body>


<script src="dtjs/popper.min.js" crossorigin="anonymous">
</script>
<script src="dtjs/bootstrap.min.js" crossorigin="anonymous">
</script>

<script src="mainjs/jquery.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }} "></script>
<script type="text/javascript" src="{{ URL::asset('dist/js/adminlte.min.js') }} "></script>
<script src="mainjs/jquery-ui.js"></script>
<script src="mainjs/jquery.timepicker.min.js"></script>
<script src="mainjs/moment.min.js" crossorigin="anonymous"></script>
<script src="mainjs/jquery.dataTables.min.js"></script>

<script src="mainjs/moment-duration-format.js"></script>

<link type="text/css" rel="Stylesheet" href="mainjs/jquery-ui.css" />


<script src="mainjs/moment.min.js" crossorigin="anonymous">
</script>

<script src="mainjs/moment-duration-format.js"></script>



<script src="mainjs/select2.min.js"></script>
<link href="erpcss/select2.min.css" rel="stylesheet" />
<script src="mainjs/sweetalert2.all.min.js"></script>
<link href="erpcss/sweetalert2.min.css" rel="stylesheet">


<script>
$(document).ready(function() {
    $('#empno').select2();

    $("#approve_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true
    });
    $("#lv_from").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true
    });

    $("#lv_to").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        onSelect: function(selected, evnt) {


            var addDay = moment.duration({
                'days': 1
            });
            var dateB = moment($("#lv_from").val());
            var dateC = moment($("#lv_to").val()).add(addDay);



            var diff = moment($("#lv_to").val()).diff(dateB, 'days');
           
            var dayss = 1;
            var fibal = parseInt(diff) + parseInt(dayss);


            $("#approve_days").val(fibal);


            var prebal = $('#pre_balance').val();
            var newbal = (parseInt($('#max_days').val()) - parseInt($('#pre_balance').val())) -
                fibal;
            if (newbal < 0) {

                $('#new_balance').val(0);

                Swal.fire(
                    'Error!',
                    'You have No Balance!',
                    'Error'
                )
            } else {
                $('#new_balance').val(newbal);


            }
        }
    });
    $('#leaveEntry').submit(function(e) {
        e.preventDefault();
        alert('d');
        var formData = $('#leavEntryMaster').serialize();


        $.ajax({
            type: 'get',
            url: '/LeaveEntry',

            data: formData,


            success: function(response) {

               if(response.status==200){

                var formData = $('#leaveEntry').serialize();



                $.ajax({
            type: 'get',
            url: '/LeaveEntryDet',

            data: formData,


            success: function(response) {

                fetAllFileData();
                if (response.status==200){
                    Swal.fire(
                    'Success!',
                    'Added!',
                    'success'
                )
                }else{
                    Swal.fire(
                    'Error!',
                    'You have No Balnce!',
                    'Error'
                )
                }
            }
        });
               }
            }
        });

    })
    $('#leave_name').on('change', function(e) {
        e.preventDefault();
        var empId = $('#empno').val();
        var year = $('#year').val();
        var leave_name = $('#leave_name').val();

        $.ajax({
            url: 'getPrebal/' + empId + '/' + year + '/' + leave_name,
            method: 'get',

            success: function(data) {
                $.each(data, function(key, lvprebal) {
                    console.log(lvprebal);

                    $('#pre_balance').val(lvprebal.approve_days);





                });
            },
            error: function(response) {

            }
        });
        $.ajax({
            url: 'getLeavBal/' + leave_name,
            method: 'get',

            success: function(data) {
                $.each(data, function(key, lvprebal) {
                    console.log(lvprebal);

                    $('#max_days').val(lvprebal.max_days);





                });
            },
            error: function(response) {

            }
        });

    })




});









$('#empno').on('change', function(e) {
    e.preventDefault();
    var empno = $("#empno").val();
    $('#emppersonalSave').hide();

    $('#emppersonalUpdate').show();

    $.ajax({
        url: 'empDetailsSearch',
        method: 'get',
        data: {
            'empno': empno
        },
        success: function(data) {
            fetAllFileData();
            $.each(data, function(key, empdt) {
                console.log(empdt);
                $('#emp_name').val(empdt.first_name + ' ' + empdt.last_name);

                $.each(empdt.getempofficial, function(key, getempofficial) {
                    console.log(getempofficial);


                    $('#lv_cat_id').val(getempofficial.lv_cat_id);
                    $('#designation').val(getempofficial.des_name);
                    $('#lv_cat_id_det').val(getempofficial.lv_cat_id);
                    $('#emp_no_det').val($('#empno').val());
                    $('#year_det').val($('#year').val());




                });





            });
        },
        error: function(response) {

        }
    });


});



    function fetAllFileData() {
        var empId = $('#empno').val();
        var year = $('#year').val();
    $.get("{{ URL::to('getLeaveDetails') }}" + '/'+ empId + '/' + year, function(data)

        {
            $('#list_value').empty().html(data);


        })
}

$(document).on('click', '#leaveDel', function(e) {

    e.preventDefault();


    var id = $(this).data('id');
    var first = $(this).data('first');
    var second = $(this).data('second');

    $.ajax({
            url: 'deleteLeave/' + first+'/'+second+'/'+id,
            method: 'get',

            success: function(response) {
                if (response.status2==200){
                    fetAllFileData();
                    Swal.fire(
                    'Success!',
                    'Deleted!',
                    'success'
                )
                }else{
                    Swal.fire(
                    'Error!',
                    'Delete Error!',
                    'Error'
                )
                }
            },
            error: function(response) {

            }
        });



})


</script>

</html>