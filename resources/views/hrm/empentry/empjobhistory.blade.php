
{{-- Job History section starts --}}
            <div class="tab-pane fade" id="job" role="tabpanel" aria-labelledby="job-tab" tabindex="8">
                <form action="" method=""id="empJob">
                  <div class="container col-lg-6 p-3">
                    <h3 class="text-center">Employee Job History</h3>

                    {{-- Employee ID input --}}
                   <div class="mb-3 row">
                       <label for="empno" class="col-sm-3 col-form-label">Employee Id :</label>
                       <div class="col-sm-8">
                         <input type="text" class="form-control" name="empnojob" id="empnojob" value="{{ old('empnojob') }}" placeholder="Employee Id">
                       </div>
                   </div>

                    {{-- Join As input --}}
                     <div class="mb-3 row">
                        <label for="join_as" class="col-sm-3 col-form-label">Join As :</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" name="join_as" id="join_as" value="{{ old('join_as') }}" placeholder="Join As">
                        </div>
                     </div>

                     {{-- Designation input --}}
                     <div class="mb-3 row">
                       <label for="designation" class="col-sm-3 col-form-label">Designation :</label>
                       <div class="col-sm-8">
                         <input type="text" class="form-control" name="designation" id="designation" value="{{ old('designation') }}" placeholder="Designation">
                       </div>
                    </div>

                     {{-- Joining date input --}}
                      <div class="mb-3 row">
                          <label for="join_date" class="col-sm-3 col-form-label">Joining Date : </label>
                          <div class="col-sm-8">
                            <input type="date" class="form-control" name="join_date" id="join_date" value="{{ old('join_date') }}"  placeholder="">
                          </div>
                      </div>

                      {{-- Job Location input --}}
                      <div class="mb-3 row">
                        <label for="work_location" class="col-sm-3 col-form-label">Job Location : </label>
                        <div class="col-sm-8">
                        <textarea class="form-control" name="work_location" id="work_location" value="{{ old('work_location') }}"  placeholder="Job Location"></textarea>
                       </div>
                      </div>

                      
                     {{-- Action Buttons --}}
                      <div class="row-md-6 m-3 text-center p-3">
                        <button class="btn btn-success" type="submit">Save</button>
                        <!-- <button class="btn btn-warning" type="button">Edit</button> -->
                        <button class="btn btn-primary" type="button" id="clearjob">Clear</button>
                      </div>

                 </div>
              </form>


              <div class="overflow-auto" style="max-width: 100%; max-height: 2200px;">
        <table id="sal_table" class="table table-bordered table-striped" style="width:100%">
            <thead class="bg-dark text-light" style="background-color:rgb(94, 21, 94)">
                <tr>

                    <th style="width:10px; text-align:left">Empno</th>
                    <th style="width:10px; text-align:center">Join As</th>
                    <th style="width:10px; text-align:center">Work Location</th>
                    <th style="width:10px; text-align:center">Join Date</th>
                    <th style="width:20px; text-align:center">Designation</th>
                    
                </tr>
            </thead>
            <tbody id="emp_job_data">

            </tbody>
        </table>
    </div>
  </div>
            {{-- Job History section ends --}}

      