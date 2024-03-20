<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Diary;
use App\Models\Reward;
use App\Models\RewardAccounting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Diary::where('user_id', $user->id);

        $query = $this->filters($request, $query);
        $diaries = deepClone($query)->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => __('diary.diary'),
            'diaries' => $diaries,
            'diariesCount' => deepClone($query)->count(),
            'diariesBookCount' => deepClone($query)->where('reference_type', 'book')->count(),
            'diariesVideoCount' => deepClone($query)->where('reference_type', 'video')->count(),
            'diariesArticleCount' => deepClone($query)->where('reference_type', 'article')->count(),
            'diariesOtherCount' => deepClone($query)->where('reference_type', 'other')->count(),
        ];

        return view(getTemplate() . '.panel.diary.index', $data);

    }
    public function filters(Request $request, Builder $query)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $search_text = $request->get('search_text');
        $reference_type = $request->get('reference_type');

        if ($from) {
            $from = date('Y-m-d', strtotime($from));
            $query->where('dated_at', '>=', $from);
        }
        if ($to) {
            $to = date('Y-m-d', strtotime($to));
            $query->where('dated_at', '<=', $to);
        }

        if (!empty($search_text)) {
            //its actually full text search
            $query->where(function ($query) use ($search_text) {
                $query->where('title', 'LIKE', '%' . $search_text . '%')
                    ->orWhere('description', 'LIKE', '%' . $search_text . '%');
            });
        }

        if (!empty($reference_type)) {
            $query->where('reference_type', $reference_type);
        }



        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view(getTemplate() . '.panel.diary.create');
    }

    public static $rules = [

        'title' => 'required|string',
        'dated_at' => 'required|date',
        'reference_type' => 'required|in:book,article,video,coaching,training,mentoring,other',
        'skills' => 'nullable|string', // Making skills nullable
        'skills' => 'nullable|string',
        'description' => 'string',
    ];
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $data = $request->get('ajax')['new'];
        $validate = Validator::make($data, self::$rules);


        if ($validate->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validate->errors()
            ], 422);
        }


        $real = new Diary();
        $real->user_id = auth()->user()->id;
        $real->title = $data['title'];
        $real->description = $data['description'];
        $real->reference_type = $data['reference_type'];
        $real->skills = $data['skills'];
        $real->dated_at = $data['dated_at'];
        $real->feedback = $data['feedback'];
        $real->organ_id = auth()->user()->organ_id;
        $real->save();

        if ($request->ajax()) {

            $type = Reward::CREATE_LEARNING_DIARY;
            $Reward = RewardAccounting::calculateScore($type);    
            RewardAccounting::makeRewardAccounting(auth()->user()->id, $Reward, $type);

            // $redirectUrl = '/panel/diary/' . $real->id . '/edit';
            $redirectUrl =  '/panel/diary';
           
            return response()->json([
                'code' => 200,
                'redirect_url' => $redirectUrl
            ]);
        } else {
            return redirect()->route('panel.diary.index', ['id' => $real->id])->with('success', __('diary.diary_created'));
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Diary $diary)
    {
        if($diary->user_id != auth()->user()->id) {
            abort(404);
        }
        return view(getTemplate() . '.panel.diary.show', compact('diary'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Diary $diary)
    {
        // if($diary->user_id != auth()->user()->id) {
        //     abort(404);
        // }
        return view(getTemplate() . '.panel.diary.edit', compact('diary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,  $id)
    {

        $diary = Diary::find($id);
        if(!$diary) {
            abort(404);
        }
        // if($diary->user_id != auth()->user()->id) {
        //     dd($diary->user_id, auth()->user()->id);
        //     abort(404);
        // }
        $data = $request->get('ajax')[$id];
        $validate = Validator::make($data, self::$rules);

        if ($validate->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validate->errors()
            ], 422);
        }


        $diary->update($data);

        if ($diary->user_id !== auth()->user()->id) {

            $notifyOptions = [
                '[student.name]' =>  auth()->user()->full_name,
                '[item_title]' => $diary->title,
            ];
            sendNotification('send_feedback', $notifyOptions, $diary->user_id);
           
         }

        if ($request->ajax()) {
            return response()->json([
                'code' => 200
            ]);
        }

        
        

        return redirect()->route('panel.diary.index')->with('success', __('diary.diary_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Http\RedirectResponse
     */
   
    public function destroy(Diary $diary)
    {
        // dd($diary, auth()->user()->id);
        if($diary->user_id != auth()->user()->id) {
            abort(404);
        }

        $diary->delete();
        $redirectUrl = '/panel/diary';

    }

    public function delete(Request $request, $id)
    {
        
        $diary = Diary::query()->findOrFail($id);

        $diary->delete();

        if ($request->ajax()) {
            return response()->json([
                'code' => 200
            ], 200);
        }

        return redirect()->back();
    }




}






