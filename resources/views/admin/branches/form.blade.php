<div class="mb-2"><label>Nombre</label><input name="name" value="{{ old('name',$branch->name ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Ciudad</label><input name="city" value="{{ old('city',$branch->city ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Dirección</label><input name="address" value="{{ old('address',$branch->address ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Zona horaria</label><input name="timezone" value="{{ old('timezone',$branch->timezone ?? 'America/Bogota') }}" class="form-control"></div>
