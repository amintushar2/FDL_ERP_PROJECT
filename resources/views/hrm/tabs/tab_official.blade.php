{{-- resources/views/hrm/tabs/tab_official.blade.php --}}
{{-- Loaded via AJAX — no layout. All dropdowns use LOV AJAX except emp_type (passed from controller) --}}
@php $off = optional(optional($emp)->getempofficial); @endphp

<form id="frmOfficial">
    @csrf
    <input type="hidden" name="empno" value="{{ $empno }}">

    <div class="page-heading"><i class="bi bi-briefcase-fill"></i> Official Information</div>

    {{-- ── Base Information ────────────────────────────── --}}
    <div class="sec-card">
        <div class="sec-card-head"><i class="bi bi-building"></i> Base Information</div>
        <div class="sec-card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Company:</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="company_id" id="of_company">
                                <option value="">Select Company</option>
                                @foreach ($companyList as $c)
                                    <option value="{{ $c->company_id }}"
                                        {{ $off->company_id == $c->company_id ? 'selected' : '' }}>
                                        {{ $c->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Emp Type:</label>
                        <div class="col-sm-7">
                            {{-- Emp Type: passed from controller (small table) --}}
                            <select class="form-select" name="emp_type" id="of_emp_type">
                                <option value="">Select One</option>
                                @foreach ($empType as $t)
                                    <option value="{{ $t->emp_type }}"
                                        {{ $off->emp_type == $t->emp_type ? 'selected' : '' }}>{{ $t->emp_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                {{-- ALL below: LOV AJAX dropdowns --}}
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Department:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="dept_no" id="of_dept" data-lov="/lov/dept"
                                data-val="{{ $off->dept_no }}" data-txt="{{ $off->dept_name ?? '' }}"
                                data-ph="Search Department…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Section:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="section_no" id="of_section" data-lov="/lov/section"
                                data-val="{{ $off->section_no }}" data-txt="{{ $off->section_name ?? '' }}"
                                data-ph="Search Section…"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Floor:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="floor_id" id="of_floor" data-lov="/lov/floor"
                                data-val="{{ $off->floor_id }}" data-txt="{{ $off->floor_desc ?? '' }}"
                                data-ph="Search Floor…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Line:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="line" id="of_line" data-lov="/lov/line"
                                data-val="{{ $off->line }}" data-txt="{{ $off->line_info ?? ($off->line ?? '') }}"
                                data-ph="Search Line…"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Designation:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="des_id" id="of_des" data-lov="/lov/designation"
                                data-val="{{ $off->des_id }}" data-txt="{{ $off->des_name ?? '' }}"
                                data-ph="Search Designation…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Grade:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="grade_id" id="of_grade" data-lov="/lov/grade"
                                data-val="{{ $off->grade_id }}"
                                data-txt="{{ $off->grade_name ?? ($off->grade_id ?? '') }}"
                                data-ph="Search Grade…"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Shift:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="shift_code" id="of_shift" data-lov="/lov/shift"
                                data-val="{{ $off->shift_code }}"
                                data-txt="{{ $off->shift_name ?? ($off->shift_code ?? '') }}"
                                data-ph="Search Shift…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Calendar:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="cal_code" id="of_cal" data-lov="/lov/calendar"
                                data-val="{{ $off->cal_code }}" data-txt="{{ $off->cal_code ?? '' }}"
                                data-ph="Search Calendar…"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Weekly Off:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="weekly_off" id="of_weeklyoff"
                                data-lov="/lov/weeklyoff" data-val="{{ $off->weekly_off }}"
                                data-txt="{{ $off->weekly_off ?? '' }}" data-ph="Select Day…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">S. Group:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="s_group_name"
                                value="{{ $off->s_group_name ?? '' }}" placeholder="Shift Group Name">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Joining & Date Information ───────────────────── --}}
    <div class="sec-card">
        <div class="sec-card-head"><i class="bi bi-calendar-check"></i> Joining & Date Information</div>
        <div class="sec-card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Join Date:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control date-pick" name="join_date"
                                value="{{ $off->joining_date ? \Carbon\Carbon::parse($off->joining_date)->format('d-m-Y') : '' }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Join Time:</label>
                        <div class="col-sm-7">
                            <input type="time" class="form-control" name="join_time"
                                value="{{ $off->join_time ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Confirmation Date:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control date-pick" name="confirmation_date"
                                value="{{ $off->confirmation_date ? \Carbon\Carbon::parse($off->confirmation_date)->format('d-m-Y') : '' }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Confirmation Duration (Days):</label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control" name="confirmation_duration"
                                value="{{ $off->confirmation_duration ?? '' }}" placeholder="Days">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Last Promo Date:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control date-pick" name="last_promo_date"
                                value="{{ $off->last_promo_date ? \Carbon\Carbon::parse($off->last_promo_date)->format('d-m-Y') : '' }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Last Increment Date:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control date-pick" name="last_increment_date"
                                value="{{ $off->last_increment_date ? \Carbon\Carbon::parse($off->last_increment_date)->format('d-m-Y') : '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Attendance & Card Information ─────────────────── --}}
    <div class="sec-card">
        <div class="sec-card-head"><i class="bi bi-clock-history"></i> Attendance & Card Information</div>
        <div class="sec-card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Punch Card No:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="punch_card_no"
                                value="{{ $off->punch_card_no ?? '' }}" placeholder="Card Number">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Proximity Card No:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="proximity_card_no"
                                value="{{ $off->proximity_card_no ?? '' }}" placeholder="Proximity Card">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">OT Category:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="ot_cat" id="of_otcat" data-lov="/lov/otcategory"
                                data-val="{{ $off->ot_cat }}" data-txt="{{ $off->ot_cat ?? '' }}"
                                data-ph="Search OT Category…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Attn. Eff. Date:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control date-pick" name="attn_eff_date"
                                value="{{ $off->attn_eff_date ? \Carbon\Carbon::parse($off->attn_eff_date)->format('d-m-Y') : '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Entitlement Information ────────────────────────── --}}
    <div class="sec-card">
        <div class="sec-card-head"><i class="bi bi-award"></i> Entitlement Information</div>
        <div class="sec-card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-5 col-form-label">Work Entitle:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="work_ent" id="of_work_ent" data-lov="/lov/workEnt"
                                data-val="{{ $off->work_ent }}" data-txt="{{ $off->work_ent ?? '' }}"
                                data-ph="Select…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-5 col-form-label">Overtime:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="ot_ent" id="of_ot_ent" data-lov="/lov/yesno"
                                data-val="{{ $off->ot_ent }}" data-txt="{{ $off->ot_ent ?? '' }}"
                                data-ph="Select…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-5 col-form-label">Residence:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="res_ent" id="of_res_ent" data-lov="/lov/yesno"
                                data-val="{{ $off->res_ent }}" data-txt="{{ $off->res_ent ?? '' }}"
                                data-ph="Select…"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-5 col-form-label">Transport:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="tran_ent" id="of_tran_ent" data-lov="/lov/yesno"
                                data-val="{{ $off->tran_ent }}" data-txt="{{ $off->tran_ent ?? '' }}"
                                data-ph="Select…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-5 col-form-label">PF Facility:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="pf_ent" id="of_pf_ent" data-lov="/lov/yesno"
                                data-val="{{ $off->pf_ent }}" data-txt="{{ $off->pf_ent ?? '' }}"
                                data-ph="Select…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-5 col-form-label">Tax:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="tax_ent" id="of_tax_ent" data-lov="/lov/yesno"
                                data-val="{{ $off->tax_ent }}" data-txt="{{ $off->tax_ent ?? '' }}"
                                data-ph="Select…"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Leave Information ──────────────────────────────── --}}
    <div class="sec-card">
        <div class="sec-card-head"><i class="bi bi-calendar-x"></i> Leave Information</div>
        <div class="sec-card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Leave Category:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="lv_cat_id" id="of_lvcat"
                                data-lov="/lov/leavecategory" data-val="{{ $off->lv_cat_id }}"
                                data-txt="{{ $off->lv_cat_name ?? '' }}" data-ph="Search Leave Category…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Entry Date:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control date-pick" name="entry_date"
                                value="{{ $off->entry_date ? \Carbon\Carbon::parse($off->entry_date)->format('d-m-Y') : '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Salary & Bank Information ──────────────────────── --}}
    <div class="sec-card">
        <div class="sec-card-head"><i class="bi bi-cash-stack"></i> Salary & Bank Information</div>
        <div class="sec-card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Gross Salary:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="gross"
                                value="{{ $off->gross ?? '' }}" placeholder="Amount" inputmode="numeric"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Other Allowance:</label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control" name="other_allowance"
                                value="{{ $off->other_allowance ?? '' }}" placeholder="Amount">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Bank Name:</label>
                        <div class="col-sm-7">
                            <select class="form-select lov" name="bank_name" id="of_bank" data-lov="/lov/bank"
                                data-val="{{ $off->bank_name }}" data-txt="{{ $off->bank_name ?? '' }}"
                                data-ph="Search Bank…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Account No:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="bank_ac_no"
                                value="{{ $off->bank_ac_no ?? '' }}" placeholder="Account No">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">TIN No:</label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control" name="tin_no"
                                value="{{ $off->tin_no ?? '' }}" placeholder="TIN No">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Tax Deduction:</label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control" name="tax_deduction"
                                value="{{ $off->tax_deduction ?? '' }}" placeholder="Amount">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Service Book No:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="service_book_number"
                                value="{{ $off->service_book_number ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">A/C:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="ac_no"
                                value="{{ $off->ac_no ?? '' }}" placeholder="A/C">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Release Information ──────────────────────────── --}}
    <div class="sec-card">
        <div class="sec-card-head"><i class="bi bi-box-arrow-right"></i> Release Information</div>
        <div class="sec-card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Termination Date:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control date-pick" name="termination_date"
                                value="{{ $off->termination_date ? \Carbon\Carbon::parse($off->termination_date)->format('d-m-Y') : '' }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Resigned Date:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control date-pick" name="resigned_date"
                                value="{{ $off->resigned_date ? \Carbon\Carbon::parse($off->resigned_date)->format('d-m-Y') : '' }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Reason:</label>
                        <div class="col-sm-7">
                            <textarea class="form-control" name="reason" placeholder="Reason for leaving…">{{ $off->reason ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Is Lefty:</label>
                        <div class="col-sm-7">
                            <select class="form-select" name="is_lefty">
                                <option value="">Choose</option>
                                <option value="L" {{ ($off->is_lefty ?? '') === 'L' ? 'selected' : '' }}>Lefty
                                </option>
                                <option value="R" {{ ($off->is_lefty ?? '') === 'R' ? 'selected' : '' }}>Resigned
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="action-bar">
        <button class="btn btn-upd" type="submit"><i class="bi bi-check-circle me-1"></i> Save Official
            Info</button>
        <button class="btn btn-clr" type="reset"><i class="bi bi-x-circle me-1"></i> Reset</button>
    </div>
</form>

<script>
    $(function() {
        // ═══════════════════════════════════════════════════════════════
        //  Initialize all LOV selects in this tab
        // ═══════════════════════════════════════════════════════════════
        lovInitAll('#frmOfficial');
        initDatePick(document);


        // ═══════════════════════════════════════════════════════════════
        //  Form Submission with LOV Values AND Text
        // ═══════════════════════════════════════════════════════════════
        $('#frmOfficial').on('submit', function(e) {
            e.preventDefault();

            // ─── Validation (optional but recommended) ─────────────────
            const requiredFields = ['dept_no', 'des_id', 'grade_id'];
            const validation = lovValidate('#frmOfficial', requiredFields);

            if (!validation.isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Required Fields',
                    html: 'Please fill in:<br>' + validation.missing.join('<br>'),
                    confirmButtonText: 'OK'
                });
                return;
            }

            // ─── METHOD 1: Using lovFormObjectWithNames (Recommended) ──
            // This automatically includes both ID and NAME for all LOV fields
            const formData = lovFormObjectWithNames('#frmOfficial');

            // Add empno if not already present
            formData.empno = formData.empno || $('#tabOfficialForm [name="empno"]').val();

            // ─── Show what data is being sent (for debugging) ──────────
            console.log('Form Data Being Sent:', formData);
            console.log('LOV Data Details:', lovFormData('#frmOfficial'));

            // ─── Submit via AJAX ───────────────────────────────────────
            $.ajax({
                url: '/api/saveEmpOfficial',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        swalOk(response.message);
                    } else {
                        swalErr(response.message || 'Save failed');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Save Error:', xhr.responseJSON);
                    swalErr(xhr.responseJSON?.message || 'An error occurred');
                }
            });
        });

        // ═══════════════════════════════════════════════════════════════
        //  Alternative Methods (for reference)
        // ═══════════════════════════════════════════════════════════════

        // METHOD 2: Manual extraction of each LOV field
        function getFormDataManual() {
            const fd = {};

            // Regular form fields
            $('#frmOfficial').serializeArray().forEach(f => fd[f.name] = f.value);

            // LOV fields - get both value and text
            fd.dept_no = lovValue('#of_dept');
            fd.dept_name = lovText('#of_dept');

            fd.section_no = lovValue('#of_section');
            fd.section_name = lovText('#of_section');

            fd.des_id = lovValue('#of_des');
            fd.des_name = lovText('#of_des');

            fd.grade_id = lovValue('#of_grade');
            fd.grade_name = lovText('#of_grade');

            fd.floor_id = lovValue('#of_floor');
            fd.floor_desc = lovText('#of_floor');

            fd.line = lovValue('#of_line');
            fd.line_info = lovText('#of_line');

            fd.shift_code = lovValue('#of_shift');
            fd.shift_name = lovText('#of_shift');

            fd.cal_code = lovValue('#of_cal');

            fd.weekly_off = lovValue('#of_weeklyoff');

            fd.ot_cat = lovValue('#of_otcat');

            fd.lv_cat_id = lovValue('#of_lvcat');
            fd.lv_cat_name = lovText('#of_lvcat');

            fd.bank_name = lovValue('#of_bank');

            return fd;
        }

        // METHOD 3: Using lovFormData to get all LOV fields
        function getAllLOVData() {
            const lovData = lovFormData('#frmOfficial');
            console.log('All LOV Fields:', lovData);

            // Example access:
            // lovData.dept_no.value  -> "DEPT001"
            // lovData.dept_no.text   -> "Human Resources"

            return lovData;
        }

        // ═══════════════════════════════════════════════════════════════
        //  Utility Functions for Individual Field Access
        // ═══════════════════════════════════════════════════════════════

        // Get individual field data
        function getDepartmentData() {
            return lovData('#of_dept');
            // Returns: {value: "DEPT001", text: "Human Resources"}
        }

        function getDesignationData() {
            return lovData('#of_des');
            // Returns: {value: "DES100", text: "Senior Manager"}
        }

        // Check if a field has value
        function isDepartmentSelected() {
            return lovHasValue('#of_dept');
        }

        // Clear a specific field
        function clearDepartment() {
            lovClear('#of_dept');
        }

        // Set a specific field value
        function setDepartment(value, text) {
            lovSet('#of_dept', value, text);
        }
    });

    // ═══════════════════════════════════════════════════════════════
    //  Example: Getting data when user changes a field
    // ═══════════════════════════════════════════════════════════════
    $('#of_dept').on('change', function() {
        const deptData = lovData('#of_dept');
        console.log('Department Changed:');
        console.log('  ID:', deptData.value);
        console.log('  Name:', deptData.text);

        // You can use this data for dependent dropdowns, calculations, etc.
    });


    /* ── Smart dd-mm-yyyy date picker for all .date-pick inputs ── */
    (function() {
        function initDatePick(scope) {
            var scope = scope || document;
            scope.querySelectorAll('input.date-pick').forEach(function(el) {
                if (el.dataset.fpInit) return; // skip if already init
                el.dataset.fpInit = '1';

                // 1. Flatpickr calendar
                if (typeof flatpickr !== 'undefined') {
                    flatpickr(el, {
                        dateFormat: 'd-m-Y',
                        allowInput: true,
                        disableMobile: true,
                        onReady: function(_, __, fp) {
                            fp.calendarContainer && fp.calendarContainer.classList.add(
                                'fp-sm');
                        }
                    });
                }

                // 2. Smart 6/8-digit auto-format on keyup
                el.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab' || e.key === 'Enter') {
                        autoFormatDate(this);
                    }
                });
                el.addEventListener('blur', function() {
                    autoFormatDate(this);
                });
            });
        }

        function autoFormatDate(el) {
            var raw = el.value.replace(/\D/g, ''); // digits only
            if (!raw) return;

            var day, mon, yr;

            if (raw.length === 6) {
                // 020496 → 02-04-1996
                day = raw.substr(0, 2);
                mon = raw.substr(2, 2);
                yr = raw.substr(4, 2);
                yr = (parseInt(yr, 10) <= 30 ? '20' : '19') + yr;
            } else if (raw.length === 8) {
                // 02041996 → 02-04-1996
                day = raw.substr(0, 2);
                mon = raw.substr(2, 2);
                yr = raw.substr(4, 4);
            } else {
                return; // don't reformat partial or already-formatted
            }

            // Validate range
            var d = parseInt(day, 10),
                m = parseInt(mon, 10),
                y = parseInt(yr, 10);
            if (d < 1 || d > 31 || m < 1 || m > 12 || y < 1900 || y > 2099) return;

            var formatted = day + '-' + mon + '-' + yr;
            el.value = formatted;

            // Push value into Flatpickr instance if present
            if (el._flatpickr) el._flatpickr.setDate(formatted, false, 'd-m-Y');
        }

        // Init on DOMContentLoaded (for empform Tab1)
        document.addEventListener('DOMContentLoaded', function() {
            initDatePick(document);
        });

        // Expose for lazy-loaded AJAX tabs
        window.initDatePick = initDatePick;
    })();
</script>
