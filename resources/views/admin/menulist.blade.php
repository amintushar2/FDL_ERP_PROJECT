<!DOCTYPE html>
<html>
<head>
    <title>FDL ADMIN</title>
    <meta name="_token" content="{{csrf_token()}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha256-aAr2Zpq8MZ+YA/D6JtRD3xtrwpEz2IqOS+pWD/7XKIw=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha256-OFRAJNoaD8L3Br5lglV7VyLRf0itmoBzWUoM+Sji4/8=" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-5">
    <!-- 1 -->

    <div class="card-body"style="width: 30rem;">
    <div class="card-header bg-info">
                    <h5 class="text-white text-center">Menu List</h5>
                </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                      Open Form
                    </button>
                <div class="containar overflow-auto" style=" max-height: 500px;">
                    <table class="table table-bordered">
       <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Course</th>
                <th scope="col">Price</th>
                <th scope="col">#</th>
            </tr>
        </thead>
        <tbody>
        @php $sl = 1; @endphp

            @foreach ($allMenu as $allMenu)

            <tr>
                <th scope="row">{{$sl}}</th>
                <td>{{$allMenu->title}}</td>
                <td>{{$allMenu->child_id}}</td>
                <td></td>
            </tr>
            @php $sl++ @endphp

            @endforeach
        </tbody>
    </table>
                    </div>
                </div>
    </div>
    <div class="col-md-7">
     <!-- 2 -->

     <div class="card-body"style="width: 52rem;">
     <div class="card-header bg-info">
                    <h5 class="text-white text-center">User List</h5>
                </div>
                <span><br></span>
                    <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#exampleModal">
                      Open Form
                    </button>
                    <span><br></span>
                  
                    <div class="container">
                <div class="overflow-auto" style="max-width: 800px; max-height: 500px;">
                    <table class="table table-bordered">
       <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">User Name</th>
                <th scope="col">Password</th>
                <th scope="col">COMPANY_ID</th>
                <th scope="col">EMPLOYEE_ID</th>
                <th scope="col">USER_MOBILE</th>
                <th scope="col">####</th>
            </tr>
        </thead>
        <tbody>
        @php $sl = 1; @endphp

        @foreach ($alluser as $alluser)

<tr>
    <th scope="row">{{$sl}}</th>
    <td>{{$alluser->user_id}}</td>
    <td>{{$alluser->initial_password}}</td>
    <td>{{$alluser->company_id}}</td>
    <td>{{$alluser->employee_id}}</td>
    <td>{{$alluser->user_mobile}}</td>
    <td></td>
</tr>
@php $sl++ @endphp

@endforeach
        </tbody>
    </table>
    </div>    </div>
                </div>

    </div>
    
  </div>
</div>







    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Laravel Bootstrap Modal Form Validation Example - NiceSnippets.com</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <form class="image-upload" method="post" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="name" class="form-control"/>
                        </div>  
                        <div class="form-group">
                            <label>Author Name</label>
                            <input type="text" name="auther_name" id="auther_name" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="textarea form-control" id="description" cols="40" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="formSubmit">Save</button>
                </div>
            </div>
        </div>
    </div>
    <script>
      
    </script>
</body>
</html>