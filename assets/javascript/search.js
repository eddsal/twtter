$(function(){
    $('.searchm').keyup(function(){
        var search = $(this).val();
        $.post('http://localhost/twtter/?action=search', {search:search}, function(data){
            $('.search-result').html(data);

        })
    })


});
