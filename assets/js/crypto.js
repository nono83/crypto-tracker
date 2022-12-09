import $ from 'jquery';

$(function(){
    $("#crypto_apiID").change(function(){
        $("#crypto_price").val('');
        if ($('#crypto_apiID option:selected').val() !=""){
            var price = $(this).find(':selected').attr("data-price");
            $("#crypto_price").val(parseFloat(price).toFixed(5));
        }
    })
})