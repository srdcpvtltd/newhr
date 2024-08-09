<div class="row">

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="pnumber" class="form-label">Request Number <span style="color: red">*</span></label>
        <input type="text" disabled class="form-control" value="{{ $procurement_number }}" id="procurement_number" name="pnumber" required autocomplete="off" placeholder="Number">
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="billing_name" class="form-label">Name</label>
        <select class="form-select" id="user_id" name="billing_name">
            <option value="" {{isset($assetDetail) || old('assigned_to') ? '': 'selected'}}>Select Employee</option>
            @foreach($employees as $key => $value)
            <option value="{{$value->id}}" {{ isset($assetDetail) && ($assetDetail->assigned_to ) == $value->id || old('assigned_to') == $value->id ? 'selected': old('assigned_to') }}>
                {{ucfirst($value->name)}}
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="email" class="form-label">Email <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="mail" name="email" required autocomplete="off" placeholder="Email">
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="type_id" class="form-label">Type <span style="color: red">*</span></label>
        <select class="form-select" id="type" name="type_id" required>
            <option value="" {{isset($assetDetail) ? '': 'selected'}} disabled>Select Type</option>
            @foreach($assetType as $key => $value)
            <option value="{{$value->id}}" {{ isset($assetDetail) && ($assetDetail->type_id ) == $value->id || old('type_id') == $value->id ? 'selected': '' }}>
                {{ucfirst($value->name)}}
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="quantity" class="form-label">Quantity<span style="color: red">*</span></label>
        <input type="number" min="1" class="form-control" id="procurement_quantity" name="quantity" required autocomplete="off" placeholder="Enter Amount">
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="amount" class="form-label">Amount<span style="color: red">*</span></label>
        <input type="number" min="1" class="form-control" id="procurement_amount" name="amount" required autocomplete="off" placeholder="Enter Amount">
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="request_date" class="form-label">Request Date <span style="color: red">*</span></label>
        <input type="date" class="form-control" id="request_date" name="request_date" value="{{ ( isset($assetDetail) ? ($assetDetail->purchased_date): old('purchased_date') )}}" required autocomplete="of f">
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="delivery_date" class="form-label">Delivery Date <span style="color: red">*</span></label>
        <input type="date" class="form-control" id="delivery_date" name="delivery_date" value="{{ ( isset($assetDetail) ? ($assetDetail->purchased_date): old('purchased_date') )}}" required autocomplete="off">
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="purpose" class="form-label">Purpose</label>
        <textarea class="form-control" name="purpose" id="ckeditor" rows="2">{{ ( isset($assetDetail) ? $assetDetail->note: old('note') )}}</textarea>
    </div>

    @canany(['edit_assets','create_assets'])
    <div class="text-start">
        <button type="submit" class="btn btn-primary">
            <i class="link-icon" data-feather="plus"></i>
            {{isset($assetDetail)? 'Update':'Create'}}
        </button>
    </div>
    @endcanany
</div>