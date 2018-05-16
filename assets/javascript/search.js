window.onload = function () {
    //search action
    $('.searchm').keyup(function () {
        var search = $(this).val();
        $.post('http://localhost/twtter/?action=search', {
            search: search

        }, function (data) {
            $('.search-result').html(data);
        });
        $(window).click(function () {
            $('.search-result').html('');

        })

    })
    $(document).on('click', '.deleteTweet', function () {
        var tweetId = $(this).data('tweet');

        $.post('http://localhost/twtter/?action=delete', {
            deleteTweet: tweetId
        });
        var del = document.querySelector('.all-tweet');
        del.remove();

    })
    $('.status').keyup(function () {
        $("#count").text((140 - $(this).val().length));
        var count = $("#count").text((140 - $(this).val().length));

    })
    $('#tweetBtn').on('click', function () {
        var tweet = $(this).val();
        var status = document.getElementById('status').value;
        var wrap = document.querySelector('.all-tweet');

        $.post('http://localhost/twtter/?action=tweet', {
            tweet: tweet,
            status: status
        }, function (wr) {
            console.log(wr);
            $(wrap).prepend(status);
            return false;

        });
    })
    //retweet action
    $('.retweet').on('click', function (data) {

        var tweetId = $(this).data('tweet');
        var user = $(this).data('user');
        $counter = $(this).find('.retweetsCount');
        $count = $counter.text();
        $button = $('.retweet-it');

        $.post('http://localhost/twtter/?action=retweet', {
                // showPopup:tweetId, 
                user: user
            },


            function (data) {
                $('.popupTweet').html(data);
            })
        console.log(showPopup);
        console.log(user);


    })

    //like function
    $(document).on('click', '.likeBtn', function () {
        var tweetId = $(this).data('tweet');
        var userId = $(this).data('user');
        var counter = $(this).find('.likeCount');
        var count = counter.text();
        var button = $(this);
        $.post('http://localhost/twtter/?action=like', {
            like: tweetId
        }, function () {
            button.addClass('unlike-btn');
            button.removeClass('like-btn');
            count++;
            counter.text(count);
            button.find('.fa-heart-o').addClass('fa-heart');
            button.find('.fa-heart').removeClass('fa-heart-o');

        })
        console.log(count);
        if (count > 2) {
            button.find('.fa-heart').removeClass('fa-heart-o');
            return false;
        }
    })
    //follow action 
    $('.follow-btn').on('click', function () {
        var follow = $(this).data('user');
        var baseUrl = (window.location).href; // You can also use document.URL
        var id = baseUrl.substring(baseUrl.lastIndexOf('=') + 1);
        $.post('http://localhost/twtter/?action=follow&id=' + id, {
            follow: follow,
            id:id
        });
        $('.follow-btn').html("Following");
        
        if( $('.follow-btn') == "following"){
           alert("ds");

        }
    });
    


};