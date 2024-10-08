<div class="row align-items-center">
    <div class="col-lg-6">
        <label for="name" class="form-label">Name<span style="color: red">*</span></label>
        <input type="text" class="form-control" id="name"
            required
            name="name"
            value="{{ ( isset($brandDetail) ? ($brandDetail->name): old('name') )}}"
            autocomplete="on"
            placeholder="">
    </div>

    @canany(['create_type','edit_type'])
    <div class="col-lg-6 mt-4">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="{{isset($assetTypeDetail)? 'edit-2':'plus'}}"></i>
            {{isset($brandDetail)? 'Update':'Create'}}
        </button>
    </div>
    @endcanany
</div>