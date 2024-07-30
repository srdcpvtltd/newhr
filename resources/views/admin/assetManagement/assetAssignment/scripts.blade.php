<script>
    $('#type').change(function() {
        let selectedAssetTypeId = $('#type option:selected').val();
        console.log(selectedAssetTypeId);
        url = "{{ url('admin/asset-assignments/get-All-Assets') }}" + '/' + selectedAssetTypeId;
        let assetId = null;
        $('#assign_asset').empty();
        if (selectedAssetTypeId) {
            $.ajax({
                type: 'GET',
                url: "{{ url('admin/asset-assignments/get-All-Assets') }}" + '/' + selectedAssetTypeId,
            }).done(function(response) {
                console.log(response.data.length);
                if (!selectedAssetTypeId) {
                    $('#assign_asset').append('<option disabled  selected >Select Type first</option>');
                }
                if (response.data.length == 0) {
                    $('#assign_asset').append('<option disabled  selected >No Assets</option>');
                }
                response.data.forEach(function(data) {
                    $('#assign_asset').append('<option ' + ((data.id === selectedAssetTypeId) ? "selected" : '') + ' value="' + data.id + '" >' + capitalize(data.name) + '</option>');
                });
            });
        }
    }).trigger('change');

    function capitalize(str) {
        strVal = '';
        str = str.split(' ');
        for (let chr = 0; chr < str.length; chr++) {
            strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length) + ' '
        }
        return strVal
    }
</script>