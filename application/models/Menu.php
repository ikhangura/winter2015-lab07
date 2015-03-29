<?php
/**
* This is a "CMS" model for quotes, but with bogus hard-coded data.
* This would be considered a "mock database" model.
*
* @author jim
*/
class Menu extends CI_Model {
    protected $xml = null;
    // protected $patty_names = array();
    protected $patties = array();
    protected $cheeses = array();
    protected $toppings = array();
    protected $sauces = array();
// Constructor
public function __construct() {
parent::__construct();
$this->xml = simplexml_load_file(DATAPATH . 'menu.xml');

 // build the list of patties - approach 1
//foreach ($this->xml->patties->patty as $patty) {
//$patty_names[(string) $patty['code']] = (string) $patty;
//}

// build a full list of patties - approach 2
foreach ($this->xml->patties->patty as $patty) {
$record = new stdClass();
$record->code = (string) $patty['code'];
$record->name = (string) $patty;
$record->price = (float) $patty['price'];
$this->patties[$record->code] = $record;
}

//using approch 2 for all othe tuuff
// build a full list of cheeses
foreach($this->xml->cheeses->cheese as $cheese) {
$record = new stdClass();
$record->code = (string) $cheese['code'];
$record->price = (string) $cheese['price'];
$record->name = $cheese;
$this->cheeses[$record->code] = $record;
}
// build a full list of toppings
foreach($this->xml->toppings->topping as $topping) {
$record = new stdClass();
$record->code = (string) $topping['code'];
$record->price = (string) $topping['price'];
$record->name = $topping;
$this->toppings[$record->code] = $record;
}
// build a full list of sauces
foreach($this->xml->sauces->sauce as $sauce) {
$record = new stdClass();
$record->code = (string) $sauce['code'];
$record->name = $sauce;
$this->sauces[$record->code] = $record;
}

}
// retrieve a patty record, perhaps for pricing
public function getPatty($code) {
if (isset($this->patties[$code])) {
return $this->patties[$code];
}
return null;
}
// retrieve a Cheese record, perhaps for pricing
public function getCheese($code) {
if (isset($this->cheeses[$code])) {
return $this->cheeses[$code];
}
return null;
}
// retrieve a Toppings record, perhaps for pricing
public function getTopping($code) {
if (isset($this->toppings[$code])) {
return $this->toppings[$code];
}
return null;
}
// retrieve a Sauce record, perhaps for pricing
public function getSauce($code) {
if (isset($this->sauces[$code])) {
return $this->sauces[$code];
}
return null;
}
}