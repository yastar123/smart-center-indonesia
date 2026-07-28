
const _processUrl         = """;
const _csrf               = """;
const _studentSearchUrl   = """;
const _studentDetailBase  = """;
const _studentUpdateBase  = """;
let _credData = {};

function showStep(step) {
    const stepNum = Number(step);
    document.querySelectorAll('.pw-panel').forEach(panel => {
        panel.classList.toggle('active', Number(panel.dataset.step) === stepNum);
    });
    document.querySelectorAll('.pw-stepper-item').forEach((item, i) => {
        const current = i + 1;
        item.classList.toggle('active', current === stepNum);
        item.classList.toggle('completed', current < stepNum);
    });
    if (stepNum === 2) populateMapelGuruFromPreviousSteps();
    if (stepNum === 3) {
        selectPaymentMethod(document.getElementById('paymentMethodInput')?.value || 'prabayar');
    }
    if (stepNum === 4) {
        buildPreview();
    }
}

document.querySelectorAll('[data-action="next"]').forEach(btn => {
    btn.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        const current = document.querySelector('.pw-panel.active');
        const next = parseInt(current?.dataset.step || '0', 10) + 1;
        if (current && current.dataset.step === '1' && next === 2) {
            try { populatePackageFieldsFromStudentInfo(); } catch (e) {}
        }
        if (next <= 4) {
            const activeForm = document.getElementById('processForm');
            if (activeForm) {
                activeForm.querySelectorAll('input, select, textarea').forEach(el => {
                    if (el.hasAttribute('required')) {
                        el.removeAttribute('required');
                    }
                });
            }
            try { showStep(next); } catch (e) {}
        }
    });
});
document.querySelectorAll('[data-action="prev"]').forEach(btn => {
    btn.addEventListener('click', () => {
        const current = document.querySelector('.pw-panel.active');
        const prev = parseInt(current.dataset.step, 10) - 1;
        if (prev >= 1) showStep(prev);
    });
});

// ── Sinkronisasi baris jadwal saat mapel dicentang/tidak ──────────────────────
function updateScheduleEmpty() {
    const visible = document.querySelectorAll('.pw-sched-tr:not([style*="display:none"]):not([style*="display: none"])');
    const scheduleWrapper = document.getElementById('scheduleTableWrapper');
    const emptyMsg = document.getElementById('scheduleEmptyMsg');
    if (visible.length === 0) {
        if (scheduleWrapper) scheduleWrapper.style.display = 'none';
        if (emptyMsg) emptyMsg.style.display = '';
    } else {
        if (scheduleWrapper) scheduleWrapper.style.display = '';
        if (emptyMsg) emptyMsg.style.display = 'none';
    }
}

function recalcTotal() {
    let total = 0, totalHonor = 0, totalSesi = 0;
    document.querySelectorAll('.course-check').forEach(chk => {
        const row = chk.closest('.pw-course-row');
        row.classList.toggle('disabled', !chk.checked);
        row.querySelectorAll('select, input').forEach(el => { if (el !== chk) el.disabled = !chk.checked; });
        // Sync matching schedule row visibility
        const schedRow = document.querySelector(`.pw-sched-tr[data-schedule-row="";
        if (schedRow) schedRow.style.display = chk.checked ? '' : 'none';
        if (chk.checked) {
            const feeInput   = row.querySelector('.fee-input');
            const honorInput = row.querySelector('.honor-input');
            const honorToggle = row.querySelector('.course-use-honor');
            const sesiInput  = row.querySelector('input[name^="course_sessions"]');
            const sesi       = parseInt(sesiInput?.value || 0, 10);
            total      += parseFloat(feeInput?.value   || 0);
            const useHonor = !!(honorToggle && honorToggle.checked);
            totalHonor += useHonor ? (parseFloat(honorInput?.value || 0) * sesi) : 0;
            totalSesi  += sesi;
        }
    });
    document.getElementById('totalBiaya').value = total || 0;
    const margin = total - totalHonor;
    const pct    = total > 0 ? Math.round((margin / total) * 100) : 0;
    const fmt    = v => 'Rp' + Number(v).toLocaleString('id-ID');
    if (document.getElementById('summaryTotalSesi'))  document.getElementById('summaryTotalSesi').textContent  = totalSesi || 0;
    if (document.getElementById('summaryTotalFee'))   document.getElementById('summaryTotalFee').textContent   = fmt(total);
    if (document.getElementById('summaryTotalHonor')) document.getElementById('summaryTotalHonor').textContent = fmt(totalHonor);
    if (document.getElementById('summaryMargin')) {
        document.getElementById('summaryMargin').childNodes[0].textContent = fmt(margin) + ' ';
        document.getElementById('summaryMarginPct').textContent = '(' + pct + '%)';
    }
    updateScheduleEmpty();
    renumberScheduleRows();
}

// Update a single row's margin display when fee or honor changes
function updateRowMargin(input) {
    const row    = input.closest('.pw-course-row');
    const fee    = parseFloat(row.querySelector('.fee-input')?.value   || 0);
    const honor  = parseFloat(row.querySelector('.honor-input')?.value || 0); // honor per sesi
    const honorToggle = row.querySelector('.course-use-honor');
    const sesi   = parseInt(row.querySelector('input[name^="course_sessions"]')?.value || 0, 10);
    const useHonor = !!(honorToggle && honorToggle.checked);
    const margin = fee - (useHonor ? (honor * sesi) : 0);
    const pct    = fee > 0 ? Math.round((margin / fee) * 100) : 0;
    const display = row.querySelector('.margin-display');
    if (display) {
        display.innerHTML = '<div class="fw-semibold" style="color:' + (margin >= 0 ? '#10b981' : '#dc2626') + '">Rp' + margin.toLocaleString('id-ID') + '</div>' +
            '<div class="text-muted" style="font-size:.78rem">(' + pct + '%)</div>';
        display.style.background = margin >= 0 ? 'rgba(16,185,129,.07)' : 'rgba(220,38,38,.07)';
        display.style.borderColor = margin >= 0 ? 'rgba(16,185,129,.2)' : 'rgba(220,38,38,.2)';
    }
    // update contract addition (if any) and totals
    updateContractAddition(row);
    recalcTotal();
}

// ── Rooms list (for schedule dropdown) ────────────────────────────────────────
const _roomsList = ";
const _roomOptions = '<option value="">— Pilih ruang —</option>' +
    _roomsList.map(r => `<option value="" ? ' (' + r.kapasitas + ' org)' : ''}</option>`).join('');

// ── Guru name sync: Card A guru-select → Card B table display ──────────────────
function syncGuruName(courseId) {
    const sel = document.querySelector(`.guru-select[data-course-id="";
    if (!sel) return;
    const chosen = sel.selectedOptions?.[0] || sel.options[sel.selectedIndex];
    const guruText = chosen && chosen.value ? chosen.textContent.trim() : '—';
    const spans = document.querySelectorAll(`.sched-guru-name[data-course-id="";
    if (!spans.length) return;
    spans.forEach(span => span.textContent = guruText || '—');
}

function syncAllGuruNames() {
    document.querySelectorAll('.guru-select[data-course-id]').forEach(el => {
        const courseId = el.dataset.courseId;
        if (courseId) syncGuruName(courseId);
    });
}

function renderTeacherSchedule(courseId, schedules, guruText) {
    const card = document.getElementById('conflict-result-' + courseId);
    if (!card) return;
    const work = card.querySelector('.teacher-schedule-list');
    if (!work) return;
    const teacherLabel = guruText ? guruText : 'Guru belum dipilih';
    if (!schedules || !schedules.length) {
        work.innerHTML = '<div><strong>Jadwal guru:</strong> ' + teacherLabel + '</div>' +
            '<div class="text-muted" style="font-size:.78rem">Belum ada jadwal terdaftar untuk guru ini.</div>';
        return;
    }
    work.innerHTML = '<div><strong>Jadwal guru:</strong> ' + teacherLabel + '</div>' +
        schedules.map(item => {
            const tanggal = item.tanggal ? item.tanggal + ' • ' + item.hari : item.hari || 'Tanpa tanggal';
            const subjek = item.mata_pelajaran || 'Mata pelajaran tidak tersedia';
            const kelas = item.kelas ? ' • ' + item.kelas : '';
            const topik = item.topik ? ' • ' + item.topik : '';
            return `<div class="border rounded-2 p-2 mt-2" style="background:rgba(248,250,252,.9);font-size:.78rem">` +
                `<div><strong>" +
                `<div>" +
                `<div>" +
                `</div>`;
        }).join('');
}

// ── Renumber visible schedule rows ─────────────────────────────────────────────
function renumberScheduleRows() {
    let n = 1;
    document.querySelectorAll('#scheduleRowsContainer .pw-sched-tr').forEach(tr => {
        const noCell = tr.querySelector('.sched-no');
        if (tr.style.display === 'none') return;
        if (noCell) noCell.textContent = n++;
    });
}

// ── Guru conflict check — conflict panel below Card B ─────────────────────────
const guruConflictCheckUrl = ";

function runConflictCheck(courseId) {
    const guruSelect = document.querySelector(`.guru-select[data-course-id="";
    const hariSelect = document.querySelector(`.hari-select[data-course-id="";
    const jamMulai   = document.querySelector(`.jam-mulai-input[data-course-id="";
    const jamSelesai = document.querySelector(`.jam-selesai-input[data-course-id="";
    const warningBox = document.querySelector(`.conflict-warning-box[data-course-id="";
    if (!warningBox) return;
    const guruId  = guruSelect?.value;
    const hari    = hariSelect?.value;
    const mulai   = jamMulai?.value;
    const selesai = jamSelesai?.value;
    const guruText = guruSelect?.selectedOptions?.[0]?.textContent.trim() || '—';
    if (!guruId) {
        warningBox.innerHTML = '<span class="text-muted" style="font-size:.78rem">Pilih guru terlebih dahulu untuk melihat jadwal.</span>';
        renderTeacherSchedule(courseId, [], guruText);
        return;
    }
    warningBox.innerHTML = '<span class="text-muted"><i class="bi bi-hourglass-split me-1"></i>Mengecek…</span>';
    fetch(guruConflictCheckUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '', 'Accept': 'application/json' },
        body: JSON.stringify({ guru_id: guruId, hari, jam_mulai: mulai, jam_selesai: selesai }),
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(data => {
        if (data.conflict) {
            warningBox.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>";
        } else if (hari === '' || !mulai || !selesai) {
            warningBox.innerHTML = '<span class="text-muted" style="font-size:.78rem">Pilih hari & jam untuk memeriksa bentrok. Jadwal guru tetap ditampilkan di bawah.</span>';
        } else {
            warningBox.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Guru tersedia.</span>';
        }
        renderTeacherSchedule(courseId, data.schedules || [], guruText);
    })
    .catch(() => {
        warningBox.innerHTML = '<span class="text-muted">Gagal mengecek.</span>';
        renderTeacherSchedule(courseId, [], guruText);
    });
}

function runAllConflictChecks() {
    // Iterate ALL conflict-warning-boxes that belong to currently checked courses
    const checkedCourseIds = new Set(
        Array.from(document.querySelectorAll('.course-check:checked')).map(el => el.value)
    );
    const boxes = document.querySelectorAll('.conflict-warning-box[data-course-id]');
    let ran = 0;
    boxes.forEach(box => {
        const cid = box.dataset.courseId;
        if (checkedCourseIds.has(cid)) {
            runConflictCheck(cid);
            ran++;
        }
    });
    if (ran === 0) {
        showToast('Pilih setidaknya satu mata pelajaran dan lengkapi jadwal (guru, hari & jam) untuk mengecek konflik.', 'warning');
    }
}

function bindGuruConflictEvents(courseId) {
    const guruSelect = document.querySelector(`.guru-select[data-course-id="";
    const hariSelect = document.querySelector(`.hari-select[data-course-id="";
    const jamMulai   = document.querySelector(`.jam-mulai-input[data-course-id="";
    const jamSelesai = document.querySelector(`.jam-selesai-input[data-course-id="";
    if (!guruSelect || !hariSelect || !jamMulai || !jamSelesai) return;
    const runCheck = () => runConflictCheck(courseId);
    [guruSelect, hariSelect, jamMulai, jamSelesai].forEach(el => el.addEventListener('change', runCheck));
    // Sync guru name to Card B on guru change
    guruSelect.addEventListener('change', () => syncGuruName(courseId));
}

function applyTeacherContractMeta(selectEl) {
    const row = selectEl.closest('.pw-course-row');
    if (!row) return;
    const infoBox = row.querySelector('.teacher-contract-info');
    const honorInput = row.querySelector('.honor-input');
    const honorToggle = row.querySelector('.course-use-honor');
    const honorHelp = row.querySelector('.honor-help');
    const sesiInput = row.querySelector('input[name^="course_sessions"]');
    const selected = selectEl.selectedOptions[0];
    const jenisGuru = (selected?.dataset.jenisGuru || '').toLowerCase();
    const salaryBase = parseFloat(selected?.dataset.salaryBase || 0);
    const sesi = Math.max(parseInt(sesiInput?.value || 8, 10), 1);
    const isContract = jenisGuru === 'kontrak' && salaryBase > 0;

    if (infoBox) {
        if (isContract) {
            infoBox.innerHTML = '<span class="badge rounded-pill me-1" style="background:rgba(14,165,233,.10);color:#0369a1;border:1px solid rgba(14,165,233,.35);font-size:.66rem;padding:.25em .6em">Jenis: Kontrak</span>' +
                '<span class="badge rounded-pill" style="background:rgba(16,185,129,.10);color:#047857;border:1px solid rgba(16,185,129,.35);font-size:.66rem;padding:.25em .6em">Gaji Bulanan: Rp ' + Number(salaryBase).toLocaleString('id-ID') + '</span>';
        } else if (jenisGuru === 'freelance') {
            infoBox.innerHTML = '<span class="badge rounded-pill" style="background:rgba(246,175,35,.12);color:#8a5e00;border:1px solid rgba(246,175,35,.35);font-size:.66rem;padding:.25em .6em">Jenis: Freelance</span>';
        } else {
            infoBox.textContent = '';
        }
    }

    if (honorToggle) {
        honorToggle.disabled = !isContract;
        if (!isContract) honorToggle.checked = false;
    }

    if (honorInput) {
        if (isContract) {
            honorInput.disabled = !honorToggle?.checked;
            if (!honorInput.value || parseFloat(honorInput.value) <= 0) {
                honorInput.value = Math.round(salaryBase / sesi);
            }
        } else {
            honorInput.disabled = true;
        }
    }

    if (honorHelp) {
        honorHelp.style.display = isContract ? '' : 'none';
    }

    updateContractAddition(row);
    recalcTotal();
}

function updateContractAddition(row) {
    if (!row) return;
    const sel = row.querySelector('.guru-select');
    const honorInput = row.querySelector('.honor-input');
    const honorToggle = row.querySelector('.course-use-honor');
    const sesiInput = row.querySelector('input[name^="course_sessions"]');
    const addBoxClass = 'contract-salary-add';
    let addBox = row.querySelector('.' + addBoxClass);
    const selected = sel && sel.selectedOptions ? sel.selectedOptions[0] : null;
    const jenisGuru = (selected?.dataset.jenisGuru || '').toLowerCase();
    const salaryBase = parseFloat(selected?.dataset.salaryBase || 0);
    const honorPerSesi = parseFloat(honorInput?.value || 0);
    const sesi = Math.max(parseInt(sesiInput?.value || 0, 10), 0);
    const useHonor = !!(honorToggle && honorToggle.checked);
    const tambahan = useHonor ? (honorPerSesi * sesi) : 0;

    if (jenisGuru === 'kontrak' && salaryBase > 0 && useHonor && tambahan > 0) {
        if (!addBox) {
            addBox = document.createElement('div');
            addBox.className = addBoxClass + ' mt-2 text-muted';
            addBox.style.fontSize = '.68rem';
            const infoContainer = row.querySelector('.teacher-contract-info');
            if (infoContainer) infoContainer.insertAdjacentElement('afterend', addBox);
        }
        addBox.innerHTML = '<strong>Tambahan ke gaji bulanan:</strong> Rp ' + Number(tambahan).toLocaleString('id-ID');
    } else if (addBox) {
        addBox.remove();
    }
}

function syncExistingClassDetail(selectEl) {
    const row = selectEl.closest('.pw-course-row');
    const detailBox = row?.querySelector('.existing-class-detail');
    if (!detailBox) return;
    const selected = selectEl.selectedOptions[0];
    if (!selected || !selected.value) {
        detailBox.innerHTML = '<span class="text-muted">Tidak ada kelas aktif dipilih. Sistem akan membuat kelas baru untuk mata pelajaran ini.</span>';
        return;
    }

    const className = selected.dataset.className || 'Kelas Aktif';
    const guruName = selected.dataset.guruName || '—';
    const studentCount = selected.dataset.studentCount || '0';
    const studentNames = (selected.dataset.studentNames || '').trim();
    const scheduleCount = selected.dataset.scheduleCount || '0';
    const classType = selected.dataset.classType || '—';
    detailBox.innerHTML = '<span class="badge rounded-pill" style="background:rgba(16,185,129,.10);color:#047857;border:1px solid rgba(16,185,129,.35);font-size:.66rem;padding:.25em .6em">' + className + '</span>' +
        '<div class="mt-1">Guru: ' + guruName + ' • Siswa: ' + studentCount + ' • Jenis: ' + classType + '</div>' +
        '<div class="mt-1 text-muted">Jadwal terdaftar: ' + scheduleCount + ' • Siswa yang sudah ada: ' + (studentNames || 'Belum ada') + '</div>' +
        '<div class="mt-1"><span class="badge rounded-pill" style="background:rgba(37,99,235,.10);color:#1d4ed8;border:1px solid rgba(37,99,235,.24);font-size:.64rem;padding:.25em .55em">Pakai kelas ini</span></div>';
}

function renderActiveClassInsights() {
    document.querySelectorAll('#conflictResultsPanel .conflict-card').forEach(card => {
        const summary = card.querySelector('.active-class-summary');
        if (!summary) return;
        let items = [];
        try {
            items = JSON.parse(card.dataset.activeClasses || '[]');
        } catch (e) {
            items = [];
        }

        if (!items.length) {
            summary.innerHTML = '<div class="text-muted" style="font-size:.74rem">Belum ada kelas aktif untuk mata pelajaran ini. Sistem akan membuat kelas baru saat pendaftaran selesai.</div>';
            return;
        }

        summary.innerHTML = items.map(item => {
            const remaining = Math.max(0, (Number(item.total_sessions || 0) - Number(item.scheduled_sessions || 0)));
            const studentNames = Array.isArray(item.student_names) ? item.student_names.filter(Boolean) : [];
            const studentText = studentNames.length
                ? studentNames.join(', ')
                : 'Belum ada siswa terdaftar.';
            return '<div class="border rounded-3 p-2 mb-2" style="background:rgba(16,185,129,.05);border-color:rgba(16,185,129,.2)">' +
                '<div class="fw-semibold" style="font-size:.76rem">' + (item.nama_kelas || 'Kelas Aktif') + '</div>' +
                '<div class="mt-1" style="font-size:.72rem">Guru: ' + (item.guru_name || '—') + ' • Jenis: ' + (item.jenis || '—') + '</div>' +
                '<div class="mt-1" style="font-size:.72rem">Siswa (' + (item.siswa_count || 0) + '): ' + studentText + '</div>' +
                '<div class="mt-1 text-muted" style="font-size:.72rem">Sesi total: ' + (item.total_sessions || 0) + ' • Sisa sesi: ' + remaining + '</div>' +
                '<div class="mt-2 d-flex gap-2 flex-wrap">' +
                '<button type="button" class="btn btn-sm btn-outline-primary" onclick="selectExistingClass(' + card.dataset.courseId + ', ' + item.id + ')"><i class="bi bi-check2-circle me-1"></i>Pakai kelas ini</button>' +
                '<button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearExistingClassSelection(' + card.dataset.courseId + ')"><i class="bi bi-plus-circle me-1"></i>Buat kelas baru</button>' +
                '</div></div>';
        }).join('');
    });
}

function selectExistingClass(courseId, classId) {
    const select = document.querySelector('.existing-class-select[data-course-id="' + courseId + '"]');
    if (!select) return;
    select.value = classId;
    select.dispatchEvent(new Event('change'));
    syncExistingClassDetail(select);
    showToast('Kelas aktif dipilih untuk mata pelajaran ini.', 'success');
}

function clearExistingClassSelection(courseId) {
    const select = document.querySelector('.existing-class-select[data-course-id="' + courseId + '"]');
    if (!select) return;
    select.value = '';
    select.dispatchEvent(new Event('change'));
    syncExistingClassDetail(select);
    showToast('Sistem akan membuat kelas baru untuk mata pelajaran ini.', 'info');
}

function bindCourseRowEvents(row) {
    row.querySelectorAll('.course-check, .fee-input, input[name^="course_sessions"], .honor-input, .course-use-honor').forEach(el => el.addEventListener('input', recalcTotal));
    row.querySelectorAll('.course-check, .fee-input, input[name^="course_sessions"], .honor-input, .course-use-honor').forEach(el => el.addEventListener('change', recalcTotal));
    row.querySelectorAll('.course-check').forEach(el => el.addEventListener('change', recalcTotal));
    const courseId = row.dataset.courseRow;
    const guruSelect = row.querySelector('.guru-select');
    const classSelect = row.querySelector('.existing-class-select');
    const honorToggle = row.querySelector('.course-use-honor');
    if (guruSelect) {
        guruSelect.addEventListener('change', () => {
            applyTeacherContractMeta(guruSelect);
            syncGuruName(courseId);
        });
        applyTeacherContractMeta(guruSelect);
        syncGuruName(courseId);
    }
    if (classSelect) {
        classSelect.addEventListener('change', () => syncExistingClassDetail(classSelect));
    }
    if (honorToggle) {
        honorToggle.addEventListener('change', () => {
            const honorInput = row.querySelector('.honor-input');
            if (honorInput) honorInput.disabled = !honorToggle.checked;
            updateContractAddition(row);
            recalcTotal();
        });
    }
    bindGuruConflictEvents(courseId);
}
document.querySelectorAll('.pw-course-row').forEach(bindCourseRowEvents);
// Ensure all teacher selections sync to schedule row labels immediately.
document.querySelectorAll('.guru-select').forEach(el => {
    const courseId = el.dataset.courseId;
    if (!courseId) return;
    el.addEventListener('change', () => syncGuruName(courseId));
    syncGuruName(courseId);
});
    syncAllGuruNames();
const courseMetaList = ";
const courseMetaMap = {};
courseMetaList.forEach(c => courseMetaMap[c.id] = c);
const usedCourseIds = new Set(");

const packageList = ";
const packageMetaMap = {};
packageList.forEach(p => packageMetaMap[p.id] = p);

function formatTeacherOptionLabel(t) {
    const jenis = (t && t.jenis_guru) ? t.jenis_guru.toString().toLowerCase() : '';
    const salary = Number(t && t.salary_base ? t.salary_base : 0);
    const jenisLabel = jenis ? ' • ' + jenis.charAt(0).toUpperCase() + jenis.slice(1) : '';
    const salaryLabel = jenis === 'kontrak' && salary > 0 ? ' • Gaji: Rp ' + Number(salary).toLocaleString('id-ID') : '';
    return `" || ''}";
}

function refreshExtraCourseSelect() {
    const sel = document.getElementById('extraCourseSelect');
    const available = courseMetaList.filter(c => !usedCourseIds.has(c.id));
    sel.innerHTML = '<option value="">— Pilih mata pelajaran —</option>' +
        available.map(c => `<option value="";
    document.getElementById('extraCourseEmptyMsg').style.display = available.length === 0 ? '' : 'none';
}

// Builds Card A (mapel + guru) row
function buildCourseRow(course, isAdmin) {
    const row = document.createElement('div');
    row.className = 'pw-course-row';
    row.dataset.courseRow = course.id;
    const guruOptions = course.guru.map(t => `<option value="" data-jenis-guru="" || ''}" data-salary-base="" || 0)}">";
    const moduleOptions = (course.modules || []).map(m => `<option value="";
    const fee    = parseFloat(course.fee) || 0;
    const sesiDef = 8;
    const honor  = Math.round((fee * 0.6) / sesiDef); // honor per sesi
    const margin = fee - (honor * sesiDef);
    const pct    = fee > 0 ? Math.round((margin / fee) * 100) : 0;
    row.innerHTML = `
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-2">
                <div class="field-label-mobile fw-semibold text-muted mb-1">Mata Pelajaran</div>
                <div class="form-check">
                    <input class="form-check-input course-check" type="checkbox" name="course_ids[]" value="" id="rowCourse" checked>
                    <label class="form-check-label fw-semibold" for="rowCourse" style="font-size:.82rem">"
                </div>
                <div class="form-text" style="font-size:.67rem">" ? 'Ditambahkan admin' : 'Mapel pilihan siswa'}</div>
            </div>
            <div class="col-12 col-md-2">
                <div class="field-label-mobile fw-semibold text-muted mb-1">Guru Pengajar</div>
                <select class="form-select form-select-sm guru-select" name="course_teacher[" data-course-id=""
                    <option value="">Pilih guru…</option>
                    "
                </select>
                <div class="teacher-contract-info text-muted mt-1" style="font-size:.68rem"></div>
                <div class="mt-2">
                    <label class="form-label fw-semibold" style="font-size:.74rem">Modul</label>
                    <select class="form-select form-select-sm" name="course_module[" multiple style="min-height:120px">
                        "
                    </select>
                    <div class="form-text" style="font-size:.7rem">Pilih satu atau lebih modul.</div>
                </div>
            </div>
            <div class="col-12 col-md-1">
                <div class="field-label-mobile fw-semibold text-muted mb-1">Sesi</div>
                <input type="number" min="1" class="form-control form-control-sm" name="course_sessions[" placeholder="Sesi" value="8">
            </div>
            <div class="col-12 col-md-2">
                <div class="field-label-mobile fw-semibold text-muted mb-1">Biaya Siswa</div>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="number" min="0" class="form-control fee-input" name="course_fee[" value="" oninput="updateRowMargin(this)">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="field-label-mobile fw-semibold text-muted mb-1">Honor Guru / Sesi</div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="input-group input-group-sm flex-grow-1">
                        <span class="input-group-text">Rp</span>
                        <input type="number" min="0" class="form-control honor-input" name="course_honor[" value="" oninput="updateRowMargin(this)" disabled>
                    </div>
                    <div class="form-check form-switch ms-1">
                        <input class="form-check-input course-use-honor" type="checkbox" name="course_use_honor[" value="1">
                        <label class="form-check-label" style="font-size:.68rem">Tambah honor/sesi</label>
                    </div>
                </div>
                <div class="honor-help text-muted mt-1" style="font-size:.68rem;display:none">Honor per sesi dapat ditambahkan sebagai tambahan gaji guru kontrak.</div>
            </div>
            <div class="col-12 col-md-2">
                <div class="field-label-mobile fw-semibold text-muted mb-1">Margin</div>
                <div class="margin-display px-2 py-1 rounded-2 text-center" style="background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.2);font-size:.78rem">
                    <span class="margin-rp fw-semibold" style="color:#10b981">Rp"
                    <span class="margin-pct text-muted ms-1" style="font-size:.7rem">("
                </div>
            </div>
            <div class="col-12 col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCourseRow(this, " title="Hapus mapel ini"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
    return row;
}

// Builds Card B (jadwal kelas) table row — now a <tr> with 7 cols
function buildScheduleRow(course) {
    const tr = document.createElement('tr');
    tr.className = 'pw-sched-tr';
    tr.dataset.scheduleRow = course.id;
    tr.innerHTML = `
        <td class="text-center text-muted sched-no" style="font-size:.78rem">—</td>
        <td><span class="badge rounded-pill" style="background:rgba(200,77,223,.12);color:#461256;font-size:.75rem;font-weight:600;padding:.3em .75em">"
        <td>
            <select class="form-select form-select-sm hari-select" name="schedule_hari[" data-course-id="" style="min-width:88px">
                <option value="">Pilih…</option>
                <option value="1">Senin</option><option value="2">Selasa</option><option value="3">Rabu</option>
                <option value="4">Kamis</option><option value="5">Jum'at</option><option value="6">Sabtu</option><option value="0">Minggu</option>
            </select>
        </td>
        <td><input type="time" class="form-control form-control-sm jam-mulai-input" name="schedule_jam_mulai[" data-course-id="" value="08:00" style="min-width:100px"></td>
        <td><input type="time" class="form-control form-select-sm jam-selesai-input" name="schedule_jam_selesai[" data-course-id="" value="10:00" style="min-width:100px"></td>
        <td class="room-column-cell"><select class="form-select form-select-sm room-select" name="schedule_room[" data-course-id="" style="min-width:140px">";
    return tr;
}

// Builds a conflict result row for Card C panel
function buildConflictResult(course) {
    const div = document.createElement('div');
    div.className = 'd-flex align-items-center gap-2 mb-2 p-2 rounded-3';
    div.id = `conflict-result-";
    div.style.background = 'var(--input-bg)';
    div.innerHTML = `
        <span class="badge rounded-pill flex-shrink-0" style="background:rgba(200,77,223,.12);color:#461256;font-size:.74rem;font-weight:600;padding:.3em .7em;min-width:80px;text-align:center">"
        <div class="conflict-warning-box text-muted" data-course-id="" style="font-size:.8rem">—</div>`;
    return div;
}

function addExtraCourse() {
    const sel = document.getElementById('extraCourseSelect');
    const id = parseInt(sel.value, 10);
    if (!id) return;
    const course = courseMetaMap[id];
    if (!course) return;
    usedCourseIds.add(id);
    const courseRow = buildCourseRow(course, true);
    document.getElementById('courseRowsContainer').appendChild(courseRow);
    const schedRow = buildScheduleRow(course);
    document.getElementById('scheduleRowsContainer').appendChild(schedRow);
    const conflictRow = buildConflictResult(course);
    document.getElementById('conflictResultsPanel').appendChild(conflictRow);
    bindCourseRowEvents(courseRow);
    refreshExtraCourseSelect();
    recalcTotal();
}

function setKelolaKelasMode(mode) {
    const newBtn = document.getElementById('kelolaNewBtn');
    const joinBtn = document.getElementById('kelolaJoinBtn');
    const newPanel = document.getElementById('kelolaNewPanel');
    const joinPanel = document.getElementById('kelolaJoinPanel');
    const conflictWrapper = document.getElementById('conflictCardWrapper');
    if (!newBtn || !joinBtn || !newPanel || !joinPanel) return;

    if (mode === 'join') {
        newBtn.classList.remove('btn-primary');
        newBtn.classList.add('btn-outline-secondary');
        joinBtn.classList.remove('btn-outline-secondary');
        joinBtn.classList.add('btn-primary');
        newPanel.style.display = 'none';
        joinPanel.style.display = '';
        if (conflictWrapper) conflictWrapper.style.display = 'none';
        const scheduleCard = document.getElementById('scheduleCard');
        if (scheduleCard) scheduleCard.style.display = 'none';
    } else {
        newBtn.classList.add('btn-primary');
        newBtn.classList.remove('btn-outline-secondary');
        joinBtn.classList.remove('btn-primary');
        joinBtn.classList.add('btn-outline-secondary');
        newPanel.style.display = '';
        joinPanel.style.display = 'none';
        if (conflictWrapper) conflictWrapper.style.display = '';
        const scheduleCard = document.getElementById('scheduleCard');
        if (scheduleCard) scheduleCard.style.display = '';
    }
}

function removeCourseRow(btn, id) {
    btn.closest('.pw-course-row').remove();
    const schedRow = document.querySelector(`#scheduleRowsContainer .pw-sched-tr[data-schedule-row="";
    if (schedRow) schedRow.remove();
    const conflictRow = document.getElementById(`conflict-result-";
    if (conflictRow) conflictRow.remove();
    usedCourseIds.delete(id);
    refreshExtraCourseSelect();
    recalcTotal();
}

refreshExtraCourseSelect();

// --- Package dropdown ---
const packageDropdown = document.getElementById('packageDropdown');
const packageInfoBox  = document.getElementById('packageInfoBox');

function onPackageDropdownChange() {
    const sel = packageDropdown.selectedOptions[0];
    const val = packageDropdown.value;
    if (val) {
        const price = parseFloat(sel.dataset.harga || 0);
        const pkg = packageMetaMap[val] || packageMetaMap[Number(val)];
        if (pkg) {
            const detailParts = [];
            if (pkg.tipe_kelas) {
                detailParts.push(pkg.tipe_kelas.charAt(0).toUpperCase() + pkg.tipe_kelas.slice(1));
            }
            if (pkg.jumlah_pertemuan) {
                detailParts.push(pkg.jumlah_pertemuan + ' pertemuan');
            }
            const mapelNames = Array.isArray(pkg.mata_pelajaran)
                ? pkg.mata_pelajaran.map(c => c.nama).filter(Boolean)
                : [];
            const mapelText = mapelNames.length
                ? 'Mata Pelajaran: ' + mapelNames.join(', ')
                : 'Mata Pelajaran: —';

            document.getElementById('pkgInfoNama').textContent = pkg.nama;
            document.getElementById('pkgInfoDetail').textContent = detailParts.join(' · ') || 'Detail paket tidak tersedia';
            document.getElementById('pkgInfoExtra').textContent = mapelText;
            document.getElementById('pkgInfoHarga').textContent = 'Rp' + Number(price).toLocaleString('id-ID');
            packageInfoBox.classList.remove('d-none');
            document.getElementById('totalBiaya').value = price;
        } else {
            const text = sel.text;
            const harga = parseFloat(sel.dataset.harga || 0);
            const parts = text.split(' — ');
            document.getElementById('pkgInfoNama').textContent = parts[0] || text;
            document.getElementById('pkgInfoDetail').textContent = parts.slice(1).join(' — ').replace(/·\s*Rp[\d.,]+/, '').trim();
            document.getElementById('pkgInfoExtra').textContent = 'Mata Pelajaran: —';
            document.getElementById('pkgInfoHarga').textContent = 'Rp' + Number(harga).toLocaleString('id-ID');
            packageInfoBox.classList.remove('d-none');
            document.getElementById('totalBiaya').value = harga;
        }
    } else {
        packageInfoBox.classList.add('d-none');
        recalcTotal();
    }
}
packageDropdown.addEventListener('change', onPackageDropdownChange);

function populatePackageFieldsFromStudentInfo() {
    const get = selector => document.querySelector(selector)?.value || '';
    const studentName    = get('[name="name"]').trim();
    const phone          = get('[name="phone"]').trim();
    const program        = get('[name="program"]').trim();
    const system         = get('[name="system"]').trim();
    const educationLevel = get('[name="education_level"]').trim();
    const tempatBelajar  = get('[name="tempat_belajar"]').trim();
    const address        = get('[name="address"]').trim();
    const parentName     = get('[name="parent_name"]').trim();
    const parentPhone    = get('[name="parent_phone"]').trim();
    const birthPlace     = get('[name="birth_place"]').trim();
    const birthDate      = get('[name="birth_date"]').trim();
    const dayInputs      = Array.from(document.querySelectorAll('input[name="hari_belajar[]"]:checked'));
    const days           = dayInputs.map(el => el.value).filter(Boolean);
    const scheduleText   = days.map(day => {
        const slots = Array.from(document.querySelectorAll('input[name="jam_detail[' + day + '][]"]'))
            .map(el => el.value.trim()).filter(Boolean);
        return slots.length ? day + ': ' + slots.join(', ') : day;
    }).filter(Boolean).join('; ');

    const pkgNameEl = document.querySelector('[name="custom_package_name"]');
    const jenisEl   = document.querySelector('[name="custom_jenis"]');
    const sesiEl    = document.querySelector('[name="jumlah_pertemuan"]');
    const metodeEl  = document.querySelector('[name="custom_metode_absensi"]');
    const tipeEl    = document.querySelector('[name="custom_tipe_kelas"]');
    const priceEl   = document.querySelector('[name="custom_package_price"]');
    const statusEl  = document.querySelector('[name="custom_status"]');
    const descEl    = document.querySelector('[name="custom_deskripsi"]');

    if (pkgNameEl && !pkgNameEl.value.trim()) {
        const baseName = studentName ? studentName + ' - ' : '';
        const programLabel = program ? program.charAt(0).toUpperCase() + program.slice(1) + ' ' : '';
        const levelLabel = educationLevel ? educationLevel.replace(/\s+\(.*\)/, '') + ' ' : '';
        pkgNameEl.value = `";
    }

    if (jenisEl && !jenisEl.value) {
        jenisEl.value = program === 'privat' ? 'privat'
            : system === 'online' ? 'online'
            : 'reguler';
    }
    if (sesiEl && (!sesiEl.value || Number(sesiEl.value) <= 0)) {
        sesiEl.value = 8;
    }
    if (metodeEl && !metodeEl.value) {
        metodeEl.value = system === 'online' ? 'otomatis' : 'manual';
    }
    if (tipeEl && !tipeEl.value) {
        tipeEl.value = system === 'online' ? 'online' : (program === 'privat' ? 'private' : 'offline');
    }
    if (priceEl && (!priceEl.value || Number(priceEl.value) <= 0)) {
        priceEl.value = 0;
    }
    if (statusEl && !statusEl.value) {
        statusEl.value = 'aktif';
    }
    if (descEl && !descEl.value.trim()) {
        const details = [program ? `Program " : null,
            system ? `Sistem " : null,
            educationLevel ? educationLevel : null,
            tempatBelajar ? `Tempat " : null,
            scheduleText ? `Jadwal: " : null].filter(Boolean);
        descEl.value = `Paket khusus untuk " || 'siswa ini'}. ` + details.join(' • ') + (parentName ? ` • Orang tua: " ? ' (' + parentPhone + ')' : ''}` : '');
    }
}

function parsePackageSessions() {
    const selectedOption = packageDropdown?.selectedOptions[0];
    if (!selectedOption) return 0;
    const match = selectedOption.text.match(/(\d+)\s*pertemuan/i);
    return match ? parseInt(match[1], 10) : 0;
}

function populateMapelGuruFromPreviousSteps() {
    const rows = Array.from(document.querySelectorAll('.pw-course-row'));
    if (!rows.length) return;

    const selectedRows = rows.filter(row => row.querySelector('.course-check')?.checked);
    const targetRows = selectedRows.length ? selectedRows : rows;
    const courseCount = targetRows.length || 1;

    const packageSessions = parseInt(document.querySelector('[name="jumlah_pertemuan"]')?.value || 0, 10) || parsePackageSessions() || 8;
    const customPrice = parseFloat(document.querySelector('[name="custom_package_price"]')?.value || 0);
    const standardPrice = parseFloat(packageDropdown?.selectedOptions[0]?.dataset.harga || 0);
    const packageFee = (customPrice > 0 && (packageMode === 'custom' || packageMode === 'request'))
        ? Math.round(customPrice / courseCount)
        : Math.round(standardPrice / courseCount);

    const scheduleDays = Array.from(document.querySelectorAll('input[name="hari_belajar[]"]:checked'))
        .map(el => el.value.trim()).filter(Boolean);
    const firstSlot = document.querySelector('input[name^="jam_detail"][name*="[]"]')?.value || '';
    const [firstStart, firstEnd] = firstSlot.split('-').map(t => t.trim());

    targetRows.forEach(row => {
        const sesiInput = row.querySelector('input[name^="course_sessions"]');
        if (sesiInput) sesiInput.value = packageSessions;

        const feeInput = row.querySelector('.fee-input');
        if (feeInput && packageFee > 0) feeInput.value = packageFee;

        const hariSelect = row.querySelector('.hari-select');
        const jamMulai = row.querySelector('.jam-mulai-input');
        const jamSelesai = row.querySelector('.jam-selesai-input');
        if (hariSelect && scheduleDays.length === 1) {
            const option = Array.from(hariSelect.options).find(o => o.text.toLowerCase().includes(scheduleDays[0].toLowerCase()));
            if (option) hariSelect.value = option.value;
        }
        if (jamMulai && firstStart) jamMulai.value = firstStart;
        if (jamSelesai && firstEnd) jamSelesai.value = firstEnd;

        const honorToggle = row.querySelector('.course-use-honor');
        const honorInput = row.querySelector('.honor-input');
        if (honorToggle && honorInput) {
            honorToggle.checked = false;
            honorInput.disabled = true;
        }
        updateRowMargin(feeInput || sesiInput);
    });
    recalcTotal();
}

let isCustomPkg = false;
let packageMode = 'standard';

function switchPackage(type) {
    packageMode = type;
    isCustomPkg = (type === 'custom' || type === 'request');

    const packageModeInput = document.getElementById('packageModeInput');
    const isCustomPackageInput = document.getElementById('isCustomPackage');
    const standardPackageEl = document.getElementById('standardPackage');
    const customPackageEl = document.getElementById('customPackage');
    const totalBiayaEl = document.getElementById('totalBiaya');
    const customPackagePriceEl = document.getElementById('customPackagePrice');

    if (packageModeInput) packageModeInput.value = packageMode;
    if (isCustomPackageInput) isCustomPackageInput.value = isCustomPkg ? '1' : '0';
    if (standardPackageEl) standardPackageEl.style.display = isCustomPkg ? 'none' : '';
    if (customPackageEl) customPackageEl.style.display = isCustomPkg ? '' : 'none';

    if (isCustomPkg) {
        try { populatePackageFieldsFromStudentInfo(); } catch (e) {}
    }

    const btnStandard = document.getElementById('btnStandard');
    const btnCustom   = document.getElementById('btnCustom');
    const btnRequest  = document.getElementById('btnRequest');
    if (btnStandard && btnCustom && btnRequest) {
        btnStandard.className = 'btn btn-sm flex-fill ' + (type === 'standard' ? 'btn-primary' : 'btn-outline-secondary');
        btnCustom.className   = 'btn btn-sm flex-fill ' + (type === 'custom' ? 'btn-primary' : 'btn-outline-secondary');
        btnRequest.className  = 'btn btn-sm flex-fill ' + (type === 'request' ? 'btn-primary' : 'btn-outline-secondary');
    }

    const titleEl = document.getElementById('packagePanelTitle');
    const hintEl  = document.getElementById('packagePanelHint');
    if (titleEl && hintEl) {
        if (type === 'request') {
            titleEl.textContent = 'Request Paket Kelas Baru';
            hintEl.textContent = 'Admin bisa mengajukan paket belajar khusus untuk siswa ini, lengkap dengan mata pelajaran, sesi, nominal, dan detail lainnya.';
        } else {
            titleEl.textContent = 'Buat Paket Custom';
            hintEl.textContent = 'Susun paket belajar khusus untuk siswa ini. Paket akan dibuat dan tersimpan di data master paket.';
        }
    }

    if (isCustomPkg && totalBiayaEl && customPackagePriceEl) {
        totalBiayaEl.value = customPackagePriceEl.value || 0;
    } else if (totalBiayaEl) {
        try { onPackageDropdownChange(); } catch (e) {}
    }
}

const customPackagePriceEl = document.getElementById('customPackagePrice');
if (customPackagePriceEl) {
    customPackagePriceEl.addEventListener('input', function() {
        const totalBiayaEl = document.getElementById('totalBiaya');
        if (isCustomPkg && totalBiayaEl) totalBiayaEl.value = this.value || 0;
    });
}

function buildPreview() {
    let pkgName = 'Tanpa Paket';
    if (isCustomPkg) {
        const cpName = document.querySelector('[name="custom_package_name"]')?.value;
        const suffix = packageMode === 'request' ? ' (Request)' : ' (Custom)';
        pkgName = cpName ? (cpName + suffix) : `— (" === 'request' ? 'Request' : 'Custom'}, belum diisi)`;
    } else {
        const sel = packageDropdown.selectedOptions[0];
        pkgName = packageDropdown.value ? sel.text.split(' — ')[0] : 'Tanpa Paket';
    }

    // Collect per-course data for both the summary table and quotation
    const rows = [];
    let totalFee = 0, totalHonor = 0;
    document.querySelectorAll('.course-check:checked').forEach(chk => {
        const row     = chk.closest('.pw-course-row');
        const teacher = row.querySelector('select').selectedOptions[0]?.text || '–';
        const sesiRaw = parseInt(row.querySelector('input[name^="course_sessions"]').value || 0, 10);
        const sesi    = sesiRaw || '–';
        const fee     = parseFloat(row.querySelector('.fee-input')?.value   || 0);
        const honorPerSesi = parseFloat(row.querySelector('.honor-input')?.value || 0);
        const honor   = honorPerSesi * sesiRaw; // total honor guru = per sesi × jumlah sesi
        const margin  = fee - honor;
        const pct     = fee > 0 ? Math.round((margin / fee) * 100) : 0;
        const name    = row.querySelector('.form-check-label').textContent.trim();
        totalFee   += fee;
        totalHonor += honor;
        const marginColor  = margin >= 0 ? '#10b981' : '#dc2626';
        rows.push({ name, teacher, sesi, fee, honorPerSesi, honor, margin, pct, marginColor });
    });

    const totalMargin = totalFee - totalHonor;
    const totalPct    = totalFee > 0 ? Math.round((totalMargin / totalFee) * 100) : 0;
    const fmt = v => 'Rp' + Number(v).toLocaleString('id-ID');

    const method    = document.getElementById('paymentMethodInput').value;
    const payStatus = document.getElementById('paymentStatusInput').value;
    const prabType  = document.getElementById('prabayarTypeInput').value;
    let metodeTxt = '–';
    if (method === 'prabayar') {
        if (prabType === 'cicilan') {
            const n = document.getElementById('jumlahCicilan')?.value || '?';
            metodeTxt = `Prabayar — Cicilan (";
        } else {
            metodeTxt = 'Prabayar — ' + (payStatus === 'lunas' ? 'Lunas Sekarang' : 'Invoice Dikirim');
        }
    } else if (method === 'pascabayar') {
        metodeTxt = 'Pascabayar (Per Sesi)';
    }

    const rowsHtml = rows.length
        ? rows.map(r => `
            <tr>
                <td class="fw-semibold" style="font-size:.83rem">"
                <td style="font-size:.82rem">"
                <td class="text-center">"
                <td class="text-end">"
                <td class="text-end">"
                <td class="text-end fw-semibold" style="color:" <span class="text-muted fw-normal" style="font-size:.75rem">("
            </tr>`).join('')
        : '<tr><td colspan="6" class="text-muted text-center">Tidak ada mapel dipilih</td></tr>';

    const totalMarginColor = totalMargin >= 0 ? '#10b981' : '#dc2626';

    // Hoist inline expressions so a dollar-brace-brace pattern never appears inside the template literal (Blade parses double braces even in JS blocks)
    const _previewEduLevel  = document.querySelector('[name="education_level"]')?.value || '–';
    const _isPrivatProgram  = document.getElementById('programSelect')?.value === 'privat';
    const _tempatMap        = {kantor:'Di Kantor', rumah:'Guru ke Rumah'};
    const _previewTempat    = _tempatMap[document.getElementById('tempatBelajarInput')?.value] || '–';
    const _previewJadwal    = (() => {
        const days = Array.from(document.querySelectorAll('input[name="hari_belajar[]"]:checked')).map(c => c.value);
        if (!days.length) return '–';
        return days.map(d => {
            const slots = Array.from(document.querySelectorAll('input[name="jam_detail[' + d + '][]"]'))
                              .map(i => i.value).filter(Boolean);
            return d + (slots.length ? ' (' + slots.join(', ') + ')' : '');
        }).join(' · ');
    })();
    const _learningLogisticsHtml = _isPrivatProgram ? `
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Tempat Belajar:</span> <strong>"
            <div class="col-md-8"><span class="text-muted" style="font-size:.83rem">Jadwal:</span> <strong style="font-size:.82rem">" : '';

    document.getElementById('previewBox').innerHTML = `
        
        <div class="row g-2 mb-3">
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Paket:</span> <strong>"
            <div class="col-md-4"><span class="text-muted" style="font-size:.83rem">Kategori:</span> <strong>"
            "
        </div>

        
        <div class="fw-bold mb-2" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)"><i class="bi bi-journal-bookmark me-1"></i>Kelola Kelas</div>
        <div class="table-responsive mb-3">
            <table class="table table-sm table-bordered align-middle mb-0" style="font-size:.82rem">
                <thead><tr style="background:var(--input-bg)">
                    <th>Mapel</th><th>Guru</th><th class="text-center">Sesi</th>
                    <th class="text-end">Biaya Siswa</th><th class="text-end">Honor Guru</th><th class="text-end">Margin</th>
                </tr></thead>
                <tbody>"
            </table>
        </div>

        
        <div class="rounded-3 overflow-hidden mb-3" style="border:1.5px solid rgba(200,77,223,.35)">
            <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:linear-gradient(90deg,rgba(70,18,86,.1),rgba(200,77,223,.08))">
                <i class="bi bi-graph-up-arrow" style="color:#c84ddf"></i>
                <span class="fw-bold" style="font-size:.82rem;color:#461256">📊 Live Quotation</span>
                <span class="ms-auto badge rounded-pill" style="background:rgba(200,77,223,.15);color:#461256;font-size:.7rem">" mapel</span>
            </div>
            <div class="p-3" style="background:var(--input-bg)">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center h-100" style="background:rgba(200,77,223,.07);border:1.5px solid rgba(200,77,223,.25)">
                            <div class="text-muted mb-1" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em"><i class="bi bi-person me-1"></i>Total Tagihan Siswa</div>
                            <div class="fw-bold" style="font-size:1.15rem;color:#461256">"
                            <div class="text-muted mt-1" style="font-size:.7rem">Tagihan yang diterbitkan ke siswa</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center h-100" style="background:rgba(14,165,233,.07);border:1.5px solid rgba(14,165,233,.25)">
                            <div class="text-muted mb-1" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em"><i class="bi bi-person-badge me-1"></i>Total Honor Guru</div>
                            <div class="fw-bold" style="font-size:1.15rem;color:#0ea5e9">"
                            <div class="text-muted mt-1" style="font-size:.7rem">Biaya yang dibayarkan ke guru</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center h-100" style="background:rgba(16,185,129,.07);border:1.5px solid rgba(16,185,129,.25)">
                            <div class="text-muted mb-1" style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em"><i class="bi bi-building me-1"></i>Pendapatan Bimbel</div>
                            <div class="fw-bold" style="font-size:1.15rem;color:"
                            <div class="mt-1">
                                <span class="badge rounded-pill" style="background:";color:";font-size:.72rem">Margin "
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 pt-3" style="border-top:1px solid var(--card-border)">
                    <div class="row g-2" style="font-size:.84rem">
                        <div class="col-md-6 d-flex justify-content-between py-1" style="border-bottom:1px dashed var(--card-border)">
                            <span class="text-muted">Metode Pembayaran</span><strong>"
                        </div>
                        <div class="col-md-6 d-flex justify-content-between py-1" style="border-bottom:1px dashed var(--card-border)">
                            <span class="text-muted">Total Biaya (Final)</span><strong class="text-primary">"
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// ═══════════════════════════════════════════
// STEP 1 — Tipe Pendaftaran: Siswa Baru vs Siswa Lama
// ═══════════════════════════════════════════
function pwSetRegType(type) {
    document.getElementById('registrationTypeInput').value = type;
    document.getElementById('regTypeBaruBtn').classList.toggle('active', type === 'baru');
    document.getElementById('regTypeLamaBtn').classList.toggle('active', type === 'lama');
    document.getElementById('existingStudentWrapper').style.display = type === 'lama' ? '' : 'none';
    if (type === 'baru') {
        document.getElementById('existingStudentIdInput').value = '';
        document.getElementById('existingStudentSearch').value = '';
        document.getElementById('existingStudentChip').style.display = 'none';
    }
}

let _existingStudentSearchTimer = null;
document.getElementById('existingStudentSearch')?.addEventListener('input', function () {
    clearTimeout(_existingStudentSearchTimer);
    const q = this.value.trim();
    const resultsBox = document.getElementById('existingStudentResults');
    document.getElementById('existingStudentIdInput').value = '';
    if (q.length < 2) { resultsBox.style.display = 'none'; return; }
    _existingStudentSearchTimer = setTimeout(() => {
        fetch(_studentSearchUrl + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
            .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
            .then(data => {
                resultsBox.innerHTML = '';
                const students = data.students || [];
                if (!students.length) {
                    resultsBox.innerHTML = '<div class="list-group-item text-muted" style="font-size:.8rem">Tidak ada siswa ditemukan</div>';
                } else {
                    students.forEach(s => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action';
                        item.style.fontSize = '.82rem';
                        item.innerHTML = '<strong>' + s.name + '</strong><br>' +
                            '<span class="text-muted" style="font-size:.72rem">' +
                            (s.nis || '-') + ' &middot; ' + (s.phone || '-') + ' &middot; ' + (s.branch || '-') + '</span>';
                        item.onclick = () => pwSelectExistingStudent(s);
                        resultsBox.appendChild(item);
                    });
                }
                resultsBox.style.display = '';
            })
            .catch(() => {
                resultsBox.innerHTML = '<div class="list-group-item text-danger" style="font-size:.8rem">Gagal memuat data siswa.</div>';
                resultsBox.style.display = '';
            });
    }, 300);
});

function pwSelectExistingStudent(s) {
    document.getElementById('existingStudentIdInput').value = s.id;
    document.getElementById('existingStudentSearch').value = s.name;
    document.getElementById('existingStudentResults').style.display = 'none';

    // Tampilkan chip siswa terpilih
    document.getElementById('espChipName').textContent = s.name;
    document.getElementById('espChipMeta').textContent = (s.nis ? 'NIS: ' + s.nis : '') + (s.phone ? ' · ' + s.phone : '') + (s.branch ? ' · ' + s.branch : '');
    document.getElementById('existingStudentChip').style.display = '';

    // Tampilkan loading di field form
    const formFields = document.querySelectorAll(
        '[name="name"],[name="phone"],[name="gender"],[name="education_level"],' +
        '[name="birth_place"],[name="birth_date"],[name="address"],[name="parent_name"],[name="parent_phone"],[name="school_name"],[name="grade"]'
    );
    formFields.forEach(el => { el.style.opacity = '.4'; el.disabled = true; });

    // Ambil semua data siswa lalu isi ke field form yang sudah ada
    fetch(_studentDetailBase + '/' + s.id, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(data => {
            const d = data.student;
            const set = (name, val) => {
                const el = document.querySelector('[name="' + name + '"]');
                if (el) el.value = val || '';
            };
            set('name',            d.name);
            set('phone',           d.phone);
            set('gender',          d.gender);
            set('education_level', d.kategori_peserta_didik);
            set('birth_place',     d.birth_place);
            set('birth_date',      d.birth_date);
            set('address',         d.address);
            set('parent_name',     d.parent_name);
            set('parent_phone',    d.parent_phone);
            set('school_name',     d.school_name);
            set('grade',           d.grade);
            // Perbarui chip meta dengan data lengkap
            document.getElementById('espChipName').textContent = d.name || s.name;
            document.getElementById('espChipMeta').textContent =
                (d.nis ? 'NIS: ' + d.nis : '') + (d.phone ? ' · ' + d.phone : '') + (d.branch_name ? ' · ' + d.branch_name : '');
        })
        .catch(() => showToast('Gagal memuat data siswa.', 'error'))
        .finally(() => {
            formFields.forEach(el => { el.style.opacity = '1'; el.disabled = false; });
        });
}

function pwClearExistingStudent() {
    document.getElementById('existingStudentIdInput').value = '';
    document.getElementById('existingStudentSearch').value = '';
    document.getElementById('existingStudentChip').style.display = 'none';
    // Reset field form ke nilai kosong / default
    ['name','phone','gender','education_level','birth_place','birth_date','address','parent_name','parent_phone','school_name','grade']
        .forEach(name => {
            const el = document.querySelector('[name="' + name + '"]');
            if (el) el.value = '';
        });
}

document.addEventListener('click', function (e) {
    const wrapper = document.getElementById('existingStudentWrapper');
    const resultsBox = document.getElementById('existingStudentResults');
    if (wrapper && resultsBox && !wrapper.contains(e.target)) resultsBox.style.display = 'none';
});

// ═══════════════════════════════════════════
// STEP 1 — Tempat Belajar & Jadwal JS
// ═══════════════════════════════════════════
function pickTempat(val, el) {
    el.closest('.d-flex').querySelectorAll('.pw-opt-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('tempatBelajarInput').value = val;
    pwToggleRoomColumn();
}

// Tempat Belajar & Jadwal Belajar hanya relevan untuk program Privat —
// kelas reguler sudah punya jadwal/lokasi sendiri lewat paket/kelas yang dipilih.
function pwToggleLearningLogistics() {
    const program = document.getElementById('programSelect')?.value;
    const wrapper = document.getElementById('learningLogisticsWrapper');
    if (!wrapper) return;
    const isPrivat = program === 'privat';
    wrapper.style.display = isPrivat ? '' : 'none';
    wrapper.querySelectorAll('input, select, textarea').forEach(el => el.disabled = !isPrivat);
}

function pwToggleCourseClassSelectors() {
    const program = document.getElementById('programSelect')?.value;
    const isKelas = program === 'kelas';
    document.querySelectorAll('.course-class-picker').forEach(wrapper => {
        wrapper.style.display = isKelas ? '' : 'none';
    });
    document.querySelectorAll('.existing-class-select').forEach(selectEl => {
        selectEl.disabled = !isKelas;
    });
}

function pwToggleRoomColumn() {
    const system = document.getElementById('systemSelect')?.value;
    const program = document.getElementById('programSelect')?.value;
    const tempat = document.getElementById('tempatBelajarInput')?.value || 'kantor';
    const shouldHide = system === 'online' || (program === 'privat' && tempat === 'rumah');

    document.querySelectorAll('.room-column-header, .room-column-cell').forEach(el => {
        el.style.display = shouldHide ? 'none' : '';
    });
    document.querySelectorAll('.room-select').forEach(el => {
        el.disabled = shouldHide;
    });
}

document.addEventListener('DOMContentLoaded', function () {
    pwToggleLearningLogistics();
    pwToggleCourseClassSelectors();
    pwToggleRoomColumn();
    const systemSelect = document.getElementById('systemSelect');
    const programSelect = document.getElementById('programSelect');
    if (systemSelect) {
        systemSelect.addEventListener('change', pwToggleRoomColumn);
    }
    if (programSelect) {
        programSelect.addEventListener('change', function () {
            pwToggleLearningLogistics();
            pwToggleCourseClassSelectors();
            pwToggleRoomColumn();
        });
    }
    document.querySelectorAll('.existing-class-select').forEach(selectEl => syncExistingClassDetail(selectEl));
    setKelolaKelasMode('new');
});

const PW_DAY_ORDER = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

function pwToggleDay(pill, dayName) {
    const cb = pill.querySelector('input[type=checkbox]');
    setTimeout(() => {
        const checked = cb.checked;
        pill.classList.toggle('selected', checked);
        if (checked) pwAddDayRow(dayName);
        else pwRemoveDayRow(dayName);
        pwUpdateScheduleWrapper();
    }, 0);
}

function pwAddDayRow(dayName) {
    if (document.getElementById('pwsrow-' + dayName)) return;
    const container = document.getElementById('pwDayScheduleContainer');
    const row = document.createElement('div');
    row.className = 'pw-schedule-row';
    row.id = 'pwsrow-' + dayName;
    row.innerHTML = `
        <div class="pw-day-label">"
        <div class="flex-fill" id="pwslots-"
            <div class="pw-time-slot">
                <input type="text" name="jam_detail["
                       class="form-control form-control-sm" placeholder="cth. 10:00 - 12:00" autocomplete="off">
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="pwAddSlot('" style="font-size:.72rem;white-space:nowrap">
            <i class="bi bi-plus"></i> Slot
        </button>`;
    // Insert in day order
    let inserted = false;
    container.querySelectorAll('.pw-schedule-row').forEach(existRow => {
        if (inserted) return;
        const existDay = existRow.id.replace('pwsrow-', '');
        if (PW_DAY_ORDER.indexOf(dayName) < PW_DAY_ORDER.indexOf(existDay)) {
            container.insertBefore(row, existRow);
            inserted = true;
        }
    });
    if (!inserted) container.appendChild(row);
}

function pwRemoveDayRow(dayName) {
    document.getElementById('pwsrow-' + dayName)?.remove();
}

function pwUpdateScheduleWrapper() {
    const wrapper   = document.getElementById('pwDayScheduleWrapper');
    const container = document.getElementById('pwDayScheduleContainer');
    if (!wrapper || !container) return;
    wrapper.style.display = container.querySelectorAll('.pw-schedule-row').length > 0 ? '' : 'none';
}

function pwAddSlot(dayName) {
    const slotsDiv = document.getElementById('pwslots-' + dayName);
    if (!slotsDiv) return;
    const div = document.createElement('div');
    div.className = 'pw-time-slot mt-1';
    div.innerHTML = `
        <input type="text" name="jam_detail["
               class="form-control form-control-sm" placeholder="cth. 15:00 - 17:00" autocomplete="off">
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="pwRemoveSlot(this)" title="Hapus">
            <i class="bi bi-x"></i>
        </button>`;
    slotsDiv.appendChild(div);
}

function pwRemoveSlot(btn) {
    btn.closest('.pw-time-slot')?.remove();
}

// ═══════════════════════════════════════════
// STEP 4 — Payment Method JS
// ═══════════════════════════════════════════
function selectPaymentMethod(method) {
    document.querySelectorAll('.pm-card').forEach(c => {
        const isActive = c.dataset.method === method;
        c.style.borderColor   = isActive ? 'var(--bs-primary)' : 'var(--card-border)';
        c.style.background    = isActive ? 'rgba(200,77,223,.06)' : '';
    });
    document.getElementById('paymentMethodInput').value = method;
    document.getElementById('prabayarPanel').style.display  = method === 'prabayar'  ? '' : 'none';
    document.getElementById('pascabayarPanel').style.display = method === 'pascabayar' ? '' : 'none';
    if (method === 'pascabayar') {
        document.getElementById('paymentStatusInput').value = 'belum_bayar';
        document.getElementById('prabayarTypeInput').value  = 'lunas';
    } else {
        onPrabayarTypeChange();
    }
}

function onPrabayarTypeChange() {
    const type = document.querySelector('input[name="prabayar_type"]:checked')?.value || '';
    document.getElementById('prabayarTypeInput').value   = type || 'lunas';
    document.getElementById('lunasPanel').style.display  = type === 'lunas'   ? '' : 'none';
    document.getElementById('cicilanPanel').style.display = type === 'cicilan' ? '' : 'none';
    if (type === 'lunas') {
        const checked = document.querySelector('input[name="prabayar_lunas_status"]:checked');
        document.getElementById('paymentStatusInput').value = checked ? checked.value : 'belum_bayar';
        updateLunasDisplay();
    } else if (type === 'cicilan') {
        document.getElementById('paymentStatusInput').value = 'belum_bayar';
        if (!document.querySelector('#cicilanRowsContainer tr')) rebuildCicilanRows();
    }
}

function updateLunasDisplay() {
    const total = parseFloat(document.getElementById('totalBiaya').value) || 0;
    const el = document.getElementById('lunasTotalDisplay');
    if (el) el.textContent = total.toLocaleString('id-ID');
}

function rebuildCicilanRows() {
    const n         = Math.max(1, parseInt(document.getElementById('jumlahCicilan').value) || 2);
    const totalBiaya = parseFloat(document.getElementById('totalBiaya').value) || 0;
    const container  = document.getElementById('cicilanRowsContainer');
    container.innerHTML = '';
    const base     = n > 0 ? Math.floor(totalBiaya / n) : 0;
    const today    = new Date();
    const pad = v => String(v).padStart(2,'0');
    const dateStr = (d) => `";
    for (let i = 1; i <= n; i++) {
        const nominal  = i === n ? (totalBiaya - base * (n - 1)) : base;
        const mulaiDate = new Date(today); mulaiDate.setDate(today.getDate() + (i-1)*30);
        const tempoDate = new Date(today); tempoDate.setDate(today.getDate() + i*30);
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center"><span class="badge bg-primary-soft text-primary" style="font-size:.75rem">"
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="number" min="0" class="form-control cicilan-nominal" name="cicilan_nominal[]"
                           value="" oninput="updateCicilanTotal()">
                </div>
            </td>
            <td><input type="date" class="form-control form-control-sm" name="cicilan_mulai[]" value=""
            <td><input type="date" class="form-control form-control-sm" name="cicilan_jatuh_tempo[]" value="";
        container.appendChild(tr);
    }
    updateCicilanTotal();
}

function updateCicilanTotal() {
    const nominals = document.querySelectorAll('.cicilan-nominal');
    const sum      = Array.from(nominals).reduce((s, el) => s + (parseFloat(el.value) || 0), 0);
    const biaya    = parseFloat(document.getElementById('totalBiaya').value) || 0;
    document.getElementById('cicilanTotalCheck').textContent = 'Rp ' + sum.toLocaleString('id-ID');
    const mismatch = Math.abs(sum - biaya) > 1;
    document.getElementById('cicilanMismatchWarning').style.display = mismatch ? '' : 'none';
    const refEl = document.getElementById('cicilanBiayaRef');
    if (refEl) refEl.textContent = biaya.toLocaleString('id-ID');
}

// Sync totalBiaya changes → lunas display + cicilan total
const totalBiayaEl = document.getElementById('totalBiaya');
if (totalBiayaEl) {
    totalBiayaEl.addEventListener('input', function() {
        updateLunasDisplay();
        if (document.getElementById('cicilanPanel').style.display !== 'none') {
            updateCicilanTotal();
        }
    });
}

// Auto-initialise payment method to prabayar on page load
if (document.querySelectorAll('.pm-card').length > 0) {
    selectPaymentMethod('prabayar');
}

function handleCheckAllGuruClick(event) {
    const btn = event.currentTarget || document.getElementById('btnCekSemuaGuru');
    if (!btn) return;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengecek…';
    runAllConflictChecks();
    renderActiveClassInsights();
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-shield-check me-1"></i>Cek Semua Guru';
        const conflicts = document.querySelectorAll('.conflict-warning-box .text-danger').length;
        if (conflicts > 0) {
            showToast(`Ditemukan " konflik jadwal guru. Periksa panel di bawah.`, 'error');
        } else {
            const checked = document.querySelectorAll('.conflict-warning-box .text-success').length;
            if (checked > 0) showToast('Semua guru tersedia — tidak ada konflik jadwal.', 'success');
        }
    }, 1800);
}

// Conflict check button — add loading state + toast summary
if (document.getElementById('btnCekSemuaGuru')) {
    document.getElementById('btnCekSemuaGuru').addEventListener('click', handleCheckAllGuruClick);
}

// ── end STEP 4 JS ──

document.getElementById('processForm').addEventListener('submit', function(e) {
    e.preventDefault();
    if (document.getElementById('registrationTypeInput').value === 'lama' && !document.getElementById('existingStudentIdInput').value) {
        showToast('Pilih siswa lama terlebih dahulu, atau ganti ke "Daftar Siswa Baru".', 'error');
        showStep(1);
        return;
    }
    const checkedCourses = document.querySelectorAll('.course-check:checked').length;
    if (checkedCourses === 0) {
        showToast('Pilih minimal satu mata pelajaran sebelum melanjutkan.', 'error');
        showStep(2);
        return;
    }
    if (isCustomPkg) {
        const cpName = document.querySelector('[name="custom_package_name"]').value.trim();
        const cpJenis = document.querySelector('[name="custom_jenis"]').value;
        const packageLabel = packageMode === 'request' ? 'Paket Request' : 'Paket Custom';
        if (!cpName || !cpJenis) {
            showToast(`Lengkapi Nama Paket & Jenis Paket pada " 'error');
            showStep(2);
            return;
        }
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    document.getElementById('submitText').classList.add('d-none');
    document.getElementById('submitLoading').classList.remove('d-none');

    const formData = new FormData(this);
    fetch(_processUrl, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _csrf },
        body: formData,
    })
    .then(r => r.json().then(d => ({ ok: r.ok, d })))
    .then(({ ok, d }) => {
        if (ok && d.success) {
            _credData = d;
            document.getElementById('cred-name').textContent = d.name || '–';
            document.getElementById('cred-email').textContent = d.email || '–';
            const passwordRow = document.getElementById('cred-password-row');
            if (d.is_existing) {
                document.getElementById('successTitle').textContent = 'Siswa Lama Didaftarkan ke Kelas Baru';
                document.getElementById('successSubtitle').textContent = 'Akun sudah ada sebelumnya — cukup infokan siswa tentang program/kelas barunya, tidak perlu kirim password baru.';
                passwordRow.style.display = 'none';
            } else {
                document.getElementById('successTitle').textContent = 'Akun Siswa Berhasil Dibuat';
                document.getElementById('successSubtitle').textContent = 'Kirim informasi akun ini ke WhatsApp siswa agar bisa langsung login.';
                document.getElementById('cred-password').textContent = d.password || '–';
                passwordRow.style.display = '';
            }
            document.getElementById('processForm').classList.add('d-none');
            document.getElementById('successPanel').classList.remove('d-none');
        } else {
            showToast(d.message || 'Gagal memproses pendaftaran.', 'error');
            submitBtn.disabled = false;
            document.getElementById('submitText').classList.remove('d-none');
            document.getElementById('submitLoading').classList.add('d-none');
        }
    })
    .catch(() => {
        showToast('Terjadi kesalahan. Coba lagi.', 'error');
        submitBtn.disabled = false;
        document.getElementById('submitText').classList.remove('d-none');
        document.getElementById('submitLoading').classList.add('d-none');
    });
});

function sendToWA() {
    const phone = (_credData.phone || '').replace(/\D/g, '');
    if (!phone) { showToast('Nomor HP siswa tidak tersedia.', 'error'); return; }
    const wa = phone.startsWith('0') ? '62' + phone.slice(1) : phone;
    const loginUrl = '"';
    const msg = encodeURIComponent(
        _credData.is_existing
            ? (
                'Halo ' + (_credData.name || 'Siswa') + ',\n\n' +
                'Pendaftaran Anda untuk program/kelas baru di Smart Center Indonesia telah *diverifikasi*.\n\n' +
                'Akun login Anda tetap sama seperti sebelumnya, silakan login untuk melihat kelas & jadwal terbaru:\n' + loginUrl + '\n\n' +
                'Terima kasih & selamat belajar!'
            )
            : (
                'Halo ' + (_credData.name || 'Siswa') + ',\n\n' +
                'Selamat datang di Smart Center Indonesia!\n\n' +
                'Pendaftaran Anda telah *diverifikasi*. Berikut data akun login Anda:\n\n' +
                '*Email:* ' + (_credData.email || '-') + '\n' +
                '*Password:* ' + (_credData.password || '-') + '\n' +
                '*No. Registrasi:* ' + (_credData.no_reg || '-') + '\n\n' +
                '*Link Login:*\n' + loginUrl + '\n\n' +
                'Segera login dan lengkapi profil Anda. Jangan bagikan password kepada siapapun.\n\n' +
                'Terima kasih & selamat belajar!'
            )
    );
    window.open('https://wa.me/' + wa + '?text=' + msg, '_blank');
}
