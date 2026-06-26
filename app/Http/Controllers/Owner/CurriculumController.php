<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\CurriculumChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CurriculumController extends Controller
{
    public function index()
    {
        $curricula = Curriculum::with('course', 'cabang', 'chapters')
            ->get()
            ->groupBy('course_id');

        $courses = Course::orderBy('nama')->get();
        $branches = Branch::orderBy('name')->get();

        return view('owner.curriculum.index', compact('curricula', 'courses', 'branches'));
    }

    public function create()
    {
        $courses  = Course::orderBy('nama')->get();
        $branches = Branch::orderBy('name')->get();
        return view('owner.curriculum.create', compact('courses', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id'            => 'required|exists:courses,id',
            'scope'                => 'required|in:global,lokal',
            'cabang_id'            => 'nullable|exists:branches,id',
            'chapters'             => 'required|array|min:1',
            'chapters.*.judul'     => 'required|string|max:255',
            'chapters.*.jumlah_sesi' => 'required|integer|min:1',
            'chapters.*.pdf'       => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $curriculum = Curriculum::create([
            'course_id' => $request->course_id,
            'scope'     => $request->scope,
            'cabang_id' => $request->scope === 'lokal' ? $request->cabang_id : null,
        ]);

        foreach ($request->chapters as $i => $ch) {
            $pdfPath = null;
            if (isset($ch['pdf']) && $ch['pdf']->isValid()) {
                $pdfPath = $ch['pdf']->store('curriculum-pdfs', 'public');
            }

            CurriculumChapter::create([
                'curriculum_id' => $curriculum->id,
                'judul'         => $ch['judul'],
                'jumlah_sesi'   => $ch['jumlah_sesi'],
                'urutan'        => $i + 1,
                'pdf_path'      => $pdfPath,
            ]);
        }

        return redirect()->route('owner.curriculum.index')
            ->with('success', 'Kurikulum & Silabus berhasil ditambahkan.');
    }

    public function show(Curriculum $curriculum)
    {
        $curriculum->load('course', 'cabang', 'chapters');
        $courses  = Course::orderBy('nama')->get();
        $branches = Branch::orderBy('name')->get();
        return view('owner.curriculum.show', compact('curriculum', 'courses', 'branches'));
    }

    public function edit(Curriculum $curriculum)
    {
        $curriculum->load('course', 'cabang', 'chapters');
        $courses  = Course::orderBy('nama')->get();
        $branches = Branch::orderBy('name')->get();
        return view('owner.curriculum.edit', compact('curriculum', 'courses', 'branches'));
    }

    public function update(Request $request, Curriculum $curriculum)
    {
        $request->validate([
            'course_id'            => 'required|exists:courses,id',
            'scope'                => 'required|in:global,lokal',
            'cabang_id'            => 'nullable|exists:branches,id',
            'chapters'             => 'required|array|min:1',
            'chapters.*.judul'     => 'required|string|max:255',
            'chapters.*.jumlah_sesi' => 'required|integer|min:1',
            'chapters.*.pdf'       => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $curriculum->update([
            'course_id' => $request->course_id,
            'scope'     => $request->scope,
            'cabang_id' => $request->scope === 'lokal' ? $request->cabang_id : null,
        ]);

        $existingIds = collect($request->input('chapter_ids', []))->filter()->values();
        $curriculum->chapters()->whereNotIn('id', $existingIds)->delete();

        foreach ($request->chapters as $i => $ch) {
            $chapterId = $request->input("chapter_ids.$i");
            $pdfPath   = $request->input("chapter_pdf_existing.$i");

            if (isset($ch['pdf']) && $ch['pdf']->isValid()) {
                if ($pdfPath) Storage::disk('public')->delete($pdfPath);
                $pdfPath = $ch['pdf']->store('curriculum-pdfs', 'public');
            }

            if ($chapterId) {
                CurriculumChapter::where('id', $chapterId)->update([
                    'judul'       => $ch['judul'],
                    'jumlah_sesi' => $ch['jumlah_sesi'],
                    'urutan'      => $i + 1,
                    'pdf_path'    => $pdfPath,
                ]);
            } else {
                CurriculumChapter::create([
                    'curriculum_id' => $curriculum->id,
                    'judul'         => $ch['judul'],
                    'jumlah_sesi'   => $ch['jumlah_sesi'],
                    'urutan'        => $i + 1,
                    'pdf_path'      => $pdfPath,
                ]);
            }
        }

        return redirect()->route('owner.curriculum.index')
            ->with('success', 'Kurikulum & Silabus berhasil diperbarui.');
    }

    public function destroy(Curriculum $curriculum)
    {
        foreach ($curriculum->chapters as $ch) {
            if ($ch->pdf_path) Storage::disk('public')->delete($ch->pdf_path);
        }
        $curriculum->delete();

        return back()->with('success', 'Kurikulum dihapus.');
    }

    public function uploadChapterPdf(Request $request, CurriculumChapter $chapter)
    {
        $request->validate(['pdf' => 'required|file|mimes:pdf|max:10240']);

        if ($chapter->pdf_path) Storage::disk('public')->delete($chapter->pdf_path);
        $path = $request->file('pdf')->store('curriculum-pdfs', 'public');
        $chapter->update(['pdf_path' => $path]);

        return back()->with('success', 'PDF berhasil diunggah.');
    }
}
