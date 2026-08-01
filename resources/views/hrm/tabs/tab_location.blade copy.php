{{-- resources/views/hrm/tabs/tab_location.blade.php --}}
@php $loc = optional(optional($emp)->getemploc?->first()); @endphp

<form id="frmLocation">
    @csrf
    <input type="hidden" name="empno" value="{{ $empno }}">
    <div class="page-heading"><i class="bi bi-geo-alt-fill"></i> Location Information</div>

    <div class="sec-card">
        <div class="sec-card-head"><i class="bi bi-house"></i> Permanent Address</div>
        <div class="sec-card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Address:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="p_address"
                                value="{{ $loc->p_address ?? '' }}" placeholder="Street / Area"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Village:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="p_village"
                                value="{{ $loc->p_village ?? '' }}"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Post Office:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="p_post_office"
                                value="{{ $loc->p_post_off ?? '' }}"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Thana/P.S:</label>
                        <div class="col-sm-8">
                            <select class="form-select lov" name="p_police_station11" id="loc_thana"
                                data-lov="/lov/thana" data-val="{{ $loc->p_police_station ?? '' }}"
                                data-txt="{{ $loc->p_police_station ?? '' }}" data-ph="Search Thana…"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">City:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="p_city"
                                value="{{ $loc->p_city ?? '' }}"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">District:</label>
                        <div class="col-sm-8">
                            <select class="form-select lov" name="p_district2" id="loc_pdist" data-lov="/lov/district"
                                data-val="{{ $loc->p_district ?? '' }}" data-txt="{{ $loc->p_district ?? '' }}"
                                data-ph="Search District…"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Phone:</label>
                        <div class="col-sm-8"><input type="tel" class="form-control" name="p_phone"
                                value="{{ $loc->p_phone ?? '' }}"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Fax:</label>
                        <div class="col-sm-8"><input type="tel" class="form-control" name="p_fax"
                                value="{{ $loc->p_fax ?? '' }}"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">PIN Code:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="p_pin_code"
                                value="{{ $loc->p_pin_code ?? '' }}"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Contact Person:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="p_cperson"
                                value="{{ $loc->p_cperson ?? '' }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sec-card">
        <div class="sec-card-head"><i class="bi bi-building"></i> Present / Residential Address</div>
        <div class="sec-card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Address:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="r_address"
                                value="{{ $loc->r_address ?? '' }}"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">City:</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="r_city"
                                value="{{ $loc->r_city ?? '' }}"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">District:</label>
                        <div class="col-sm-8">
                            <select class="form-select lov" name="r_district" id="loc_rdist"
                                data-lov="/lov/district" data-val="{{ $loc->r_district ?? '' }}"
                                data-txt="{{ $loc->r_district ?? '' }}" data-ph="Search District…"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Phone:</label>
                        <div class="col-sm-8"><input type="tel" class="form-control" name="r_phone"
                                value="{{ $loc->r_phone ?? '' }}"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Mobile:</label>
                        <div class="col-sm-8"><input type="tel" class="form-control" name="r_mobile"
                                value="{{ $loc->r_mobile ?? '' }}"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Fax:</label>
                        <div class="col-sm-8"><input type="tel" class="form-control" name="r_fax"
                                value="{{ $loc->r_fax ?? '' }}"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Email:</label>
                        <div class="col-sm-8"><input type="email" class="form-control" name="r_email"
                                value="{{ $loc->r_email ?? '' }}"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row p-1"><label class="col-sm-4 col-form-label">Contact:</label>
                        <div class="col-sm-8"><input type="tel" class="form-control" name="r_cperson"
                                value="{{ $loc->r_cperson ?? '' }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="action-bar">
        <button class="btn btn-upd" type="submit"><i class="bi bi-check-circle me-1"></i> Save Location</button>
        <button class="btn btn-clr" type="reset"><i class="bi bi-x-circle me-1"></i> Reset</button>
    </div>
</form>

<script>
    $(function() {
        $('#add .lov').each(function() {
            lovSelect(this, $(this).data('lov'), $(this).data('ph'), $(this).data('val'), $(this).data(
                'txt'));
        });
        $('#frmLocation').on('submit', function(e) {
            e.preventDefault();
            const fd = {};
            $(this).serializeArray().forEach(f => fd[f.name] = f.value);
            fd.empno = fd.empno || $('#frmLoc [name="empno"]').val();
            $.ajax({
                url: '/api/saveEmpLocation',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(fd),
                dataType: 'json',
                success: res => swalOk(res.message),
                error: swalErr
            });
        });
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
