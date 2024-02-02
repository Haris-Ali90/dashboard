@if(can_access_route('orderConfirmation.transfer',$userPermissoins))
    <button class="btn orange-gradient edit" type="submit" data-id='<?php echo $record->id; ?>'>Order Approval </button>
@endif