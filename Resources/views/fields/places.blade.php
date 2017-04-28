<div class="form-group">
    <label for="{{ $settingName }}">{{ $moduleInfo['description'] }}</label>
    <select multiple class="places" name="{{ $settingName }}[]" id="{{ $settingName }}">
        <option value="none">None</option>
        <?php try { ?>
            @foreach (mconfig('reception.options.destinations') as $id => $role)
            <?php try { ?>
                <option value="{{ $id }}" {{ (isset($data['db_settings'][$settingName]) && isset(array_flip(json_decode($data['db_settings'][$settingName]->value))[$id])) ? 'selected' : '' }}>
                        {{ $role }}</option>
                    <?php } catch (Exception $ex) {

                    } ?>
            @endforeach
            <?php
        } catch (Exception $ex) {

        }
        ?>
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