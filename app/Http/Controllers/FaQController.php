<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\InvalidOrderException;
use App\Http\Resources\AnswerQuestion;
use App\Http\Resources\FaQResource;
use App\Models\FaQ;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FaQController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $user = auth()->user();
        $code = Str::lower(request()->code);
        if ($code[0] !== "g")
            $group = $user->group()->find($code);
        else
            $group = $user->group()->where('code', $code)->first();
        if (empty($group)) abort(404);
        $this->faq = $group->faq();
    }

    public function index()
    {
        return $this->sendResponse(null, $this->faq->select('id', 'name')->get()->toArray(), 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'question' => 'array|min:1|required',
            'question.*' => 'required',
            'answer' => 'array|min:1|required',
            'answer.*' => 'required'
        ]);
        if ($validate->fails()) return $this->sendResponse('Validation Error', $validate->messages(), 422);
        DB::beginTransaction();
        try {

            $faq = $this->faq->create([
                'name' => $request->name,
            ]);
            $question = $request->question;
            foreach ($question as $key => $value) {
                $faq->answerQuestion()->create([
                    'question' => $value,
                    'answer' => $request->answer[$key]
                ]);
            }
            DB::commit();
            return $this->sendResponse('Success create faq', $faq, 200);
        } catch (InvalidOrderException $th) {
            DB::rollback();
            return $this->sendResponse("Error ", $th, 500);
        }
    }
    public function show($id)
    {
        $faq = FaQ::findOrFail($id);
        return $this->sendResponse(null, new FaQResource($faq->load('answerQuestion', 'group')), 200);
    }
    public function update(Request $request, $id)
    {
        $faq = FaQ::findOrFail($id);
        $faq->update($request->only(['name']));
        return $this->sendResponse("Success edit FaQ", null, 200);
    }
    public function destroy($id)
    {
        $faq = FaQ::findOrFail($id);
        $faq->delete();
        return $this->sendResponse("Success delete FaQ", null, 200);
    }

    public function storeAQ(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required'
        ]);
        if ($validate->fails()) return $this->sendResponse('Validation Error', $validate->messages(), 422);
        $faq = FaQ::findOrFail($id);
        DB::beginTransaction();
        try {
            $faq->answerQuestion()->create([
                'question' => $request->question,
                'answer' => $request->answer,
            ]);
            DB::commit();
            return $this->sendResponse('Success add answer question', $faq, 200);
        } catch (InvalidOrderException $th) {
            DB::rollback();
            return $this->sendResponse("Error ", $th, 500);
        }
    }
    public function showAQ($id, $aq)
    {
        $faq = FaQ::findOrFail($id)->answerQuestion()->findOrFail($aq);
        return $this->sendResponse(null, new AnswerQuestion($faq), 200);
    }
    public function updateAQ(Request $request, $id, $aq)
    {
        $faq = FaQ::findOrFail($id)->answerQuestion()->findOrFail($aq);
        $faq->update($request->only(['question', 'answer']));
        return $this->sendResponse("Success edit answer question", null, 200);
    }
    public function destroyAQ($id, $aq)
    {
        $faq = FaQ::findOrFail($id)->answerQuestion()->findOrFail($aq);
        $faq->delete();
        return $this->sendResponse("Success delete answer question", null, 200);
    }
}
