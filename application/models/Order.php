<?php
/**
* This is a "CMS" model for quotes, but with bogus hard-coded data.
* This would be considered a "mock database" model.
*
* @author jim
*/
//Copied menu model for reference 
class Order extends CI_Model {
    protected $xml = null;
    // protected $patty_names = array();
    protected $processedOrds = array();
    protected $orderDetails = array();
    // Constructor
    public function __construct() {
    parent::__construct();
    $listOfOrders = $this->getOrdersList(); 
    //Process each order from xml file 
    foreach ($listOfOrders as $order) {
    $this->xml = simplexml_load_file(DATAPATH . $order);
    $this->orderDetails[] = array('ordernum' => pathinfo($order)['filename'],
    'customer' => (string) $this->xml->customer);
    $orderTotal = 0.0;
    $file = array();
    $burgerCount = 0;
    $file['ordertype'] = (string) $this->xml['type'];
    $file['customer'] = (string) $this->xml->customer;
    foreach($this->xml->burger as $burger) {
    $selectedburger = array();
    $burgertotal = 0.0;
    $selectedburger['burgernum'] = ++$burgerCount;
    // Process patty for burger
    $ppatty = $this->menu->getPatty((string)$burger->patty['type']);
    $selectedburger['patty'] = (string)$ppatty->name;
    $burgertotal += (float)$ppatty->price;
    $orderTotal += (float)$ppatty->price;
    // Process cheese for burger
    $cheesesList = '';
    if(isset($burger->cheeses)) {
    // Top
    if(isset($burger->cheeses['top'])) {
    $pcheese = $this->menu->getCheese((string)$burger->cheeses['top']);
    $cheesesList = (string)$pcheese->name . ' (top)';
    $burgertotal += (float)$pcheese->price;
    $orderTotal += (float)$pcheese->price;
    }
    // Bottom
    if(isset($burger->cheeses['bottom'])) {
    $pcheese = $this->menu->getCheese((string)$burger->cheeses['bottom']);

    $cheesesList = $cheesesList . ' ' . (string)$pcheese->name . ' (bottom)';
    $burgertotal += (float)$pcheese->price;
    $orderTotal += (float)$pcheese->price;
    }
    }
    $selectedburger['cheeses'] = trim($cheesesList);
    // Parse toppings info
    $toppings = '';
    if(!isset($burger->topping)) {
    $toppings = 'none';
    $selectedburger['toppings'] = $toppings;
    } else {
    foreach($burger->topping as $topping) {
    $ptopping = $this->menu->getTopping((string) $topping['type']);
    $toppings = $toppings . $ptopping->name . ', ';
    $burgertotal += (float) $ptopping->price;
    $orderTotal += (float) $ptopping->price;
    }
    $selectedburger['toppings'] = substr($toppings, 0, strlen($toppings) - 2);
    }
    // Parse sauces
    $sauces = '';
    if(!isset($burger->sauce)) {
    $sauces = 'none';
    $selectedburger['sauces'] = $sauces;
    } else {
    foreach($burger->sauce as $sauce) {
    $psauce = $this->menu->getSauce((string) $sauce['type']);
    $sauces = $sauces . (string)$psauce->name . ', ';
    }
    $selectedburger['sauces'] = substr($sauces, 0, strlen($sauces) - 2);
    }
    $selectedburger['instructions'] = (string)$this->xml->burger->instructions;
    $selectedburger['total'] = $burgertotal;
    $file['burgers'][] = $selectedburger;
    }
    $file['ordertotal'] = $orderTotal;
    $this->processedOrds[pathinfo($order)['filename']] = $file;
    }
    }
    // get all details
    function getOrders() {
    return $this->orderDetails;
    }
    // for a  order 
    function getOrder($orderNum) {
    if(isset($this->processedOrds[$orderNum])) {
    return $this->processedOrds[$orderNum];
    } else {
    return null;
    }
    }
    private function getOrdersList() {
    $path = 'data';
    $this->load->helper('directory');
    $map = directory_map($path, 1, TRUE);
    return $this->filterOrders($map);
    }
   
 
     private function filterOrders($mydata) {
    $myFiles = [];
    foreach($mydata as $file) {
    if(preg_match('#^order.*.xml$#', $file) == 1) {
    $myFiles[] = $file;
    }
    }
    return $myFiles;
    }
}