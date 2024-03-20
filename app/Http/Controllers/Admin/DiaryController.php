<?php

namespace App\Http\Controllers\Admin;

use App\Exports\QuizResultsExport;
use App\Exports\QuizzesAdminExport;
use App\Http\Controllers\Controller;
use App\Models\Diary;
use App\Models\Quiz;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesResult;
use App\Models\Translation\QuizTranslation;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use App\Models\WebinarChapterItem;
use App\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin_diary_lists');

        removeContentLocale();

        $query = Diary::query();

        $totalDiary = deepClone($query)->count();
        $totalStudents = Diary::select('user_id')->distinct()->count();

        $query = $this->filters($query, $request);

        $quizzes = $query
            ->with([
                'user'
            ])
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/quiz.admin_quizzes_list'),
            'diaries' => $quizzes,
            'totalDiary' => $totalDiary,
            'totalStudents' => $totalStudents,
        ];



        return view('admin.diary.lists', $data);
    }

    private function filters(\Illuminate\Database\Eloquent\Builder $query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $title = $request->get('title', null);
        $sort = $request->get('sort', null);
        $reference_type = $request->get('reference_type', null);

        if($from){
            $from = date('Y-m-d', strtotime($from));
            $query->whereDate('dated_at', '>=', $from);
        }
        if($to){
            $to = date('Y-m-d', strtotime($to));
            $query->whereDate('dated_at', '<=', $to);
        }

        if (!empty($title)) {
            //its actually full text search
            $query->where('title', 'like', '%' . $title . '%');
            $query->orWhere('description', 'like', '%' . $title . '%');
            $query->orWhere('theme', 'like', '%' . $title . '%');
            // and find by user full name
            $query->orWhereHas('user', function ($q) use ($title) {
                $q->where('full_name', 'like', '%' . $title . '%');
            });
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'have_certificate':
                    $query->where('certificate', true);
                    break;
                case 'students_count_asc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'asc');
                    break;

                case 'students_count_desc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'desc');
                    break;
                case 'passed_count_asc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->where('quizzes_results.status', 'passed')
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'asc');
                    break;

                case 'passed_count_desc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->where('quizzes_results.status', 'passed')
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'desc');
                    break;

                case 'grade_avg_asc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', 'quizzes_results.user_grade', DB::raw('avg(quizzes_results.user_grade) as grade_avg'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('grade_avg', 'asc');
                    break;

                case 'grade_avg_desc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', 'quizzes_results.user_grade', DB::raw('avg(quizzes_results.user_grade) as grade_avg'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('grade_avg', 'desc');
                    break;

                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if (!empty($reference_type) and $reference_type !== 'all') {
            $query->where('reference_type', $reference_type);
        }

        return $query;
    }

    public function create()
    {
        $this->authorize('admin_diary_create');

        $data = [
            'pageTitle' => trans('quiz.new_quiz'),
        ];

        return view('admin.diary.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_diary_create');

        $data = $request->get('ajax')['new'];
        $locale = $data['locale'] ?? getDefaultLocale();



        $validate = Validator::make($data, \App\Http\Controllers\Panel\DiaryController::$rules);

        if ($validate->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validate->errors()
            ], 422);
        }




        if (!empty($diary)) {

            $diary = Diary::create([
                    'title' => $data['title'],
                    'theme' => $data['theme'],
                    'description' => $data['description'],
                    'reference_type' => $data['reference_type'],
                    'dated_at' => $data['dated_at'],
                    'updated_at' => time(),
                ]);


            if ($request->ajax()) {
                return response()->json([
                    'code' => 200
                ]);
            } else {
                return redirect()->route('panel.diary.index', ['id' => $diary->id])->with('success', __('diary.diary_updated'));
            }
        } else {
            return back()->withErrors([
                'id' => trans('validation.exists', ['attribute' => trans('diary.diary')])
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_diary_edit');

        $diary = Diary::find($id);

        if (empty($diary)) {
            abort(404);
        }

        $data = [
            'diary' => $diary,
        ];

        return view('admin.diary.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $diary = Diary::query()->findOrFail($id);
        $data = $request->get('ajax')[$id];
        $locale = $data['locale'] ?? getDefaultLocale();

        $validate = Validator::make($data, \App\Http\Controllers\Panel\DiaryController::$rules);

        if ($validate->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validate->errors()
            ], 422);
        }



        $diary->update([
            'title' => $data['title'],
            'theme' => $data['theme'],
            'description' => $data['description'],
            'reference_type' => $data['reference_type'],
            'dated_at' => $data['dated_at'],
            'updated_at' => time(),
        ]);



        if ($request->ajax()) {
            return response()->json([
                'code' => 200
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function delete(Request $request, $id)
    {
        $this->authorize('admin_diary_delete');

        $diary = Diary::query()->findOrFail($id);

        $diary->delete();

        if ($request->ajax()) {
            return response()->json([
                'code' => 200
            ], 200);
        }

        return redirect()->back();
    }






    public function exportExcel(Request $request)
    {
        $this->authorize('admin_diary_exports');

        $query = Diary::query();

        $query = $this->filters($query, $request);

        $quizzes = $query->with([
            'user'
        ])->get();

        return Excel::download(new QuizzesAdminExport($quizzes), trans('quiz.quizzes') . '.xlsx');
    }

}
