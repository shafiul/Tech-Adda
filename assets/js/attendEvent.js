console.log(this);
$('input.attendButton').click(function(){
    console.log(this);
    eventId = $(this).attr('id').substr(12);
    $.post(
        BASE_URL + '?page=attendAjax',
        {
            'event_id': eventId
        },
        function(data){
            response = jQuery.parseJSON(data);
            console.log(response);
            if(response[0] == true){
                $('#attendButton'+eventId).parent().append('<a class="btn success" href="#">You Attending</a>');
                $('#attendButton'+eventId).hide();
            }else{
                $('#attendButton'+eventId).attr('value', 'Try again');
            }
        }
        );
});