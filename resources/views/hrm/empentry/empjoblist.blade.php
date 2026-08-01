
@foreach($jobList as $jobList)
                        <tr>
                            <td>{{$jobList->empno}}</td>
                            <td>{{$jobList->join_as}}</td>
                            <td>{{$jobList->work_location}}</td>
                            <td>{{$jobList->join_date}}</td>
                            <td>{{$jobList->designation}}</td>
                          
                                                </tr>
@endforeach