{{-- address section starts --}}

<div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="add-tab" tabindex="2">
    <form action="" method="" id="empadressSave">
        <div class="container-fluid col-lg-10 w-100 p-5">
            <h3 class="text-center">Location Information </h3>

            {{--employee id--}}
            <div class="mb-3 row">
                <label for="empno" class="col-sm-3 col-form-label">Employee ID :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="empadempno" id="empadempno" value="{{old('empno')}}"
                        placeholder="Employee id">
                </div>
            </div>
            <div class="form-container">
                {{-- Type of Address --}}
                <div class="mb-3 row">
                    <h3 class="text-center">Permanent Address</h3>

                </div>


                <div class="row">
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="p_address" class="col-sm-3 col-form-label">Address : </label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="p_address" id="p_address"
                                    value="{{old('p_address')}}" placeholder="Enter address">

                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="p_village" class="col-sm-3 col-form-label">Village : </label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="p_village" id="p_village"
                                    placeholder="Village " value="{{old('p_village')}}" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="p_post_office" class="col-sm-3 col-form-label">P.O : </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="p_post_office" id="p_post_office"
                                    value="{{old('p_post_office')}}" placeholder="Enter pin code">

                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="p_police_station" class="col-sm-3 col-form-label">P.S : </label>
                            <div class="col-sm-7">
                                <select class="form-select" aria-label="Default select example"
                                    name="p_police_station11" id="p_police_station">
                                    <option selected value="">Select Thana</option>
                                    @foreach($upazilla as $upazilla)
                                    <option value="{{$upazilla->name}}">{{$upazilla->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="p_city" class="col-sm-3 col-form-label">City :</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="p_city" id="p_city" value="{{old('P_city')}}"
                                    placeholder="" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="p_district" class="col-sm-3 col-form-label">District : </label>

                            <div class="col-sm-8">

                                <select class="form-select" aria-label="Default select example" name="p_district2"
                                    id="p_district2">
                                    <option selected value="">Select District</option>
                                    @foreach($district as $district)
                                    <option value="{{$district->district}}">{{$district->district}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="p_pin_code" class="col-sm-3 col-form-label">National Id:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="p_pin_code" id="p_pin_code"
                                    placeholder="National Id" value="{{old('P_pin_code')}}" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="p_phone" class="col-sm-3 col-form-label">Phone No :</label>
                            <div class="col-sm-7">
                                <input type="tel" class="form-control" name="p_phone" id="p_phone"
                                    placeholder="phone No" value="{{old('P_phone')}}" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="p_fax" class="col-sm-3 col-form-label">Fax No :</label>
                            <div class="col-sm-8">
                                <input type="tel" class="form-control" name="p_fax" id="p_fax" placeholder="fax No"
                                    value="{{old('P_fax')}}" />

                            </div>
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="p_pin_code" class="col-sm-3 col-form-label">National Id:</label>
                            <div class="col-sm-7">
                                <input type="tel" class="form-control" name="p_cperson" id="p_cperson"
                                    value="{{old('P_cperson')}}" placeholder="Contact No" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <div class="col-sm-7">


                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <div class="col-sm-8">


                            </div>
                        </div>
                    </div>

                </div>

            </div>


            <div class="form-container">
                {{-- Type of Address --}}
                <div class="mb-3 row">
                    <h3 class="text-center">Present Address </h3>

                </div>




                <div class="row">
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="r_address" class="col-sm-3 col-form-label">Address : </label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="r_address" id="r_address"
                                    value="{{old('r_address')}}" placeholder="Enter address">

                            </div>
                        </div>
                    </div>
                    <div class="col-md">

                        <div class="row p-1">
                            <label for="r_city" class="col-sm-3 col-form-label">City :</label>


                            <div class="col-sm-7">
                                <input class="form-control" name="r_city" id="r_city" value="{{old('r_city')}}"
                                    placeholder="city" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md">

                        <div class="row p-1">
                            <label for="r_district" class="col-sm-3 col-form-label">District : </label>

                            <div class="col-sm-8">

                                <select class="form-select" aria-label="Default select example" name="r_district"
                                    id="r_district">
                                    <option selected value="">Select District</option>
                                    @foreach($district2 as $districtAS)
                                    <option value="{{$districtAS->district}}">{{$districtAS->district}}</option>
                                    @endforeach
                                </select>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>









                <div class="row">
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="r_phone" class="col-sm-3 col-form-label">Phone No :</label>
                            <div class="col-sm-7">
                                <input type="tel" class="form-control" name="r_phone" id="r_phone"
                                    placeholder="phone No" value="{{old('r_phone')}}" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md">

                        <div class="row p-1">
                            <label for="r_fax" class="col-sm-3 col-form-label">Fax No :</label>

                            <div class="col-sm-7">
                                <input type="tel" class="form-control" name="r_fax" id="r_fax" value="{{old('r_fax')}}"
                                    placeholder="fax No" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md">

                        <div class="row p-1">
                            <label for="r_mobile" class="col-sm-3 col-form-label">Mobile No :</label>

                            <div class="col-sm-8">
                                <input type="tel" class="form-control" name="r_mobile" id="r_mobile"
                                    value="{{old('r_mobile')}}" placeholder="Mobile No" />
                            </div>
                        </div>
                    </div>

                </div>



                {{-- Email address --}}
                <div class="mb-3 row">
                    <label for="r_email" class="col-sm-3 col-form-label">Email : </label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" name="r_email" id="r_email" value="{{old('r_email')}}"
                            placeholder="Email" />
                    </div>
                </div>

                {{-- Contact no input --}}
                <div class="mb-3 row">
                    <label for="r_cperson" class="col-sm-3 col-form-label">Contact No :</label>
                    <div class="col-sm-8">
                        <input type="tel" class="form-control" name="r_cperson" id="r_cperson"
                            value="{{old('r_cperson')}}" placeholder="Contact No" />
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="row-md-6 m-3 text-center p-3">
                    <button class="btn btn-success" type="submit">Save</button>
                    <!-- <button class="btn btn-warning" type="button">Edit</button> -->
                    <button class="btn btn-danger" type="button" id="clearaddr">Clear</button>

                </div>
            </div>
        </div>
    </form>
</div>