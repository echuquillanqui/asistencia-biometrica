<div class="mb-2"><label>Código</label><input name="employee_code" value="{{ old('employee_code',$employee->employee_code ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Nombres</label><input name="first_name" value="{{ old('first_name',$employee->first_name ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Apellidos</label><input name="last_name" value="{{ old('last_name',$employee->last_name ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Documento</label><input name="document_number" value="{{ old('document_number',$employee->document_number ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Sucursal</label><select name="branch_id" class="form-select">@foreach($branches as $b)<option value="{{ $b->id }}" @selected(old('branch_id',$employee->branch_id ?? '')==$b->id)>{{ $b->name }}</option>@endforeach</select></div>
<div class="mb-2"><label>ID Huella</label><input name="fingerprint_id" value="{{ old('fingerprint_id',$employee->fingerprint_id ?? '') }}" class="form-control"></div>
<div class="mb-2"><label>Estado</label><select name="status" class="form-select"><option value="active" @selected(old('status',$employee->status ?? '')=='active')>active</option><option value="inactive" @selected(old('status',$employee->status ?? '')=='inactive')>inactive</option></select></div>
