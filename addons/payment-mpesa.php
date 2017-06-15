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
    public function __consruct()
    {
                $this->id           = 'lipa_na_mpesa';
				$this->method_title = __('Lipa na MPESA', 'camptix');
				$this->method_description = __('Allows payments through Lipa na MPESA.', 'camptix');
				$this->has_fields   = true;
				$this->testmode     = ($this->get_option('testmode') === 'yes') ? true : false;
				$this->debug	      = $this->get_option('debug');
				// Load the settings.
				$this->init_form_fields();
				$this->init_settings();
				// Get settings
				$this->title              					= $this->get_option('title');
				$this->field_title        					= $this->get_option('field_title');
				$this->phone_title        					= $this->get_option('phone_title');
				$this->till_number        					= $this->get_option('till_number');
				$this->description        					= $this->get_option('description');
				$this->instructions       					= $this->get_option('instructions', $this->description);
				$this->enable_for_methods 					= $this->get_option('enable_for_methods', array());
				$this->enable_for_virtual 					= $this->get_option('enable_for_virtual', 'yes') === 'yes' ? true : false;
				$this->auto_complete_virtual_orders = $this->get_option('auto_complete_virtual_orders', 'yes') === 'yes' ? true : false;
				$this->kopokopo_api_key   					= $this->get_option('kopokopo_api_key');
    }
                
    /**
     * an array to store the options
     */
    protected $options = array();

    function camptix_init(){
        
					$mpesa_instructions = '
						<div class="mpesa-instructions">
						  <p>
						    <h3>' . __('Payment Instructions', 'camptix') . '</h3>
						    <p>
						      ' . __('On your Safaricom phone go the M-PESA menu', 'camptix') . '</br>
						      ' . __('Select Lipa Na M-PESA and then select Buy Goods and Services', 'camptix') . '</br>
						      ' . __('Enter the Till Number', 'camptix') . ' <strong>' . $this->till_number . '</strong> </br>
						      ' . __('Enter exactly the amount due', 'camptix') . '</br>
						      ' . __('Follow subsequent prompts to complete the transaction.', 'camptix') . ' </br>
						      ' . __('You will receive a confirmation SMS from M-PESA with a Confirmation Code.', 'camptix') . ' </br>
						      ' . __('After you receive the confirmation code, please input your phone number and the confirmation code that you received from M-PESA below.', 'camptix') . '</br>
						    </p>
						  </p>
						</div>      
					';
					$this->form_fields = array(
						'enabled' => array(
							'title'   => __('Enable/Disable', 'camptix'),
							'type'    => 'checkbox',
							'label'   => __('Enable Lipa na MPESA', 'camptix'),
							'default' => 'no'
							),
						'title' => array(
							'title'       => __('Title', 'camptix'),
							'type'        => 'text',
							'description' => __('This controls the title which the user sees during checkout.', 'camptix'),
							'default'     => __('Lipa na MPESA', 'camptix'),
							'desc_tip'    => true,
							),
						'till_number' => array(
							'title'       => __('Lipa na MPESA Till Number', 'camptix'),
							'type'        => 'text',
							'description' => __('The Lipa na MPESA till number where money is sent to.', 'camptix'),
							'desc_tip'    => true,
							),
						'description' => array(
							'title'       => __('Description', 'camptix'),
							'type'        => 'textarea',
							'description' => __('Payment method description that the customer will see on your checkout.', 'camptix'),
							'default'     => $mpesa_instructions,
							'desc_tip'    => true,
							),
						'instructions' => array(
							'title'       => __('Instructions', 'camptix'),
							'type'        => 'textarea',
							'description' => __('Instructions that will be added to the thank you page and emails.', 'camptix'),
							'default'     => $mpesa_instructions,
							'desc_tip'    => true,
							),
						'field_title' => array(
							'title'       => __('Confirmation Code Field Title', 'camptix'),
							'type'        => 'text',
							'description' => __('This controls the MPESA confirmation field title which the user sees during checkout.', 'camptix'),
							'default'     => __('MPESA Confirmation Code', 'camptix'),
							'desc_tip'    => true,
							),
						'phone_title' => array(
							'title'       => __('Phone Number Field Title', 'camptix'),
							'type'        => 'text',
							'description' => __('This controls the MPESA phone number field title which the user sees during checkout.', 'camptix'),
							'default'     => __("MPESA Phone Number", 'Lipa Na Mpesa'),
							'desc_tip'    => true,
							),
						'enable_for_methods' => array(
							'title'             => __('Enable for shipping methods', 'camptix'),
							'type'              => 'multiselect',
							'class'             => 'wc-enhanced-select',
							'css'               => 'width: 450px;',
							'default'           => '',
							'description'       => __('If Lipa na MPESA is only available for certain methods, set it up here. Leave blank to enable for all methods.', 'camptix'),
							'options'           => $shipping_methods,
							'desc_tip'          => true,
							'custom_attributes' => array(
								'data-placeholder' => __('Select shipping methods', 'camptix')
								)
							),
						'enable_for_virtual' => array(
							'title'             => __('Accept for virtual orders', 'camptix'),
							'label'             => __('Accept Lipa na MPESA if the order is virtual', 'camptix'),
							'type'              => 'checkbox',
							'default'           => 'yes'
							),
						'auto_complete_virtual_orders' => array(
							'title'             => __('Auto-complete for virtual orders', 'camptix'),
							'label'             => __('Automatically mark virtual orders as completed once payment is received', 'camptix'),
							'type'              => 'checkbox',
							'default'           => 'no'
							),						
						'kopokopo_api_key' => array(
							'title'       => __('KopoKopo API Key', 'camptix'),
							'type'        => 'text',
							'description' => __('The API Key received from KopoKopo.com.', 'camptix'),
							'desc_tip'    => true,
							),
						);

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
