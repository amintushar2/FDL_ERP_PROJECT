
@foreach($eduList as $eduList)
                        <tr>
                            <td>{{$eduList->empno}}</td>
                            <td>{{$eduList->name_of_ins}}</td>
                            <td>{{$eduList->passed_exam}}</td>
                            <td>{{$eduList->year}}</td>
                            <td>{{$eduList->board}}</td>
                            <td>{{$eduList->marks}}</td>
                            <td>{{$eduList->subject}}</td>
                                                </tr>
@endforeach