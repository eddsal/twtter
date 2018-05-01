window.onload = function(){


$.ajax({url: "Model/TweetManager.php"}).done(function( html ) {
    $(".tweets").append(html);
});