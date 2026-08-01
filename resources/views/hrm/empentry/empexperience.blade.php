{{-- experience info section starts --}}
<div class="tab-pane fade" id="exp" role="tabpanel" aria-labelledby="exp-tab" tabindex="6">
    <form action="" method="" id="empExp">
        <div class="container-fluid col-lg-12 w-100 p-6">
            <h3 class="text-center p-2">Experience Information</h3>

            <div class="row">
                <div class="col-md">
                    <div class="row p-1">
                        <label for="empnoexp" class="col-sm-3 col-form-label">Employee Id :</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="empnoexp" id="empnoexp"
                                value="{{ old('empno') }}" placeholder="Employee Id">

                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                        <label for="prv_emp_no" class="col-sm-3 col-form-label"> Previous Employee Id :</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="prv_emp_no" id="prv_emp_no"
                                value="{{ old('prv_emp_no') }}" placeholder="Previous Employee Id">


                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                        <label for="organization" class="col-sm-3 col-form-label">Organization :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="organization" id="organization"
                                value="{{ old('organization') }}" placeholder="Organization Name">


                        </div>
                    </div>
                </div>

            </div>




            <div class="row">
                <div class="col-md">
                    <div class="row p-1">
                    <label for="org_address" class="col-sm-3 col-form-label">Address :</label>
                        <div class="col-sm-7">
                        <textarea class="form-control" name="org_address" id="org_address" value="{{ old('org_address') }}"
                        placeholder="Organization Address" ></textarea>

                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                    <label for="org_tel" class="col-sm-3 col-form-label">Tel No :</label>
                        <div class="col-sm-7">
                        <input type="tel" class="form-control" name="org_tel" id="org_tel" value="{{ old('org_tel') }}"
                        placeholder="Telephone No" />


                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                    <label for="designation" class="col-sm-3 col-form-label">Designation :</label>
                        <div class="col-sm-8">
                        <input type="text" class="form-control" name="designation" id="designation"
                        value="{{ old('designation') }}" placeholder="Designation" />


                        </div>
                    </div>
                </div>

            </div>


            <div class="row">
                <div class="col-md">
                    <div class="row p-1">
                    <label for="dept" class="col-sm-3 col-form-label">Department :</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="dept" id="dept"  placeholder="Department"/>

                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                    <label for="d_from" class="col-sm-3 col-form-label">From :</label>
                        <div class="col-sm-7">
                        <input type="date" class="form-control" name="d_from" id="d_from" value="{{ old('d_from') }}"
                        placeholder="Date From" />

                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                    <label for="d_to" class="col-sm-3 col-form-label">To :</label>
                        <div class="col-sm-8">
                        <input type="date" class="form-control" name="d_to" id="d_to" value="{{ old('d_to') }}"
                        placeholder="Date To" />


                        </div>
                    </div>
                </div>

            </div>


            <div class="row">
                <div class="col-md">
                    <div class="row p-1">
                    <label for="total_years" class="col-sm-3 col-form-label">Total Years :</label>
                        <div class="col-sm-7">
                        <input type="number" class="form-control" name="total_years" id="total_years"
                        value="{{ old('total_years') }}" placeholder="Total Years">
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                    <label for="leave_reason" class="col-sm-3 col-form-label">Leave Reason:</label>
                        <div class="col-sm-7">
                        <textarea class="form-control" name="leave_reason" id="leave_reason"
                        value="{{ old('leave_reason') }}" placeholder="Leave Reason"></textarea>

                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                    <label for="last_sal_drawn" class="col-sm-3 col-form-label">Last Salary :</label>
                        <div class="col-sm-8">
                        <input type="number" class="form-control" name="last_sal_drawn" id="last_sal_drawn"
                        value="{{ old('last_sal_drawn') }}" placeholder="Amount">

                        </div>
                    </div>
                </div>

            </div>





            {{-- Action Buttons --}}
            <div class="row-md-6 m-3 text-center">
                <button class="btn btn-white border-black" type="submit">Add</button>
                <button class="btn btn-danger" type="button" id="clearexp">Clear</button>
            </div>

        </div>
    </form>
    <div class="overflow-auto" style="max-width: 100%; max-height: 2200px;">
        <table id="sal_table" class="table table-bordered table-striped" style="width:100%">
            <thead class="bg-dark text-light" style="background-color:rgb(94, 21, 94)">
                <tr>
                    <th style="width:10px; text-align:left">Empno</th>
                    <th style="width:10px; text-align:center">Organization</th>
                    <th style="width:10px; text-align:center">Designation</th>
                    <th style="width:10px; text-align:left">Leave Reason</th>
                    <th style="width:10px; text-align:center">Total Days</th>
                    <th style="width:10px; text-align:center">From </th>
                    <th style="width:20px; text-align:center">To </th>

                </tr>
            </thead>
            <tbody id="emp_exp_data">

            </tbody>
        </table>
    </div>



</div>
{{-- experience info section ends --}}

