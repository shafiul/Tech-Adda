<div >
    <form id="commentForm"  class="validationForm" action="<?php echo BASE_URL . '?page=' . ($mode == 'talk' ? 'talk' : 'event') . '&id=' . $modeId; ?>" method="post">
        <fieldset>

            <div class="clearfix">
                <label for="xlInput3">Rating</label>
                <div class="input">
                    <input name="star" type="radio" class="star"/>
                    <input name="star"  type="radio" class="star"/>
                    <input name="star" type="radio" class="star" checked="checked"/>
                    <input name="star" type="radio" class="star"/>
                    <input name="star" type="radio" class="star"/>
                </div>
                <input name="rating" type="hidden" value=""/>
            </div>

            <div class="clearfix">
                <label for="xlInput3">Leave a comment *</label>
                <div class="input">
                    <textarea class="span6 required" id="body" name="body" value="<?php echo ViewHelper::getInput('body') ?>" rows="7" cols="30"></textarea>
                </div>
            </div>

            <input type="hidden" name="modeId" value="<?php echo $modeId; ?>"></input>
            <input type="hidden" name="mode" value="<?php echo $mode == 'talk' ? 'talk' : 'event'; ?>"></input>

            <div class="actions transparent">
                <input type="submit" value="Submit" class="btn primary">
            </div>
        </fieldset>
    </form>
    <script type="text/javascript">
        $('#commentForm').submit(function(){
            try{
                var stars = $('input.star');
                var rating=0;
                var i;
                for(i=4;i>=0; i--){
                    if($(stars[i]).attr('checked') != undefined){
                        $('input[name="rating"]').attr('value', i+1);
//                        $('input[name="rating"]').value(i+1);
                        break;
                    }
                }
            }catch(e){
                console.log(e);
                return false;   
            }
            
            return true;
        });
    </script>
</div>
