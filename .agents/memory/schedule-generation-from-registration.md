---
name: Registration "Jadwal Kelas" now generates real Schedule rows
description: How the process page's per-mapel hari/jam/ruang inputs are wired to actual recurring Schedule records, and the day-of-week convention used.
---

The "Jadwal Kelas" table on `/admin/registration-list/{id}/process` (Card B, per mata pelajaran: guru/hari/jam/ruang) used to have **no `name` attributes** on its hari-select/jam-mulai/jam-selesai inputs — it was purely cosmetic (conflict-check UI only) and none of it was ever submitted or persisted.

Fixed by:
- Adding `name="schedule_hari[{courseId}]"`, `schedule_jam_mulai[{courseId}]`, `schedule_jam_selesai[{courseId}]` (room already had `schedule_room[{courseId}]`).
- Giving the time inputs sensible defaults (`08:00`/`10:00`) instead of leaving them empty — empty native `<input type="time">` triggers browsers to fall back to the current wall-clock time when the user interacts with the spinner arrows, which looked like a random/buggy default.
- In `RegistrationListController@processStore`, when a **brand-new** `SchoolClass` is created for a course (not when reusing an existing active class — reused classes already have their own schedule), it now generates `jumlah_pertemuan` weekly `Schedule` rows: `tanggal` = next occurrence of the selected weekday from `now()`, repeating `+1 week` per session, `jam_mulai`/`jam_selesai` from the form, `ruangan` resolved from `rooms.nama_ruangan` (Schedule.ruangan is a plain string, not an FK), `honor_per_sesi` from `course_honor[courseId]`.

**Why:** `schedules.tanggal` is a single date column (no native recurring/day-of-week concept), so "recurring weekly" has to be materialized as N individual dated rows at creation time — there's no other model for recurring templates.

**How to apply:** Day-of-week values in the `hari-select` (`0`=Minggu…`6`=Sabtu) match Carbon's `dayOfWeek` (0=Sunday), consistent with the `EXTRACT(DOW FROM tanggal)` convention already used in `ScheduleController@conflictCheck`. When testing schedule generation, remember the app's `now()` can be ahead of the shell's `date` output by hours (timezone/env clock offset) — don't assume "today" server-side matches the wall-clock date you see in the shell.
