<?php

namespace App\Http\Controllers;

use App\Classes\QuestMapper;
use App\Forms\QuestForm;
use App\Quest;
use App\QuestProgress;
use App\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Kris\LaravelFormBuilder\FormBuilder;

class QuestController extends Controller
{
	public function index()
	{
		$quests = Quest::orderBy('created_at', 'DESC')->paginate(12);

		return view('quests.index', [
			'quests' => $quests,
		]);
	}

	public function show(Quest $quest)
	{
		return view('quests.show', [
			'quest' => $quest,
		]);
	}

	public function start(Quest $quest)
	{
		$questProgress = QuestProgress::make();

		$questProgress->progress = 0;
		$questProgress->user_id = Auth::user()->id;
		$questProgress->quest_id = $quest->id;

		$questProgress->save();

		$title = $quest->title;
		flash()->success("Quest <strong>$title</strong> has started!");

		return redirect()->back();
	}

	public function finish(Quest $quest)
	{
		if ($quest->success === true) {
			$questProgress = $quest->getQuestProgressForAuthedUser();

			if ($questProgress->reward()->exists()) {
				flash()->error('You cannot get rewarded multiple times by the same quest!')->important();

				return redirect()->back();
			}

			$reward = Reward::make();
			$reward->questProgress()->associate($questProgress);
			$reward->save();

			flash()->success("<strong>Congratulations!</strong> You just finished quest <strong>$quest->title</strong> and got awarded with $quest->reward <i class=\"fas fa-coins\"></i>.")->important();

			return redirect()->back();
		} else {
			flash()->error('You must complete the goal of the quest before finishing it!')->important();

			return redirect()->back();
		}
	}

	public function create(FormBuilder $formBuilder)
	{
		$form = $formBuilder->create(QuestForm::class, [
			'method' => 'POST',
			'url'    => route('quests.store'),
		]);

		return view('form', [
			'title'       => 'New quest form',
			'submit_text' => 'Create new quest',
			'form'        => $form,
		]);
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'title'       => 'required',
			'description' => 'required',
			'type'        => [
				'required',
				Rule::in(QuestMapper::getTypes()),
			],
			'cost'        => 'required|numeric|gte:0',
			'goal'        => 'required|numeric|gt:0',
			'reward'      => 'required|numeric|gt:0',
			'startAt'       => 'required|date_format:Y-m-d G:i:s',
			'endAt'         => 'required|date_format:Y-m-d G:i:s',
		]);

		$quest = Quest::make();

		$quest->fill($validated);

		$quest->save();

		flash()->success("Quest $quest->title successfully created!");

		return redirect()->route('quests.show', $quest);
	}
}
