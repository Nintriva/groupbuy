<div class="view">

<table>
<th colspan="2"><?php echo $this->buyer->first_name." bought ".$this->buyer->getUserBoughtCount($this->deal_id)." coupon(s)."; ?>
<tr><td align='right'>Name:<td><?php echo $this->buyer->first_name. " ".$this->buyer->last_name; ?>
<tr><td align='right'>Location:<td><?php echo  $this->buyer->location; ?>
<tr><td align='right'>E-mail:<td><?php  echo $this->buyer->email; ?>
<tr><td align='right'>Gender:<td><?php  echo $this->buyer->gender; ?>
<tr><td align='right'>Time zone:<td><?php  echo "GMT+".$this->buyer->timezone; ?>
<tr><td align='right'>Locale:<td><?php  echo $this->buyer->locale; ?>
<tr><td align='right'>Country:<td><?php  echo $this->buyer->country; ?>
<tr><td align='right'>buyer since:<td><?php  echo $this->buyer->create_time; ?>
</table>
</div>


