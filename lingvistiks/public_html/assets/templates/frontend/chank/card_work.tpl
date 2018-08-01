<form action="{{ HOST_NAME }}/billing/card/work/" method="post" id="addcart" class="ajax_form">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="void_id" value="{{ card_info.card_id }}">

	<div class="row">
		<div class="col-md-12">
			<p><strong>{{ lang.billing_card_number }}</strong></p>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<input type="text" class="form-control" name="card_number[]" maxlength="4" value="{{ card_info.card_number.0 }}" placeholder="XXXX">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<input type="text" class="form-control" name="card_number[]" maxlength="4" value="{{ card_info.card_number.1 }}" placeholder="XXXX">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<input type="text" class="form-control" name="card_number[]" maxlength="4" value="{{ card_info.card_number.2 }}" placeholder="XXXX">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<input type="text" class="form-control" name="card_number[]" maxlength="4" value="{{ card_info.card_number.3 }}" placeholder="XXXX">
			</div>
		</div>
		<div class="col-md-9">
			<p><strong>{{ lang.billing_card_owner }}</strong></p>
			<div class="form-group">
				<input type="text" class="form-control" name="card_owner_name" value="{{ card_info.card_owner_name }}" placeholder="">
			</div>
		</div>
		<div class="col-md-3">
			<p><strong>{{ lang.billing_card_date }}</strong></p>
			<div class="form-group">
				<input type="text" class="form-control" name="card_date" value="{{ card_info.card_date }}"  placeholder="XX/XX">
			</div>
		</div>
	</div>
	<p><button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_save }}</button></p>
</form>
