<div class="tab-pane fade" id="empimg" role="tabpanel" aria-labelledby="empimg-tab" tabindex="10">
<form action="" method="">
        <div class="container col-lg-6 p-3">
            <h3 class="text-center p-2">Nominee Information</h3>
            <div class="col-md">
                    <div class="row p-1">
                        <label for="empno" class="col-sm-3 col-form-label">Employee Id :</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="empnoimg" id="empnoimg"
                                value="{{ old('empnoimg') }}" placeholder="Employee Id">

                        </div>
                    </div>
                </div>
            {{-- Photo --}}
            <div class="mb-3 row">
                <label for="photo" class="col-sm-3 col-form-label">Photo :</label>
                <div class="col-sm-8">
                    <input class="form-control" type="file" id="photo" name="photo">
                    <div class="border dflex justify-content-centre" style="height:300px;width:300Px;">
                <img id="preview-image" src="#" alt="Preview" width="300Px" height="300Px">

                </div>
                </div>
                
            </div>

            {{-- Signature --}}
            <div class="mb-3 row">
                <label for="signature" class="col-sm-3 col-form-label">Signature :</label>
                <div class="col-sm-8">
                    <input class="form-control" type="file" id="signature" name="signature">
                    <div class="border dflex justify-content-centre" style="height:80px;width:200Px;">

                    <img id="preview-sign" src="#" alt="Preview"width="200Px" height="80Px">

                </div>
                </div>
            </div>

        </div>
        <div class="row-md-6 m-3 text-center p-3">
                <button class="btn btn-white border-black" type="submit">Add</button>
                <button class="btn btn-danger" type="button" id="clearimg">Clear</button>
            </div>

    </form>


</div>

