<?php

namespace App\Http\Controllers;

use App\Models\EducationModule;
use App\Models\UserModuleProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    /**
     * Daftar semua modul edukasi + progress user.
     */
    public function index()
    {
        $userId = Auth::id();

        $modules = EducationModule::orderBy('order_index')->get();

        $progress = UserModuleProgress::where('id_user', $userId)
            ->get()
            ->keyBy('module_id');

        $completedCount = $progress->where('completed', true)->count();
        $totalModules = $modules->count();

        $modules = $modules->map(function ($module) use ($progress) {
            $module->progress = $progress[$module->id] ?? null;
            $module->is_completed = ($progress[$module->id] ?? null)?->completed ?? false;
            $module->score = ($progress[$module->id] ?? null)?->score ?? 0;

            return $module;
        });

        return view('education.index', compact('modules', 'completedCount', 'totalModules'));
    }

    /**
     * Detail modul + quiz.
     */
    public function show($slug)
    {
        $module = EducationModule::where('slug', $slug)->firstOrFail();
        $quizzes = $module->quizzes;

        $userId = Auth::id();
        $progress = UserModuleProgress::where('id_user', $userId)
            ->where('module_id', $module->id)
            ->first();

        return view('education.show', compact('module', 'quizzes', 'progress'));
    }

    /**
     * Proses quiz, hitung skor, simpan progress.
     */
    public function submitQuiz(Request $request, $slug)
    {
        $module = EducationModule::where('slug', $slug)->firstOrFail();
        $quizzes = $module->quizzes;

        $request->validate([
            'answers' => 'required|array',
        ]);

        $score = 0;
        $results = [];

        foreach ($quizzes as $quiz) {
            $userAnswer = $request->input('answers.'.$quiz->id);
            $isCorrect = $userAnswer === $quiz->correct_answer;
            if ($isCorrect) {
                $score++;
            }

            $results[] = [
                'quiz_id' => $quiz->id,
                'question' => $quiz->question,
                'user_answer' => $userAnswer,
                'correct_answer' => $quiz->correct_answer,
                'is_correct' => $isCorrect,
                'explanation' => $quiz->explanation,
                'options' => [
                    'a' => $quiz->option_a,
                    'b' => $quiz->option_b,
                    'c' => $quiz->option_c,
                    'd' => $quiz->option_d,
                ],
            ];
        }

        $maxScore = $quizzes->count();
        $completed = $score === $maxScore; // lulus kalau semua benar? Atau cukup di atas batas?
        // Disini syarat lulus: semua benar (100%). Bisa disesuaikan.
        $passed = $score === $maxScore;

        UserModuleProgress::updateOrCreate(
            [
                'id_user' => Auth::id(),
                'module_id' => $module->id,
            ],
            [
                'completed' => $passed,
                'score' => $score,
                'max_score' => $maxScore,
                'completed_at' => $passed ? now() : null,
            ]
        );

        $completedCount = UserModuleProgress::where('id_user', Auth::id())
            ->where('completed', true)
            ->count();
        $totalModules = EducationModule::count();
        $allCompleted = $completedCount >= $totalModules;

        return response()->json([
            'success' => true,
            'score' => $score,
            'max_score' => $maxScore,
            'passed' => $passed,
            'all_completed' => $allCompleted,
            'results' => $results,
            'certificate_url' => $allCompleted ? route('education.certificate') : null,
        ]);
    }

    /**
     * Halaman sertifikat digital.
     */
    public function certificate()
    {
        $userId = Auth::id();
        $totalModules = EducationModule::count();
        $completedCount = UserModuleProgress::where('id_user', $userId)
            ->where('completed', true)
            ->count();

        if ($completedCount < $totalModules) {
            return redirect()->route('education.index')
                ->with('error', 'Selesaikan semua modul terlebih dahulu untuk mendapatkan sertifikat.');
        }

        $user = Auth::user();
        $lastProgress = UserModuleProgress::where('id_user', $userId)
            ->where('completed', true)
            ->orderBy('completed_at', 'desc')
            ->first();

        return view('education.certificate', compact('user', 'lastProgress', 'totalModules', 'completedCount'));
    }
}
