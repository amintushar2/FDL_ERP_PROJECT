
@foreach($tableData as $attData)
<tr>
    <td><input type="text" class="form-control" name="att_date[]" id="att_date" value="{{old('att_date',Illuminate\Support\Carbon::parse($attData->att_date)->format('Y-m-d'))}}" placeholder="{{ $attData->att_date }}"></td>
    <td><input type="number" class="form-control" name="empno_new[]" id="empno_new" value="{{ $attData->empno_new }}" placeholder="{{ $attData->empno_new }}"></td>
    <td><input type="number" class="form-control" name="empno[]" id="empno" value="{{ $attData->empno }}" placeholder="{{ $attData->empno }}"></td>
    <td><input type="text" class="form-control" name="in_time[]" id="in_time" value="{{ old('in_time',Illuminate\Support\Carbon::parse($attData->in_time)->format('h:i:s A'))}}" placeholder="{{ $attData->in_time }}"></td>
    <td><input type="text" class="form-control" name="in_time2[]" id="in_time2" value="{{ old('in_time2',Illuminate\Support\Carbon::parse($attData->in_time2)->format('h:i:s A'))}}" placeholder="{{ $attData->in_time2 }}"></td>
    <td><input type="number" class="form-control" name="late[]" id="late" value="{{ $attData->late }}" placeholder="{{ $attData->late }}"></td>
    <td><input type="text" class="form-control" name="out_time[]" id="out_time" value="{{ old('out_time',Illuminate\Support\Carbon::parse($attData->out_time)->format('h:i:s A'))}}" placeholder="{{ $attData->out_time }}"></td>
    <td><input type="text" class="form-control" name="out_time2[]" id="out_time2" value="{{ old('out_time2',Illuminate\Support\Carbon::parse($attData->out_time2)->format('h:i:s A'))}}" placeholder="{{ $attData->out_time2 }}"></td>
    <td><input type="number" class="form-control" name="othour[]" id="othour" value="{{ $attData->othour }}" placeholder="{{ $attData->othour }}"></td>
    <td><input type="number" class="form-control" name="othour2[]" id="othour2" value="{{ $attData->othour2 }}" placeholder="{{ $attData->othour2 }}"></td>
    <td><input type="number" class="form-control" name="extraot[]" id="extraot" value="{{ $attData->extraot }}" placeholder="{{ $attData->extraot }}"></td>
    <td><input type="text" class="form-control" name="status[]" id="status" value="{{ $attData->status }}" placeholder="{{ $attData->status }}"></td>
    <td><input type="text" class="form-control" name="status2[]" id="status2" value="{{ $attData->status2 }}" placeholder="{{ $attData->status2 }}"></td>
    <td><input type="number" class="form-control" name="late_extra[]" id="late_extra" value="{{ $attData->late_extra }}" placeholder="{{ $attData->late_extra }}"></td>

</tr>


@endforeach
  

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



