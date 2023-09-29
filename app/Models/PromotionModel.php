<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class PromotionModel extends Model
{
    protected $db;
    protected $table      = 'promotion';
    protected $builder;

    protected $primaryKey = 'promotion_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['promotion_id', 'promotion_type', 'promotion_code', 'promotion_condition', 'parameter', 'start_date', 'end_date'];
    protected $fields = 'promotion_id, promotion_type, promotion_code, promotion_condition, parameter, start_date, end_date';

    private $_insertBatch;

    public function __construct(ConnectionInterface &$db)
    {
        // Truyền vào database
        $this->db =& $db;
        // khởi tạo builder lấy bảng mặc định trong model
        $this->builder = $this->db->table($this->table);

    }

    public function setInsertBatch($insertBatch) 
    {
        $this->_insertBatch = $insertBatch;
    }

    public function countAll()
    {
        return $this->builder->countAll();
    }

    public function readAll($col = null) 
    {
        // get: Lấy tất cả thông tin liên quan truy vấn database
        // getResult: trả về kết quả truy vấn
        if ($col != null) 
            $this->builder->orderBy($col, 'desc');
        return $this->builder->select($this->fields)->get()->getResult();
    }


    public function readItem($where, $col = null) 
    {
        $this->builder->select('*');
        $this->builder->where($where);
        if ($col != null) 
            $this->builder->orderBy($col, 'desc');
        return $this->builder->get()->getResult()[0];
    }

    public function readOptions($where, $col = null) 
    {
        $this->builder->where($where);
        if ($col != null) 
            $this->builder->orderBy($col, 'asc');
        return $this->builder->get()->getResult();
    }

    public function create($data)
    {
        // Cách 1: Có thể truyền dữ liệu bằng phương thức: $this->builder->set('name', $name); Sau đó sử dụng $this->builder->insert();
        // cách 2: truyền vào mảng data cần insert
        return $this->builder->insert($data);
    }

    public function createMulti()
    {
        $data = $this->_insertBatch;
        return $this->builder->insertBatch($data);
    }

    public function edit($where, $data)
    {
        $this->builder->where($where);
        return $this->builder->update($data);
    }

    public function del($where)
    {
        $this->builder->where($where);
        return $this->builder->delete();        
    }

    public function isAlreadyExist($where)
    {
        $this->builder->selectCount('id');
        $this->builder->where($where);
        return (($this->builder->get()->getResult()[0]->id) > 0) ? true : false;   
    }



}
