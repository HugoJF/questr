<div class="form-group">
    <label for="{{ $name }}" class="control-label required">{{ $options['label'] }}</label>
    <div class='input-group date' id='datetimepicker-{{ $name }}' data-target-input="nearest">
        <div style="cursor: pointer;" class="input-group-prepend" data-target="#datetimepicker-{{ $name }}" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="far fa-clock"></i></div>
        </div>
        
        <input type='text' value="{{ $options['value'] }}" name="{{ $name }}" class="form-control datetimepicker-input" data-target="#datetimepicker-{{ $name }}"/>
        
        <div class="input-group-append" data-target="#datetimepicker-{{ $name }}" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="far fa-clock"></i></div>
        </div>
    </div>
    @if ($options['help_block']['text'] && !$options['is_child'])
        <{{ $options['help_block']['tag'] }} {!! $options['help_block']['helpBlockAttrs']  !!}>
        {{ $options['help_block']['text'] }}
        </{{ $options['help_block']['tag'] }} >
    @endif

    <?php if ($showError && isset($errors) && $errors->hasBag($errorBag)): ?>
    <?php foreach ($errors->getBag($errorBag)->get($nameKey) as $err): ?>
    <div <?= $options['errorAttrs'] ?>><?= $err ?></div>
    <?php endforeach; ?>
    <?php endif; ?>


</div>
@push('scripts')
    <script type="text/javascript">
        $(function () {
            $('#datetimepicker-{{ $name }}').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                sideBySide: true,
                toolbarPlacement: 'bottom',
                allowInputToggle: true,
                icons: {
                    time: 'far fa-clock',
                    date: 'fas fa-calendar',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'far fa-calendar-check',
                    clear: 'fas fa-trash',
                    close: 'fas fa-times'
                },
                stepping: 5,
                buttons:  {
                    showToday: true,
                    showClear: true,
                    showClose: true
                },
                widgetPositioning: {
                    horizontal: 'left',
                }
            });
        });
    </script>
@endpush