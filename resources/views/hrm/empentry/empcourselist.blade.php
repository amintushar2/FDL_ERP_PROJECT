
@foreach($courseList as $courseList)
                        <tr>
                            <td>{{$courseList->empno}}</td>
                            <td>{{$courseList->course_name}}</td>
                            <td>{{$courseList->conducted_by}}</td>
                            <td>{{$courseList->c_from}}</td>
                            <td>{{$courseList->certificate}}</td>
                            <td>{{$courseList->total_day}}</td>
                                                </tr>
@endforeach