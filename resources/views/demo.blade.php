


<!DOCTYPE html>
<html>
<head>
    <title>Employee Information</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     {{-- font awesome cdn --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <script src="{{ asset('/js/app.js') }}" defer></script>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
</head>
<body>

<form>
  <div class="form-group">
    <label for="exampleInputEmail1">Amount</label>
    <input type="number" class="form-control" name="amount" id="amount" aria-describedby="emailHelp" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">installment </label>
    <input type="number" class="form-control"name="installMonth" id="installmentmonth" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">installment </label>
    <input type="number" class="form-control"name="monthlyinstall" id="monthlyinstall"placeholder="fffffff">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">1stInstall date </label>
    <input type="date" class="form-control"name="firdtInsDat" id="firdtInsDat"placeholder="DATE">
  </div>

  <button type="submit" id="sheduled"class="btn btn-primary">Submit</button>
</form>

<div class="">

<table class="table" id="maintb">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">PBBOM</th>
      <th scope="col">INSTALL AMOUNT</th>
      <th scope="col">Install Date</th>
      <th scope="col">PBEOM</th>
      <th scope="col">PAY DATE</th>
      <th scope="col">STATUS</th>
    </tr>
  </thead>
  <tbody>
    
  </tbody>
</table>

</div>
</body>

<script type="text/javascript" src="{{ URL::asset('plugins/jquery/jquery.min.js') }} "></script>
<script>

$(function(){

    $("#installmentmonth").on('keyup',function(e){
        e.preventDefault();
        var totalamount=(parseInt($('#amount').val()));
        var installmonth=(parseInt($('#installmentmonth').val()));
        var installAmount=totalamount/installmonth;
        $('#monthlyinstall').val(installAmount);
        

    });

    $("#sheduled").on('click',function(e){
        e.preventDefault();
        var totalamount=(parseInt($('#amount').val()));
        var firdtInsDat=$('#firdtInsDat').val();
        var installmonth=(parseInt($('#installmentmonth').val()));
        var installAmount=totalamount/installmonth;
        var ball=totalamount-installAmount;
    $('#maintb tr:last').after('<tr><td>'+'1'+'</td><td>'+totalamount+'</td><td id="installamounttd">'+installAmount+'</td><td>'+firdtInsDat+'</td><td  id="insamm">'+ball+'</td><td>'+'Deu'+'</td><td>'+'Deu'+'</td></tr>');
   var erty=parseInt($('table:first tr').find('#insamm').last().text())
        console.log(erty);
        if(!erty=='0'){
    for (let i =1; i < installmonth; i++) {
  // some code
  var tt =parseInt($('table:first tr').find('#insamm').last().text());
  var installamounttd =parseInt($('table:first tr').find('#installamounttd').last().text());

  var ttamm= tt-installAmount;
var ttER= i+1
        $('#maintb tr:last').after('<tr><td>'+ttER+'</td><td>'+tt+'</td><td id="installamounttd">'+installAmount+'</td><td>'+firdtInsDat+'</td><td  id="insamm">'+ttamm+'</td><td>'+'Deu'+'</td><td>'+'Deu'+'</td></tr>');
        $(this).prop("disabled",true);

    }}
console.log(tt);
});



})


</script>
</html>