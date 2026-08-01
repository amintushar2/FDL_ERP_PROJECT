{{-- Nominee section starts --}}
<div class="tab-pane fade" id="nomi" role="tabpanel" aria-labelledby="nomi-tab" tabindex="3">
    <form action="" method=""id="empFamForm">
        <div class="container col-lg-6 p-3">
            <h3 class="text-center p-2">Nominee Information</h3>

            {{-- Employee ID input --}}
            <div class="mb-3 row">
                <label for="empno" class="col-sm-3 col-form-label">Employee Id :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="empnoNominee" id="empnoNominee" value="{{ old('empno') }}"
                        placeholder="Employee Id">
                </div>
            </div>

            {{-- Name input --}}
            <div class="mb-3 row">
                <label for="depd_name" class="col-sm-3 col-form-label">Name of Nominee :</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="depd_name" id="depd_name"
                        value="{{ old('depd_name') }}" placeholder="Name in English" />
                </div>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="depent_name_bangla" id="depent_name_bangla"
                        value="{{ old('depent_name_bangla') }}" placeholder="Name in Bangla" />
                </div>
            </div>

            {{-- Relation with nominee --}}
            <div class="mb-3 row">
                <label for="relationship" class="col-sm-3 col-form-label">Relationship :</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="relationship" id="relationship"
                        value="{{ old('relationship') }}" placeholder="In English" />
                </div>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="relation_bn" id="relation_bn"
                        value="{{ old('relation_bn') }}" placeholder="In Bangla" />
                </div>
            </div>

            {{-- Address of nominee --}}
            <div class="mb-3 row">
                <label for="address" class="col-sm-3 col-form-label">Address :</label>
                <div class="col-sm-4">
                    <textarea class="form-control" name="address" id="address" value="{{ old('address') }}"
                        placeholder="In English"></textarea>
                </div>
                <div class="col-sm-4">
                    <textarea class="form-control" name="address_bn" id="address_bn" value="{{ old('address_bn') }}"
                        placeholder="In Bangla"></textarea>
                </div>
            </div>

            {{-- Age input --}}
            <div class="mb-3 row">
                <label for="d_age" class="col-sm-3 col-form-label">Age :</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="d_age" id="d_age" value="{{ old('d_age') }}"
                        placeholder="Age" />
                </div>
            </div>

            {{-- Gender selection --}}
            <div class="mb-3 row">
                <label for="d_sex" class="col-sm-3 col-form-label">Gender :</label>
                <div class="col-sm-8">
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="male" name="d_sex" value="male">
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="female" name="d_sex" value="female">
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                    {{-- <div class="form-check form-check-inline">
                                 <input type="radio" class="form-check-input" id="radio3" name="d_sex" value="option2">
                                 <label class="form-check-label" for="radio2">Others</label>
                              </div> --}}
                </div>
            </div>


            {{-- Percentage input --}}
            <div class="mb-3 row">
                <label for="percentage" class="col-sm-3 col-form-label">Percentage :</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="percentage" id="percentage"
                        value="{{ old('percentage') }}" placeholder="Percentage">
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="row-md-6 m-3 text-center p-3">
                <button class="btn btn-primary border-black" type="submit">Add</button>
                <button class="btn btn-danger" type="button" id="clearnominee">Clear</button>
            </div>
        </div>

    </form>



    <div class="overflow-auto" style="max-width: 3000px; max-height: 2000px;">
            <hr />
            <table id="sal_table" class="table table-bordered p-3" style="width:100%">
            <thead class="bg-dark text-light" width="200%" style="background-color:rgb(94, 21, 94)">
                <tr>
                    <th style="width:10px; text-align:left">Empno</th>
                    <th style="width:10px; text-align:center">DEPENDED NAME</th>
                    <th style="width:10px; text-align:center">DEPENDED NAME BN</th>
                    <th style="width:10px; text-align:center">Relationship</th>
                    <th style="width:10px; text-align:center">Relationship BN</th>
                    <th style="width:10px; text-align:center">Date Of Birth</th>
                    <th style="width:20px; text-align:center">Age</th>
                    <th style="width:10px; text-align:left">Gender</th>
                    <th style="width:10px; text-align:center">Percentage</th>
                    <th style="width:10px; text-align:center">Address</th>
                    <th style="width:10px; text-align:center">Address BN</th>
                </tr>
            </thead>
            <tbody id="emp_nom_data">

            </tbody>
        </table>
    </div>
</div>
{{-- Nominee section ends --}}
