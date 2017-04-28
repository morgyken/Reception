<div class="form-group">
    <label for="{{ $settingName }}">{{ $moduleInfo['description'] }}</label>
    <select multiple class="destinations" name="{{ $settingName }}[]" id="{{ $settingName }}">
        <option value="none">none</option>
        <?php try { ?>
            @foreach (all_roles() as $id => $role)
            <option value="{{ $id }}" {{ (isset($data['db_settings'][$settingName]) && isset(array_flip(json_decode($data['db_settings'][$settingName]->value))[$id])) ? 'selected' : '' }}>
                    {{ $role }}</option>
            @endforeach
        <?php } catch (\Exception $e) { ?>
            @foreach (all_roles() as $id => $role)
            <option value="{{$role->id}}">
                {{ $role }}
            </option>>
            @endforeach
        <?php } ?>
    </select>
    @if (!empty($moduleInfo['hint']))
    <p class="help-block">{{$moduleInfo['hint']}}</p>
    @endif
</div>
<script>
    $(document).ready(function () {
        $('.destinations').selectize({
            delimiter: ',',
            plugins: ['remove_button']
        });
    });
</script>