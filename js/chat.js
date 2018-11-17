(function($){
    $messages_container = $(".messages-container");
    $new_messages = $('.new_messages');
    $message_form = $("#message-form");

    $message_form.on('submit',send_message);

    //scroll to end if clicked on new messages
    $new_messages.on('click', function(){
        $new_messages.removeClass('active');
        $messages_container.scrollTop(
            $messages_container[0].scrollHeight
        );
    });

    function send_message(evt)
    {
        evt.preventDefault();
        let $message = $message_form.find('input[name=message]');
        let message = $message.val();
        $message.val('');

        $.ajax({
            type:'post',
            url:BASE_URL+'/send_message.php',
            data:{
                message:message,
                uid:profile.user_id
            },
            success:(d)=>{
                let response = JSON.parse(d);
                if(response.status == 'success')
                {
                    $messages_container.append(
                        $(`<div class="message message-your">${message}</div>`)
                    );
                    $messages_container.append($('<div class="clearfix" />'));

                    //scroll to end
                    $messages_container.scrollTop(
                        $messages_container[0].scrollHeight
                    );

                }else{
                    alert("Error sending message");
                }
            }
        })

    }


    //load messages every 1 second
    setInterval(loadMessages, 1000);
    function loadMessages()
    {
        //clear old messages in container
        //find total messages we will need that as an offset
        $messages = $messages_container.find('.message');
        let total = $messages.length;

        let height = $messages_container.height();
        let oldScrollHeight = $messages_container[0].scrollHeight;
        let oldScrollY = $messages_container[0].scrollTop;
        let oldScrollSum = oldScrollY+height;

        $.ajax({
            url:BASE_URL+'/load_messages.php',
            type:'POST',
            data:{
                total:total,
                uid:profile.user_id
            },
            success:(data)=>{
                //convert to json
                let messages_data = JSON.parse(data);
                console.log(messages_data);
                let status = messages_data['status'];

                if(status!='success')
                {
                    alert("error while fetching messages");
                    return;
                }

                let messages = messages_data['messages'];
                if(messages.length==0)
                {
                    return;
                }

                for(message of messages){
                    $message = $("<div/>");
                    $message.addClass('message');
                    //if sender is myself
                    if(message.user_id==user.user_id)
                    {
                        $message.addClass('message-your');
                    }else{
                        $message.addClass('message-other');
                    }
                    $message.html(message.message);

                    $messages_container.append($message);
                    //add clearfix after every message
                    $messages_container.append($("<div class='clearfix'/>"));
                }

                //we need to scroll down if user was at end of the messages
                //or we leave it to its old place we will use old positions
                //to decide
                //if sum of visible hieght + scrollY == total heaight
                //means we are at end of the document so we will scroll to end again
                if(Math.abs(oldScrollSum-oldScrollHeight)<1)
                {
                    $messages_container.scrollTop(
                        $messages_container[0].scrollHeight
                    );
                }else{
                    $new_messages.addClass('active');
                }



            }
        });


    }
})(jQuery);