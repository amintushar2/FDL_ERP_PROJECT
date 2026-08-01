{{-- professional training section starts --}}
<div class="tab-pane fade" id="train" role="tabpanel" aria-labelledby="train-tab" tabindex="7">
    <form action="" method="">
    <div class="container-fluid col-lg-12 w-100 p-6">
            <h3 class="text-center p-2">Training Information</h3>


            <div class="row">
                <div class="col-md">
                    <div class="row p-1">
                        <label for="empnotraining" class="col-sm-3 col-form-label">Employee Id :</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="empno" id="empnotraining"
                                value="{{ old('empno') }}" placeholder="Employee Id">

                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                        <label for="t_title" class="col-sm-3 col-form-label">Title :</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="t_title" id="t_title"
                                value="{{ old('t_title') }}" placeholder="Title">


                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                        <label for="t_conducted_by" class="col-sm-3 col-form-label">Conducted By :</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="t_conducted_by" id="t_conducted_by"
                                value="{{ old('t_conducted_by') }}" placeholder="Conducted By"></textarea>


                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md">
                    <div class="row p-1">
                    <label for="t_from" class="col-sm-3 col-form-label">From:</label>
                        <div class="col-sm-7">
                        <input type="date" class="form-control" name="t_from" id="t_from" value="{{ old('t_from') }}"
                        placeholder="Start Date" />
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                    <label for="t_to" class="col-sm-3 col-form-label">To :</label>
                        <div class="col-sm-7">
                        <input type="date" class="form-control" name="t_to" id="t_to" value="{{ old('t_to') }}"
                        placeholder="End Date" />


                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="row p-1">
                    <label for="to_days" class="col-sm-3 col-form-label">Total Days :</label>
                        <div class="col-sm-8">
                        <input type="number" class="form-control" name="to_days" id="to_days" value="{{ old('to_days') }}"
                        placeholder="Total Days">

                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md">
                    <div class="row p-1">
                    <label for="t_certificate" class="col-sm-3 col-form-label">Certificate :</label>
                        <div class="col-sm-7">
                        <textarea class="form-control" name="t_certificate" id="t_certificate"
                        value="{{ old('t_certificate') }}" placeholder="Certificate"></textarea>
                        </div>
                    </div>
                </div>
               
                <div class="col-md">
                    <div class="row p-1">
                    <label for="skill_type" class="col-sm-3 col-form-label">Skill Type :</label>
                        <div class="col-sm-8">
                        <textarea class="form-control" name="skill_type" id="skill_type" value="{{ old('skill_type') }}"
                        placeholder="Skill Type"></textarea>

                        </div>
                    </div>
                </div>

            </div>







            {{-- Action buttons --}}
            <div class="row-md-6 m-3 text-center p-3">
                <button class="btn btn-white border-black" type="submit">Add</button>
                <button class="btn btn-danger" type="button" id="clearleave">Clear</button>
            </div>
        </div>

    </form>
</div>
{{-- professional training section ends --}}

