<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- font awesome cdn --}}
    <link rel="stylesheet" href="{{URL::asset('plugins/fontawesome-free/css/all.min.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">



    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"
        integrity="sha512-KBeR1NhClUySj9xBB0+KRqYLPkM6VvXiiWaSz/8LCQNdRpUm38SWUrj0ccNDNSkwCD9qPA4KobLliG26yPppJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <style>
    #ui-datepicker-div {}
    </style>

    <title>Salary Entry</title>

    <style>
    .scroll-bar {
        height: 100px;
        width: 900px;
        overflow: auto;
    }
    </style>

</head>

<body>
    @section('title', 'Page Title')
    @include('topbar.sidebar')
    <!-- {{-- <div class=" p-3"> --}} -->
    <div class="container-fluid">
        <div class="content-wrapper">



            <form action="" method="" id="salaryForm">
                <div class="container col-lg-6 p-3">
                    <h3 class="text-center">Salary Entry for Employee</h3>
                    <div class="mb-3 row">
                        <label for="company_name" class="col-sm-3 col-form-label ">Company Name : </label>
                        <div class="col-sm-8">
                            <select class="form-select" id="report_list" name="company_name">
                                @foreach($reportLits as $reportLits)
                                <option value="{{ $reportLits->report_id }}">{{ $reportLits->report_title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr />
                    {{-- Leave Catogory input --}}
                    <div class="mb-3 row" id="P_COMPANY_div">
                        <label for="company_name" class="col-sm-3 col-form-label ">Company Name : </label>
                        <div class="col-sm-8">
                            <select class="form-select" id="P_COMPANY" name="P_COMPANY">
                                @foreach($getCompanyDetails as $company)
                                <option value="{{ $company->company_id }}">{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Remarks input --}}
                    <div class="mb-3 row container" id="P_EMP_TYPE_div">
                        <label for="sal_date" class="col-sm-3 col-form-label ">EMP TYPE : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_EMP_TYPE" id="P_EMP_TYPE" placeholder=""
                                value="{{ old('sal_date') }}">
                        </div>
                    </div>

                    {{-- section --}}
                    <div class="mb-3 row container"id="P_SECTION_NO_div">
                        <label for="sal_date" class="col-sm-3 col-form-label ">SECTION  : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_SECTION_NO" id="P_SECTION_NO" placeholder=""
                                value="{{ old('P_SECTION_NO') }}">
                        </div>
                    </div>

                    {{-- deptartment --}}
                    <div class="mb-3 row container"id="P_DEPT_NO_div">
                        <label for="P_DEPT_NO" class="col-sm-3 col-form-label ">Department  : </label>
                        <div class="col-sm-8">
                            <select class="form-select" id="P_DEPT_NO" name="P_DEPT_NO">
                                
                            </select>
                        </div>
                    </div>

                    {{-- floor id --}}
                    <div class="mb-3 row container"id="P_FLOOR_ID_div">
                        <label for="P_FLOOR_ID" class="col-sm-3 col-form-label ">Floor  : </label>
                        <div class="col-sm-8">
                            <select class="form-select" id="P_FLOOR_ID" name="P_FLOOR_ID">
                                
                            </select>
                        </div>
                    </div>

                    {{-- increment type --}}
                    <div class="mb-3 row container"id="P_INCREMENT_TYPE_div">
                        <label for="P_INCREMENT_TYPE" class="col-sm-3 col-form-label ">Increment Type  : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_INCREMENT_TYPE" id="P_INCREMENT_TYPE" placeholder=""
                                value="{{ old('P_INCREMENT_TYPE') }}">
                        </div>
                    </div>

                    {{-- payment date --}}
                    <div class="mb-3 row container"id="P_PAYMENT_DATE_div">
                        <label for="P_PAYMENT_DATE" class="col-sm-3 col-form-label ">Payment Date  : </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="P_PAYMENT_DATE" id="P_PAYMENT_DATE" placeholder=""
                                value="{{ old('P_PAYMENT_DATE') }}">
                        </div>
                    </div>

                    {{-- print date --}}
                    <div class="mb-3 row container"id="P_PRINT_DATE_div">
                        <label for="P_PRINT_DATE" class="col-sm-3 col-form-label ">Print Date  : </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="P_PRINT_DATE" id="P_PRINT_DATE" placeholder=""
                                value="{{ old('P_PRINT_DATE') }}">
                        </div>
                    </div>

                    {{-- p date --}}
                    <div class="mb-3 row container"id="P_DATE_div">
                        <label for="P_DATE" class="col-sm-3 col-form-label ">Date  : </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="P_DATE" id="P_DATE" placeholder=""
                                value="{{ old('P_DATE') }}">
                        </div>
                    </div>

                    {{-- Bonus --}}
                    <div class="mb-3 row container"id="P_BNOUS_div">
                        <label for="P_BNOUS" class="col-sm-3 col-form-label ">Bonus  : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_BNOUS" id="P_BNOUS" placeholder=""
                                value="{{ old('P_BNOUS') }}">
                        </div>
                    </div>

                    {{-- status --}}
                    <div class="mb-3 row container"id="P_PAYMENT_DATE_div">
                        <label for="P_PAYMENT_DATE" class="col-sm-3 col-form-label ">Status  : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_PAYMENT_DATE" id="P_PAYMENT_DATE" placeholder=""
                                value="{{ old('P_PAYMENT_DATE') }}">
                        </div>
                    </div>

                    {{-- p days --}}
                    <div class="mb-3 row container"id="P_PRINT_DATE_div">
                        <label for="P_PRINT_DATE" class="col-sm-3 col-form-label ">Dayes  : </label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="P_PRINT_DATE" id="P_PRINT_DATE" placeholder=""
                                value="{{ old('P_PRINT_DATE') }}">
                        </div>
                    </div>

                    {{-- p month --}}
                    <div class="mb-3 row container"id="P_MONTH_div">
                        <label for="P_MONTH" class="col-sm-3 col-form-label ">Month  : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_MONTH" id="P_MONTH" placeholder=""
                                value="{{ old('P_MONTH') }}">
                        </div>
                    </div>

                    {{-- from date --}}
                    <div class="mb-3 row container"id="P_FROM_DATE_div">
                        <label for="P_FROM_DATE" class="col-sm-3 col-form-label ">From Date  : </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="P_FROM_DATE" id="P_FROM_DATE" placeholder=""
                                value="{{ old('P_FROM_DATE') }}">
                        </div>
                    </div>

                    {{-- to date --}}
                    <div class="mb-3 row container" id="P_TO_DATE_div">
                        <label for="P_TO_DATE" class="col-sm-3 col-form-label ">To Date : </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="P_TO_DATE" id="P_TO_DATE" placeholder=""
                                value="{{ old('P_TO_DATE') }}">
                        </div>
                    </div>

                    {{-- As on --}}
                    <div class="mb-3 row container"id="P_AS_ON_div">
                        <label for="P_AS_ON" class="col-sm-3 col-form-label ">As On  : </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="P_AS_ON" id="P_AS_ON" placeholder=""
                                value="{{ old('P_AS_ON') }}">
                        </div>
                    </div>

                    {{-- attendance date --}}
                    <div class="mb-3 row container"id="P_ATT_DATE_div">
                        <label for="P_ATT_DATE" class="col-sm-3 col-form-label ">Att. Date  : </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="P_ATT_DATE" id="P_ATT_DATE" placeholder=""
                                value="{{ old('P_ATT_DATE') }}">
                        </div>
                    </div>

                    {{-- Employee no --}}
                    <div class="mb-3 row container"id="P_EMP_NO_div">
                        <label for="P_EMP_NO" class="col-sm-3 col-form-label ">Employee No  : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_EMP_NO" id="P_EMP_NO" placeholder=""
                                value="{{ old('P_EMP_NO') }}">
                        </div>
                    </div>

                    {{-- idesignation no --}}
                    <div class="mb-3 row container"id="P_DES_NO_div">
                        <label for="P_DES_NO" class="col-sm-3 col-form-label ">Des No.  : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_DES_NO" id="P_DES_NO" placeholder=""
                                value="{{ old('P_DES_NO') }}">
                        </div>
                    </div>

                    {{-- Work Ent --}}
                    <div class="mb-3 row container"id="P_WRK_ENT_div">
                        <label for="P_WRK_ENT" class="col-sm-3 col-form-label ">Work Ent.  : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_WRK_ENT" id="P_WRK_ENT" placeholder=""
                                value="{{ old('P_WRK_ENT') }}">
                        </div>
                    </div>

                    {{-- Year --}}
                    <div class="mb-3 row container"id="P_YEAR_div">
                        <label for="P_YEAR" class="col-sm-3 col-form-label ">Year  : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_YEAR" id="P_YEAR" placeholder=""
                                value="{{ old('P_YEAR') }}">
                        </div>
                    </div>

                    {{-- Grade --}}
                    <div class="mb-3 row container"id="P_GRADE_div">
                        <label for="P_GRADE" class="col-sm-3 col-form-label ">Grade  : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_GRADE" id="P_GRADE" placeholder=""
                                value="{{ old('P_GRADE') }}">
                        </div>
                    </div>

                    {{-- Religion --}}
                    <div class="mb-3 row container"id="P_RELIGION_div">
                        <label for="P_RELIGION" class="col-sm-3 col-form-label ">Religion : </label>
                        <div class="col-sm-8">
                            <select class="form-select" id="P_RELIGION" name="P_RELIGION">
                                
                            </select>
                        </div>
                    </div>

                    {{-- Blood Group --}}
                    <div class="mb-3 row container"id="P_BLOOD_GROUP_div">
                        <label for="P_BLOOD_GROUP" class="col-sm-3 col-form-label ">Blood Group : </label>
                        <div class="col-sm-8">
                            <select class="form-select" id="P_BLOOD_GROUP" name="P_BLOOD_GROUP">
                                
                            </select>
                        </div>
                    </div>

                    {{-- Sex --}}
                    <div class="mb-3 row container"id="P_SEX_div">
                        <label for="P_SEX" class="col-sm-3 col-form-label ">Sex : </label>
                        <div class="col-sm-8">
                            <select class="form-select" id="P_SEX" name="P_SEX">
                                
                            </select>
                        </div>
                    </div>

                    {{-- Shift code --}}
                    <div class="mb-3 row container"id="P_SHIFT_CODE_div">
                        <label for="P_SHIFT_CODE" class="col-sm-3 col-form-label ">Shift Code : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_SHIFT_CODE" id="P_SHIFT_CODE" placeholder=""
                                value="{{ old('P_SHIFT_CODE') }}">
                        </div>
                    </div>

                    {{-- bank salary --}}
                    <div class="mb-3 row container"id="P_BANK_SAL_div">
                        <label for="P_BANK_SAL" class="col-sm-3 col-form-label ">Bank Sal. : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_BANK_SAL" id="P_BANK_SAL" placeholder=""
                                value="{{ old('P_BANK_SAL') }}">
                        </div>
                    </div>

                    {{-- Line --}}
                    <div class="mb-3 row container"id="P_LINE_div">
                        <label for="P_LINE" class="col-sm-3 col-form-label ">Line  : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_LINE" id="P_LINE" placeholder=""
                                value="{{ old('P_LINE') }}">
                        </div>
                    </div>

                    {{-- Bank name --}}
                    <div class="mb-3 row container"id="P_BANK_NAME_div">
                        <label for="P_BANK_NAME" class="col-sm-3 col-form-label ">Bank Name : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_BANK_NAME" id="P_BANK_NAME" placeholder=""
                                value="{{ old('P_BANK_NAME') }}">
                        </div>
                    </div>

                    {{-- Bill --}}
                    <div class="mb-3 row container"id="P_BILL_div">
                        <label for="P_BILL" class="col-sm-3 col-form-label ">Bill : </label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="P_BILL" id="P_BILL" placeholder=""
                                value="{{ old('P_BILL') }}">
                        </div>
                    </div>

                    {{-- Late deduct --}}
                    <div class="mb-3 row container"id="P_LATE_DEDUCT_div">
                        <label for="P_LATE_DEDUCT" class="col-sm-3 col-form-label ">Late Deduct : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_LATE_DEDUCT" id="P_LATE_DEDUCT" placeholder=""
                                value="{{ old('P_LATE_DEDUCT') }}">
                        </div>
                    </div>

                    {{-- Letter No.--}}
                    <div class="mb-3 row container"id="P_LETTER_NO_div">
                        <label for="P_LETTER_NO" class="col-sm-3 col-form-label ">Letter No. : </label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="P_LETTER_NO" id="P_LETTER_NO" placeholder=""
                                value="{{ old('P_LETTER_NO') }}">
                        </div>
                    </div>

                    {{-- Food Deduct --}}
                    <div class="mb-3 row container"id="P_FOOD_DEDUCT_div">
                        <label for="P_FOOD_DEDUCT" class="col-sm-3 col-form-label ">Food Deduct : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="P_FOOD_DEDUCT" id="P_FOOD_DEDUCT" placeholder=""
                                value="{{ old('P_FOOD_DEDUCT') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 p-3" id="button">
                            <div class="row p-3">
                                <button class="btn btn-info" type="submit" id="sal_action">Action</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

</body>



<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }} "></script>
<script type="text/javascript" src="{{ URL::asset('dist/js/adminlte.min.js') }} "></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script src="https://unpkg.com/moment-duration-format@2.3.2/lib/moment-duration-format.js"></script>

<link type="text/css" rel="Stylesheet"
    href="http://ajax.microsoft.com/ajax/jquery.ui/1.8.6/themes/smoothness/jquery-ui.css" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#company_idd').select2();
});
</script>
<script>
  $(document).ready(function() {
    $("#P_COMPANY_div").hide();
    $('#P_EMP_TYPE_div').hide();
    $('#P_SECTION_NO_div').hide();




  });
$('#report_list').on('change',function(e){
    e.preventDefault();
   var report_id=$('#report_list').val();
   $("#P_COMPANY_div").hide();
    $('#P_EMP_TYPE_div').hide();
    $('#P_SECTION_NO_div').hide();

   $.ajax({
                    url: '/getReportPera/' + report_id,
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data) {
                       
                            $.each(data, function(key, getReport) {
                             console.log(getReport);

                             $('#'+getReport.parameter_name+'_div').show();
                            });
                        } else {

        
                        }
                    }
                });
})

</script>
</html>