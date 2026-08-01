


<!DOCTYPE html>
<html>
<head>
    <title>Employee Information</title>
    <!-- Required meta tags -->
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


    
</head>
<body>
<div class="">

<table class="table" id="maintb">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">CITY </th>
      <th scope="col">****</th>
    </tr>
  </thead>
  <tbody>
  @foreach($city as $city)
    <tr class="">
<td>{{$city->city_id}}</td>
<td>{{$city->city}}</td>
<td><a href='#' class='btn btn-success edit' data-id='{{$city->city_id}}' data-first='{{$city->city}}'>Edit</a></td>
    </tr>
    @endforeach

  </tbody>
</table>

</div>




<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Edit Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
            <form action="{{ URL::to('update') }}" id="editForm">
                <input type="hidden" id="memid" name="id">
                <div class="mb-3">
                    <label for="firstname">Firstname</label>
                    <input type="text" name="firstname" id="firstname" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="lastname">Lastname</label>
                    <input type="text" name="lastname" id="lastname" class="form-control">
                </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>

<script type="text/javascript" src="{{ URL::asset('plugins/jquery/jquery.min.js') }} ">

</script>
<script>



$(document).on('click', '.edit', function(event){
                event.preventDefault();
                var id = $(this).data('id');
                var firstname = $(this).data('first');
                var lastname = $(this).data('last');
                $('#editmodal').modal('show');
                $('#firstname').val(firstname);
                $('#memid').val(id);
            });
</script>
</html>