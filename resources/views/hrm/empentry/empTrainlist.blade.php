
@foreach($trainList as $trainList)
                        <tr>
                            <td>{{$trainList->empno}}</td>
                            <td>{{$trainList->skill_type}}</td>
                            <td>{{$trainList->t_certificate}}</td>
                            <td>{{$trainList->t_conducted_by}}</td>
                            <td>{{$trainList->t_from}}</td>
                            <td>{{$trainList->t_to}}</td>
                            <td>{{$trainList->to_days}}</td>
                          
                                                </tr>
@endforeach