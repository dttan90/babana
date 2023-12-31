<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\BillModel;
use App\Models\BillDetailModel;
use App\Models\TableOrderModel;
use App\Models\AreaModel;
use App\Models\PromotionModel;
use App\Models\FoodModel;
use App\Models\SizeUnitModel;
use App\Models\FoodSizeModel;

// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\Writer\Csv;

class Home extends BaseController
{
    // public function __construct()
    // {
    //     // load helpers
    //     helper(['url', 'form', 'html', 'cookie']);

    //     // load library
    //     $validation =  \Config\Services::validation();
    //     // $cache = \Config\Services::cache();

    // }

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        helper(['url', 'form', 'html', 'cookie']);
        $validation =  \Config\Services::validation();
        // $cache = \Config\Services::cache();

        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function index()
    {
        return view('index');
    }

    public function load()
    {
        // init
        $data = array();

        $db = db_connect();
        $BillModel = new BillModel($db);
        $TableOrderModel = new TableOrderModel($db);
        $PromotionModel = new PromotionModel($db);
        $AreaModel = new AreaModel($db);

        // get data
        $billData = $BillModel->readOptions(array('status <>' => 'Done'), 'bill_id');
        foreach ($billData as $bill) {

            $bill_id = $bill->bill_id;
            $table_id = $bill->table_id;
            $promotion_id = $bill->promotion_id;

            $tableItem = !empty($table_id) ? $TableOrderModel->readItem(array('table_id' => $table_id)) : array();
            $promotionItem = !empty($promotion_id) ? $PromotionModel->readItem(array('promotion_id' => $bill->promotion_id)) : array();

            $area_id = isset($tableItem->area_id) ? $tableItem->area_id : '';
            $areaItem = !empty($area_id) ? $AreaModel->readItem(array('area_id' => $area_id)) : array();

            $status = isset($bill->status) ? $bill->status : '';
            $status = $this->getOrderStatus($status);

            // // update note data
            $note = $this->getOrderNote($bill_id);
            $note = str_replace("Trà sữa", "TS", $note);
            $note = str_replace("trà sữa", "ts", $note);
            $BillModel->edit(['bill_id' => $bill_id], ['note' => $note]);

            $data[] = array(
                'bill_id' => $bill_id,
                'table_order_name' => !empty($tableItem) ? $tableItem->table_id . "__" . $tableItem->table_order_name : '',
                'date_check_in' => date('H:i:s', strtotime($bill->date_check_in)),
                'date_check_out' => date('H:i:s', strtotime($bill->date_check_out)),
                'total' => $bill->total,
                'status' => $status,
                'area_name' => !empty($areaItem) ? $areaItem->area_id . "__" . $areaItem->area_name : '',
                // 'area_name' => '',
                'promotion_description' => !empty($promotionItem) ? $promotionItem->description : 'Không',
                'note' => $note,
                // 'detail' => '<button class="btn btn-info detail-btn">Xem</button>'
            );
        }

        // close db
        $db->close();

        // $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $data;
    }

    public function detailLoad()
    {
        $data = array();
        $detailData = array();
        $foodOptions = array();
        $sizeUnitOptions = array();


        $request = \Config\Services::request();
        if ($request->is('get')) {

            $bill_id = $this->request->getVar('bill_id');

            $db = db_connect();
            $BillDetailModel = new BillDetailModel($db);
            $FoodModel = new FoodModel($db);
            $SizeUnitModel = new SizeUnitModel($db);

            // get data
            $billDetailData = $BillDetailModel->readOptions(array('bill_id' => $bill_id), 'food_id');
            foreach ($billDetailData as $billDetail) {

                $price = $billDetail->price;
                $count = $billDetail->count;
                $food_id = $billDetail->food_id;
                $size_unit_code = $billDetail->size_unit_code;
                $detail_total = $price * $count;

                $foodItem = !empty($food_id) ? $FoodModel->readItem(array('food_id' => $food_id)) : array();
                $sizeUnitItem = !empty($size_unit_code) ? $SizeUnitModel->readItem(array('size_unit_code' => $size_unit_code)) : array();

                $detailData[] = array(
                    'detail_food_name' => !empty($foodItem) ? ($foodItem->food_id . "__" . $foodItem->food_name) : '',
                    'detail_size_unit_code' => !empty($sizeUnitItem) ? $sizeUnitItem->size_unit_code . "__" . $sizeUnitItem->description : '',
                    'detail_count' => $billDetail->count,
                    'detail_price' => $price,
                    'detail_total' => $detail_total,
                    'detail_bill_id' => $billDetail->bill_id,
                    

                    'detail_note' => $billDetail->note
                );
            }

            for ($i = 0; $i < 9; $i++) {
                $detailData[] = array(
                    'detail_food_name' => "",
                    'detail_size_unit_code' => "1:M__Ly size M",
                    'detail_count' => "",
                    'detail_price' => "",
                    'detail_total' => "",
                    'detail_bill_id' => "",
                    
                    'detail_note' => ""
                );
            }

            // close db
            $db->close();
        }

        $foodData = $FoodModel->readOptions(array('status' => 1), 'food_id');
        if (!empty($foodData)) {
            foreach ($foodData as $value) {
                $foodOptions[] = $value->food_id . "__" . $value->food_name;
            }
        }

        $sizeUnitData = $SizeUnitModel->readAll('size_unit_code');
        foreach ($sizeUnitData as $value) {
            $sizeUnitOptions[] = $value->size_unit_code . "__" . $value->description;
        }

        $data['detailData'] = $detailData;
        $data['foodOptions'] = $foodOptions;
        $data['sizeUnitOptions'] = $sizeUnitOptions;

        // $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function getOrderNote($bill_id)
    {
        $data = array();
        // connect database and models
        $db = db_connect();
        $BillDetailModel = new BillDetailModel($db);
        $FoodModel = new FoodModel($db);

        // // get data
        $billDetailData = $BillDetailModel->readOptions(array('bill_id' => $bill_id), 'bill_detail_id');
        foreach ($billDetailData as $billDetail) {

            $note = $billDetail->note;
            $food_id = $billDetail->food_id;
            $foodItem = !empty($food_id) ? $FoodModel->readItem(array('food_id' => $food_id)) : array();
            $detail_food_description = !empty($foodItem) ? $foodItem->description : '';
            if (!empty($note)) {
                $data[] = $detail_food_description . ": " . $note;
            }
        }

        return implode(";", $data);
    }

    public function getOptionsOfOrderGrid()
    {
        // init
        $data = array();
        $tableOptions = array();
        $promotionOptions = array();
        $areaOptions = array();
        $foodOtions = array();

        $db = db_connect();
        $TableOrderModel = new TableOrderModel($db);
        $PromotionModel = new PromotionModel($db);
        $AreaModel = new AreaModel($db);
        $FoodModel = new FoodModel($db);

        $tableOrderData = $TableOrderModel->readAll('table_order_name');
        if (!empty($tableOrderData)) {
            foreach ($tableOrderData as $value) {
                $tableOptions[] = $value->table_id . "__" . $value->table_order_name;
            }
        }

        $promotionData = $PromotionModel->readOptions(array('start_date <=' => date('Y-m-d H:i:s'), 'end_date >= ' => date('Y-m-d H:i:s')), 'updated_date');
        if (!empty($promotionData)) {
            foreach ($promotionData as $value) {
                $promotionOptions[] = $value->promotion_id . "__" . $value->description;
            }
        }

        $areaData = $AreaModel->readAll('area_name');
        if (!empty($areaData)) {
            foreach ($areaData as $value) {
                $areaOptions[] = $value->area_id . "__" . $value->area_name;
            }
        }

        $foodData = $FoodModel->readOptions(array('status' => 1), 'food_name');
        if (!empty($foodData)) {
            foreach ($foodData as $value) {
                $foodOtions[] = $value->food_id . "__" . $value->food_name;
            }
        }

        $data['dataset'] = $this->load();
        $data['tableOptions'] = $tableOptions;
        $data['promotionOptions'] = $promotionOptions;
        $data['areaOptions'] = $areaOptions;
        $data['foodOtions'] = $foodOtions;


        $db->close();

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function getOrderStatus($status, $reverse = 0)
    {
        if ($reverse == 0) {
            switch ($status) {
                case "Done":
                    $status = "Đã thanh toán";
                    break;
                case "Delivered":
                    $status = "Đã giao món";
                    break;
                case "In-progress":
                    $status = "Đang đợi";
                    break;
                case "Cancelled":
                    $status = "Hủy";
                    break;
                default:
                    $status = "Chưa xác định";
                    break;
            }
        } else {

            switch ($status) {
                case "Đã thanh toán":
                    $status = "Done";
                    break;
                case "Đã giao món":
                    $status = "Delivered";
                    break;
                case "Đang đợi":
                    $status = "In-progress";
                    break;
                case "Hủy":
                    $status = "Cancelled";
                    break;
                default:
                    $status = "Chưa xác định";
                    break;
            }
        }

        return $status;
    }

    /**
     * *************************************** Hàm lấy dữ liệu cho thêm mới đơn hàng  ********************************
     */
    public function getDataToAddForm()
    {
        // init
        $data = array();
        $tableOptions = array();
        $promotionOptions = array();
        $areaOptions = array();
        $sizeUnitOptions = array();
        $foodDataSet = array();

        // connect and model
        $db = db_connect();
        $TableOrderModel = new TableOrderModel($db);
        $PromotionModel = new PromotionModel($db);
        $AreaModel = new AreaModel($db);
        $FoodModel = new FoodModel($db);
        $SizeUnitModel = new SizeUnitModel($db);
        $FoodSizeModel = new FoodSizeModel($db);

        $tableOrderData = $TableOrderModel->readAll('table_order_name');
        if (!empty($tableOrderData)) {
            foreach ($tableOrderData as $value) {
                $tableOptions[] = [
                    'value' => $value->table_order_name,
                    'id' => $value->table_id
                ];
            }
        }

        $promotionData = $PromotionModel->readOptions(array('start_date <=' => date('Y-m-d H:i:s'), 'end_date >= ' => date('Y-m-d H:i:s')), 'updated_date');
        if (!empty($promotionData)) {
            foreach ($promotionData as $value) {
                $promotionOptions[] = [
                    'value' => $value->description,
                    'id' => $value->promotion_id
                ];
            }
        }

        $areaData = $AreaModel->readAll('area_name');
        if (!empty($areaData)) {
            foreach ($areaData as $value) {
                $areaOptions[] = [
                    'value' => $value->area_name,
                    'id' => $value->area_id
                ];
            }
        }

        $sizeUnitData = $SizeUnitModel->readAll('size_unit_code');
        foreach ($sizeUnitData as $value) {
            $sizeUnitOptions[] = $value->size_unit_code . "__" . $value->description;
        }

        $foodData = $FoodModel->readOptions(array('status' => 1), 'food_id');
        if (!empty($foodData)) {
            foreach ($foodData as $food) {

                $food_id = $food->food_id;
                $catalogy_id = $food->catalogy_id;
                $food_size_unit_code = $catalogy_id == 1 ? '1:M__Ly size M' : '';
                $promotion_price = 0;
                $price = 0;
                if (!empty($food_size_unit_code)) {
                    $foodSizeItem = $FoodSizeModel->readItem(array('food_id' => $food_id, 'size_unit_code' => '1:M'));
                    $promotion_price = !empty($foodSizeItem) ? $foodSizeItem->promotion_price : 0;
                    $price = !empty($foodSizeItem) ? $foodSizeItem->price : 0;
                    if ($promotion_price > 0 ) {
                        $price = $promotion_price;
                    }
                }

                $foodDataSet[] = array(
                    'food_name' => $food->food_id . '__' . $food->food_name,
                    'food_size_unit_code' => $food_size_unit_code,
                    'food_price' => $price,
                    'food_count' => 1,
                    // 'food_total' => ($food->price * 1),
                    'food_note' => ''
                );
            }
        }

        $data['tableOptions'] = $tableOptions;
        $data['promotionOptions'] = $promotionOptions;
        $data['areaOptions'] = $areaOptions;
        $data['sizeUnitOptions'] = $sizeUnitOptions;

        $data['foodDataSet'] = $foodDataSet;

        $db->close();

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function testData()
    {
        $data = array('Test');
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function imports()
    {
        // set time out
        ini_set('max_execution_time', 1800);

        // init PhpSpreadsheet Xlsx
        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        // get sheet 0 (sheet 1)
        $file_data = WRITEPATH . 'uploads/test.xlsx';
        $spreadSheet = $Reader->load($file_data);
        $spreadSheet = $spreadSheet->getSheet(0);
        $allDataInSheet = $spreadSheet->toArray(null, true, true, true);

        print_r($allDataInSheet);
    }

    // dữ liệu in hóa đơn
    public function printer()
    {
        $data = array();

        // get bill id
        $bill_id = $this->request->getVar('bill_id');

        $data['bill_id'] = $bill_id;


        return view('printer', $data);
    }

    public function getDetailDataToAdd()
    {
        // init
        $price = 0;

        $request = \Config\Services::request();
        if ($request->is('get')) {

            $detail_food_name = $this->request->getVar('detail_food_name');
            $detail_size_unit_code = $this->request->getVar('detail_size_unit_code');

            $food_id = (!empty($detail_food_name) && strpos($detail_food_name, "__") !== false) ? explode("__", $detail_food_name)[0] : 0;
            $size_unit_code = (!empty($detail_size_unit_code) && strpos($detail_size_unit_code, "__") !== false) ? explode("__", $detail_size_unit_code)[0] : 0;
            

            if (!empty($food_id) && !empty($size_unit_code) ) {

                // connect and model
                $db = db_connect();
                $FoodSizeModel = new FoodSizeModel($db);

                $where = ['food_id' => $food_id, 'size_unit_code' => $size_unit_code];
                if ($FoodSizeModel->isAlreadyExist($where) ) {
                    $foodSizeData = $FoodSizeModel->readItem($where);
                    if (!empty($foodSizeData) ) {
                        $promotion_price = $foodSizeData->promotion_price;
                        $price = ($promotion_price > 0 ) ? $promotion_price : $foodSizeData->price;
                    }
                }
                
            }
        }

        return json_encode(['price' => $price], JSON_UNESCAPED_UNICODE);
    }

    public function saveOrder()
    {
        $status = false;
        $message = 'Đơn hàng chưa lưu';

        // $data = '{"formData":{"table_order_name":"3","area_id":"1","promotion_description":"","count_orders":1,"total":"16,000","sum_orders":1},"gridData":[{"detail_food_name_add":"2__Trà+sữa+trân+châu+đường+đen","detail_size_unit":"1:M__Ly+size+M","detail_price_add":"16000","detail_count_add":1,"detail_total_add":16000,"detail_note_add":"","id":"u1696221244600"}]}';

        $request = \Config\Services::request();
        if ($request->is('post')) {

            $db = db_connect();
            $BillModel = new BillModel($db);
            $BillDetailModel = new BillDetailModel($db);

            /** -----------------------------------------------------------------------------------------
             * save thông tin chung đơn hàng
             */

            $data = $this->request->getVar('data');
            $data = json_decode($data, true);
            // print_r($formData); exit();

            $formData = $data['formData'];
            $gridData = $data['gridData'];


            $table_id = $formData['table_order_name'];
            $promotion_id = $formData['promotion_description'];
            $count_orders = $formData['count_orders'];
            $total = (int)str_replace(",", "", $formData['total']);
            $sum_orders = $formData['sum_orders'];
            // save
            $formSave = array(
                'date_check_in' => date('Y-m-d H:i:s'),
                'count_orders' => $count_orders,
                'sum_orders' => $sum_orders,
                'total' => $total,
                'status' => 'In-progress',
                'table_id' => $table_id,
                'promotion_id' => $promotion_id
            );

            $result = $BillModel->create($formSave);
            if (!$result) {
                $message = 'Đơn hàng có lỗi khi lưu, vui lòng kiểm tra lại thông tin đơn hàng ';
            } else {

                // get bill id 
                $billItem = $BillModel->readLastItem();
                $bill_id = $billItem->bill_id;
                /** -----------------------------------------------------------------------------------------
                 * save chi tiết đơn hàng
                 */

                // $gridData = $this->request->getVar('gridData');
                foreach ($gridData as $item) {

                    $count = $item['detail_count_add'];
                    $price = $item['detail_price_add'];

                    $bill_detail_total = is_int($item['detail_total_add']) ? (int)$item['detail_total_add'] : 0;
                    $size_unit_code = (strpos($item['detail_size_unit'], "__") !== false) ? explode("__", $item['detail_size_unit'])[0] : '';
                    $bill_detail_description = $item['detail_note_add'];

                    $food_id = (strpos($item['detail_food_name_add'], "__") !== false) ? explode("__", $item['detail_food_name_add'])[0] : 0;
                    $food_id = (int)$food_id;

                    // check data
                    if ($count == 0) {
                        $message = 'Số lượng của sản phẩm ' . $item['detail_food_name_add'] . ' bằng 0. Kiểm tra lại đơn hàng hoặc liên hệ Kỹ thuật';
                    } else if ($price == 0) {
                        $message = 'Giá của sản phẩm ' . $item['detail_food_name_add'] . ' bằng 0. Kiểm tra lại đơn hàng hoặc liên hệ Kỹ thuật';
                    } else if ($food_id == 0) {
                        $message = 'Mã của sản phẩm ' . $item['detail_food_name_add'] . ' bằng 0. Kiểm tra lại đơn hàng hoặc liên hệ Kỹ thuật';
                    } else if ($bill_detail_total == 0) {
                        $message = 'Tổng tiền của sản phẩm ' . $item['detail_food_name_add'] . ' bằng 0. Kiểm tra lại đơn hàng hoặc liên hệ Kỹ thuật';
                    } else if ($bill_detail_total == 0) {
                        $message = 'Tổng tiền của sản phẩm ' . $item['detail_food_name_add'] . ' bằng 0. Kiểm tra lại đơn hàng hoặc liên hệ Kỹ thuật';
                    } else {
                        $where = array('bill_id' => $bill_id, 'food_id' => $food_id);
                        $billDetailSave = array(
                            'count' => $count,
                            'price' => $price,
                            'status' => 1,
                            'bill_detail_total' => $bill_detail_total,
                            'size_unit_code' => $size_unit_code,
                            'bill_detail_description' => $bill_detail_description,
                            'bill_id' => $bill_id,
                            'food_id' => $food_id

                        );

                        // Nếu mà sản phẩm đã có trong bill id thì: lấy sản phẩm đã có này cộng thêm sản phẩm chuẩn bị thêm vào hệ thống. 
                        // Sau đó xóa dữ liệu đã tồn tại này đi
                        // Rồi mới thêm vào trở lại
                        if ($BillDetailModel->isAlreadyExist($where)) {

                            // lấy dữ liệu tồn tại này
                            $billDetailItem = $BillDetailModel->readItem($where);
                            $count_new = $count + $billDetailItem->count;
                            $bill_detail_total_new =  $count_new * $price;

                            // xóa bỏ dữ liệu đã tồn tại
                            $BillDetailModel->del($where);

                            // cập nhật lại count và total của sản phẩm
                            $billDetailSave['count'] = $count_new;
                            $billDetailSave['bill_detail_total'] = $bill_detail_total_new;
                        }

                        // thêm dữ liệu vào hệ thống
                        $result = $BillDetailModel->create($billDetailSave);
                        if (!$result) {
                            // xóa bỏ dữ liệu đã lưu trước đó
                            $BillModel->del(array('bill_id' => $bill_id));
                            $BillDetailModel->del(array('bill_id' => $bill_id));

                            // return error message
                            $status = false;
                            $message = 'Đơn hàng có lỗi khi lưu sản phẩm ' . $item['food_name'] . ', vui lòng kiểm tra lại thông tin đơn hàng ';
                            break;
                        } else {
                            $status = true;
                            $message = 'Đơn hàng đã tạo thành công';
                        }
                    }
                }
            }
        }

        // return view('save_order');
        return json_encode(array('status' => $status, 'message' => $message), JSON_UNESCAPED_UNICODE);
    }

    public function getAreaId()
    {
        $area_id = '';
        $area_name = '';
        $status = false;
        $message = 'Không lấy được Khu vực của Bàn đã chọn';

        $request = \Config\Services::request();
        if ($request->is('post')) {

            // get and set form data
            $table_id = $this->request->getVar('table_id');
            if (!empty($table_id)) {

                $db = db_connect();
                $TableOrderModel = new TableOrderModel($db);
                $AreaModel = new AreaModel($db);
                $BillModel = new BillModel($db);

                $tableItem = $TableOrderModel->readItem(array('table_id' => $table_id));

                // lấy các bàn có trạng thái Delivered và In-progress
                $tableList = [];
                $billData = $BillModel->readOptionsIn('status', ['Delivered', 'In-progress']);
                foreach($billData as $bill ) {
                    $tableList[] = $bill->table_id;
                }

                // check 
                if (in_array($table_id, $tableList) ) {
                    $table_order_name = !empty($tableItem) ? $tableItem->table_order_name : '';
                    $message = "Bàn $table_order_name đã được đặt chổ";
                } else {

                    $area_id = !empty($tableItem) ? $tableItem->area_id : '';
                    $areaItem = $AreaModel->readItem(array('area_id' => $area_id));
                    $area_name = !empty($areaItem) ? $areaItem->area_name : '';
                    $status = true;
                }
                
            }
        }

        return json_encode(['status' => $status, 'message' => $message, 'data' => ['area_id' => $area_id, 'area_name' => $area_name] ], JSON_UNESCAPED_UNICODE);
    }

    public function getFoodPrice()
    {
        $price = 0;
        $promotion_price = 0;

        $request = \Config\Services::request();
        if ($request->is('post')) {

            // get and set form data
            $food_name = $this->request->getVar('food_name');
            $food_size_unit_code = $this->request->getVar('food_size_unit_code');
            if (!empty($food_name) && !empty($food_size_unit_code)) {
                $db = db_connect();
                $FoodSizeModel = new FoodSizeModel($db);

                $food_id = (strpos($food_name, "__" ) !== false) ? explode("__", $food_name)[0] : 0;
                $size_unit_code = (strpos($food_size_unit_code, "__" ) !== false) ? explode("__", $food_size_unit_code)[0] : 0;

                $foodSizeItem = $FoodSizeModel->readItem(array('food_id' => $food_id, 'size_unit_code' => $size_unit_code));
                if (!empty($foodSizeItem) ) {
                    $promotion_price = $foodSizeItem->promotion_price;
                    $price = ($promotion_price > 0 ) ? $promotion_price : $foodSizeItem->price;
                }
                
            }
        }

        return json_encode(array('price' => $price), JSON_UNESCAPED_UNICODE);
    }
    








    //////////////////////////////////////////////////////////////////////////////////////////////////////

    public function imports2()
    {
        // set time out
        ini_set('max_execution_time', 1800);

        // check login
        if (!get_cookie('adUser')) return view('users/index');

        // get title
        $_data['title'] = "Revise Promise Date";

        // get data
        /* Một số trường hợp file excel có dạng: application/zip hoặc application/octet-stream  */
        $input = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,application/vnd.ms-excel,application/octet-stream,application/zip,text/xls,text/xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet]',
            ]
        ]);
        // set init
        $fileData = array(); // lấy dữ liệu
        $message = "No Import data has been updated";
        $log_error = '';
        // Biến đếm update bao nhiêu
        $successCount = 0;

        if (!$input) {
            print_r('Choose a valid file');
        } else {
            /* Hàm lấy mine type $file->getMimeType();*/

            $file = $this->request->getFile('file');
            if (!$file->isValid()) {
                throw new RuntimeException($file->getErrorString() . '(' . $file->getError() . ')');
            } else {
                // set user data, file
                $updated_by = get_cookie('adUser');
                $updated_date = date('Y-m-d H:i:s');
                $file_name = 'RevisePD_' . $_SERVER['REMOTE_ADDR'] . '_' . $updated_by . '_' . date('YmdHis') . '.xlsx';
                // move this file to writable/uploads folder
                $file->move(WRITEPATH . 'uploads', $file_name);

                // init PhpSpreadsheet Xlsx
                $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                // get sheet 0 (sheet 1)
                $file_data = WRITEPATH . 'uploads/' . $file_name;
                $spreadSheet = $Reader->load($file_data);
                $spreadSheet = $spreadSheet->getSheet(0);
                $allDataInSheet = $spreadSheet->toArray(null, true, true, true);

                /* START: check col ----------------------------------------------------------------------------------------------- */
                // check col name exist
                $createArray = array('OU', 'Order No', 'Line No', 'Request Date', 'Promise Date');
                $makeArray = array('OU' => 'OU', 'OrderNo' => 'OrderNo', 'LineNo' => 'LineNo', 'RequestDate' => 'RequestDate', 'PromiseDate' => 'PromiseDate');
                $SheetDataKey = array();
                foreach ($allDataInSheet as $dataInSheet) {
                    foreach ($dataInSheet as $key => $value) {
                        if (in_array(trim($value), $createArray)) {
                            $value = preg_replace('/\s+/', '', $value);
                            $SheetDataKey[trim($value)] = $key;
                        }
                    }

                    break;
                }

                // check data
                $flag = 0;
                $data = array_diff_key($makeArray, $SheetDataKey);
                if (empty($data)) {
                    $flag = 1;
                }
                /* END: check col ----------------------------------------------------------------------------------------------- */

                /* START: GET DATA ----------------------------------------------------------------------------------------------- */

                // True, excute
                if ($flag == 1) {

                    // open db and models
                    $db = db_connect();
                    $Revise = new PromiseDateRevise($db);
                    // load
                    for ($i = 2; $i <= count($allDataInSheet); $i++) {
                        // get col key
                        $OU = $SheetDataKey['OU'];
                        $OrderNo = $SheetDataKey['OrderNo'];
                        $LineNo = $SheetDataKey['LineNo'];
                        $RequestDate = $SheetDataKey['RequestDate'];
                        $PromiseDate = $SheetDataKey['PromiseDate'];

                        // get data
                        $OU = filter_var(trim($allDataInSheet[$i][$OU]), FILTER_SANITIZE_STRING); // key primary
                        $OrderNo = filter_var(trim($allDataInSheet[$i][$OrderNo]), FILTER_SANITIZE_STRING);
                        $LineNo = filter_var(trim($allDataInSheet[$i][$LineNo]), FILTER_SANITIZE_STRING);
                        $RequestDate = trim($allDataInSheet[$i][$RequestDate]);
                        $PromiseDate = trim($allDataInSheet[$i][$PromiseDate]);

                        // set validation
                        $RequestDate = date('Y-m-d', strtotime($RequestDate)); // get date and set format
                        $PromiseDate = date('Y-m-d', strtotime($PromiseDate)); // get date and set format
                        // status
                        $status = 1;
                        if (!empty($PromiseDate) && ($PromiseDate != '1970-01-01')) {
                            $status = 1;
                        }
                        $so_line = $OrderNo . "-" . $LineNo;
                        $updated_by_ip = $_SERVER['REMOTE_ADDR']; // IP

                        // check validation
                        if (empty($OrderNo) || empty($OrderNo) || empty($PromiseDate)) break;
                        if (strlen($OrderNo) != 8) break; // Trường hợp Order không phải 8 chữ số

                        // get data
                        $fileData[] = array(
                            'status' => $status,
                            'OU' => $OU,
                            'order_number' => $OrderNo,
                            'line_number' => $LineNo,
                            'so_line' => $so_line,
                            'request_date' => $RequestDate,
                            'promise_date' => $PromiseDate,
                            'updated_by_name' => $updated_by,
                            'updated_by_ip' => $updated_by_ip,
                            'updated_date' => $updated_date
                        );

                        if (count($fileData) == 500) {

                            // check updated
                            foreach ($fileData as $key => $value) {

                                $where = array('order_number' => $value['order_number'], 'line_number' => $value['line_number']);
                                if ($Revise->isAlreadyExist($where)) {

                                    $updateData = $fileData[$key];
                                    unset($updateData['order_number']); // xóa điều kiện
                                    unset($updateData['line_number']); // xóa điều kiện
                                    $result = $Revise->edit($where, $updateData);
                                    // Lưu lỗi update
                                    if (!$result) {
                                        $message = "Import data error (Update)";
                                        $log_error .= ($key + 2) . ",";
                                    } else {
                                        $message = "Import data success (U)";
                                        $successCount++;
                                    }
                                    // Xóa bỏ dữ liệu đã cập nhật (cập nhật lỗi vẫn xóa)
                                    unset($fileData[$key]);
                                }
                            }

                            // reset fileDate
                            $fileData = array();
                        }
                    }

                    // check and update
                    if (!empty($fileData)) {

                        foreach ($fileData as $key => $value) {

                            $where = array('order_number' => $value['order_number'], 'line_number' => $value['line_number']);
                            if ($Revise->isAlreadyExist($where)) {

                                $updateData = $fileData[$key];
                                unset($updateData['order_number']); // xóa điều kiện
                                unset($updateData['line_number']); // xóa điều kiện
                                $result = $Revise->edit($where, $updateData);

                                // Lưu lỗi update
                                if (!$result) {
                                    $message = "Import data error (Update)";
                                    $log_error .= ($key + 2) . ",";
                                } else {
                                    $message = "Import data success (U)";
                                    $successCount++;
                                }
                                // Xóa bỏ dữ liệu đã cập nhật (cập nhật lỗi vẫn xóa)
                                unset($fileData[$key]);
                            }
                        }
                    }


                    // close db
                    $db->close();
                }

                /* END: GET DATA ----------------------------------------------------------------------------------------------- */
            }
        }

        // results
        $this->_data['message'] = $message;
        $this->_data['successCount'] = $successCount;
        $this->_data['log_error'] = $log_error;

        return view('revise_pd/imports/reviseImports', $this->_data);
    }

    // exports data
    public function exports()
    {
        // check login
        if (!get_cookie('adUser')) return view('users/index');

        // get title
        $_data['title'] = "Revise Promise Date";

        // open db
        $db = db_connect();

        // models
        $Revise = new PromiseDateRevise($db);
        $UserModel = new UserModel($db);

        // get username
        $username = get_cookie('adUser');

        // ============== INIT SHEET ==============================================
        // create
        // $spreadsheet = new Spreadsheet();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;


        // Add some data
        $spreadsheet->setActiveSheetIndex(0);

        // active and set title
        $spreadsheet->getActiveSheet()->setTitle('BlankPD');



        // set the names of header cells
        // set Header, width
        $columns = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X',

            'Y', 'Z', 'AA', 'AB', 'AC', 'AD',
            'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN',
            'AO'
        ];

        $headers = [
            'OU', 'Order No', 'Line No', 'Request Date', 'Promise Date', 'Status', 'SOLine', 'Item', 'Qty', 'Planner Code',
            'Production Method', 'Ship To Customer', 'Bill To Customer', 'RBO', 'CS', 'Order Type Name', 'Flow Status Code', 'Packing Instructions', 'Ordered Date', 'Shipment Number',
            'Makebuy', 'Cust Po Number', 'Sample', 'Customer Item'
        ];

        foreach ($headers as $key => $header) {
            // width
            $spreadsheet->getActiveSheet()->getColumnDimension($columns[$key])->setWidth(20);
            // headers
            $spreadsheet->getActiveSheet()->setCellValue($columns[$key] . '1', $header);
        }

        // Font
        $spreadsheet->getActiveSheet()->getStyle('A1:X1')->getFont()->setBold(true)->setName('Arial')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle('A1:X1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3399ff');
        $spreadsheet->getActiveSheet()->getStyle('A:X')->getFont()->setName('Arial')->setSize(10);

        // ============== LOAD DATA ==============================================

        // load get
        $views = '';
        if ($this->request->getMethod() == "get") {
            $views = $this->request->getVar('views');
        }

        // get user info
        if (!$UserModel->isAlreadyExist($username)) {
            $_data['results'] = array(
                'status' => false,
                'message' => 'Username is not exist. Please contact the Admin'
            );
        } else {
            // change to array
            $userInfo = (array)$UserModel->readItem($username);
            $production_line = $userInfo['department'];
            $factory_code = $userInfo['factory_code'];
            $OU = '';
            if ($factory_code == 'LH') {
                $OU = 'VN';
            } else if ($factory_code == 'BN') {
                $OU = 'BNH';
            }



            // load data. If user department is AUTOMATION then get all data, not true get department data
            if ($production_line == 'AUTOMATION') {
                $data = $Revise->readAll();
            } else {
                $data = ($views == 'all') ? $Revise->readAll() : $Revise->readDepartment($OU, $production_line);
            }
            // $data = ($production_line == 'AUTOMATION' ) ? $Revise->readAll() : $Revise->readDepartment($OU,$production_line);


            if (empty($data)) {
                $_data['results'] = array(
                    'status' => false,
                    'message' => 'Load data empty'
                );
            } else {

                // data
                $rowCount = 1;
                foreach ($data as $key => $element) {
                    $rowCount++;

                    $element = (array)$element;

                    // add to excel file
                    $spreadsheet->getActiveSheet()->SetCellValue('A' . $rowCount, trim($element['OU']));
                    $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, trim($element['order_number']));
                    $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, trim($element['line_number']));
                    $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, trim($element['request_date']));
                    $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, trim($element['promise_date']));
                    $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, trim($element['status']));
                    $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, trim($element['so_line']));
                    $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, trim($element['item']));
                    $spreadsheet->getActiveSheet()->SetCellValue('I' . $rowCount, trim($element['qty']));
                    $spreadsheet->getActiveSheet()->SetCellValue('J' . $rowCount, trim($element['planner_code']));

                    $spreadsheet->getActiveSheet()->SetCellValue('K' . $rowCount, trim($element['production_method']));
                    $spreadsheet->getActiveSheet()->SetCellValue('L' . $rowCount, trim($element['ship_to_customer']));
                    $spreadsheet->getActiveSheet()->SetCellValue('M' . $rowCount, trim($element['bill_to_customer']));
                    $spreadsheet->getActiveSheet()->SetCellValue('N' . $rowCount, trim($element['sold_to_customer']));
                    $spreadsheet->getActiveSheet()->SetCellValue('O' . $rowCount, trim($element['cs']));
                    $spreadsheet->getActiveSheet()->SetCellValue('P' . $rowCount, trim($element['order_type_name']));

                    $spreadsheet->getActiveSheet()->SetCellValue('Q' . $rowCount, trim($element['flow_status_code']));
                    $spreadsheet->getActiveSheet()->SetCellValue('R' . $rowCount, trim($element['packing_instructions']));
                    $spreadsheet->getActiveSheet()->SetCellValue('S' . $rowCount, trim($element['ordered_date']));
                    $spreadsheet->getActiveSheet()->SetCellValue('T' . $rowCount, trim($element['shipment_number']));

                    $spreadsheet->getActiveSheet()->SetCellValue('U' . $rowCount, trim($element['makebuy']));
                    $spreadsheet->getActiveSheet()->SetCellValue('V' . $rowCount, trim($element['cust_po_number']));
                    $spreadsheet->getActiveSheet()->SetCellValue('W' . $rowCount, trim($element['sample']));
                    $spreadsheet->getActiveSheet()->SetCellValue('X' . $rowCount, trim($element['customer_item']));
                }
            }
        }


        // ============== OUTPUT ==============================================

        // set filename for excel file to be exported
        $filename = 'PD_Blank_' . date('Y_m_d__H_i_s');

        // header: generate excel file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        // writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        // ============== END ==============================================

    }

    public function exportByYearData()
    {
        // ini_set('max_execution_time', 7200);
        // ini_set('memory_limit', '1024M');

        ini_set('memory_limit', '-1');
        // // ini_set('memory_limit', '1024M');
        set_time_limit(0);


        // get
        $type = 'actual';
        $from_year = '';
        $to_year = '';
        if ($this->request->getMethod() == "get") {
            $type = trim($this->request->getVar('type'));
            $from_year = trim($this->request->getVar('from_year'));
            $to_year = trim($this->request->getVar('to_year'));
        }



        // Get year
        $current_year = getdate()['year'];
        $current_month = getdate()['mon'];

        // limit
        $limit = null; // không giới hạn
        // where
        if (!empty($from_year) && !empty($to_year)) {
            $where = " full_year>='$from_year' AND full_year<='$to_year' ";
        } else {
            $where = " full_year>='$current_year' ";
        }


        // open db and models
        $db = \Config\Database::connect();
        $cs_avery_db = \Config\Database::connect('cs_avery_db', false);
        $FinanceFtyPICListMain = new FinanceFtyPICListMain($db);
        $FinanceMaterialCode = new FinanceMaterialCode($db);
        $FinanceProductionItem = new FinanceProductionItem($cs_avery_db);

        // type
        if ($type == 'actual') {
            $FinanceData = new FinanceActualData($db);
            $type_label = 'Actual';
        } else if ($type == 'forecast') {
            $FinanceData = new FinanceForecastData($db);
            $type_label = 'Forecast';
        }

        // create
        $spreadsheet = new Spreadsheet();

        // set the names of header cells
        // set Header, width
        $columns = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',  'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',  'U', 'V', 'W', 'X', 'Y');

        if ($FinanceData->countAll() > 0) {

            /* ========================= SHEET DETAILS ==============================================================*/

            // Add new sheet
            $spreadsheet->createSheet();

            // Add some data
            $spreadsheet->setActiveSheetIndex(0);

            // active and set title
            $spreadsheet->getActiveSheet()->setTitle($type_label);

            $header1 = array(
                'No.', 'Factory PIC', 'Bill To Code', 'Bill To Customer', 'Item ID', 'RB Report Name', 'Cumstomer Item ID', 'Material Code', 'Location', 'Production Line',
                'Status', 'Check', 'YEAR', 'M01', 'M02', 'M03', 'M04', 'M05', 'M06', 'M07', 'M08', 'M09', 'M10', 'M11', 'M12'
            );

            $id = 0;
            foreach ($header1 as $header) {
                for ($index = $id; $index < count($header1); $index++) {
                    // width
                    $spreadsheet->getActiveSheet()->getColumnDimension($columns[$index])->setWidth(20);

                    // headers
                    $spreadsheet->getActiveSheet()->setCellValue($columns[$index] . '1', $header);

                    $id++;
                    break;
                }
            }

            // Font
            $spreadsheet->getActiveSheet()->getStyle('A1:Y1')->getFont()->setBold(true)->setName('Arial')->setSize(10);
            $spreadsheet->getActiveSheet()->getStyle('A1:Y1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3399ff');
            $spreadsheet->getActiveSheet()->getStyle('A:Y')->getFont()->setName('Arial')->setSize(10);

            // get data
            $data = $FinanceData->readMainData($where, $limit);

            // set data
            $index = 0;
            $rowCount = 1;

            foreach ($data as $element) {

                $index++;
                $rowCount++;


                $item = trim($element->item);
                $bill_to_code = trim($element->bill_to_code);

                $material_code = trim($element->material_code);
                $sold_to_customer = trim($element->sold_to_customer);
                $customer_item = trim($element->customer_item);
                $production_line = trim($element->production_line);
                $status = trim($element->status);


                $factory_pic = '';
                $whereF = array('bill_to_code' => $bill_to_code);
                if ($FinanceFtyPICListMain->isAlreadyExist($whereF)) {
                    $picItem = $FinanceFtyPICListMain->readItem($whereF);
                    $factory_pic = $picItem->pic;
                }



                // get data from production item
                if ($FinanceProductionItem->isAlreadyExist(array('Item' => $item))) {
                    $productionItem = $FinanceProductionItem->readItem($item);
                    $customer_item = (empty($customer_item)) ? $productionItem->CustomerItemNumber : $customer_item;
                    $production_line = (empty($production_line)) ? $productionItem->ProductLine : $production_line;
                    $status = (empty($status)) ? $productionItem->Status : $status;
                    $sold_to_customer = (empty($sold_to_customer)) ? $productionItem->SoldToName : $sold_to_customer;
                    // $sold_to_customer = htmlspecialchars($sold_to_customer, ENT_QUOTES, 'UTF-8');
                }



                // get data from material code
                if ($FinanceMaterialCode->isAlreadyExist(array('Item' => $item))) {
                    $materialItem = $FinanceMaterialCode->readItem($item);
                    $material_code = (empty($material_code)) ? $FinanceMaterialCode->material_code : $material_code;
                }

                // check
                $check = '';

                // set data
                $spreadsheet->getActiveSheet()->SetCellValue('A' . $rowCount, $index);
                $spreadsheet->getActiveSheet()->SetCellValue('B' . $rowCount, $factory_pic);
                $spreadsheet->getActiveSheet()->SetCellValue('C' . $rowCount, trim($bill_to_code));
                $spreadsheet->getActiveSheet()->SetCellValue('D' . $rowCount, trim($element->bill_to_customer));
                $spreadsheet->getActiveSheet()->SetCellValue('E' . $rowCount, trim($item));
                $spreadsheet->getActiveSheet()->SetCellValue('F' . $rowCount, trim($sold_to_customer));
                $spreadsheet->getActiveSheet()->SetCellValue('G' . $rowCount, trim($customer_item));
                $spreadsheet->getActiveSheet()->SetCellValue('H' . $rowCount, trim($material_code));
                $spreadsheet->getActiveSheet()->SetCellValue('I' . $rowCount, trim($element->location));
                $spreadsheet->getActiveSheet()->SetCellValue('J' . $rowCount, trim($production_line));
                $spreadsheet->getActiveSheet()->SetCellValue('K' . $rowCount, trim($status));
                $spreadsheet->getActiveSheet()->SetCellValue('L' . $rowCount, trim($check));

                $spreadsheet->getActiveSheet()->SetCellValue('M' . $rowCount, trim($element->full_year));
                $spreadsheet->getActiveSheet()->SetCellValue('N' . $rowCount, trim($element->M01));
                $spreadsheet->getActiveSheet()->SetCellValue('O' . $rowCount, trim($element->M02));
                $spreadsheet->getActiveSheet()->SetCellValue('P' . $rowCount, trim($element->M03));
                $spreadsheet->getActiveSheet()->SetCellValue('Q' . $rowCount, trim($element->M04));
                $spreadsheet->getActiveSheet()->SetCellValue('R' . $rowCount, trim($element->M05));
                $spreadsheet->getActiveSheet()->SetCellValue('S' . $rowCount, trim($element->M06));
                $spreadsheet->getActiveSheet()->SetCellValue('T' . $rowCount, trim($element->M07));
                $spreadsheet->getActiveSheet()->SetCellValue('U' . $rowCount, trim($element->M08));
                $spreadsheet->getActiveSheet()->SetCellValue('V' . $rowCount, trim($element->M09));
                $spreadsheet->getActiveSheet()->SetCellValue('W' . $rowCount, trim($element->M10));
                $spreadsheet->getActiveSheet()->SetCellValue('X' . $rowCount, trim($element->M11));
                $spreadsheet->getActiveSheet()->SetCellValue('Y' . $rowCount, trim($element->M12));
            }
        }

        // print_r($spreadsheet->getActiveSheet()); exit();
        /* ========================= OUT PUT ==============================================================*/

        // set filename for excel file to be exported
        $filename = $type_label . '_Report_' . date("Y_m_d__H_i_s");

        // header: generate excel file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        // writer
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function exportCsv()
    {
        ini_set('memory_limit', '-1');
        // // ini_set('memory_limit', '1024M');
        set_time_limit(0);


        // get
        $type = 'actual';
        $from_year = '';
        $to_year = '';
        if ($this->request->getMethod() == "get") {
            $type = trim($this->request->getVar('type'));
            $from_year = trim($this->request->getVar('from_year'));
            $to_year = trim($this->request->getVar('to_year'));
        }


        // set header data
        $file_name = ($type == 'actual') ? 'Actual_Report_' : 'Forecast_Report_';
        $file_name .= date('YmdHis') . '.csv';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $file_name);
        header('Cache-Control: max-age=0');

        // check login
        if (!get_cookie('adLoginUser')) return view('users/index');



        // Get year
        $current_year = getdate()['year'];
        $current_month = getdate()['mon'];

        // limit
        $limit = null; // không giới hạn
        // where
        if (!empty($from_year) && !empty($to_year)) {
            $where = " full_year>='$from_year' AND full_year<='$to_year' ";
        } else {
            $where = " full_year>='$current_year' ";
        }

        // open db and models
        $db = \Config\Database::connect();
        $cs_avery_db = \Config\Database::connect('cs_avery_db', false);
        $FinanceFtyPICListMain = new FinanceFtyPICListMain($db);
        $FinanceMaterialCode = new FinanceMaterialCode($db);
        $FinanceProductionItem = new FinanceProductionItem($cs_avery_db);

        // type
        if ($type == 'actual') {
            $FinanceData = new FinanceActualData($db);
            $type_label = 'Actual';
        } else if ($type == 'forecast') {
            $FinanceData = new FinanceForecastData($db);
            $type_label = 'Forecast';
        }

        /* START HEADER ------------------------------------------------------------------------------------------------------------ */
        // title
        $headlist = array('No.', 'Factory PIC', 'Bill To Code', 'Bill To Customer', 'Item ID', 'RB Report Name', 'Cumstomer Item ID', 'Material Code', 'Location', 'Production Line', 'Status', 'Check', 'YEAR', 'M01', 'M02', 'M03', 'M04', 'M05', 'M06', 'M07', 'M08', 'M09', 'M10', 'M11', 'M12');

        //Open the PHP file handle, php://output means direct output to the browser
        $fp = fopen('php://output', 'a');
        //Output Excel column name information
        foreach ($headlist as $key => $value) {
            //CSV Excel supports GBK encoding, must be converted, otherwise garbled
            $headlist[$key] = iconv('utf-8', 'gbk', $value);
        }

        //Write the data to the file handle through fputcsv
        fputcsv($fp, $headlist);
        /* END HEADER ------------------------------------------------------------------------------------------------------------ */

        /* START DATA ----------------------------------------------------------------------------------------------------------- */

        //Counter
        $num = 0;

        //Refresh the output buffer every $limit line, not too big or too small
        $refresh = 5000;

        // get data
        $data = $FinanceData->readMainData($where, $limit);

        // set data
        $index = 0;
        foreach ($data as $element) {

            $index++;
            $num++;

            $item = trim($element->item);
            $bill_to_code = trim($element->bill_to_code);

            $material_code = trim($element->material_code);
            $sold_to_customer = trim($element->sold_to_customer);
            $customer_item = trim($element->customer_item);
            $production_line = trim($element->production_line);
            $status = trim($element->status);


            $factory_pic = '';
            $whereF = array('bill_to_code' => $bill_to_code);
            if ($FinanceFtyPICListMain->isAlreadyExist($whereF)) {
                $picItem = $FinanceFtyPICListMain->readItem($whereF);
                $factory_pic = $picItem->pic;
            }



            // get data from production item
            if ($FinanceProductionItem->isAlreadyExist(array('Item' => $item))) {
                $productionItem = $FinanceProductionItem->readItem($item);
                $customer_item = (empty($customer_item)) ? $productionItem->CustomerItemNumber : $customer_item;
                $production_line = (empty($production_line)) ? $productionItem->ProductLine : $production_line;
                $status = (empty($status)) ? $productionItem->Status : $status;
                $sold_to_customer = (empty($sold_to_customer)) ? $productionItem->SoldToName : $sold_to_customer;
                // $sold_to_customer = htmlspecialchars($sold_to_customer, ENT_QUOTES, 'UTF-8');
            }



            // get data from material code
            if ($FinanceMaterialCode->isAlreadyExist(array('Item' => $item))) {
                $materialItem = $FinanceMaterialCode->readItem($item);
                $material_code = (empty($material_code)) ? $FinanceMaterialCode->material_code : $material_code;
            }

            // check
            $check = '';

            $header1 = array(
                'No.', 'Factory PIC', 'Bill To Code', 'Bill To Customer', 'Item ID', 'RB Report Name', 'Cumstomer Item ID', 'Material Code', 'Location', 'Production Line',
                'Status', 'Check', 'YEAR', 'M01', 'M02', 'M03', 'M04', 'M05', 'M06', 'M07', 'M08', 'M09', 'M10', 'M11', 'M12'
            );

            // set data
            $content = array(
                $index, trim($factory_pic), trim($bill_to_code), trim($element->bill_to_customer), $item, trim($sold_to_customer), trim($customer_item), trim($material_code), trim($element->location), $production_line,
                $status, $check, trim($element->full_year), trim($element->M01), trim($element->M02), trim($element->M03), trim($element->M04), trim($element->M05), trim($element->M06), trim($element->M07), trim($element->M08), trim($element->M09), trim($element->M10), trim($element->M11), trim($element->M12)
            );


            //Output Excel column name information
            foreach ($content as $key => $value) {
                //CSV Excel supports GBK encoding, must be converted, otherwise garbled
                $content[$key] = iconv('utf-8', 'gbk', $value);
            }

            //Write the data to the file handle through fputcsv
            fputcsv($fp, $content);

            // refresh output buffer, to prevent problems caused by excessive data
            if ($refresh == $num) {
                ob_flush();
                flush(); // refresh buffer
                $num = 0;
            }
        }

        // close file
        fclose($fp);
        exit();

        /* END DATA ----------------------------------------------------------------------------------------------------------- */
    }
}
