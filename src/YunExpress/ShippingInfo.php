<?php

namespace Dakalab\YunExpress;

// 收件人信息
class ShippingInfo
{
    use ToArray;

    /**
     * 收件人企业税号，欧盟可以填EORI，巴西可以填CPF等，非必填
     *
     * @var string
     */
    public $ShippingTaxId;

    /**
     * 收件人所在国家，填写国际通用标准2位简码，可通过国家查询服务查询，必填
     *
     * @var string
     */
    public $CountryCode;

    /**
     * 收件人名 备注（荷兰邮政小包挂号长度限制收件人姓和收件人名 总长度60），必填
     *
     * @var string
     */
    public $ShippingFirstName;

    /**
     * 收件人姓，非必填
     *
     * @var string
     */
    public $ShippingLastName;

    /**
     * 收件人公司名称，非必填
     *
     * @var string
     */
    public $ShippingCompany;

    /**
     * 收件人详情地址 备注（发中美专线长度限制35个字符、荷兰邮政小包挂号限制200，不能包含中文），必填
     *
     * @var string
     */
    public $ShippingAddress;

    /**
     * 收件人详情地址1 备注（发中美专线长度限制35个字符），非必填
     *
     * @var string
     */
    public $ShippingAddress1;

    /**
     * 收件人详情地址2 备注（发中美专线长度限制35个字符），非必填
     *
     * @var string
     */
    public $ShippingAddress2;

    /**
     * 收件人所在城市 备注（荷兰邮政小包限制30个字符），必填
     *
     * @var string
     */
    public $ShippingCity;

    /**
     * 收货人省/州 备注（荷兰邮政小包挂号非必填，但限制30个字符），非必填
     *
     * @var string
     */
    public $ShippingState;

    /**
     * 收货人邮编 备注（荷兰邮政小包挂号非必填，但限制30个字符），非必填
     *
     * @var string
     */
    public $ShippingZip;

    /**
     * 收货人电话 备注（荷兰邮政小包挂号非必填，但限制20个字符，只能是数字），非必填
     *
     * @var string
     */
    public $ShippingPhone;
}
