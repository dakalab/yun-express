<?php

namespace Dakalab\YunExpress;

// 发件人信息
class SenderInfo
{
    use ToArray;

    /**
     * 发件人所在国家，填写国际通用标准2位简码，可通过国家查询服务查询，非必填
     *
     * @var string
     */
    public $CountryCode;

    /**
     * 发件人名，非必填
     *
     * @var string
     */
    public $SenderFirstName;

    /**
     * 发件人姓，非必填
     *
     * @var string
     */
    public $SenderLastName;

    /**
     * 发件人公司名称，非必填
     *
     * @var string
     */
    public $SenderCompany;

    /**
     * 发件人详情地址，非必填
     *
     * @var string
     */
    public $SenderAddress;

    /**
     * 发件人所在城市，非必填
     *
     * @var string
     */
    public $SenderCity;

    /**
     * 发货人省/州，非必填
     *
     * @var string
     */
    public $SenderState;

    /**
     * 发货人邮编，非必填
     *
     * @var string
     */
    public $SenderZip;

    /**
     * 发货人电话，非必填
     *
     * @var string
     */
    public $SenderPhone;
}
