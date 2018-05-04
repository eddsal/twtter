$(function(){
    $('.searchm').keyup(function(){
        var search = $(this).val();
        $.post('http://localhost/twtter/?action=search', {search:search}, function(data){
            $('.search-result').html(data);
        });
       $(window).click(function(){
        $('.search-result').html('');

       })

    })

    $('.deleteTweet').on('click',function(){
        alert("dsds");
    })


});
