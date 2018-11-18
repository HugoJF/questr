<?php

namespace App\Http\Controllers;

use App\Classes\QuestMapper;
use App\Quest;
use App\Forms\QuestFilterForm;
use App\QuestFilter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Kris\LaravelFormBuilder\FormBuilder;

class QuestFilterController extends Controller
{
	public function create(FormBuilder $formBuilder, Quest $quest)
	{
		$form = $formBuilder->create(QuestFilterForm::class, [
			'method' => 'POST',
			'url'    => route('quests.filters.store', $quest),
		], [
			'quest' => $quest,
		]);

		return view('form', [
			'title'       => 'New quest filter form',
			'submit_text' => 'Create new quest filter',
			'form'        => $form,
		]);
	}

	public function store(Request $request, Quest $quest)
	{
		$validated = $request->validate([
			'key'   => [
				'required', Rule::in(QuestMapper::getFilterKeys($quest->type)),
			],
			'value' => [
				'required', Rule::in(QuestMapper::getFilterValues($quest->type, $request->input('key'))),
			],
		]);

		if ($quest->questFilters()->where('key', $request->input('key'))->exists()) {
			flash()->warning("Quest with key <strong>{$request->input('key')}</strong> already exists!");

			return redirect()->back();
		}

		$filter = QuestFilter::make();
		$filter->fill($validated);
		$filter->quest()->associate($quest);
		$filter->save();

		flash()->success('Filter created successfully!');

		return redirect()->route('quests.show', $quest);
	}

	public function delete(QuestFilter $filter)
	{
		$deleted = $filter->delete();

		if ($deleted) {
			flash()->success("Quest filter <strong>$filter->key: $filter->value</strong> was successfully deleted!");
		} else {
			flash()->error("Quest filter <strong>$filter->key: $filter->value</strong> could not be deleted!");
		}

		return redirect()->back();
	}
}
