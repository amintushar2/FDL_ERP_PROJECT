{{-- official info section starts --}}





































<div class="tab-pane fade show active" id="off" role="tabpanel" aria-labelledby="off-tab" tabindex="0">


    <form action="" method="" id="empofficialSave">
        <div class="container col-lg-10 w-100 p-3">
            <h3 class="text-center" style="color:rgb(163, 34, 163)">Official Information</h3>


            {{-- Employee Id input --}}
            <div class="mb-5 row">
                <label for="empno" class="col-sm-3 col-form-label">Employee Id :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="empof_id" id="empof_id" value="{{ old('empno') }}"
                        placeholder="Enter Employee Id" />
                </div>
            </div>


            {{-- User name input --}}
            <div class="row">
                <h4 class="text-center">Base Information</h4>
                <hr />
                <!-- {{-- company name input --}} -->
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="company_name" class="col-sm-4 col-form-label">Company :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="company_id" id="comapnylist_of"
                                aria-label="Default select example">
                                <option selected value="">Select Company</option>

                                @foreach($companyList as $comapnyLists)

                                <option value="{{$comapnyLists->company_id}}">{{$comapnyLists->company_name}}</option>
                                @endforeach

                            </select>

                        </div>
                    </div>
                </div>

                {{-- Department name input --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="dept_name" class="col-sm-4 col-form-label">Department :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="dept_no" id="deptList" name="deptList"
                                aria-label="Default select example">
                                <option selected value="">Select One</option>

                                @foreach($deptList as $deptList)
                                <option value="{{$deptList->dept_no}}">{{$deptList->dept_name}}</option>
                                @endforeach


                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Section input --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="section_name" name="section_no" class="col-sm-4 col-form-label">Section :</label>
                        <div class="col-sm-7">
                            <select class="form-select" id="section_no" name="section_no"
                                aria-label="Default select example">
                                <option selected value="">Select One</option>

                                @foreach($section_list as $section_list)
                                <option value="{{$section_list->section_no}}">{{$section_list->section_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Floor no input --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="floor_id" class="col-sm-4 col-form-label">Floor No :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="floor_id" id="floorList" name="floorList"
                                aria-label="Default select example">
                                <option value="">Select One</option>
                                @foreach($floorList as $floorList)
                                <option value="{{$floorList->floor_id}}">{{$floorList->floor_desc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Line no input --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="line" name="line" class="col-sm-4 col-form-label">Line No :</label>
                        <div class="col-sm-7">
                            <select class="form-select w-100" name="line" id="line" aria-label="Default select example">
                                <option selected value="">Select One</option>

                                @foreach($lineInfo as $lineInfo)
                                <option value="{{$lineInfo->line_no}}">{{$lineInfo->line}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Designation input --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="designation_name" class="col-sm-4 col-form-label">Designation :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="des_id" id="des_id" aria-label="Default select example">
                                <option selected>Select One</option>
                                @foreach($designation as $designation)
                                <option value="{{$designation->des_id}}">{{$designation->designation_name}}</option>


                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Employee type input --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="emp_type" class="col-sm-4 col-form-label">Emp Type :</label>
                        <div class="col-sm-7">
                            <select class="form-select w-100" name="emp_type" id="emp_type"
                                aria-label="Default select example">
                                <option selected value="">Select One</option>
                                @foreach($empType as $empType)
                                <option selected value="{{$empType->emp_type}}">{{$empType->emp_type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{--Grade input --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="grade_id" class="col-sm-4 col-form-label">Grade :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="grade_id" id="grade_id"
                                aria-label="Default select example">
                                <option selected value="">Select One</option>

                                @foreach($gradeInfo as $gradeInfo)
                                <option selected value="{{$gradeInfo->grade_id}}">{{$gradeInfo->grade_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Shift input --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="shift_code" class="col-sm-4 col-form-label">Shift :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="shift_code" id="shift_code"
                                aria-label="Default select example">
                                <option selected value="">Select One</option>

                                @foreach($shiftInfo as $shiftInfo)
                                <option selected value="{{$shiftInfo->shift_code}}">{{$shiftInfo->shift_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{--Cal Code input --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="cal_code" class="col-sm-4 col-form-label">Cal Code :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="cal_code" id="cal_code"
                                aria-label="Default select example">
                                <option selected value="">Select One</option>

                                @foreach($calInfo as $calInfo)
                                <option selected value="{{$calInfo->cal_code}}">{{$calInfo->cal_code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Weekly Off input --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="weekly_off" class="col-sm-4 col-form-label">Weekly Off :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="weekly_off" id="weekly_off"
                                aria-label="Default select example">
                                <option selected value="Friday">Friday</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{--Operation input --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="opt_no" class="col-sm-4 col-form-label">Operation :</label>
                        <div class="col-sm-7">
                            <select class="form-select" id="opt_no" name="opt_no" aria-label="Default select example">
                                <option selected>Select One</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <hr />



            <div class="row">
                <!-- {{-- Joining Date input --}} -->
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="joining_date" class="col-sm-4 col-form-label">Joining Date :</label>
                        <div class="col-sm-7">
                            <input type="text" name="joining_date" class="form-control" id="joining_date"
                                value="{{old('joining_date') }}" placeholder="Joining Date">
                        </div>
                    </div>
                </div>

                <!-- {{-- Join As input --}} -->
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="as_on_join" class="col-sm-4 col-form-label">Join As :</label>
                        <div class="col-sm-7">
                            <input type="text" name="as_on" class="form-control" name="as_on_join" id="as_on_join"
                                value="{{old('as_on_join') }}" placeholder="Join As">
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <!-- {{-- Confirmation Date input --}} -->
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="conform_date" class="col-sm-4 col-form-label">Confirmation Date :</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="conform_date" id="conform_date"
                                value="{{ old('conform_date') }}" placeholder="rr">
                        </div>
                    </div>
                </div>

                <!-- {{-- Increment Date input --}} -->
                <div class="col-md-6">

                </div>
            </div>

            <div class="row">
                <!-- {{-- Provision Period input --}} -->
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="provision_period" class="col-sm-4 col-form-label">Provision Period :</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="provision_period" id="provision_period"
                                value="{{ old('provision_period') }}" placeholder="">
                        </div>
                    </div>
                </div>
                <!-- {{--  --}} -->
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="month" class="col-sm-4 col-form-label ">Month</label>
                    </div>
                </div>
            </div>


            <div class="row p-2">
                <h4 class="text-center">Group Assign</h4>
                <hr />
                {{-- Leave Cat selection --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="lv_cat_id" class="col-sm-4 col-form-label">Leave Cat :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="lv_cat_id" id="lv_cat_id"
                                aria-label="Default select example">
                                <option selected value="">Select One</option>

                                @foreach($leaveCat as $leaveCat)
                                <option selected value="{{$leaveCat->lv_cat_id}}">{{$leaveCat->lv_cat_id}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Allowance --}}

                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="allw_cat_id" class="col-sm-4 col-form-label">Allowance :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="allw_cat_id" id="allw_cat_id"
                                aria-label="Default select example">

                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Shift Gropu selection --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="shift_group" class="col-sm-4 col-form-label">Shift Group :</label>
                        <div class="col-sm-7">
                            <select class="form-select" id="shift_group" name="shift_group"
                                aria-label="Default select example">
                                <option selected>Select One</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Appraisal cal selection --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="appraisal_cal" class="col-sm-4 col-form-label">Appraisal Cal :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="appraisal_cal" id="appraisal_cal"
                                aria-label="Default select example">
                                <option selected>Select One</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <hr />

            <div class="row p-2">
                <h4 class="text-center">Entitlement</h4>
                <hr />
                {{-- Work Entitle selection --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="work_ent" class="col-sm-4 col-form-label">Work Entitle :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="work_ent" id="work_ent"
                                aria-label="Default select example">
                                <option selected value="">Choose One</option>
                                <option value="Worker">Worker</option>
                                <option value="Officer">Officer</option>
                                <option value="Staff">Staff</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Overtime --}}

                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="ot_ent" class="col-sm-4 col-form-label">Overtime :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="ot_ent" id="ot_ent" aria-label="Default select example">
                                <option value="">Choose</option>

                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Residence selection --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="res_ent" class="col-sm-4 col-form-label">Residence :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="res_ent" id="res_ent" aria-label="Default select example">
                                <option value="">Choose</option>

                                <option selected value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- {{-- Transport selection --}} -->
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="tran_ent" class="col-sm-4 col-form-label">Transport :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="tran_ent" id="tran_ent"
                                aria-label="Default select example">
                                <option value="">Choose</option>

                                <option value="Yes">Yes</option>
                                <option selected value="No">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- PF Facility --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="pf_ent" class="col-sm-4 col-form-label">PF Facility :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="pf_ent" id="pf_ent" aria-label="Default select example">
                                <option value="">Choose</option>

                                <option selected value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tax selection --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="tax_ent" class="col-sm-4 col-form-label">Tax :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="tax_ent" id="tax_ent" aria-label="Default select example">

                                <option value="">Choose</option>
                                <option value="Yes">Yes</option>
                                <option selected value="No">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <hr />


            <div class="row p-2">
                <h4 class="text-center">Balance</h4>
                <hr />
                {{-- Provident Fund selection --}}
                <div class="col-md-12">
                    <div class="row p-1">
                        <label for="pro_fund" class="col-sm-4 col-form-label">Provident Fund :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pro_fund" id="pro_fund"
                                value="{{ old('pro_fund') }}" placeholder="Provident Fund">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Increment Date --}}
            <div class="row p-2">
                <div class="col-md-12">
                    <div class="row p-1">
                        <label for="increment_date" class="col-sm-4 col-form-label">Increment Date :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="increment_date" id="increment_date"
                                value="{{ old('increment_date') }}" placeholder="">
                        </div>
                    </div>
                </div>
            </div>

            <hr />

            <div class="row p-2">
                <h4 class="text-center">Salary</h4>
                <hr />
                {{-- Gross Input --}}
                <div class="col-md-12">
                    <div class="row p-1">
                        <label for="gross" class="col-sm-4 col-form-label">Gross :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="gross" id="gross" value="{{ old('gross') }}"
                                placeholder="Gross">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Other allowance input --}}
            <div class="row p-2">
                <div class="col-md-12">
                    <div class="row p-1">
                        <label for="other_allowance" class="col-sm-4 col-form-label">Other Allowance :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="other_allowance" id="other_allowance"
                                value="{{ old('other_allowance') }}" placeholder="Other Allowance">
                        </div>
                    </div>
                </div>
            </div>

            <hr />

            <div class="row p-2">
                <h4 class="text-center">Bank & Others</h4>
                <hr />
                {{-- Bank selection --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="bank_name" class="col-sm-4 col-form-label">Bank :</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="bank_name" id="bank_name"
                                aria-label="Default select example">
                                <option selected value="">Select One</option>

                                @foreach($bankNmae as $bankNmae)
                                <option selected value="{{$bankNmae->bank_name}}">{{$bankNmae->bank_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Account No --}}

                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="bank_ac_no" class="col-sm-4 col-form-label">Account No :</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="bank_ac_no" id="bank_ac_no"
                                value="{{ old('bank_ac_no') }}" placeholder="Account No">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- TIN No input--}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="tin_no" class="col-sm-4 col-form-label">Tin No :</label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control" name="tin_no" id="tin_no"
                                value="{{ old('tin_no') }}" placeholder="Tin No">
                        </div>
                    </div>
                </div>

                {{--Tax Deduction --}}
                <div class="col-md-6">
                    <div class="row p-1">
                        <label for="tax_deduction" class="col-sm-4 col-form-label">TAX Deduction :</label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control" name="tax_deduction" id="tax_deduction"
                                value="{{ old('tax_deduction') }}" placeholder="Amount">
                        </div>
                    </div>
                </div>
            </div>

            <hr />

            <div class="row p-2">
                <h4 class="text-center">Release Information</h4>
                <hr />



                <div class="row">
                    {{-- Weekly Off input --}}
                    <div class="col-md-6">
                        <div class="row p-1">
                            <label for="dismisal_date" class="col-sm-4 col-form-label">Dismisal Date :</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control" name="termination_date" id="dismisal_date"
                                    value="{{ old('dismisal_date')  }}" placeholder="">
                            </div>
                        </div>
                    </div>

                    {{--Operation input --}}
                    <div class="col-md-6">
                        <div class="row p-1">
                            <label for="resigned_date" class="col-sm-4 col-form-label">Resigned Date :</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control" name="resigned_date" id="resigned_date"
                                    value="{{ old('resigned_date') }}" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>

















                <div class="row p-2">
                    {{-- Reason input--}}
                    <div class="col-md-12">
                        <div class="row p-1">
                            <label for="reason" class="col-sm-4 col-form-label">Reason :</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="reason" id="reason" value="{{ old('reason') }}"
                                    placeholder="Reason in details..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row p-2">

                    {{-- Service Book No --}}
                    <div class="col-md-12">
                        <div class="row p-1">
                            <label for="service_book_number" class="col-sm-4 col-form-label">Service Book No:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="service_book_number"
                                    id="service_book_number" value="{{ old('service_book_number') }}"
                                    placeholder="Service Book No">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row p-2">

                    {{-- A/C input --}}
                    <div class="col-md-12">
                        <div class="row p-1">
                            <label for="ac_no" class="col-sm-4 col-form-label">A/C:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="ac_no" id="ac_no"
                                    value="{{ old('ac_no') }}" placeholder="A/C">
                            </div>
                        </div>
                    </div>

                </div>

                <hr />
                {{-- action buttons --}}
                <div class="row-md-6 m-3 text-center p-3">
                    <button class="btn btn-success" type="submit">Save</button>
                    <!-- <button class="btn btn-warning" type="button">Edit</button> -->
                    <button class="btn btn-primary" type="button" id="clearoff">Clear</button>
                </div>

            </div>
    </form>


</div>



{{-- official info section ends --}}