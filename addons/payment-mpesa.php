<?php

/**
 * Mpesa Payment Method for CampTix
 *
 * This class is a payment method for CampTix which implements
 * Mpesa. You can use this as a base to create
 * your own redirect-based payment method for CampTix.
 * @author Odhiambo Dormnic<ayimdomnic@gmail.com>
 * @since CampTix 1.2
 */

class Camptix_Payment_Method_Mpesa extends Camptix_Payment_Method
{
    
    /**
     * Here I will define all the variables required for the payment to go through
     */
    public $id ='mpesa';
    public $name = 'M-pesa';
    public $description = 'Mobile Money payment Gateway';
    public $supported_currencies = array('KES','USD' );
    public $supported_features = array(
        'refund_single'=>false,
        'refund_all' => false,
    );

    /**
     * an array to store the options
     */
    protected $options = array();

    function camptix_init(){
        //merchant details should be loaded here
        $this->options = array_merge(array(
            'pay_bill_number' => '',
            'account_number' => '',
            'payload_url' => '',
            'merchant_name' => '',
        ), $this->get_payment_options());

        add_action('template_redirect',array($this, 'template_redirect'));
    }

    function payment_settings_field(){
        if (count($this->get_predefined_accounts())>0) {
            # code...
            //chack if there were existing paybil numbers
            $this->add_settings_field_helper( 'paybill_number', __( 'Predefined Paybill', '888888' ), array( $this, 'field_paybill_number'	) );
        }
        //define all the other fields, therefore
    }
}
