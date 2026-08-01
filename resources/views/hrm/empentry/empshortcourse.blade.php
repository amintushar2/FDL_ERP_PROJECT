{{-- Short course info section starts --}}
<div class="tab-pane fade" id="course" role="tabpanel" aria-labelledby="course-tab" tabindex="5">
    <form action="" method=""id="empshort">
        <div class="container-fluid col-lg-12 w-100 p-6">

            <h3 class="text-center p-2">Short Course Information</h3>
                <div class="row">
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="empnoshtcourse" class="col-sm-3 col-form-label">Employee Id :</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="empnoshtcourse" id="empnoshtcourse"
                                    value="{{ old('empno') }}" placeholder="Employee Id">

                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="course_name" class="col-sm-3 col-form-label">Course Name :</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="course_name" id="course_name"
                                    value="{{ old('course_name') }}" placeholder="Title">

                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="conducted_by" class="col-sm-3 col-form-label">Conducted By :</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="conducted_by" id="conducted_by"
                                    value="{{ old('conducted_by') }}" placeholder="Conducted By"></textarea>

                            </div>
                        </div>
                    </div>

                </div>





                <div class="row">
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="c_from" class="col-sm-3 col-form-label">From:</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control" name="c_from" id="c_from"
                                    value="{{ old('c_from') }}" placeholder="Start Date" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="total_day" class="col-sm-3 col-form-label">Total Days :</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="total_day" id="total_day"
                                    value="{{ old('total_day') }}" placeholder="Total Days">
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row p-1">
                            <label for="certificate" class="col-sm-3 col-form-label">Certificate :</label>

                            <div class="col-sm-8">

                                <textarea class="form-control" name="certificate" id="certificate"
                                    value="{{ old('certificate') }}" placeholder="Certificate"></textarea>


                            </div>
                        </div>
                    </div>

                </div>


                {{-- Action buttons --}}
                <div class="row-md-6 m-3 text-center p-3">
                    <button class="btn btn-white border-black" type="submit">Add</button>
                    <button class="btn btn-danger" id="clearshortc" type="button">Clear</button>
                </div>

        </div>

    </form>


    <div class="overflow-auto" style="max-width: 100%; max-height: 2200px;">
        <table id="sal_table" class="table table-bordered table-striped" style="width:100%">
            <thead class="bg-dark text-light" style="background-color:rgb(94, 21, 94)">
                <tr>
                    <th style="width:10px; text-align:left">EMPNO</th>
                    <th style="width:10px; text-align:center">Course Name</th>
                    <th style="width:10px; text-align:center">conducted_by</th>
                    <th style="width:10px; text-align:center">From</th>
                    <th style="width:20px; text-align:center">Certificate</th>
                    <th style="width:10px; text-align:left">total_day</th>
                </tr>
            </thead>
            <tbody id="emp_course_data">

            </tbody>
        </table>
    </div>
</div>
{{-- Short course info section starts --}}

