# Yii2-Paypal-basic
Basic Yii2 paypal (Sanbox)

# Regis DevPayPal
  - Search sanbox Test Account -> regis
  - Add new App
  https://developer.paypal.com/developer/applications/#sthash.6xD73z7T.dpuf

# Work to basic
  Create Table transction_paypal
  
  1 yii migrate/create create_transction_paypal
 
   - edit file 
    public function up()
    {
       $this->createTable(
       
              'transaction_paypal', [ 
              
                'id' => $this->primaryKey(),
                
                'user_id' => $this->integer(), 
                
                'payment_id' => $this->string(100), 
                
                'hash' => $this->string(100),  
                
                'complete' => $this->integer(1), 
                
                'create_time' => $this->string(50), 
                
                'update_time' => $this->string(50), 
                
                'product_id' => $this->integer(11) 
                
              ]
              
          );
          
    }
    
    - yii migrate
    
    
  2 composer require paypal/rest-api-sdk-php
  
  3 Copy common>components go to Project.
  
  4 Edit file common>components>Paypal.php
  
    $apiContext = new \PayPal\Rest\ApiContext(
    
            new \PayPal\Auth\OAuthTokenCredential(
            
                'ClientID',         // ClientID
                
                'ClientSecret'      // ClientSecret
                
            )
            
        );
        
    - Simple Test 3 Simple
    
   5 Edit file frontend/controllers/SiteControllers.php
   
     -> actionIndex()
     
   6 Download file PayPalController.php form Clone Project WSbook/Yii2-Paypal-basic
   
      Extract File > frontend/controllers/PayPalController.php
 
