<table>
<th align='left' colspan='4'>Submit Order</th>
<tr><th>Deal Name</th><th>Quantity</th><th>Price</th><th>Total</th>
<tr style='border:solid 1px;background:silver;'>
<td><?php echo $this->deal->title; ?></td>
<td><?php echo CHtml::textField('quantity','1',array('size'=>'2','onkeyup'=>'calculate_total();','onchange'=>'check()'))."<br><div id='max_qty'>max:".($this->deal->max_available-$this->deal->sold_no)."</div>"; ?></td>
<td><div id='price'><?php echo "$".($this->deal->retail_price-$this->deal->discount_value); ?> </div></td>

<td> <div id='total'><?php  echo "$".($this->deal->retail_price-$this->deal->discount_value); ?></div></td>

</tr>
<tr style='color:red;'>
<td>Total amount of orders value:</td><td><td><td> <div id='total_amt' > <?php echo "$".($this->deal->retail_price-$this->deal->discount_value); ?></div>
</tr>

</table>

<script type='text/javascript'>
function calculate_total()
{


var qty=document.getElementById('quantity').value;
var max_qty=document.getElementById('max_qty').innerHTML;
max_qty=parseInt(max_qty.substring(4));

if(qty>max_qty)
 {
  // alert("You cannot buy more than "+max_qty);
  return;
 }

var price=document.getElementById('price').innerHTML;
 price=price.substring(1);

if(qty==0||qty=="")
{
if(qty=="")
return;
alert("Invalid quantity");
return;
}

if(!checkforInteger(qty))
 {
  alert("Invalid quantity"); 
  return;
 }

document.getElementById('total').innerHTML='$'+(qty*price);
document.getElementById('total_amt').innerHTML='$'+(qty*price);

}
function checkforInteger(value)
 {
        if (parseInt(value) != value)
	   return false;
        else 
           return true;
 }
function check()
{
var qty=document.getElementById('quantity').value;
var max_qty=document.getElementById('max_qty').innerHTML;
max_qty=parseInt(max_qty.substring(4));

if(qty>max_qty)
 {
  alert("You cannot buy more than "+max_qty);
  return;
 }
}
</script>
