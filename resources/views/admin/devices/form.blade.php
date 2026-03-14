<div class="mb-2"><label>Sucursal</label><select name="branch_id" class="form-select">@foreach($branches as $b)<option value="{{ $b->id }}" @selected(old('branch_id',$device->branch_id ?? '')==$b->id)>{{ $b->name }}</option>@endforeach</select></div>
<div class="mb-2"><label>Nombre</label><input name="name" value="{{ old('name',$device->name ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Serial</label><input name="serial_number" value="{{ old('serial_number',$device->serial_number ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>IP</label><input name="ip_address" value="{{ old('ip_address',$device->ip_address ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Puerto</label><input name="port" value="{{ old('port',$device->port ?? 4370) }}" class="form-control"></div>
<div class="mb-2"><label>Password dispositivo</label><input name="device_password" value="{{ old('device_password',$device->device_password ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Estado</label><select name="status" class="form-select"><option value="online" @selected(old('status',$device->status ?? '')=='online')>online</option><option value="offline" @selected(old('status',$device->status ?? 'offline')=='offline')>offline</option></select></div>
