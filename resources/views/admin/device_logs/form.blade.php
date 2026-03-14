<div class="mb-2"><label>Dispositivo</label><select name="device_id" class="form-select">@foreach($devices as $d)<option value="{{ $d->id }}" @selected(old('device_id',$deviceLog->device_id ?? '')==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
<div class="mb-2"><label>User ID</label><input name="user_id" value="{{ old('user_id',$deviceLog->user_id ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Hora</label><input type="datetime-local" name="log_time" value="{{ old('log_time',isset($deviceLog)?$deviceLog->log_time->format('Y-m-d\\TH:i'):'' ) }}" class="form-control"></div>
<div class="mb-2"><label>Estado</label><input name="status" value="{{ old('status',$deviceLog->status ?? 'ok') }}" class="form-control"></div>
