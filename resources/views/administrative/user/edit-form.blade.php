@if($role_id == 3)
<div class="form-group {{ $errors->has('zone_id') ? 'has-error' : '' }}">
  <label for="department_id">Select Zone</label>
  <select name="zone_id" id="zone_id" class="form-control" required>
    <option value="">Select</option>
    @foreach($zones as $zone)
    <option value="{{ $zone->id }}" {{  isset($data) && $data->zone_id == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
    @endforeach
  </select>
  @if($errors->has('zone_id'))
  <label id="name-error" class="error mt-2 text-danger" for="name">Please enter a Zone</label>
  @endif
</div>

<!-- <div class="form-group {{ $errors->has('area_ids') ? 'has-error' : '' }}">
  <label for="roles">Select Areas</label>
  <select name="area_ids[]" id="area_ids" class="form-control select2" multiple="multiple" required>
    @foreach($areas as $area)
    <option value="{{ $area->id }}" {{ (in_array($area->id, old('area_ids', [])) || isset($data) && in_array($area->id, $data->area_ids ? $data->area_ids : [])) ? 'selected' : '' }}>{{ $area->name }}</option>
    @endforeach
  </select>
  @if($errors->has('area_ids'))
  <label id="name-error" class="error mt-2 text-danger" for="name">Please enter Area</label>
  @endif
</div> -->
@endif

@if($role_id == 4)
<div class="form-group {{ $errors->has('area_id') ? 'has-error' : '' }}">
  <label for="area_id">Select Area</label>
  <select name="area_id" id="area_id" class="form-control" required>
    <option value="">Select</option>
    @foreach($areas as $area)
    <option value="{{ $area->id }}" {{  isset($data) && $data->area_id == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
    @endforeach
  </select>
  @if($errors->has('area_id'))
  <label id="name-error" class="error mt-2 text-danger" for="name">Please enter a Area</label>
  @endif
</div>

<!-- <div class="form-group {{ $errors->has('unit_ids') ? 'has-error' : '' }}">
  <label for="unit_ids">Select Units</label>
  <select name="unit_ids[]" id="unit_ids" class="form-control select2" multiple="multiple" required>
    @foreach($units as $unit)
    <option value="{{ $unit->id }}" {{ (in_array($unit->id, old('unit_ids', [])) || isset($data) && in_array($unit->id, $data->unit_ids ? $data->unit_ids : [])) ? 'selected' : '' }}>{{ $unit->name }}</option>
    @endforeach
  </select>
  @if($errors->has('unit_ids'))
  <label id="name-error" class="error mt-2 text-danger" for="name">Please enter Unit</label>
  @endif
</div> -->
@endif

@if($role_id == 5)
<div class="form-group {{ $errors->has('unit_id') ? 'has-error' : '' }}">
  <label for="unit_id">Select Unit</label>
  <select name="unit_id" id="unit_id" class="form-control" required>
    <option value="">Select</option>
    @foreach($units as $unit)
    <option value="{{ $unit->id }}" {{  isset($data) && $data->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
    @endforeach
  </select>
  @if($errors->has('unit_id'))
  <label id="name-error" class="error mt-2 text-danger" for="name">Please enter a Unit</label>
  @endif
</div>

@endif

@if($role_id == 6)

<div class="form-group {{ $errors->has('unit_id') ? 'has-error' : '' }}">
  <label for="unit_id">Select Unit</label>
  <select name="unit_id" class="unit_id" class="form-control" required>
    <option value="">Select</option>
    @foreach($units as $unit)
    <option value="{{ $unit->id }}" @if($unit->id==$data->unit_id) selected @endif>{{ $unit->name }}</option>
    @endforeach
  </select>
  @if($errors->has('unit_id'))
  <label id="name-error" class="error mt-2 text-danger" for="name">Please enter a Unit</label>
  @endif
</div>
@endif
