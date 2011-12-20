<div class="view">

<table>
<th colspan="2"><?php echo $data->first_name." bought ".$data->getUserBoughtCount($this->deal_id)." coupon(s)."; ?>
<tr><td align='right'>Name:<td><?php echo $data->first_name. " ".$data->last_name; ?>
<tr><td align='right'>Location:<td><?php echo  $data->location; ?>
<tr><td align='right'>E-mail:<td><?php  echo $data->email; ?>
<tr><td align='right'>Gender:<td><?php  echo $data->gender; ?>
<tr><td align='right'>Time zone:<td><?php  echo "GMT+".$data->timezone; ?>
<tr><td align='right'>Locale:<td><?php  echo $data->locale; ?>
<tr><td align='right'>Country:<td><?php  echo $data->country; ?>
<tr><td align='right'>buyer since:<td><?php  echo $data->create_time; ?>
</table>
</div>


