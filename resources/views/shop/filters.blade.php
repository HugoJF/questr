<div class="text-center mb-3">
    {!! Form::open(['url' => [url()->current()], 'method' => 'GET']) !!}
    
    <button class="btn btn-outline-success mr-3">Filter</button>
    
    <div class="d-inline mr-3">
        {!! Form::select('weapons[]', collect(config('constants.name-to-short'))->flip()->keyBy(function ($item) { return $item; }), request()->input('weapons'), [
            'multiple' => true,
            'data-live-search' => 'true',
            'data-width' => 'auto',
            'class' => 'selectpicker',
            'title' => '=== Weapons ==='
        ]); !!}
    </div>
    
    @php
        $skins = \App\ShopItem::selectRaw('DISTINCT skin_name')
        ->orderBy('skin_name')
        ->get()
        ->pluck('skin_name')
        ->keyBy(
            function ($item) {
                return $item;
            }
        );
    @endphp
    
    <div class="d-inline mr-3">
        {!! Form::select('skins[]', $skins , request()->input('skins'), [
            'multiple' => true,
            'data-live-search' => 'true',
            'data-width' => 'auto',
            'class' => 'selectpicker',
            'title' => '=== Skins ==='
        ]); !!}
    </div>
    
    <div class="d-inline mr-3">
        {!! Form::select('conditions[]', [
            'Factory New' => 'Factory New',
            'Minimal Wear' => 'Minimal Wear',
            'Field-Tested' => 'Field-Tested',
            'Well-Worn' => 'Well-Worn',
            'Battle-Scarred' => 'Battle-Scarred',
        ], request()->input('conditions'), [
            'multiple' => true,
            'data-live-search' => 'true',
            'data-width' => 'auto',
            'class' => 'selectpicker',
            'title' => '=== Conditions ==='
        ]); !!}
    </div>
    
    <button class="btn btn-outline-success">Filter</button>
    
    {!! Form::close() !!}

</div>