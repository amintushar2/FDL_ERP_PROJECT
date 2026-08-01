{{-- resources/views/hrm/tabs/tab_experience.blade.php --}}
<div class="page-heading"><i class="bi bi-buildings-fill"></i> Experience</div>
<form id="frmExp">@csrf<input type="hidden" name="empno" value="{{ $empno }}"><input type="hidden"
        name="work_exp_id" value="">
    <div class="sec-card">
        <div class="sec-card-head"><i class="bi bi-briefcase-fill"></i> Add Experience Record</div>
        <div class="sec-card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Organization:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="organization"
                                placeholder="Organization Name"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Prev Emp ID:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="prv_emp_no"
                                placeholder="Previous Employee ID"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Designation:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="designation"
                                placeholder="Designation"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Address:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="org_address" placeholder="Organization Address"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Tel No:</label>
                        <div class="col-sm-8"><input type="tel" class="form-control" name="org_tel"
                                placeholder="Telephone"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Last Salary:</label>
                        <div class="col-sm-8"><input type="number" class="form-control" name="last_sal_drawn"
                                placeholder="Amount"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">From:</label>
                        <div class="col-sm-8"><input type="text" class="form-control date-pick" name="d_from"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">To:</label>
                        <div class="col-sm-8"><input type="text" class="form-control date-pick" name="d_to"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Total Years:</label>
                        <div class="col-sm-8"><input type="number" class="form-control" name="total_years"
                                placeholder="Years"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Leave Reason:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="leave_reason" placeholder="Reason for leaving"></textarea>
                        </div>
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
                <th>Organization</th>
                <th>Designation</th>
                <th>From</th>
                <th>To</th>
                <th>Years</th>
                <th>Last Salary</th>
                <th style="width:70px;">Action</th>
            </tr>
        </thead>
        <tbody id="tbl_exp"></tbody>
    </table>
</div>
<script>
    $(function() {
        loadExpRows();

        $('#frmExp').on('submit', function(e) {
            e.preventDefault();

            const fd = {};
            $(this).serializeArray().forEach(f => fd[f.name] = f.value);
            fd.empno = fd.empno || $('#frmExp [name="empno"]').val();

            const workExpId = $('#frmExp [name="work_exp_id"]').val();
            const ajaxOptions = {
                url: workExpId ? `/api/updateEmpWorkExp/${workExpId}` : '/api/saveEmpWorkExp',
                method: workExpId ? 'PUT' : 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify(fd),
                dataType: 'json',
                success: res => {
                    swalOk(res.message);
                    loadExpRows();
                    resetExpForm();
                },
                error: swalErr
            };

            $.ajax(ajaxOptions);
        });

        $('#frmExp').on('reset', function() {
            resetExpForm();
            setTimeout(function() {
                if (window.initDatePick) initDatePick(document);
            }, 50);
        });
    });

    function loadExpRows() {
        const empno = $('#frmExp [name="empno"]').val();
        if (!empno) return;

        $.get("{{ URL::to('api/getEmpWorkExperience') }}" + '/' + empno, function(res) {
            const rows = (res.success && Array.isArray(res.data) ? res.data : []).map(function(item) {
                const recordId = item.id || item.slno || item.SLNO || '';
                return '<tr>' +
                    '<td>' + (item.empno || '') + '</td>' +
                    '<td>' + (item.organization || '') + '</td>' +
                    '<td>' + (item.designation || '') + '</td>' +
                    '<td>' + (item.d_from || '') + '</td>' +
                    '<td>' + (item.d_to || '') + '</td>' +
                    '<td>' + (item.total_days || '') + '</td>' +
                    '<td>' + (item.last_sal_drawn || '') + '</td>' +
                    '<td class="text-center">' +
                    '<button type="button" class="btn btn-sm btn-secondary btn-edit-exp me-1" data-item="' +
                    encodeURIComponent(JSON.stringify(item)) +
                    '" title="Edit"><i class="bi bi-pencil-fill"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-danger btn-delete-exp" data-id="' +
                    recordId + '" title="Delete"><i class="bi bi-trash-fill"></i></button>' +
                    '</td>' +
                    '</tr>';
            }).join('');

            $('#tbl_exp').html(rows ||
                '<tr><td colspan="8" class="text-center">No experience records found.</td></tr>');
        }, 'json');

        setTimeout(function() {
            if (window.initDatePick) initDatePick(document);
        }, 50);
    }

    function fillExpForm(item) {
        $('#frmExp [name="work_exp_id"]').val(item.id || item.slno || item.SLNO || '');
        $('#frmExp [name="organization"]').val(item.organization || '');
        $('#frmExp [name="prv_emp_no"]').val(item.prv_emp_no || '');
        $('#frmExp [name="designation"]').val(item.designation || '');
        $('#frmExp [name="org_address"]').val(item.org_address || '');
        $('#frmExp [name="org_tel"]').val(item.org_tel || '');
        $('#frmExp [name="last_sal_drawn"]').val(item.last_sal_drawn || '');
        $('#frmExp [name="d_from"]').val(item.d_from || '');
        $('#frmExp [name="d_to"]').val(item.d_to || '');
        $('#frmExp [name="total_years"]').val(item.total_days || '');
        $('#frmExp [name="leave_reason"]').val(item.leave_reason || '');
        $('#frmExp button[type="submit"]').html('<i class="bi bi-pencil-square me-1"></i> Update');
    }

    function resetExpForm() {
        $('#frmExp')[0].reset();
        $('#frmExp [name="work_exp_id"]').val('');
        $('#frmExp button[type="submit"]').html('<i class="bi bi-plus-circle me-1"></i> Add');
    }

    $(document).on('click', '.btn-edit-exp', function() {
        const item = JSON.parse(decodeURIComponent($(this).attr('data-item')) || '{}');
        if (!item) return;
        fillExpForm(item);
    });

    $(document).on('click', '.btn-delete-exp', function() {
        const id = $(this).data('id');
        if (!id) return;

        Swal.fire({
            title: 'Delete experience?',
            text: 'This work experience record will be removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: `/api/deleteEmpWorkExp/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire('Deleted!', response.message, 'success');
                    loadExpRows();
                    if ($('#frmExp [name="work_exp_id"]').val() === String(id)) {
                        resetExpForm();
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

                if (typeof flatpickr !== 'undefined') {
                    flatpickr(el, {
                        dateFormat: 'd-m-Y',
                        allowInput: true,
                        disableMobile: true,
                        onReady: function(_, __, fp) {
                            fp.calendarContainer && fp.calendarContainer.classList.add('fp-sm');
                        }
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
            var raw = el.value.replace(/\D/g, ''); // digits only
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
                return; // don't reformat partial or already-formatted
            }

            var d = parseInt(day, 10),
                m = parseInt(mon, 10),
                y = parseInt(yr, 10);
            if (d < 1 || d > 31 || m < 1 || m > 12 || y < 1900 || y > 2099) return;

            var formatted = day + '-' + mon + '-' + yr;
            el.value = formatted;
            if (el._flatpickr) el._flatpickr.setDate(formatted, false, 'd-m-Y');
        }

        document.addEventListener('DOMContentLoaded', function() {
            initDatePick(document);
        });

        window.initDatePick = initDatePick;
    })();
</script>
