
@foreach($famList as $famList)
                        <tr>
                            <td>{{$famList->empno}}</td>
                            <td>{{$famList->depd_name}}</td>
                            <td id="dep_name_bn">{{$famList->depent_name_bangla}}</td>
                            <td>{{$famList->relationship}}</td>
                            <td id="relation_bn">{{$famList->relation_bn}}</td>
                            <td>{{$famList->d_dob}}</td>
                            <td>{{$famList->d_age}}</td>
                            <td>{{$famList->d_sex}}</td>
                            <td>{{$famList->percentage}}</td>
                            <td>{{$famList->address}}</td>
                            <td id="address_bn">{{$famList->address_bn}}</td>
                        </tr>
@endforeach



