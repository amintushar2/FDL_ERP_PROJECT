{{-- resources/views/hrm/tabs/tab_location.blade.php --}}
@php
    $loc = optional($emp)->getemploc?->first();
    $locBn = optional($emp)->locationBangla;
    $locExists = $loc ? true : false;
    $locBnExists = $locBn ? true : false;
@endphp

<style>
    @font-face {
        font-family: 'SutonnyMJ';
        src: url('/fonts/SutonnyMJ.ttf') format('truetype');
    }

    .bangla-text {
        font-family: 'SutonnyMJ', Arial, sans-serif;
        font-size: 18px !important;
        line-height: 1.1;
    }

    .bangla-text-large {
        font-family: 'SutonnyMJ', Arial, sans-serif;
        font-size: 20px !important;
        line-height: 1.4;
        font-weight: bold;
    }

    .bangla-heading {
        font-family: 'SutonnyMJ', Arial, sans-serif;
        font-size: 20px !important;
        font-weight: bold;
        color: #fcfcfc;
        line-height: 1.4;
    }

    .bangla-label {
        font-family: 'SutonnyMJ', Arial, sans-serif;
        font-size: 16px !important;
        color: #000000;
    }

    .bangla-input {
        font-family: 'SutonnyMJ', Arial, sans-serif;
        font-size: 20px !important;
        font-weight: bold;
        color: #000000;
    }

    .bangla-table {
        font-family: 'SutonnyMJ', Arial, sans-serif;
    }

    .bangla-table td,
    .bangla-table th {
        font-family: 'SutonnyMJ', Arial, sans-serif;
        font-size: 16px !important;
    }

    #banglaLocation .form-label {
        font-size: 16px !important;
        font-weight: 600 !important;
    }

    #banglaLocation .form-control {
        font-size: 18px !important;
        height: auto !important;
        line-height: 1.8 !important;
    }

    #banglaLocation .form-control::placeholder {
        font-size: 16px !important;
    }

    .nav-tabs .nav-link {
        color: #495057;
        border: none;
        border-bottom: 3px solid transparent;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #007bff;
    }

    .nav-tabs .nav-link.active {
        color: #007bff;
        border-bottom-color: #007bff;
    }

    .sec-card-head.bangla-heading {
        padding: 12px 15px !important;
        font-size: 20px !important;
    }

    /* Record status badges */
    .record-badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 10px;
        margin-left: 8px;
        vertical-align: middle;
    }

    .badge-exists {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .badge-missing {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#englishLocation"
            type="button" role="tab">
            📍 English Location
            @if ($locExists)
                <span class="record-badge badge-exists">✔ Saved</span>
            @else
                <span class="record-badge badge-missing">✘ No Record</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link bangla-text" id="bangla-tab" data-bs-toggle="tab" data-bs-target="#banglaLocation"
            type="button" role="tab">
            📍 বাংলা অবস্থান
            @if ($locBnExists)
                <span class="record-badge badge-exists">✔ সংরক্ষিত</span>
            @else
                <span class="record-badge badge-missing">✘ নেই</span>
            @endif
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content">

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- ENGLISH LOCATION TAB                                               --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade show active" id="englishLocation" role="tabpanel">
        <form id="frmLocation">
            @csrf
            <input type="hidden" name="empno" id="eng_empno" value="{{ $empno }}">
            <input type="hidden" id="english_record_exists" value="{{ $locExists ? '1' : '0' }}">

            <div class="page-heading"><i class="bi bi-geo-alt-fill"></i> Location Information</div>

            {{-- Permanent Address --}}
            <div class="sec-card">
                <div class="sec-card-head"><i class="bi bi-house"></i> Permanent Address</div>
                <div class="sec-card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Address:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="p_address" id="p_address1"
                                        value="{{ $loc->p_address ?? '' }}" placeholder="Street / Area">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Village:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="p_village" id="p_village"
                                        value="{{ $loc->p_village ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Post Office:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="p_post_office" id="p_post_off"
                                        value="{{ $loc->p_post_off ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Thana/P.S:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="p_police_station"
                                        id="p_police_station11" value="{{ $loc->p_police_station ?? '' }}"
                                        placeholder="Police Station">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">City:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="p_city" id="p_city"
                                        value="{{ $loc->p_city ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">District:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="p_district" id="p_district2"
                                        value="{{ $loc->p_district ?? '' }}" placeholder="District">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Phone:</label>
                                <div class="col-sm-8">
                                    <input type="tel" class="form-control" name="p_phone" id="p_phone"
                                        value="{{ $loc->p_phone ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Fax:</label>
                                <div class="col-sm-8">
                                    <input type="tel" class="form-control" name="p_fax" id="p_fax"
                                        value="{{ $loc->p_fax ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">PIN Code:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="p_pin_code" id="p_postcode"
                                        value="{{ $loc->p_pin_code ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Contact Person:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="p_cperson" id="p_cperson"
                                        value="{{ $loc->p_cperson ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Present / Residential Address --}}
            <div class="sec-card">
                <div class="sec-card-head"><i class="bi bi-building"></i> Present / Residential Address</div>
                <div class="sec-card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Address:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="r_address" id="r_address1"
                                        value="{{ $loc->r_address ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">City:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="r_city" id="r_city"
                                        value="{{ $loc->r_city ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">District:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="r_district" id="r_district"
                                        value="{{ $loc->r_district ?? '' }}" placeholder="District">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Phone:</label>
                                <div class="col-sm-8">
                                    <input type="tel" class="form-control" name="r_phone" id="r_phone"
                                        value="{{ $loc->r_phone ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Mobile:</label>
                                <div class="col-sm-8">
                                    <input type="tel" class="form-control" name="r_mobile" id="r_mobile"
                                        value="{{ $loc->r_mobile ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Fax:</label>
                                <div class="col-sm-8">
                                    <input type="tel" class="form-control" name="r_fax" id="r_fax"
                                        value="{{ $loc->r_fax ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Email:</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" name="r_email" id="r_email"
                                        value="{{ $loc->r_email ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label">Contact:</label>
                                <div class="col-sm-8">
                                    <input type="tel" class="form-control" name="r_cperson" id="r_cperson"
                                        value="{{ $loc->r_cperson ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <button class="btn btn-upd" id="saveEnglishBtn" type="button">
                    <i class="bi bi-check-circle me-1"></i> Save Location
                </button>
                <button class="btn btn-danger" id="deleteEnglishBtn" type="button"
                    {{ $locExists ? '' : 'disabled' }}>
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
                <button class="btn btn-clr" type="reset">
                    <i class="bi bi-x-circle me-1"></i> Reset
                </button>
            </div>
        </form>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- BANGLA LOCATION TAB                                                --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="banglaLocation" role="tabpanel">
        <form id="frmLocationBangla">
            @csrf
            <input type="hidden" name="empno" id="bn_empno" value="{{ $empno }}">
            <input type="hidden" id="bangla_record_exists" value="{{ $locBnExists ? '1' : '0' }}">

            <div class="page-heading"><i class="bi bi-geo-alt-fill"></i> ঠিকানা</div>

            {{-- Family Information --}}
            <div class="sec-card">
                <div class="sec-card-head bangla-heading bangla-text">
                    <i class="bi bi-people-fill"></i> পারিবারিক তথ্য
                </div>
                <div class="sec-card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">পিতার নাম</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="father_name" id="father_name_bn"
                                        value="{{ $locBn->father_name ?? '' }}" placeholder="পিতার নাম লিখুন">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">মাতার নাম</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="mother_name" id="mother_name_bn"
                                        value="{{ $locBn->mother_name ?? '' }}" placeholder="মাতার নাম লিখুন">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">স্বামী/স্ত্রীর নাম</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="sopuse_name" id="sopuse_name_bn"
                                        value="{{ $locBn->sopuse_name ?? '' }}" placeholder="স্বামী/স্ত্রীর নাম">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">শ্রমিক শ্রেণী</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="worker_class" id="worker_class_bn"
                                        value="{{ $locBn->worker_class ?? '' }}" placeholder="শ্রেণী">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">কাজের ধরন</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="working_type" id="working_type_bn"
                                        value="{{ $locBn->working_type ?? '' }}" placeholder="কাজের ধরন">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">নতুন কর্মচারী নং</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="new_empno" id="new_empno_bn" value="{{ $locBn->new_empno ?? '' }}"
                                        placeholder="নতুন নং">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Present Address --}}
            <div class="sec-card">
                <div class="sec-card-head bangla-heading bangla-text">
                    <i class="bi bi-geo-alt-fill"></i> বর্তমান ঠিকানা
                </div>
                <div class="sec-card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">গ্রাম</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="present_village" id="present_village_bn"
                                        value="{{ $locBn->present_village ?? '' }}" placeholder="গ্রাম লিখুন">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">পোস্ট</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="present_psot" id="present_psot_bn"
                                        value="{{ $locBn->present_psot ?? '' }}" placeholder="পোস্ট লিখুন">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">থানা</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="present_thana" id="present_thana_bn"
                                        value="{{ $locBn->present_thana ?? '' }}" placeholder="থানা">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">জেলা</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="present_dist" id="present_dist_bn"
                                        value="{{ $locBn->present_dist ?? '' }}" placeholder="জেলা">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Permanent Address --}}
            <div class="sec-card">
                <div class="sec-card-head bangla-heading bangla-text">
                    <i class="bi bi-geo-alt-fill"></i> স্থায়ী ঠিকানা
                </div>
                <div class="sec-card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">গ্রাম</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="permanent_village" id="permanent_village_bn"
                                        value="{{ $locBn->permanent_village ?? '' }}" placeholder="গ্রাম লিখুন">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">পোস্ট</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="parmaent_post" id="parmaent_post_bn"
                                        value="{{ $locBn->parmaent_post ?? '' }}" placeholder="পোস্ট লিখুন">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">থানা</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="permanent_thana" id="permanent_thana_bn"
                                        value="{{ $locBn->permanent_thana ?? '' }}" placeholder="থানা">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row p-1">
                                <label class="col-sm-4 col-form-label bangla-label">জেলা</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control bangla-input bangla-text"
                                        name="permanent_dist" id="permanent_dist_bn"
                                        value="{{ $locBn->permanent_dist ?? '' }}" placeholder="জেলা">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <button class="btn btn-upd" id="saveBanglaBtn" type="button">
                    <i class="bi bi-check-circle me-1"></i>
                    <span class="bangla-text">সংরক্ষণ করুন</span>
                </button>
                <button class="btn btn-danger" id="deleteBanglaBtn" type="button"
                    {{ $locBnExists ? '' : 'disabled' }}>
                    <i class="bi bi-trash me-1"></i>
                    <span class="bangla-text">মুছুন</span>
                </button>
                <button class="btn btn-clr" type="reset">
                    <i class="bi bi-x-circle me-1"></i>
                    <span class="bangla-text">পরিষ্কার করুন</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    $(function() {
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        const empno = '{{ $empno }}';

        // ═══════════════════════════════════════════════════════════════════════
        // HELPER — update tab badge after save/delete
        // ═══════════════════════════════════════════════════════════════════════
        function setEnglishBadge(exists) {
            const badge = $('#english-tab .record-badge');
            if (exists) {
                badge.removeClass('badge-missing').addClass('badge-exists').text('✔ Saved');
                $('#deleteEnglishBtn').prop('disabled', false);
            } else {
                badge.removeClass('badge-exists').addClass('badge-missing').text('✘ No Record');
                $('#deleteEnglishBtn').prop('disabled', true);
            }
            $('#english_record_exists').val(exists ? '1' : '0');
        }

        function setBanglaBadge(exists) {
            const badge = $('#bangla-tab .record-badge');
            if (exists) {
                badge.removeClass('badge-missing').addClass('badge-exists').text('✔ সংরক্ষিত');
                $('#deleteBanglaBtn').prop('disabled', false);
            } else {
                badge.removeClass('badge-exists').addClass('badge-missing').text('✘ নেই');
                $('#deleteBanglaBtn').prop('disabled', true);
            }
            $('#bangla_record_exists').val(exists ? '1' : '0');
        }

        // ═══════════════════════════════════════════════════════════════════════
        // ENGLISH LOCATION — SAVE / UPDATE
        // ═══════════════════════════════════════════════════════════════════════
        $('#saveEnglishBtn').on('click', function(e) {
            e.preventDefault();

            const englishData = {
                empno: empno,
                p_address: $('#p_address1').val(),
                p_village: $('#p_village').val(),
                p_post_off: $('#p_post_off').val(),
                p_police_station: $('#p_police_station11').val(),
                p_city: $('#p_city').val(),
                p_district: $('#p_district2').val(),
                p_phone: $('#p_phone').val(),
                p_fax: $('#p_fax').val(),
                p_pin_code: $('#p_postcode').val(),
                p_cperson: $('#p_cperson').val(),
                r_address: $('#r_address1').val(),
                r_city: $('#r_city').val(),
                r_district: $('#r_district').val(),
                r_phone: $('#r_phone').val(),
                r_mobile: $('#r_mobile').val(),
                r_fax: $('#r_fax').val(),
                r_email: $('#r_email').val(),
                r_cperson: $('#r_cperson').val()
            };

            $.ajax({
                url: '/api/saveEmpLocation',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify(englishData),
                success: function(response) {
                    setEnglishBadge(true);
                    Swal.fire('Success!', response.message, 'success');
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'An error occurred',
                        'error');
                }
            });
        });

        // ═══════════════════════════════════════════════════════════════════════
        // ENGLISH LOCATION — DELETE
        // ═══════════════════════════════════════════════════════════════════════
        $('#deleteEnglishBtn').on('click', function(e) {
            e.preventDefault();

            if ($('#english_record_exists').val() !== '1') {
                Swal.fire('Warning!', 'No record found to delete.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'Delete English location information?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#7f8c8d',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/deleteEmpLocation/${empno}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        success: function(response) {
                            setEnglishBadge(false);
                            $('#frmLocation')[0].reset();
                            Swal.fire('Deleted!', response.message, 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON?.message ||
                                'An error occurred', 'error');
                        }
                    });
                }
            });
        });

        // ═══════════════════════════════════════════════════════════════════════
        // BANGLA LOCATION — SAVE / UPDATE
        // ═══════════════════════════════════════════════════════════════════════
        $('#saveBanglaBtn').on('click', function(e) {
            e.preventDefault();

            const banglaData = {
                empno: empno,
                father_name: $('#father_name_bn').val(),
                mother_name: $('#mother_name_bn').val(),
                present_village: $('#present_village_bn').val(),
                present_psot: $('#present_psot_bn').val(),
                present_thana: $('#present_thana_bn').val(),
                present_dist: $('#present_dist_bn').val(),
                permanent_village: $('#permanent_village_bn').val(),
                parmaent_post: $('#parmaent_post_bn').val(),
                permanent_thana: $('#permanent_thana_bn').val(),
                permanent_dist: $('#permanent_dist_bn').val(),
                sopuse_name: $('#sopuse_name_bn').val(),
                worker_class: $('#worker_class_bn').val(),
                working_type: $('#working_type_bn').val(),
                new_empno: $('#new_empno_bn').val()
            };

            $.ajax({
                url: '/api/saveEmpLocationBangla',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify(banglaData),
                success: function(response) {
                    setBanglaBadge(true);
                    Swal.fire('সফল!', response.message, 'success');
                },
                error: function(xhr) {
                    Swal.fire('ত্রুটি!', xhr.responseJSON?.message || 'একটি ত্রুটি ঘটেছে',
                        'error');
                }
            });
        });

        // ═══════════════════════════════════════════════════════════════════════
        // BANGLA LOCATION — DELETE
        // ═══════════════════════════════════════════════════════════════════════
        $('#deleteBanglaBtn').on('click', function(e) {
            e.preventDefault();

            if ($('#bangla_record_exists').val() !== '1') {
                Swal.fire('সতর্কতা!', 'মুছার জন্য কোনো রেকর্ড নেই।', 'warning');
                return;
            }

            Swal.fire({
                title: 'নিশ্চিত করুন',
                text: 'আপনি কি বাংলা অবস্থান তথ্য মুছতে চান?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#7f8c8d',
                confirmButtonText: 'হ্যাঁ, মুছুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/deleteEmpLocationBangla/${empno}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        success: function(response) {
                            setBanglaBadge(false);
                            $('#frmLocationBangla')[0].reset();
                            Swal.fire('মুছা হয়েছে!', response.message, 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('ত্রুটি!', xhr.responseJSON?.message ||
                                'একটি ত্রুটি ঘটেছে', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
