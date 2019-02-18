<?php

namespace Dakalab\YunExpress;

// 申请单信息
class ApplicationInfo
{
    use ToArray;

    /**
     * 包裹中货品（英文） 申报名称，打印CN22用，备注（荷兰邮政小包挂号限制50个字符，且只能为英文），必填
     *
     * @var string
     */
    public $ApplicationName;

    /**
     * 包裹中货品 申报编码，打印CN22用，非必填（欧洲专线必填；荷兰邮政小包挂号不必填，限制10个字符，只能为数字）
     *
     * @var string
     */
    public $HSCode;

    /**
     * 包裹中货品 申报数量，打印CN22用，必填
     *
     * @var int
     */
    public $Qty;

    /**
     * 包裹中货品 申报价格，单位USD，打印CN22用，必填
     *
     * @var Decimal(18,2)
     */
    public $UnitPrice;

    /**
     * 包裹中货品 申报重量，单位kg，打印CN22用，必填
     *
     * @var Decimal(18,3)
     */
    public $UnitWeight;

    /**
     * 包裹的申报中文名称，非必填
     *
     * @var string
     */
    public $PickingName;

    /**
     * 用于打印配货单，非必填（欧洲专线必填）
     *
     * @var string
     */
    public $Remark;

    /**
     * 产品链接地址，非必填
     *
     * @var string
     */
    public $ProductUrl;

    /**
     * 商品SKU，非必填
     *
     * @var string
     */
    public $SKU;
}
