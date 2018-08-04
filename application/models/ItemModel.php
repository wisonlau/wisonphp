<?php

/**
 * 用户Model
 */
class ItemModel extends Model
{
    /**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 item 表
     * @var string
     */
    protected $table = 'item';

    /**
     * 主键名
     */
    protected $primaryKey = 'id';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    protected $timestamps = true;

    /**
     * 模型日期列的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 能被批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [];




    /**
     * 搜索功能，因为Sql父类里面没有现成的like搜索，
     * 所以需要自己写SQL语句，对数据库的操作应该都放
     * 在Model里面，然后提供给Controller直接调用
     * @param $title string 查询的关键词
     * @return array 返回的数据
     */
    public function search($keyword)
    {
        $sql = "select * from `$this->table` where `item_name` like :keyword";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [':keyword' => "%$keyword%"]);
        $sth->execute();

        return $sth->fetchAll();
    }

    public function insert($data)
    {
        if ($this->timestamps)
        {
            $time = array(self::CREATED_AT => date($this->dateFormat, time()), self::UPDATED_AT => date($this->dateFormat, time()));
            $data = array_merge($data, $time);
        }
        if ( ! empty($this->guarded))
        {
            $intersect = array_intersect($this->guarded, $this->fillable);
            foreach ($this->guarded as $value)
            {
                if( ! in_array($value, $intersect))
                {
                    unset($data[$value]);
                }
            }
        }

        $sql = 'insert into ' . $this->table;
        $params = [];
        $keys = [];
        foreach ($data as $key => $value)
        {
            array_push($keys, $key);
            array_push($params, $value);
        }
        $sql .= ' (`' . implode('` , `', $keys) . "`) values ( '" . implode("' , '", $params) . "')";
    }
}
