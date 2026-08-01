{{-- resources/views/hrm/tabs/tab_nominee.blade.php --}}

<style>
    .bn-font {
        font-family: SutonnyMJ, sans-serif !important;
        font-size: 18px !important;
        line-height: 1.1;
    }
</style>

<div class="page-heading">
    <i class="bi bi-people-fill"></i> Nominee / Family
</div>

<form id="frmNominee">

    @csrf

    <input type="hidden" name="empno" value="{{ $empno }}">

    <input type="hidden" name="family_id" value="">

    <input type="hidden" name="depd_no" value="">

    <div class="sec-card">

        <div class="sec-card-head">
            <i class="bi bi-person-plus"></i> Add Nominee Record
        </div>

        <div class="sec-card-body">

            {{-- ROW 1 --}}
            <div class="row">

                <div class="col-md-6">

                    <div class="row p-1">

                        <label class="col-sm-3 col-form-label">
                            Name (EN):
                        </label>

                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="depd_name"
                                placeholder="Full Name in English">

                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="row p-1">

                        <label class="col-sm-3 col-form-label">
                            Name (BN):
                        </label>

                        <div class="col-sm-9">

                            <input type="text" class="form-control bn-font" name="depent_name_bangla"
                                placeholder="বাংলায় নাম">

                        </div>

                    </div>

                </div>

            </div>

            {{-- ROW 2 --}}
            <div class="row">

                <div class="col-md-6">

                    <div class="row p-1">

                        <label class="col-sm-3 col-form-label">
                            Relation (EN):
                        </label>

                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="relationship" placeholder="e.g. Wife, Son">

                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="row p-1">

                        <label class="col-sm-3 col-form-label">
                            Relation (BN):
                        </label>

                        <div class="col-sm-9">

                            <input type="text" class="form-control bn-font" name="relation_bn" placeholder="সম্পর্ক">

                        </div>

                    </div>

                </div>

            </div>

            {{-- ROW 3 --}}
            <div class="row">

                <div class="col-md-4">

                    <div class="row p-1">

                        <label class="col-sm-4 col-form-label">
                            DOB:
                        </label>

                        <div class="col-sm-8">

                            <input type="text" class="form-control date-pick" name="d_dob">

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="row p-1">

                        <label class="col-sm-4 col-form-label">
                            Age:
                        </label>

                        <div class="col-sm-8">

                            <input type="number" class="form-control" name="d_age">

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="row p-1">

                        <label class="col-sm-4 col-form-label">
                            Gender:
                        </label>

                        <div class="col-sm-8 pt-2">

                            <div class="form-check form-check-inline">

                                <input type="radio" class="form-check-input" name="d_sex" value="male"
                                    id="nmM">

                                <label class="form-check-label" for="nmM">
                                    Male
                                </label>

                            </div>

                            <div class="form-check form-check-inline">

                                <input type="radio" class="form-check-input" name="d_sex" value="female"
                                    id="nmF">

                                <label class="form-check-label" for="nmF">
                                    Female
                                </label>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- ROW 4 --}}
            <div class="row">

                <div class="col-md-6">

                    <div class="row p-1">

                        <label class="col-sm-3 col-form-label">
                            As On:
                        </label>

                        <div class="col-sm-9">

                            <input type="text" class="form-control date-pick" name="d_as_on">

                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="row p-1">

                        <label class="col-sm-3 col-form-label">
                            Percentage:
                        </label>

                        <div class="col-sm-9">

                            <input type="number" class="form-control" name="percentage" placeholder="%"
                                max="100">

                        </div>

                    </div>

                </div>

            </div>

            {{-- ROW 5 --}}
            <div class="row">

                <div class="col-md-6">

                    <div class="row p-1">

                        <label class="col-sm-3 col-form-label">
                            Address (EN):
                        </label>

                        <div class="col-sm-9">

                            <textarea class="form-control" name="address" rows="2" placeholder="Address in English"></textarea>

                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="row p-1">

                        <label class="col-sm-3 col-form-label">
                            Address (BN):
                        </label>

                        <div class="col-sm-9">

                            <textarea class="form-control bn-font" name="address_bn" rows="2" placeholder="বাংলায় ঠিকানা"></textarea>

                        </div>

                    </div>

                </div>

            </div>

            {{-- ROW 6 --}}
            <div class="row">

                <div class="col-md-3">

                    <div class="row p-1">

                        <label class="col-sm-4 col-form-label">
                            Village (Bangla):
                        </label>

                        <div class="col-sm-8">

                            <input type="text" class="form-control bn-font" name="village_bn"
                                placeholder="গ্রাম">

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="row p-1">

                        <label class="col-sm-4 col-form-label">
                            PO (Bangla):
                        </label>

                        <div class="col-sm-8">

                            <input type="text" class="form-control bn-font" name="po_bn"
                                placeholder="পোষ্ট অফিস">

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="row p-1">

                        <label class="col-sm-4 col-form-label">
                            PS:
                        </label>

                        <div class="col-sm-8">

                            <input type="text" class="form-control bn-font" name="ps_bn"
                                placeholder="পুলিশ স্টেশন">

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="row p-1">

                        <label class="col-sm-4 col-form-label">
                            District:
                        </label>

                        <div class="col-sm-8">

                            <input type="text" class="form-control bn-font" name="district_bn">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="action-bar">

        <button class="btn btn-save" type="submit">
            <i class="bi bi-plus-circle me-1"></i> Add
        </button>

        <button class="btn btn-clr" type="reset">
            <i class="bi bi-x-circle me-1"></i> Clear
        </button>

    </div>

</form>

<div class="sub-table-wrap">

    <table class="emp-table">

        <thead>

            <tr>

                <th>Empno</th>
                <th>Name</th>
                <th>Name BN</th>
                <th>Relation</th>
                <th>Relation BN</th>
                <th>DOB</th>
                <th>Age</th>
                <th>Gender</th>
                <th>%</th>
                <th>Address</th>
                <th>Village</th>
                <th>PO</th>
                <th>PS</th>
                <th>District</th>
                <th style="width:70px;">Action</th>

            </tr>

        </thead>

        <tbody id="tbl_nom"></tbody>

    </table>

</div>

<script>
    $(function() {

        loadNomRows();

        $('#frmNominee').on('submit', function(e) {

            e.preventDefault();

            const fd = {};

            $(this).serializeArray().forEach(f => fd[f.name] = f.value);

            fd.d_sex = $('input[name="d_sex"]:checked').val();

            fd.empno = fd.empno || $('#frmNominee [name="empno"]').val();

            const familyId = fd.family_id;

            const ajaxOptions = {

                url: familyId ?
                    `/api/updateEmpFamily` : '/api/saveEmpFamily',

                method: familyId ? 'PUT' : 'POST',

                contentType: 'application/json',

                data: JSON.stringify(fd),

                dataType: 'json',

                success: res => {

                    swalOk(res.message);

                    loadNomRows();

                    resetNomineeForm();

                },

                error: swalErr
            };

            $.ajax(ajaxOptions);

        });

    });

    function loadNomRows() {
        const empno = $('#frmNominee [name="empno"]').val();

        if (!empno) return;

        $.get("{{ URL::to('api/getEmpFamily') }}/" + empno, function(res) {

            const rows = (res.success && Array.isArray(res.data) ?
                res.data : []).map(function(item) {

                return '<tr>' +

                    '<td>' + (item.empno || '') + '</td>' +

                    '<td>' + (item.depd_name || '') + '</td>' +

                    '<td class="bn-font">' + (item.depent_name_bangla || '') + '</td>' +

                    '<td>' + (item.relationship || '') + '</td>' +

                    '<td class="bn-font">' + (item.relation_bn || '') + '</td>' +

                    '<td>' + (item.d_dob || '') + '</td>' +

                    '<td>' + (item.d_age || '') + '</td>' +

                    '<td>' + (item.d_sex || '') + '</td>' +

                    '<td>' + (item.percentage || '') + '</td>' +

                    '<td class="bn-font">' + (item.address_bn || '') + '</td>' +

                    '<td class="bn-font">' + (item.village_bn || '') + '</td>' +

                    '<td class="bn-font">' + (item.po_bn || '') + '</td>' +

                    '<td class="bn-font">' + (item.ps_bn || '') + '</td>' +

                    '<td class="bn-font">' + (item.district_bn || '') + '</td>' +

                    '<td class="text-center">' +

                    '<button type="button" class="btn btn-sm btn-secondary btn-edit-nom me-1" data-item="' +
                    encodeURIComponent(JSON.stringify(item)) +
                    '" title="Edit"><i class="bi bi-pencil-fill"></i></button>' +

                    '<button type="button" class="btn btn-sm btn-danger btn-delete-nom" ' +
                    'data-empno="' + (item.empno || '') + '" ' +
                    'data-depdno="' + (item.depd_no || '') + '" ' +
                    'title="Delete"><i class="bi bi-trash-fill"></i></button>' +

                    '</td>' +

                    '</tr>';

            }).join('');

            $('#tbl_nom').html(
                rows ||
                '<tr><td colspan="15" class="text-center">No nominee records found.</td></tr>'
            );

        }, 'json');

        setTimeout(function() {

            if (window.initDatePick) {

                initDatePick(document);
            }

        }, 50);
    }

    function fillNomineeForm(item) {
        $('#frmNominee [name="family_id"]').val(item.depd_no || '');

        $('#frmNominee [name="depd_no"]').val(item.depd_no || '');

        $('#frmNominee [name="depd_name"]').val(item.depd_name || '');

        $('#frmNominee [name="depent_name_bangla"]').val(item.depent_name_bangla || '');

        $('#frmNominee [name="relationship"]').val(item.relationship || '');

        $('#frmNominee [name="relation_bn"]').val(item.relation_bn || '');

        $('#frmNominee [name="d_dob"]').val(item.d_dob || '');

        $('#frmNominee [name="d_age"]').val(item.d_age || '');

        $('input[name="d_sex"]').prop('checked', false);

        if (item.d_sex) {

            $('input[name="d_sex"][value="' + item.d_sex + '"]')
                .prop('checked', true);
        }

        $('#frmNominee [name="d_as_on"]').val(item.d_as_on || '');

        $('#frmNominee [name="percentage"]').val(item.percentage || '');

        $('#frmNominee [name="address"]').val(item.address || '');

        $('#frmNominee [name="address_bn"]').val(item.address_bn || '');

        $('#frmNominee [name="village_bn"]').val(item.village_bn || '');

        $('#frmNominee [name="po_bn"]').val(item.po_bn || '');

        $('#frmNominee [name="ps_bn"]').val(item.ps_bn || '');

        $('#frmNominee [name="district_bn"]').val(item.district_bn || '');

        $('#frmNominee button[type="submit"]')
            .html('<i class="bi bi-pencil-square me-1"></i> Update');
    }

    function resetNomineeForm() {
        $('#frmNominee')[0].reset();

        $('#frmNominee [name="family_id"]').val('');

        $('#frmNominee [name="depd_no"]').val('');

        $('#frmNominee button[type="submit"]')
            .html('<i class="bi bi-plus-circle me-1"></i> Add');
    }

    $(document).on('click', '.btn-edit-nom', function() {

        const item = JSON.parse(
            decodeURIComponent($(this).attr('data-item')) || '{}'
        );

        if (!item) return;

        fillNomineeForm(item);
    });

    $(document).on('click', '.btn-delete-nom', function() {

        const empno = $(this).data('empno');

        const depdno = $(this).data('depdno');

        if (!empno || !depdno) return;

        Swal.fire({

            title: 'Delete nominee?',

            text: 'This nominee record will be removed.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#d33',

            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Yes, delete it'

        }).then((result) => {

            if (!result.isConfirmed) return;

            $.ajax({

                url: `/api/deleteEmpFamily`,

                method: 'POST',

                data: {

                    empno: empno,
                    depd_no: depdno
                },

                success: function(response) {

                    Swal.fire('Deleted!', response.message, 'success');

                    loadNomRows();

                    resetNomineeForm();

                },

                error: function(xhr) {

                    Swal.fire(
                        'Error',
                        xhr.responseJSON?.message || 'An error occurred.',
                        'error'
                    );
                }
            });
        });
    });

    /* Smart dd-mm-yyyy date picker */

    (function() {

        function initDatePick(scope) {
            var scope = scope || document;

            scope.querySelectorAll('input.date-pick').forEach(function(el) {

                if (el.dataset.fpInit) return;

                el.dataset.fpInit = '1';

                if (typeof flatpickr !== 'undefined') {

                    flatpickr(el, {

                        dateFormat: 'd-m-Y',

                        allowInput: true,

                        disableMobile: true
                    });
                }

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
            var raw = el.value.replace(/\D/g, '');

            if (!raw) return;

            var day, mon, yr;

            if (raw.length === 6) {

                day = raw.substr(0, 2);

                mon = raw.substr(2, 2);

                yr = raw.substr(4, 2);

                yr = (parseInt(yr, 10) <= 30 ? '20' : '19') + yr;

            } else if (raw.length === 8) {

                day = raw.substr(0, 2);

                mon = raw.substr(2, 2);

                yr = raw.substr(4, 4);

            } else {

                return;
            }

            var formatted = day + '-' + mon + '-' + yr;

            el.value = formatted;

            if (el._flatpickr) {

                el._flatpickr.setDate(
                    formatted,
                    false,
                    'd-m-Y'
                );
            }
        }

        document.addEventListener('DOMContentLoaded', function() {

            initDatePick(document);
        });

        window.initDatePick = initDatePick;

    })();
</script>
