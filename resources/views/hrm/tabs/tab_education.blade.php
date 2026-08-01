{{-- ═══════════════════════════════════════════════════════
     resources/views/hrm/tabs/tab_education.blade.php
═══════════════════════════════════════════════════════ --}}

<div class="page-heading"><i class="bi bi-mortarboard-fill"></i> Education</div>
<form id="frmEdu">@csrf<input type="hidden" name="empno" value="{{ $empno }}"><input type="hidden"
        name="qualification_id" value="">
    <div class="sec-card">
        <div class="sec-card-head"><i class="bi bi-journal-text"></i> Add Education Record</div>
        <div class="sec-card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Institute:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="name_of_ins"
                                placeholder="Institute Name"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Exam:</label>
                        <div class="col-sm-8"><select class="form-control select2" name="passed_exam" id="passed_exam">
                                <option value="">Select Exam</option>
                            </select></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Division:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="division"
                                placeholder="Division / Grade / CGPA"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Year:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="year"
                                placeholder="Passing Year"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Board/Univ:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="board"
                                placeholder="Board or University"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Marks:</label>
                        <div class="col-sm-8"><input type="number" class="form-control" name="marks"
                                placeholder="Marks / GPA"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Subject/Group:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="subject"
                                placeholder="Subject or Group"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="action-bar">
        <button class="btn btn-save" type="submit"><i class="bi bi-plus-circle me-1"></i> Add</button>
        <button class="btn btn-clr" type="reset"><i class="bi bi-x-circle me-1"></i> Clear</button>
    </div>
</form>
<div class="sub-table-wrap">
    <table class="emp-table">
        <thead>
            <tr>
                <th>Empno</th>
                <th>Institute</th>
                <th>Exam</th>
                <th>Year</th>
                <th>Board</th>
                <th>Marks</th>
                <th>Subject</th>
                <th style="width:70px;">Action</th>
            </tr>
        </thead>
        <tbody id="tbl_edu"></tbody>
    </table>

</div>
<script>
    // With this:
    loadEduRows();
    initSelect2();

    function initSelect2() {
        $('#passed_exam').select2({
            placeholder: 'Select Exam',
            ajax: {
                url: '/api/getPassedExams',
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.passed_exam,
                                text: item.passed_exam + (item.passed_exam ? ' (' + item
                                    .passed_exam + ')' : '')
                            };
                        })
                    };
                }
            }
        });
    }

    $(document).on('submit', '#frmEdu', function(e) {
        e.preventDefault();

        const empno = $(this).find('[name="empno"]').val();

        if (!empno) {
            Swal.fire('Error', 'Employee number not found. Please save personal info first.', 'error');
            return;
        }

        const qualificationId = $(this).find('[name="qualification_id"]').val();
        const fd = {
            empno: empno,
            name_of_ins: $(this).find('[name="name_of_ins"]').val(),
            passed_exam: $('#passed_exam').val(),
            division: $(this).find('[name="division"]').val(),
            year: $(this).find('[name="year"]').val(),
            board: $(this).find('[name="board"]').val(),
            marks: $(this).find('[name="marks"]').val(),
            subject: $(this).find('[name="subject"]').val(),
        };

        const ajaxOptions = {
            url: qualificationId ? `/api/updateEmpQualification/${qualificationId}` :
                '/api/saveEmpQualification',
            method: qualificationId ? 'PUT' : 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(fd),
            dataType: 'json',
            success: function(res) {
                Swal.fire('Success!', res.message, 'success');
                loadEduRows();
                resetEduForm();
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.errors ?
                    Object.values(xhr.responseJSON.errors).flat().join('\n') :
                    (xhr.responseJSON?.message || 'An error occurred.');
                Swal.fire('Error', msg, 'error');
            }
        };

        $.ajax(ajaxOptions);
    });

    function loadEduRows() {
        const empno = $('#frmEdu [name="empno"]').val();
        if (!empno) return;

        $.get("{{ URL::to('api/getEmpQualifications') }}/" + empno, function(res) {
            const rows = (res.success && Array.isArray(res.data) ? res.data : []).map(function(item) {
                return '<tr>' +
                    '<td>' + (item.empno || '') + '</td>' +
                    '<td>' + (item.name_of_ins || '') + '</td>' +
                    '<td>' + (item.passed_exam || '') + '</td>' +
                    '<td>' + (item.year || '') + '</td>' +
                    '<td>' + (item.board || '') + '</td>' +
                    '<td>' + (item.marks || '') + '</td>' +
                    '<td>' + (item.subject || '') + '</td>' +
                    '<td class="text-center">' +
                    '<button type="button" class="btn btn-sm btn-secondary btn-edit-edu me-1" data-item="' +
                    encodeURIComponent(JSON.stringify(item)) +
                    '" title="Edit"><i class="bi bi-pencil-fill"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-danger btn-delete-edu"id="btnDelete" data-id="' +
                    (
                        item.id || item.slno || item.SLNO || '') +
                    '" title="Delete"><i class="bi bi-trash-fill"></i></button>' +
                    '</td>' +
                    '</tr>';
            }).join('');

            $('#tbl_edu').html(rows ||
                '<tr><td colspan="8" class="text-center">No education records found.</td></tr>');
        }, 'json');
    }

    function fillEduForm(item) {
        $('#frmEdu [name="qualification_id"]').val(item.id || item.slno || item.SLNO || '');
        $('#frmEdu [name="name_of_ins"]').val(item.name_of_ins || '');
        $('#passed_exam').val(item.passed_exam || '').trigger('change');
        $('#frmEdu [name="division"]').val(item.division || '');
        $('#frmEdu [name="year"]').val(item.year || '');
        $('#frmEdu [name="board"]').val(item.board || '');
        $('#frmEdu [name="marks"]').val(item.marks || '');
        $('#frmEdu [name="subject"]').val(item.subject || '');
        $('#frmEdu button[type="submit"]').html('<i class="bi bi-pencil-square me-1"></i> Update');
    }

    function resetEduForm() {
        $('#frmEdu')[0].reset();
        $('#passed_exam').val(null).trigger('change');
        $('#frmEdu [name="qualification_id"]').val('');
        $('#frmEdu button[type="submit"]').html('<i class="bi bi-plus-circle me-1"></i> Add');
    }

    $(document).on('click', '.btn-edit-edu', function() {
        const item = JSON.parse(decodeURIComponent($(this).attr('data-item')) || '{}');
        if (!item) return;
        fillEduForm(item);
    });

    $(document).on('click', '#btnDelete', function() {
        const id = $(this).data('id');
        if (!id) return;

        Swal.fire({
            title: 'Delete record?',
            text: 'This qualification record will be removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: `/api/deleteEmpQualification/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire('Deleted!', response.message, 'success');
                    loadEduRows();
                    if ($('#frmEdu [name="qualification_id"]').val() === String(id)) {
                        resetEduForm();
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred.',
                        'error');
                }
            });
        });
    });

    setTimeout(function() {
        if (window.initDatePick) initDatePick(document);
    }, 50);


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
