import $ from 'jquery';

$(function(){
    $("#cryptoTable tr").click(function() {
        var selected = $(this).hasClass("highlight");
        $("#cryptoTable tr").removeClass("highlight");
        if (!selected)
        $(this).addClass("highlight");
        var crypto_id = $("#cryptoTable tr.highlight").attr("data-id");
        $("#edit").attr("href", `crypto/show/${crypto_id}`);
    });

}) 