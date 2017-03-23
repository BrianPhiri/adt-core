<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\InventoryModels\StockItem;
use App\Models\InventoryModels\Stock;
use App\Models\InventoryModels\Store;

use App\Models\InventoryModels\TransactionType;
use App\Models\DrugModels\Drug;
use App\Models\InventoryModels\RecordedStockItems;
use App\Models\InventoryModels\StockBalance;

use App\Events\StockTransactionEvent;

class StockApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // $this->middleware('api.auth');
    }

    /**
     * Operation stocks
     *
     * Fetch a 's stock.
     *
     
     * @param int  ID&#39;s of  that needs to be fetched (required)
     *
     * @return Http response
     */
    public function stockget()
    {
        $response = Stock::get();
        if(!$response){  
            return response()->json(['msg' => 'could not find stock'], 204);
        }else{  
            return response()->json($response, 200);
        }
    }
    /**
     * Operation stocks
     *
     * Fetch a 's stock.
     *
     
     * @param int  ID&#39;s of  that needs to be fetched (required)
     * @param int $stock_id ID stock that needs to be fetched (required)
     *
     * @return Http response
     */
    public function stockByIdget($stock_id)
    {
        $response = Stock::findOrFail($stock_id);
        if(!$response){  
            return response()->json(['msg' => 'could not find stock for this '], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation addstocks
     *
     * Add a new stock to a .
     *
     * @param int  ID&#39;s of  (required)
     *
     * @return Http response
     */
    public function stockpost($store_id)
    {
        $input = Request::all();
        $transaction_qty_type = '';

        if(array_key_exists('transaction_type_id', $input)){
            $transaction_type_id = $input['transaction_type_id'];
            $transaction_type = TransactionType::findOrFail($transaction_type_id);
            $kind_of_transaction = 'plain';
            if(!$transaction_type['effect'] == ''){
                $transaction_qty_type =  'in';
            }else{
                $transaction_qty_type = 'out';
            }
        } 
        event(new StockTransactionEvent($input, $transaction_qty_type, $store_id, $kind_of_transaction));
        return response()->json(['msg'=> 'Transaction complite', 'response'=> $input], 201);
    }

    /**
     * Operation updatestocks
     *
     * Update an existing stocks .
     *
     * @param int   id to update (required)
     * @param int $stock_id stock id to update (required)
     *
     * @return Http response
     */
    public function stockput($stock_id)
    {
        $input = Request::all();
        $stock = Stock::findOrFail($stock_id)->update([
                                        
        ]);
        if($stock){
            return response()->json(['msg' => 'Updated stock']);
        }else{
            return response()->json(['msg' => 'Could not update record'], 405);
        }
    }

    /**
     * Operation deletestock
     *
     * Remove a  stock.
     *
     * @param int  ID&#39;s of  and tb that needs to be fetched (required)
     * @param int $stock_id ID of tb that needs to be fetched (required)
     *
     * @return Http response
     */
    public function stockdelete($stock_id)
    {
        $deleted_stock = Stock::destroy($stock_id);
        if($deleted_stock){
            return response()->json(['msg' => 'Saftly deleted the  stock'],200);
        }else{
            return response()->json(['msg' => 'Could not delete record'], 400);
        }
    }

    // stock items 

    /**
     * Operation stockItems
     *
     * Fetch a visit's stockItem.
     *
     
     * @param int $stock_id ID&#39;s of visit that needs to be fetched (required)
     *
     * @return Http response
     */
    public function stockItemget($stock_id)
    {
        $response = StockItem::where('stock_id',  $stock_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find vist item'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation stockItems
     *
     * Fetch a visit's stockItem.
     *
     
     * @param int $stock_id ID&#39;s of visit that needs to be fetched (required)
     * @param int $stock_item_id ID stockItem that needs to be fetched (required)
     *
     * @return Http response
     */
    public function stockItemByIdget($stock_id, $stock_item_id)
    {
        $response = StockItem::where('stock_id', $stock_id)
                                            ->where('id', $stock_item_id)
                                            ->first();
        if(!$response){  
            return response()->json(['msg' => 'could not find stockItem for this visit'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation addstockItems
     *
     * Add a new stockItem to a visit.
     *
     * @param int $stock_id ID&#39;s of visit (required)
     *
     * @return Http response
     */
    public function StockItempost()
    {
        $input = Request::all();
        $new_stockItem = StockItem::create($input);
        if($new_stockItem){
            return response()->json(['msg'=> 'added stockItem to visit', 'response'=> $new_stockItem], 201);
        }else{
            return response()->json(['msg'=> 'Could not add stockItem'], 400);
        }
    }

    /**
     * Operation updatestockItems
     *
     * Update an existing stockItems .
     *
     * @param int $stock_id visit id to update (required)
     * @param int $stock_item_id stockItem id to update (required)
     *
     * @return Http response
     */
    public function stockItemput($stock_id, $stock_item_id)
    {
        $input = Request::all();
        $visit_stockItem = StockItem::where('stock_id', $stock_id)
                                    ->where('id', $stock_item_id)
                                    ->update([
                                        "batch_number" => $input['batch_number'],
                                        "expiry_date" => $input['expiry_date'],
                                        "quantity_in" => $input['quantity_in'],
                                        "quantity_out" => $input['quantity_out'],
                                        "quantity_packs" => $input['quantity_packs'],
                                        "balance_before" => $input['balance_before'],
                                        "balance_after" => $input['balance_after'],
                                        "unit_cost" => $input['unit_cost'],
                                        "total_cost" => $input['total_cost'],
                                        "comment" => $input['comment'],
                                        "drug_id" => $input['drug_id'],
                                        "stock_id" => $input['stock_id']
                                    ]);
        if($visit_stockItem){
            return response()->json(['msg' => 'Updated stockItem']);
        }else{
            return response()->json(['msg' => 'Could not update record'], 405);
        }
    }

    /**
     * Operation deletestockItem
     *
     * Remove a visit stockItem.
     *
     * @param int $stock_id ID&#39;s of visit and tb that needs to be fetched (required)
     * @param int $stock_item_id ID of tb that needs to be fetched (required)
     *
     * @return Http response
     */
    public function stockItemdelete($stock_id, $stock_item_id)
    {
        $visit_tb = StockItem::where('stock_id', $stock_id)
                                            ->where('id', $stock_item_id)
                                            ->delete();
        if($visit_tb){
            return response()->json(['msg' => 'Saftly deleted the visit stockItem'],200);
        }else{
            return response()->json(['msg' => 'Could not delete record'], 400);
        }
    }

    // drug balance

    /**
     * Operation stockItemBalance
     *
     * fetches a list stock balances for an item.
     *
     *
     * @return Http response
     */
    public function stockItemBalanceget($stock_item_id)
    {
        $response = StockBalance::where('stock_item_id', $stock_item_id)->get();
        return response()->json($response, 200);
    }
    /**
     * Operation stockItemBalanceDrugIdGet
     *
     * Find drug by drugId.
     *
     * @param int $balance_id Particular Service at facility specified by the ID (required)
     *
     * @return Http response
     */
    public function stockItemBalanceByIdget($stock_item_id, $balance_id)
    {
        $response = StockBalance::where('stock_item_id', $stock_item_id)->where('id', $balance_id)->get();
        return response()->json($response, 200);
    }
    /**
     * Operation stockItemBalancePost
     *
     * Add a new service to the facility.
     *
     *
     * @return Http response
     */
    public function stockItemBalancepost($stock_item_id)
    {
        $input = Request::all();
        $new_stock_item_balance = StockBalance::create($input);
        if($new_stock_item_balance){
            return response()->json(['msg' => 'Added new stock item balance', 'balance' => $new_stock_item_balance],201);
        }
        return response()->json(['msg' => 'could not save drug to regimen'],400);
    }
    /**
     * Operation stockItemBalanceDrugIdPut
     *
     * Update an existing drug specified by the drugId.
     *
     * @param int $balance_id Particular Service at facility specified by the ID (required)
     *
     * @return Http response
     */
    public function stockItemBalanceput($stock_item_id, $balance_id)
    {
        $input = Request::all();
        $stock_item_balance = StockBalance::where('stock_item_id', $stock_item_id)->where('id', $balance_id)
                            ->update([
                                "stock_item_id" => $input['stock_item_id'],
                                "stock_type" => $input['stock_type'],
                                "balance" => $input['balance']
                            ]);
        if($stock_item_balance){
            return response()->json(['msg' => 'updated balance', 'updated balance' => $stock_item_balance],202);
        }else{
            return response()->json(['msg' => 'Could not update'], 400);
        }
    }
    /**
     * Operation stockItemBalanceDrugIdDelete
     *
     * Deletes the drug specified by drugId.
     *
     * @param int $balance_id Particular Service at facility specified by the ID (required)
     *
     * @return Http response
     */
    public function stockItemBalancedelete($stock_item_id, $balance_id)
    {
        $deleted_stock_item_balance = StockBalance::where('stock_item_id', $stock_item_id)->where('id', $balance_id)->delete();
        if($deleted_stock_item_balance){
            return response()->json(['msg' => 'Deleted balance'],301);
        }
    }

    // bincard
    /**
     * Operation stockStockIdBincardGet
     *
     * Fetch all details of a commodity specified by stockId.
     *
     * @param int $stock_id ID of commodity whose details needs to be fetched (required)
     *
     * @return Http response
     */
    public function stockBincardget($drug_id)
    {
        $transactions = Drug::with('unit','stock_item', 'stock_item.stock')->where('id', $drug_id)->get();

        $batch_information = Drug::with('unit','stock_item')->where('id', $drug_id)->whereHas('stock_item.balance', function($query){
            $query->where('balance', '>', '0');
        })->get();

        $drug_information = StockBalance::where('drug_id', $drug_id)->get();
        
        $response = [
        'transactions' => $transactions,
        'batch_information' => $batch_information,
        'drug_information' => $drug_information
        ];
        return response()->json($response,200);
    }


    // transactions
    public function stock_transaction_get(){
        return response()->json(TransactionType::with('stock.stock_item')->get(),200);
    }


    public function storeget(){
        $response = Store::get();
        return response()->json($response, 200);
    }
    public function storeByIdget($store_id){
        $input = Request::all();
        $response = Store::findOrFail($store_id);
        return response()->json($response, 200);
    }

    public function storepost(){
        $input = Request::all();
        $new_store = Store::create($input);
        if($new_store){
            return response()->json(['msg' => 'Store added' , 'data'=>$new_store]);
        }else{
            return response('Failed to add Store');
        }
    }

    public function storeput($store_id)
    {
        $input = Request::all();
        $store = Store::findOrFail($store_id);
        $store->update([
            'name' => $input['name'],
            'type' => $input['type'],
            'facility_id' => $input['facility_id']
            ]);
        if($store->save()){
            return response()->json(['msg' => 'Update store' ,'data'=>$store]);
        }else{
            return response('Oops, it seems like there was a problem updating the store');
        } 
    }
    public function storesdelete($store_id)
    {
      $deleted_store = Store::destroy($store_id);
      if($deleted_store){
            return response()->json(['msg' => 'Deleted store']);
        }
    }

    // store stock 

    public function storeStockget($store_id){
        $response = DB::table('tbl_stock_item')
                          ->join('tbl_stock', 'tbl_stock_item.stock_id', 'tbl_stock.id')
                          ->join('tbl_store', 'tbl_stock.store_id', 'tbl_store.id')
                          ->join('tbl_drug', 'tbl_stock_item.drug_id', 'tbl_drug.id')
                          ->where('tbl_store.id', $store_id)
                          ->join('tbl_unit', 'tbl_drug.unit_id', 'tbl_unit.id')
                          ->join('tbl_dose', 'tbl_drug.dose_id', 'tbl_dose.id')
                          ->join('tbl_generic', 'tbl_drug.generic_id', 'tbl_generic.id')
                       ->select('tbl_unit.name as unit', 'pack_size', 'tbl_generic.name as generic', 
                                'tbl_dose.name as dose', 'batch_number', 'expiry_date', 'balance_before', 
                                'balance_after', 'unit_cost', 'comment', 'store', 'drug_id as id', 
                                'tbl_drug.name'
                                )
                       ->get()->unique('name')->values();

        return response()->json($response,200);
    }
 
    public function storeStockpost($store_id)
    {
        $input = Request::all();
        
        $transaction_qty_type = '';
        $kind_of_transaction = 'plain';

        if(array_key_exists('transaction_type_id', $input)){
            // return response()->json($input['drugs'], 200);

            $transaction_type_id = $input['transaction_type_id'];
            $transaction_type = TransactionType::findOrFail($transaction_type_id);
            if(!$transaction_type['effect'] == ''){
                $transaction_qty_type =  'in';
            }else{
                $transaction_qty_type = 'out';
            }
        }
        // $drugs = $input['drugs'];
        // foreach($drugs as $key => $drug){
        //     $new_item = new StockItem;
        //     $new_item->drug_id = $drug['drug_id'];
        //     $new_item->batch_number = $drug['batch_number']; 
        //     $new_item->expiry_date = $drug['expiry_date'];
        //     $new_item->quantity_packs = $drug['quantity_packs'];
        //     $new_item->quantity_in = $drug['quantity_in'];
        //     $new_item->balance_before = 0;
        //     $new_item->unit_cost = $drug['unit_cost'];
        //     $new_item->comment = "hello";  
        //     $new_item->stock_id = 1;  
        //     $new_item->save();         
        // }
        // return response()->json($a);
        event(new StockTransactionEvent($input, $transaction_qty_type, $store_id, $kind_of_transaction));
        return response()->json(['msg'=> 'Transaction complite', 'response'=> $input], 201);
    }

    public function storeStockDrugByIdget($store_id, $drug_id, $date){
        if($date == 'now'){
            $current_date = date('Y-m-d');
            $response = DB::table('tbl_store')
                       ->join('tbl_stock', 'tbl_store.id', 'tbl_stock.store_id')
                       ->join('tbl_stock_item', 'tbl_stock.id', 'tbl_stock_item.stock_id')
                       ->join('tbl_drug', 'tbl_stock_item.drug_id', 'tbl_drug.id')
                       ->join('tbl_unit', 'tbl_drug.unit_id', 'tbl_unit.id')
                       ->join('tbl_dose', 'tbl_drug.dose_id', 'tbl_dose.id')
                       ->join('tbl_generic', 'tbl_drug.generic_id', 'tbl_generic.id')
                       ->join('tbl_transaction_type', 'tbl_stock.transaction_type_id', 'tbl_transaction_type.id')
                       ->where('tbl_store.id', $store_id)
                       ->where('tbl_stock_item.drug_id', $drug_id) 
                       ->where('tbl_stock_item.expiry_date', '>', $current_date)  
                       ->select( 'tbl_unit.name as unit', 'pack_size', 'tbl_generic.name as generic', 
                                 'tbl_dose.name as dose', 'batch_number', 'expiry_date', 'balance_before', 
                                 'balance_after', 'unit_cost', 'comment', 'store', 'drug_id as id', 
                                 'tbl_drug.name', 'tbl_stock.ref_number', 'tbl_stock.transaction_time as transaction_date',
                                 'tbl_stock.transaction_detail', 'tbl_stock.transaction_type_id', 'tbl_transaction_type.name as transaction_name',
                                 'tbl_stock_item.expiry_date', 'tbl_stock_item.quantity_packs', 'tbl_stock_item.total_cost'
                               )
                       ->get()->unique('batch_number')->sortBy('expiry_date')->values();
        }else if($date == 'all'){
            $current_date = '0000:00:00';
            $response = DB::table('tbl_store')
                       ->join('tbl_stock', 'tbl_store.id', 'tbl_stock.store_id')
                       ->join('tbl_stock_item', 'tbl_stock.id', 'tbl_stock_item.stock_id')
                       ->join('tbl_drug', 'tbl_stock_item.drug_id', 'tbl_drug.id')
                       ->join('tbl_unit', 'tbl_drug.unit_id', 'tbl_unit.id')
                       ->join('tbl_dose', 'tbl_drug.dose_id', 'tbl_dose.id')
                       ->join('tbl_generic', 'tbl_drug.generic_id', 'tbl_generic.id')
                       ->join('tbl_transaction_type', 'tbl_stock.transaction_type_id', 'tbl_transaction_type.id')
                       ->where('tbl_store.id', $store_id)
                       ->where('tbl_stock_item.drug_id', $drug_id) 
                       ->where('tbl_stock_item.expiry_date', '>', $current_date)  
                       ->select( 'tbl_unit.name as unit', 'pack_size', 'tbl_generic.name as generic', 
                                 'tbl_dose.name as dose', 'batch_number', 'expiry_date', 'balance_before', 
                                 'balance_after', 'unit_cost', 'comment', 'store', 'drug_id as id', 
                                 'tbl_drug.name', 'tbl_stock.ref_number', 'tbl_stock.transaction_time as transaction_date',
                                 'tbl_stock.transaction_detail', 'tbl_stock.transaction_type_id', 'tbl_transaction_type.name as transaction_name',
                                 'tbl_stock_item.expiry_date', 'tbl_stock_item.quantity_packs', 'tbl_stock_item.total_cost'
                               )
                       ->get();
        }
        else{
            return response()->json(['msg' => 'please enter a valid route']);
        }
        
        return response()->json($response,200);
    }

    public function storeStockDrugInformationget($store_id, $drug_id){
        // $batch_information = Drug::with('unit','stock_item')->where('id', $drug_id)->whereHas('stock_item.balance', function($query){
        //     $query->where('balance', '>', '0');
        // })->get();

        $response = [ 
            'commodity' => "ABACAVIR (ABC) Liquid 20MG/ML (240ml)",
            'unit' => 'Bottle',
            'total_stock' => '0',
            'max_stock_level' => '0',
            'min_stock_level' => '0',
            'avg_monthly_consumption' => '0' 
        ];

        return response()->json($response,200);
    }

    // get stock record
    public function recordedStockItems(){
        $response = RecordedStockItems::get();
        return response()->json($response,200);
    }

    public function recordedStockItemsById($store_id){
        $response = RecordedStockItems::where('store_id', $store_id)->get();
        return response()->json($response,200);
    }

    public function recordedStockItemsDrug($store_id){
        $response = RecordedStockItems::where('store_id', $store_id)->get();
        return response()->json($response,200);
    }
    public function recordedStockItemsDrugById($store_id, $drug_id){
        // $response = RecordedStockItems::where('store_id', $store_id)->where('drug_id', $drug_id)->get();
        // $response = DB::table('v_stock_balance')->where('store_id', $store_id)->where('drug_id', $drug_id)->get()->keyBy('batch_number');
        $response = DB::table('v_stock_balance')->where('store_id', $store_id)->where('drug_id', $drug_id)->select('batch_number', 'expiry_date', 'facility_id', 'balance', 'last_update', 'store_id', 'drug_id as id')->get();
        return response()->json($response,200);
    }

    public function stockItemAllget(){
        $response = StockItem::get();

        return response()->json($response,200);
    }

    // stock transacions
    public function stockTransactionpost(){
        $input = Request::all();
        return response()->json($input);

        event(new StockTransactionEvent($input));
        return response()->json(['msg'=> 'Transaction complite', 'response'=> $input], 201);
    }

}                                           
