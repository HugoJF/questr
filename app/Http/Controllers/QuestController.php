<?php

namespace App\Http\Controllers;

use App\Classes\QuestMapper;
use App\Forms\QuestForm;
use App\Quest;
use App\QuestProgress;
use App\Transaction;
use Carbon\Carbon;
use hugojf\CsgoServerApi\Facades\CsgoApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Kris\LaravelFormBuilder\FormBuilder;

class QuestController extends Controller
{
	public function index()
	{
		$quests = Quest::visible()->latest()->paginate(12);

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
		// Check if quest is unlocked
		if ($quest->startAt->isFuture()) {
			flash()->error('You cannot start locked quests!')->important();

			return back();
		}

		// Check if quest is valid
		if ($quest->endAt->isPast()) {
			flash()->error('You cannot start expired quests!')->important();

			return back();
		}

		// Check if it's not started by authed user
		if (Auth::user()->questProgresses()->where('quest_id', $quest->id)->exists()) {
			flash()->warning('Quest is already in progress!')->important();

			return back();
		}

		// Check if user can afford the quest cost
		if ($quest->cost > 0 && Auth::user()->getBalanceAttribute(true) < $quest->cost) {
			flash()->error('Balance is insufficient to start quest')->important();

			return back();
		}

		// Generate debit transaction
		if ($quest->cost > 0) {
			$transaction = Transaction::make();

			$transaction->value = -$quest->cost;
			$transaction->user()->associate(Auth::user());
			$transaction->owner()->associate($quest);

			$transaction->save();
		}

		// Generate quest progress
		$questProgress = QuestProgress::make();

		$questProgress->progress = 0;
		$questProgress->user_id = Auth::user()->id;
		$questProgress->quest_id = $quest->id;

		$questProgress->save();

		// Notify user of result
		flash()->success("Quest <strong>{$quest->title}</strong> has started!");

		return back();
	}

	public function finish(Quest $quest)
	{
		// Verify if user has succeeded quest
		if ($quest->success(null) !== true) {
			flash()->error('You must complete the goal of the quest before finishing it!')->important();

			return back();
		}

		// Get current quest progress
		$questProgress = $quest->getQuestProgress(Auth::user());

		// Check if user already finished quest
		if ($questProgress->finished_at !== null) {
			flash()->error('You cannot get rewarded multiple times by the same quest!')->important();

			return back();
		}

		// Check if user owns progress
		if ($questProgress->user != Auth::user()) {
			flash()->error('You cannot finish quests that are not yours!')->important();

			return back();
		}

		// Update quest progress as finished
		$questProgress->finished_at = Carbon::now();
		$saved = $questProgress->save();

		// Check if quest was updated as finished
		if (!$saved) {
			flash()->success("Could not update quest $quest->title!");

			return back();
		}

		// Generate quest transaction for reward
		/** @var Transaction $transaction */
		$transaction = Transaction::make();

		$transaction->value = $quest->reward;
		$transaction->user()->associate(Auth::user());
		$transaction->owner()->associate($questProgress);

		$transaction->save();

		flash()->success("<strong>Congratulations!</strong> You just finished quest <strong>$quest->title</strong> and got awarded with $quest->reward <i class=\"fas fa-coins\"></i>.")->important();

		return back();
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
			'hidden'      => 'boolean',
			'code'        => 'alpha_num|nullable',
			'startAt'     => 'required|date_format:Y-m-d H:i:s',
			'endAt'       => 'required|date_format:Y-m-d H:i:s',
		]);

		$quest = Quest::make();

		$quest->fill($validated);

		$quest->hidden = $request->input('hidden') ? true : false;

		$quest->save();

		flash()->success("Quest $quest->title successfully created!");

		if(!$quest->hidden) {
			$url = redirect()->route('quests.show', $quest);

			CsgoApi::broadcast()->commands([
				["sm_csay Nova quest disponível no Questr: {$quest->title}", 0],
				["sm_say Para iniciar a quest também, acesse: $url", 1500]
			])->send();
		}

		if ($quest->hidden) {
			return redirect()->route('quests.show', $quest->code);
		} else {
			return redirect()->route('quests.show', $quest);
		}
	}
}
