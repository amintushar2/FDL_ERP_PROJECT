@foreach($tableList as $list)
<tr>
    <td>{{ $list->incr_date }}</td>
    <td>{{ $list->empno }}</td>
    <td>{{ $list->prev_designation }}</td>
    <td>{{ $list->cur_designation }}</td>
    <td>{{ $list->prev_ot_ent }}</td>
    <td>{{ $list->cur_ot_ent }}</td>
    <td>{{ $list->prev_gross }}</td>
    <td>{{ $list->incr_type }}</td>
    <td>{{ $list->increment_amt }}</td>
    <td>{{ $list->cur_gross }}</td>
    <td>{{ $list->remark_text }}</td>
    <td>{{ $list->effective_date }}</td>
    <td>{{ $list->prev_basic }}</td>
    <td>{{ $list->prev_house_rent }}</td>
    <td>{{ $list->prev_medical }}</td>
    <td>{{ $list->cur_basic }}</td>
    <td>{{ $list->cur_house_rent }}</td>
    <td>{{ $list->cur_medical }}</td>
    <!-- <td><button type="button" id="newemplist" class="btn btn-primary" onclick="windowOpen()">EDIT</button></td> -->
</tr>
@endforeach