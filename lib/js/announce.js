window.fbAsyncInit = function() {
    FB.init({
        appId      : '646223165562165',
        xfbml      : true,
        version    : 'v2.8'
    });
    FB.AppEvents.logPageView();

    var AccessToken = 'EAAJLvI46nTUBAGIwMrc7qL9yRab2kX7NFd73M5MW9LHHTn0ruHZBFFXLA2BQwuu918wS8w5ZBFhDaOoFKVCrhesIUjxf2yIUWvYgN59va9vF4WJ8WwmEro6v3YUboWo323nxhZB0scGZARZAMd6zyUTQVjecdKe4ZD';
    
    FB.api(
        '390769774648470?fields=id,name,cover,picture.height(1080),posts.limit(12){message,full_picture,description,status_type,parent_id,created_time}', 'get',
        {
            access_token: AccessToken
        },
        function(response) {
            if (!response || response.error) {
                alert('Error occured');
            } else {
                //alert(response.data[0].status_type);
                //console.log(response);

                $('<img/>', {
                    src: response.picture.data.url,
                    alt: 'Fan Page Picture',
                    style: 'width: 100%; border: 1px solid lightgrey;'
                }).appendTo('.title-pic');

                $('<a/>', {
                    href: 'http://www.facebook.com/' + response.id,
                    html: '<b style="margin: 0px; color: black;">' + response.name +'</b>'
                }).appendTo('.title-name');

                var width = $(window).width();
                if (width < 768)
                    $('.title-cover').css('display','none');
                else
                    $('.title-cover').css('display','block');
                if (width < 768)
                    $('.title-name').css('font-size','160%');
                else if (width < 992)
                    $('.title-name').css('font-size','110%');
                else if (width < 1170)
                    $('.title-name').css('font-size','130%');
                else
                    $('.title-name').css('font-size','150%');
                
                $('<img/>', {
                    src: response.cover.source,
                    alt: 'Fan Page Cover',
                    style: 'width: 100%;'
                }).appendTo('.title-cover');

                var post_N = response.posts.data.length;
                for (var i = 0; i < post_N; i++) {
                    var element = response.posts.data[i];
                    var po_container = $('<div>', {
                        "id" : "post-container",
                        "class": "col-xs-12 col-sm-12 col-md-6",
                    });

                    var po_item = $('<div>', {
                        "class": "post-item",
                    }).appendTo(po_container);
                    var po_box = $('<a>', {
                        "target":"_blank",
                        "href":"https://fb.com/"+response.id+"/posts/"+element.id.substr(response.id.length + 1),
                    }).appendTo(po_item);

                    if( element.full_picture ) {
                         $('<div>', {
                        "class": "image-clip",
                        "style": "background-image: url(\""+element.full_picture+"\"); background-size: cover; background-position: center center; background-repeat: no-repeat;"
                        }).appendTo(po_box);                  
                    } else {

                    }
                    if( element.message ) {
                        $('<p>', {
                            "class": "summary",
                            "text": element.message ,
                        }).appendTo(po_item);
                        po_container.appendTo('#post-container');
                    } else {
                        
                    }

                    var link_box = $('<a>', {
                        "target":"_blank",
                        "href":"https://fb.com/"+response.id+"/posts/"+element.id.substr(response.id.length + 1),
                    }).appendTo(po_item);
                    var link_text = $('<p>', {
                        "text":"...看原文",
                        "class":"link-text",
                    }).appendTo(link_box);
                }
            }
        }
    );
};

// fb js.sdk init
(function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));

$(window).resize(function(){
    var width = $(window).width();
    if (width < 768)
        $('.title-cover').css('display','none');
    else
        $('.title-cover').css('display','block');
    if (width < 768)
        $('.title-name').css('font-size','160%');
    else if (width < 992)
        $('.title-name').css('font-size','110%');
    else if (width < 1170)
        $('.title-name').css('font-size','130%');
    else
        $('.title-name').css('font-size','150%');
});