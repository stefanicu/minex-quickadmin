<div class="form-row">
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="name">{{ trans('form.field_name') }}</label>
		<input class="form-control" type="text" id="name" name="name" placeholder="{{ trans('form.field_name') }}" autocomplete="off">
	</div>
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="surname">{{ trans('form.field_surname') }}</label>
		<input class="form-control" type="text" id="surname" name="surname" placeholder="{{ trans('form.field_surname') }}" autocomplete="off">
	</div>
</div>
<div class="form-row">
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="company">{{ trans('form.field_company') }}</label>
		<input class="form-control" type="text" id="company" name="company" placeholder="{{ trans('form.field_company') }}" autocomplete="off">
	</div>
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="job">{{ trans('form.field_job') }}</label>
		<input class="form-control" type="text" id="job" name="job" placeholder="{{ trans('form.field_job') }}" autocomplete="off">
	</div>
</div>
<div class="form-row">
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="industry">{{ trans('form.field_industry') }}</label>
		<input class="form-control" type="text" id="industry" name="industry" placeholder="{{ trans('form.field_industry') }}" autocomplete="off">
	</div>
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="email">{{ trans('form.field_email') }}</label>
		<input class="form-control" type="text" id="email" name="email" placeholder="{{ trans('form.field_email') }}" autocomplete="off">
	</div>
</div>
<div class="form-row">
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="country">{{ trans('form.field_country') }}</label>
		<select class="form-control" id="country" name="country" required>
			<option value="--" disabled selected>{{ trans('form.field_country') }}</option>
		</select>
	</div>
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="county">{{ trans('form.field_county') }}</label>
		<select class="form-control" id="county" name="county" required>
			<option value="" disabled selected>{{ trans('form.field_county') }}</option>
		</select>
	</div>
</div>
<div class="form-row">
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="city">{{ trans('form.field_city') }}address</label>
		<input class="form-control" type="text" id="city" name="city" placeholder="{{ trans('form.field_city') }}" autocomplete="off">
	</div>
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="phone">{{ trans('form.field_phone') }}</label>
		<input class="form-control" type="text" id="phone" name="phone" placeholder="{{ trans('form.field_phone') }}" autocomplete="off">
	</div>
</div>

<div class="form-row pb-3">
	<div class="col">
		<label class="form-label" for="how_about">{{ trans('form.field_how_about') }}</label>
		<input class="form-control" type="text" id="how_about" name="how_about" placeholder="{{ trans('form.field_how_about') }}" autocomplete="off">
	</div>
</div>

<div class="form-row pb-3">
	<div class="col">
		<label class="form-label" for="message">{{ trans('form.field_messages') }}</label>
		<textarea class="form-control" rows="3" id="message" name="message" placeholder="{{ trans('form.field_messages') }}"></textarea>
	</div>
</div>
<div class="form-row pb-3">
	<div class="col">
		<div class="p-2 check-bg">
			<div class="form-check form-check-inline">
				<label class="form-check-label form-newsletter">
					<input class="form-check-input form-checkbox-min" type="checkbox" id="checkbox" name="checkbox" checked>
					{{ trans('form.field_checkbox') }}
					<a href="{{ url('') }}/gdpr/" target="_blank"><strong>{{ trans('frontend.gdpr_compliance') }}</strong></a>
				</label>
			</div>
		</div>
	</div>
</div>
