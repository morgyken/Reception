<div class="form-group">
    <label for="{{ $settingName }}">{{ $moduleInfo['description'] }}</label>
    <select multiple class="places" name="{{ $settingName }}[]" id="{{ $settingName }}">
        @foreach (mconfig('reception.options.destinations') as $id => $role)
        <option value="{{ $id }}" {{ (isset($data['db_settings'][$settingName]) && isset(array_flip(json_decode($data['db_settings'][$settingName]->value))[$id])) ? 'selected' : '' }}>
                {{ $role }}
    </option>
    @endforeach
</select>
@if (!empty($moduleInfo['hint']))
<p class="help-block">{{$moduleInfo['hint']}}</p>
@endif
</div>
<script>
    $(document).ready(function () {
        $('.places').selectize({
            delimiter: ',',
            plugins: ['remove_button']
        });
    });
</script>