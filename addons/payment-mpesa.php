<? php

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
    }
}
