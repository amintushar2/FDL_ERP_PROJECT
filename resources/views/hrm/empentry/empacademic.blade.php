{{-- academic info section starts --}}
<div class="tab-pane fade show" id="academi" role="tabpanel" aria-labelledby="academi-tab">
    <form  id="empEduInsert">
    <div class="container-fluid col-lg-12 w-100 p-6">
            <h3 class="text-center">Educational Information</h3>



            <div class="row">
                <div class="col-md">
                    <div class="row p-1">
                        <label for="empno" class="col-sm-3 col-form-label">Employee Id :</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="empnoedu" id="empnoedu"
                                value="{{ old('empnoedu') }}" placeholder="Employee Id">

                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                        <label for="name_of_ins" class="col-sm-3 col-form-label">Name Of Institute :</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="name_of_ins" id="name_of_ins"
                                value="{{ old('name_of_ins') }}" placeholder="Name of Institute" />

                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                        <label for="passed_exam" class="col-sm-3 col-form-label">Passed Examination : </label>
                        <div class="col-sm-8">
                            <select class="form-select" id="passed_exam" name="passed_exam"aria-label="Default select example">
                                <option selected placeholder="Select pass_xm">Select One</option>
                                <option selected placeholder="2002">2002</option>
                           
                            </select>

                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md">
                    <div class="row p-1">
                    <label for="division" class="col-sm-3 col-form-label">Division :</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="division" id="division" value="{{ old('division') }}"
                        placeholder="Enter Division">

                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                    <label for="year" class="col-sm-3 col-form-label">Year : </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="year" id="year"
                                value="{{ old('name_of_ins') }}" placeholder="Name of Institute" />

                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                    <label for="marks" class="col-sm-3 col-form-label">Marks :</label>
                        <div class="col-sm-8">
                        <input type="number" class="form-control" name="marks" id="marks" value="{{ old('marks') }}"
                        placeholder="Obtained Marks" />

                        </div>
                    </div>
                </div>

            </div>


            <div class="row">
                <div class="col-md">
                    <div class="row p-1">
                    <label for="board" class="col-sm-3 col-form-label">Board/University :</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="board" id="board" value="{{ old('board') }}"
                        placeholder="Board or University" />

                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                    <label for="subject" class="col-sm-3 col-form-label">Subject/Group :</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="subject" id="subject" value="{{ old('subject') }}"
                        placeholder="Subject or Group" />

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


            {{-- Action buttons --}}
            <div class="row-md-6 m-3 text-center p-3">
                <button class="btn btn-white border-black" type="submit">Add</button>
                <button class="btn btn-danger" type="button" id="clearacedamy">Clear</button>
            </div>

        </div>
    </form>


    <div class="overflow-auto" style="max-width: 100%; max-height: 2200px;">
        <table id="sal_table" class="table table-bordered table-striped" style="width:100%">
            <thead class="bg-dark text-light" style="background-color:rgb(94, 21, 94)">
                <tr>
                    <th style="width:10px; text-align:left">EMPNO</th>
                    <th style="width:10px; text-align:center">NAME_OF_INS</th>
                    <th style="width:10px; text-align:center">PASSED_EXAM</th>
                    <th style="width:10px; text-align:center">YEAR</th>
                    <th style="width:20px; text-align:center">BOARD</th>
                    <th style="width:10px; text-align:left">MARKS</th>
                    <th style="width:10px; text-align:center">SUBJECT</th>
                </tr>
            </thead>
            <tbody id="emp_edu_data">

            </tbody>
        </table>
    </div>
</div>

