<?php
/**
* Our homepage. Show the most recently added quote.
*
* controllers/Welcome.php
*
* ------------------------------------------------------------------------
*/
class Welcome extends Application {
function __construct()
{
parent::__construct();
}
//-------------------------------------------------------------
// Homepage: show a list of the orders on file
//-------------------------------------------------------------
function index()
{
// Build a list of orders
// Present the list to choose from
$allorders = $this->order->getOrders();

$this->data['orders'] = $allorders;
$this->data['pagebody'] = 'homepage';
$this->render();
}
//-------------------------------------------------------------
// Show the "receipt" for a specific order
//-------------------------------------------------------------
function order($orderNo)
{
// Build a receipt for the chosen order
$order = $this->order->getOrder($orderNo);
// Present the list to choose from
$this->data['pagebody'] = 'justone';
// display order details to the user
$this->data['ordernum'] = $orderNo;
$this->data['ordertype'] = $order['ordertype'];
$this->data['customer'] = $order['customer'];
$this->data['ordertotal'] = $order['ordertotal'];
$this->data['burgers'] = $order['burgers'];

$this->render();
}
}