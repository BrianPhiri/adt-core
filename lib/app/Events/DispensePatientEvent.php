<?php

namespace App\Events;

use App\Models\VisitModels\Appointment;
use App\Models\VisitModels\Visit;
use App\Models\VisitModels\VisitItem;
use App\Models\InventoryModels\StockItem;

class DispensePatientEvent extends Event
{

    protected $visit_information;
    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct($request, $stock_item_id)
    {
        $this->visit_information = $request;
        $this->handle($stock_item_id);
    }

    public function handle(){
        $this->stockTransaction();
    }

    // STOCK TRANSACTION
    public function stockTransaction(){
        $new_stock = new Stock;
        $new_stock->transaction_time = $this->visit_information['transaction_date'];
        $new_stock->ref_number = $this->visit_information['ref_number'];
        $new_stock->user_id = 1;
        $new_stock->store_id = $this->store_id;
        $new_stock->facility_id = 1;
        if(array_key_exists('transaction_detail',$this->visit_information)){
            $new_stock->transaction_detail = $this->visit_information['transaction_detail'];
        }
        $new_stock->transaction_type_id = $this->visit_information['transaction_type_id'];
        $indication['indication_id'] = 1; //temp

        // VISITS
        $visit_update = Visit::create($this->visit_information);

        if($new_stock->save() && $visit_update){
            $new_visit_id['visit_id'] = $visit_update->id;
            $make_appointment = Appointment::create($this->visit_information);
            if(array_key_exists('change_reason_id', $this->visit_information)){
                //  add to regimen change
            }
            if(array_key_exists('drugs', $this->visit_information)){
                if(array_key_exists('store_id', $this->visit_information)){
                    $store = $this->visit_information['store_id'];
                }else{
                    $store = $this->visit_information['store'];
                }

                $new_stock_id['stock_id'] = $new_stock->id;
                $stock_items = $this->visit_information['drugs'];
                
                foreach($stock_items as $key => $stock_item){
                    $new_item = new StockItem;
                    $new_item->stock_id = $new_stock_id['stock_id'];
                    $new_item->batch_number = $stock_item['batch_number'];
                    $new_item->drug_id = $stock_item['drug_id'];
                    $new_item->store = $store; 
                    $new_item->unit_cost = $stock_item['unit_cost'];
                    $new_item->expiry_date = $stock_item['expiry_date'];
                    $new_item->quantity_packs = $stock_item['quantity_packs'];
                    if($stock_item['balance_before'] > 0){
                        $balance_before = $stock_item['balance_before'];
                    }else{
                        $balance_before = 0;
                    }
                    $new_item->balance_before = $balance_before;
                    if($this->transaction_qty_type == 'in'){
                        $new_item->quantity_in = $stock_item['quantity'];
                    }else{
                        $new_item->quantity_out = $stock_item['quantity'];
                    }
                    if($new_item->save()){
                        $stock_item_id['stock_item_id'] = $new_item->id();
                        $unit_cost['unit_cost'] = 10;
                        $visit_item = array_merge($unit_cost,$stock_item, $new_visit_id, $stock_item_id, $indication);
                        // add visit item
                        VisitItem::create($visit_item);
                    }
                }
            }
        }        
    }
}