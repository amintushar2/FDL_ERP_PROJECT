<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

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

    <style>
    #ui-datepicker-div {
        display: none;
    }

    .select2-selection__rendered {
        line-height: 31px !important;
    }

    .select2-container .select2-selection--single {
        height: 35px !important;
    }

    .select2-selection__arrow {
        height: 34px !important;
    }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance Process</title>
</head>

<body>

    <div class="container-fluid">
        <div class="content-wrapper">


            <h3 class="text-center">Attendance Process</h3>
            <hr />
            <form action="" method="" id="salfrom">
                @csrf

                <div class="row">
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <div class="card">
                            <div class="card-body text-left">
                                <h5 class="card-title">Device List </h5>

                                <div class="row p-1">

                                    <div class="col-sm-10">
                                        <select class="js-example-responsive form-control" id="deviceIp" name="deviceIp">
                                        <option selected value="">Select Device</option>
                                        <option value="192.168.189.67">1st Floor</option>
                                        </select>
                                    </div>
                                </div>



                                <div class="text left p-2">
                                    <button type="button" class="btn btn-danger" id="getData">Data Download</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="container col-sm-12 p-3">

                                    <div class="row">
                                        {{-- To input --}}
                                        <div class="col-md-12">
                                            <div class="mb-3 row">
                                                <label for="company_name" class="col-sm-3 col-form-label ">Company Name
                                                    : </label>
                                                <div class="col-sm-8">
                                                    <select class="js-example-responsive form-select" id="company_id"
                                                        name="company_name" required>
                                                        @foreach($companyList as $companyList)
                                                        <option value="{{ $companyList->company_id }}">
                                                         {{ $companyList->company_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text left p-2">
                                    <button type="button" class="btn btn-danger" id="processData">Data Process</button>
                                </div>


                                    </div>

                                 
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js
"></script>
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css
" rel="stylesheet">
<script>
$(document).ready(function() {
    $('#company_id').select2({});
    $('#salDate').select2();
    $('#month').select2();
});
</script>



<script>
$(document).ready(function() {

 
});
</script>

<script>
$("#processData").on('click',function(e) {
    e.preventDefault();

    var company_id = $('#company_id').val();

    $.ajaxSetup({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    console.log(company_id);
    $.ajax({
        type: 'GET',
        url: 'attDataProcess',
        data: {'company_id':company_id},

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



$("#getData").on('click',function(e) {
    e.preventDefault();

    var arrData = $('#deviceIp').val();

    $.ajaxSetup({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    console.log(arrData);
    $.ajax({
        type: 'GET',
        url: 'attProcess',
        data: {'arrData':arrData},

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

























