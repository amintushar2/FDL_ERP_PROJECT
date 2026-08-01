
@foreach($empExpe as $empExpe)
                        <tr>
                            <td>{{$empExpe->empno}}</td>
                            <td>{{$empExpe->organization}}</td>
                            <td>{{$empExpe->designation}}</td>
                            <td>{{$empExpe->leave_reason}}</td>
                            <td>{{$empExpe->d_from}}</td>
                            <td>{{$empExpe->d_to}}</td>
                            <td>{{$empExpe->total_days}}</td>
                                                </tr>
@endforeach