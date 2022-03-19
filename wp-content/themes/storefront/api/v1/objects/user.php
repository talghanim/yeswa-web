<?php

class User{ 
    
 
    // database connection and table name
    private $conn;
    private $table_name = "ya_users";
    private $table_namemeta = "ya_usermeta";
    private $table_wishlist = "ya_tinvwl_items"; 
    private $table_order = "ya_woocommerce_order_items";
    private $table_ordermeta = "ya_woocommerce_order_itemmeta";
    private $table_addressmeta = "ya_users_addressmeta";
    private $table_profile_detail = "ya_users_profile_detail";
    private $table_posts = "ya_posts";
    private $table_postmeta = "ya_postmeta";
    private $table_terms = "ya_terms";
    private $table_term_relationships = "ya_term_relationships";
    private $table_term_taxonomy = "ya_term_taxonomy";
    private $table_termmeta = "ya_termmeta";
    private $table_wcpv_commissions = "ya_wcpv_commissions";
    private $table_tax_rates = "ya_woocommerce_tax_rates";
    private $table_woocommerce_shipping_zone_locations = "ya_woocommerce_shipping_zone_locations";
    private $table_woocommerce_shipping_zone_methods = "ya_woocommerce_shipping_zone_methods";
    private $table_woocommerce_sessions = "ya_woocommerce_sessions";
    private $table_api_token = "ya_api_token";
    private $table_device_info = "ya_device_info";
	private $table_user_forgotpass = "ya_user_forgotpass";
    // object properties
    public $id;
    public $username;
    public $password;
    public $cpassword;
    public $email;
    public $femail;
    public $created;
    public $fusername;
	private $key = "dyuf7r67t";

    //echo 'test';
    // constructor with $db as database connection
    public function __construct($db){ 
        $this->conn = $db;
    }

    // signup user 
    function create_user(){ 

        $query = "SELECT * FROM ".$this->table_name. " WHERE user_login='".$this->username."' OR user_email='".$this->useremail."'"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if(empty($stmt->rowCount())) { 

            if ( !$user_id and email_exists($this->useremail) == false ) {
                
                $user_id = wp_create_user( $this->username, $this->userpassword, $this->useremail ); 

                wp_set_password( $this->userpassword, $user_id );
            } else {
                $random_password = __('User already exists.  Password inherited.');
            }

            $query = "UPDATE " . $this->table_name . " SET user_login='" .$this->username. "',user_email='" .$this->useremail. "' ,user_registered='" .date('Y-m-d H:i:s') . "' WHERE ID=".$user_id.""; 
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            //$user = get_user_by( 'ID', 73 );   print_r($user);
             $user = get_user_by( 'email', $this->useremail ); 
             $user->set_role('customer');  // print_r($user);


            update_user_meta( $user_id, 'first_name', $this->user_firstname);
            update_user_meta( $user_id, 'last_name', $this->user_lastname);
            update_user_meta( $user_id, 'country_code', $this->country_code);
            
            $query = "INSERT INTO " . $this->table_namemeta . " (user_id,meta_key,meta_value) VALUES (".$user_id .",'billing_phone','".$this->user_phone."')";
            $stmt1 = $this->conn->prepare($query);
            $stmt1->execute();     // print_r($query);

            $user = get_user_by( 'email', $this->useremail ); 
            $user_info[ID] = $user->ID;
            $user_info[user_login] = $user->user_login;
            $user_info[user_nicename] = $user->user_nicename;
            $user_info[user_email] = $user->user_email;
            $user_info[user_registered] = $user->user_registered;
            $user_info[display_name] = $user->display_name;
            $user_info[mobile] = $this->user_phone;
            $user_info[country_code] = $this->country_code;

            $query = "SELECT * FROM ".$this->table_device_info. " ";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            if($stmt->rowCount() == 0){ 
                $query = "CREATE TABLE " . $this->table_device_info . " (user_id INT(100) NOT NULL,device_id VARCHAR(700) ,device_type VARCHAR(10))";
                            $stmt = $this->conn->prepare($query);
                            $stmt->execute();
            }

            $query = "INSERT INTO " . $this->table_device_info . " (user_id,device_id,device_type) VALUES (".$user_id .",'".$this->device_id."','".$this->device_type."')";
            $stmt = $this->conn->prepare($query);

            if($stmt->execute()){
                $user_info[device_id] = $this->device_id;
                $user_info[device_type] = $this->device_type;
                return $user_info;
            } 
        }
        return false;
    }
    function create(){ 
        $query = "SELECT * FROM ".$this->table_name. " WHERE user_login='".$this->username."' OR user_email='".$this->useremail."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if(empty($stmt->rowCount())){
        if ( !$user_id and email_exists($this->useremail) == false ) {
            
            $user_id = wp_create_user( $this->username, $this->userpassword, $this->useremail );
            wp_set_password( $this->userpassword, $user_id );
        } else {
            $random_password = __('User already exists.  Password inherited.');
        }
            $query = "UPDATE " . $this->table_name . " SET user_login='" .$this->username. "',user_email='" .$this->useremail. "' ,user_registered='" .date('Y-m-d H:i:s') . "' WHERE ID=".$user_id.""; 
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
             $user = get_user_by( 'email', $this->useremail ); 
             $user->set_role('wc_product_vendors_manager_vendor'); 
            if($stmt->execute()){ 
                return true;
            } 
        }
        $user = $stmt->fetch(PDO::FETCH_ASSOC); 
        if($this->username == $user[user_login] && $this->useremail == $user[user_email]){
            $error = "username and Email already exist!!"; 
        } elseif ($this->username == $user[user_login]) {
            $error = "username already exist!!";
        } else {
            $error = "Email already exist!!";
        }
        return $error;
    }
    function emailExists()  { 
        $user = get_user_by( 'email', $this->useremail );  
            if ( $user && wp_check_password( $this->userpassword, $user->user_pass ) ) {
                $check = 1;
            } else { 
                $check = 0;
            }
        if($check == 1){
            
            if($this->vendor == 1){
                $user = get_user_by( 'email', $this->useremail );   
                $vendor_info = get_term_by('name',$user->user_login,'wcpv_product_vendors');  
                if(empty($vendor_info)){
                    return false;
                }  
            }
            if($this->vendor == 0){
                $user = get_user_by( 'email', $this->useremail );   
                $vendor_info = get_term_by('name',$user->user_login,'wcpv_product_vendors'); 
                if(!empty($vendor_info)){
                    return false;
                }  
            }

            $query = "SELECT ID,user_login,user_pass,user_email FROM " . $this->table_name . " WHERE user_email = '".$this->useremail."'";
            $stmt = $this->conn->prepare( $query );
            // sanitize
            $this->email=htmlspecialchars(strip_tags($this->useremail));
            // bind given email value
            $stmt->bindParam(1, $this->useremail);
            $stmt->execute();
            $num = $stmt->rowCount();
            if($num>0){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                //print_r($row);$vendor_name = get_term_by('id',$this->vid,'wcpv_product_vendors');
                $this->ID = $row[ID];
                $user_info[user_id] = $row[ID];
                $this->user_name = $row[user_login];
               // $this->user_email = $row['user_email'];
                $this->password = $row[user_pass];
                $query = "SELECT term_id FROM ".$this->table_terms." WHERE name='".$row[user_login]."'";
                $stmt = $this->conn->prepare($query);
                $stmt->execute(); 
                $num1 = $stmt->rowCount(); 
                if($num1>0) {
                    $vendor_term_id = $stmt->fetch(PDO::FETCH_ASSOC); 
                    $user_info[vendor_id] = $vendor_term_id[term_id];
                }

                $query = "SELECT * FROM ".$this->table_device_info." WHERE user_id=".$user_info[user_id].""; //print_r($query); 
                $stmt = $this->conn->prepare($query);
                $stmt->execute();       // $row_c = $stmt->rowCount();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                if(empty($row)){ 
                    $query = "INSERT INTO " . $this->table_device_info . " (user_id,device_id,device_type) VALUES (".$user_info[user_id] .",'".$this->device_id."','".$this->device_type."')";
                       
                } else {
                    $query = "UPDATE " . $this->table_device_info . " SET device_id='".$this->device_id."',device_type='".$this->device_type."' WHERE user_id=".$user_info[user_id].""; //print_r($query);
                }
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                $user_info[device_id] = $this->device_id;
                $user_info[device_type] = $this->device_type;
                return $user_info;
            }
        }
        return false;
    }
    function forgetpass(){	
		
        define( 'API_ACCESS_KEY', 'AAAAhJ63EUE:APA91bE5Of01l9GX1zIbn8nWSlNQTsbKDodXPn8DGnAW0dQKc3aWb9thDyKdUKbYuhed3CGgnsf8BAYDZg5u2DfFp36VEzWyLgqGAhyvi1m-DkcflKCnoj3JORglsodhBtaTf5Uh0IS0' ); // AIzaSyDtY_FUGSOSWD_vkmcmhmyVh8kmt31pA5s
        require(get_template_directory() . '/../../../wp-load.php');  
         $query = "SELECT ID,user_login FROM " . $this->table_name . " WHERE user_email='".$this->femail."'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $user_detail = $stmt->fetch(PDO::FETCH_ASSOC);  
                $user = new WP_User( intval($user_detail[ID]) );
				//echo "<pre>"; print_r(); die;
                $random_password = wp_generate_password( 12, false );
                $encrypt_pass = wp_hash_password($random_password);
                //print_r($encrypt_pass); die;
                //
                wp_set_password( $random_password, $user->ID );
                
                $to = $this->femail;
                $subject = 'Neswa key to reset password';
                //$body = $response1->shortLink;  // previewLink
                $body = '<p>Hi,</p><p>Your key to reset password for Neswa app is: '.$random_password.'<br></p><p>Regards<br>All at Neswa</p>';
                $headers1 = array('Content-Type: text/html; charset=UTF-8');

                wp_mail( $to, $subject, $body, $headers1 );
                //return $pass_reset_link;
                return true;
            }
            return false;
    }
    function reset_password(){
        define( 'API_ACCESS_KEY', 'AAAAhJ63EUE:APA91bE5Of01l9GX1zIbn8nWSlNQTsbKDodXPn8DGnAW0dQKc3aWb9thDyKdUKbYuhed3CGgnsf8BAYDZg5u2DfFp36VEzWyLgqGAhyvi1m-DkcflKCnoj3JORglsodhBtaTf5Uh0IS0' ); // AIzaSyDtY_FUGSOSWD_vkmcmhmyVh8kmt31pA5s
        require(get_template_directory() . '/../../../wp-load.php');  
         $query = "SELECT ID,user_login FROM " . $this->table_name . " WHERE user_email='".$this->femail."'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $user_detail = $stmt->fetch(PDO::FETCH_ASSOC);  
                $user = new WP_User( intval($user_detail[ID]) );
				
                $random_password = $this->password;

                wp_set_password( $random_password, $user->ID );
                
                /*$to = $this->femail;
                $subject = 'Neswa key to reset password';
                //$body = $response1->shortLink;  // previewLink
                $body = '<p>Hi,</p><p>Your key to reset password for Neswa app is: '.$random_password.'<br></p><p>Regards<br>All at Neswa</p>';
                $headers1 = array('Content-Type: text/html; charset=UTF-8');

                wp_mail( $to, $subject, $body, $headers1 );*/
                //return $pass_reset_link;
                return true;
            }
            return false;
    }
	function encrypt($string, $key) {
		
        $iv = mcrypt_create_iv(
                        mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
                        MCRYPT_DEV_URANDOM
                    );
        $encrypted = base64_encode(
                        $iv .
                        mcrypt_encrypt(
                            MCRYPT_RIJNDAEL_128,
                            hash('sha256', $this->key, true),
                            $string,
                            MCRYPT_MODE_CBC,
                            $iv
                        )
                    );
        return $encrypted;
    }
    function decrypt($encrypted, $key) {
        $data = base64_decode($encrypted);
        $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

        $decrypted = rtrim(
                mcrypt_decrypt(
                MCRYPT_RIJNDAEL_128,
                hash('sha256', $key, true),
                substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
                MCRYPT_MODE_CBC,
                $iv
            ),
            "\0"
        );
        return $decrypted;
    }
    function add_profile_img($img_url,$new_folder_month){
            //print_r($new_folder_month);
            $files = scandir($new_folder_month, SCANDIR_SORT_DESCENDING); //print_r($files);
            $newest_file = $files[0];  //print_r($files);
            //  print_r($newest_file);
            $file_name = explode('-',$newest_file);  //print_r($file_name);
            $img_name = explode('.',$file_name[0]);
            $img_name = $img_name[0] + 1;  //print_r($img_name);
            //$file_name = $file_name + 1; 
            //$img_name = $file_name[0]; print_r($img_name);
        
        //print_r($value1);

            foreach($img_url as $key1 => $value1){
                $source_file = $value1; //print_r($source_file); 
                //print_r($source_file);
                $find = substr($value1,strrpos($value1,'/')+1);
                $check = explode('-',$find); //print_r($check);
                
                    
                    $check[0] = $img_name;
                    $find2 = implode('-',$check); //print_r($find2);

                    $destination_path = $new_folder_month.'/'.$find2;
                    $no = 1;
                    while (file_exists($destination_path)) {
                        $img_name = $img_name . "(". $no . ")";
                        $check[0] = $img_name;
                        $find2 = implode('-',$check);
                       $destination_path = $new_folder_month.'/'.$find2; 
                       $no++;
                    }
                    //file_put_contents($destination_path, file_get_contents($source_file));
                   // print_r($destination_path);
                    //$img_items = $destination_path;
                    $img_items = explode('..',$destination_path); //print_r($img_items[4]);
                    $img_items = "" .site_url()."/wp-content".$img_items[4].""; 
            } // print_r($img_items);
            
            //print_r($img_items); 
        return $img_items;
    }
    function editprofile() { 
		$msg = array();
        if($_SERVER['REQUEST_METHOD'] == "GET"){
            $user_data = get_userdata($this->fid);

            if(!empty($user_data)) {
                $edit_profile_output['uid'] = $user_data->data->ID;
                $edit_profile_output['full_name'] = $user_data->data->user_login;
                $edit_profile_output['user_name'] = $user_data->data->display_name;
                $edit_profile_output['user_email'] = $user_data->data->user_email;
                $edit_profile_output['bio'] = get_user_meta($this->fid, 'description', true);
                $edit_profile_output['mobile'] = get_user_meta($this->fid, 'billing_phone', true);
                $edit_profile_output['country_code'] = get_user_meta($this->fid, 'country_code', true);

                $query = "SELECT * FROM " . $this->table_profile_detail  . " WHERE user_id=".$this->fid." LIMIT 1 ";
                $stmt = $this->conn->prepare($query); 
                $stmt->execute(); 
                $details = $stmt->fetch(PDO::FETCH_ASSOC);  
                
                $edit_profile_output['gender'] = $details[a_gender];
                $edit_profile_output['birthday'] = $details[a_birthdate]; 
                $edit_profile_output['image_url'] = $details[a_image_url];
                
                return $edit_profile_output;
            } else {
                return false;
            }
        }

            //$user_info = get_user_by('email',$this->fuseremail);
            $query = "SELECT * FROM ".$this->table_name." WHERE user_email='".$this->fuseremail."'";  
            $stmt = $this->conn->prepare($query);
            $stmt->execute();   
            $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$flag = 0;
		
			foreach($user_info as $key => $value){
				if($value['ID'] != $this->fid){
					$flag = 1;
					break;
				}
			}			
            //if(count($user_info) <= 1){
            if($flag != 1) {
                $query1 = "SELECT ID FROM " . $this->table_name . " WHERE ID=".$this->fid."";
                $stmt = $this->conn->prepare($query1);
                $stmt->execute();
                    //print_r($stmt);
                if($stmt->rowCount() > 0) {
                        $query = "SELECT * FROM " . $this->table_profile_detail . " LIMIT 1 ";
                        // prepare query statement
                        $stmt = $this->conn->prepare($query);
                        // execute query
                        $stmt->execute();
                        //print_r($stmt);
                            if($stmt->rowCount() == 0){
                                //echo 'test';
                                $query = "CREATE TABLE " . $this->table_profile_detail . "(user_id INT(100) NOT NULL,a_civil_id VARCHAR(100) ,a_gender VARCHAR(10),a_birthdate VARCHAR,a_image_url VARCHAR(150),a_latitude FLOAT(50),a_longitude FLOAT(50),show_location INT(10),a_address VARCHAR(500))";
                                $stmt = $this->conn->prepare($query);
                                //print_r($stmt);
                                $stmt->execute();
                            }
                              /*  else {   
                                    $query = "ALTER TABLE " . $this->table_profile_detail . " ADD a_latitude FLOAT(50),ADD a_longitude FLOAT(50),ADD show_location INT(5),ADD a_address VARCHAR(100)"; 
                                    $stmt = $this->conn->prepare($query);
                                    //print_r($stmt);
                                    $stmt->execute();
                                } */
								
                              for ($i=1; $i < 100; $i++) { 
                                if(!empty($this->image[$i])){
                                    //print_r($this->image[$i]);
                                    $upload_dir       = wp_upload_dir(); 
                                    $unique_file_name = wp_unique_filename( $upload_dir['path'], $this->image[$i]["name"] ) ;
                                    $filename         = basename( $unique_file_name );
                                    //print_r($filename);

                                    $file1 = $upload_dir['url'] . '/' . $filename;
                                    if( wp_mkdir_p( $upload_dir['path'] ) ) {
                                        $file = $upload_dir['path'] . '/' . $filename;
                                    } else {
                                        $file = $upload_dir['basedir'] . '/' . $filename;
                                    }   //print_r($file);

                                   $wp_filetype = wp_check_filetype( $filename, null );    //print_r($wp_filetype);
                                    // print_r( file_get_contents($_FILES["image"]["tmp_name"]) );
                                    $data = file_get_contents($this->image[$i]["tmp_name"]); //print_r($data);
                                    //echo $upload_dir[basedir]."/images/".$this->image[$i]['name'];
                                    move_uploaded_file($this->image[$i]["tmp_name"],$upload_dir[basedir]."/images/".$this->image[$i]['name']);
                                    $data = file_get_contents($upload_dir[basedir]."/images/".$this->image[$i]['name']); 
                                    //print_r($upload_dir[basedir].'/images'); 
                                    file_put_contents( $file, $data );
                                    
                                   
                                /*    $wp_filetype = wp_check_filetype( $filename, null );    //print_r($wp_filetype);
                                    // print_r( file_get_contents($_FILES["image"]["tmp_name"]) );
                                    $data = file_get_contents($this->image[$i]["tmp_name"]); //print_r($data);
                                    # code...
                                    file_put_contents( $file, $data );*/
                                    $org_img[0] = $file1;
                                }
                            }            
                            $query = "SELECT * FROM " . $this->table_profile_detail . " WHERE user_id=".$this->fid;
							// prepare query statement
							$stmt = $this->conn->prepare($query);
							// execute query
							$stmt->execute();   

                            if($stmt->rowCount() == 0){
                                $query = "INSERT INTO " . $this->table_profile_detail . "(user_id,a_civil_id,a_gender,a_birthdate,a_image_url) VALUES (".$this->fid .",'" .$this->fusercivil. "','" .$this->fusergender. "', '" . $this->fuserbirthdate."','".$org_img[0]."')";
                                $stmt1 = $this->conn->prepare($query);
                                //print_r($stmt1);
                                $stmt1->execute();  
                               // echo 'insert table value';
                            }
                    $query = "UPDATE " . $this->table_profile_detail . " SET a_civil_id='" .$this->fusercivil. "',a_gender='" .$this->fusergender. "' ,a_birthdate='" .$this->fuserbirthdate . "',a_image_url='" .$org_img[0]. "'   WHERE user_id=".$this->fid.""; //print_r($query);
                    $stmt4 = $this->conn->prepare($query);
                    $stmt4->execute();  //print_r($stmt4->execute());
                    //print_r($stmt4);
                    // query to update full name
                    $query = "UPDATE " . $this->table_name . " SET display_name='" .$this->fusername. "',user_email='" .$this->fuseremail. "' WHERE ID=".$this->fid."";
                    $stmt1 = $this->conn->prepare($query);
                    $stmt1->execute();
                    //print_r($stmt1);
                    // query to update Bio
                    $query = "SELECT * FROM " .  $this->table_namemeta  . " WHERE user_id=".$this->fid." AND meta_key='description' LIMIT 1 ";
                        // prepare query statement
                        $stmt = $this->conn->prepare($query);
                        // execute query
                        $stmt->execute();
                        //print_r($stmt);
                            if($stmt->rowCount() == 0){
                                $query = "INSERT INTO " . $this->table_namemeta . "(user_id,meta_key,meta_value) VALUES (".$this->fid .",'description','".$this->fuserbio."')";
                                $stmt1 = $this->conn->prepare($query);
                                //print_r($stmt1);
                                $stmt1->execute();
                                //echo 'insert table value';
                            }
                    $query = "UPDATE " . $this->table_namemeta . " SET meta_value='".$this->fuserbio."' WHERE user_id=".$this->fid." AND meta_key='description'";
                    $stmt2 = $this->conn->prepare($query);
                    $stmt2->execute();
                    //print_r($stmt2);
                    // query to update phone number
                    $query = "SELECT * FROM " .  $this->table_namemeta  . " WHERE user_id=".$this->fid." AND meta_key='billing_phone' LIMIT 1 ";
                        // prepare query statement
                        $stmt = $this->conn->prepare($query);
                        // execute query
                        $stmt->execute();
                        //print_r($stmt);
                            if($stmt->rowCount() == 0){
                                $query = "INSERT INTO " . $this->table_namemeta . "(user_id,meta_key,meta_value) VALUES (".$this->fid .",'billing_phone','".$this->fuserphone."')";
                                $stmt1 = $this->conn->prepare($query);
                                //print_r($stmt1);
                                $stmt1->execute();
                               // echo 'insert table value';
                            }
                    $query = "UPDATE " . $this->table_namemeta . " SET meta_value='".$this->fuserphone."' WHERE user_id=".$this->fid." AND meta_key='billing_phone'";
                    $stmt3 = $this->conn->prepare($query);
                    $stmt3->execute();
                   // print_r($stmt3);
					$msg['status'] = "true";
					$msg['msg'] = "update success";
					return $msg;
                }
				$msg['status'] = "false";
				$msg['msg'] = "user not found";
				return $msg;    
            }
		$msg['status'] = "false";
		$msg['msg'] = "email exists";
        return $msg;    
    }
     function GetAllDetailProfile($fid){
        //UPDATE ya_usermeta SET meta_value = 'firstname' WHERE user_id=1 AND meta_key='first_name';
        $query = "SELECT * FROM " . $this->table_namemeta . " WHERE user_id=".$fid."";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            $stmt->execute();
            //print_r($stmt);
            return $stmt;
        
         $query = "SELECT * FROM " . $this->table_namemeta . " WHERE user_id=".$fid."";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            $stmt->execute();
            //print_r($stmt);
            return $stmt;
    }
        function settingscreen(){
            if(isset( $this->uid)){
                $query = "SELECT * FROM " . $this->table_name . " WHERE ID='".$this->uid ."'";
                // prepare query statement
                $stmt = $this->conn->prepare($query);
                // execute query
                $stmt->execute();
                //print_r($stmt);
                if($stmt->rowCount() > 0){
                    //UPDATE ya_usermeta SET meta_value = 'firstname' WHERE user_id=1 AND meta_key='first_name';
                    $query = "UPDATE " . $this->table_namemeta . " SET meta_value='" . $this->billingcountry . "' WHERE user_id=".$this->uid." AND meta_key='billing_country'";
                    // prepare query statement
                    $stmt = $this->conn->prepare($query);
                    // execute query
                    $stmt->execute();
                    //print_r($stmt);
                    //return $stmt;
                    $query = "UPDATE " . $this->table_namemeta . " SET meta_value='" . $this->language . "' WHERE user_id=".$this->uid." AND meta_key='icl_admin_language'";
                    // prepare query statement
                    $stmt = $this->conn->prepare($query);
                    // execute query
                    $stmt->execute();
                    //print_r($stmt);
                    return $stmt;
                    //return true;
                }
                return false;
        }else{
            return false;
        }
    }

    function profile(){
        $this->fid;

        $user_info = get_userdata($this->fid);

        if(!empty($user_info)) {
            $user_data['ID'] = $user_info->data->ID;
            $user_data['display_name'] = $user_info->data->display_name;
            $user_data['user_login_name'] = $user_info->data->user_login;
            $user_data['email'] = $user_info->data->user_email;
            $user_data['description'] = get_user_meta($this->fid, 'description', true);
            $user_data['billing_phone'] = get_user_meta($this->fid, 'billing_phone', true);
            $user_data['country_code'] = get_user_meta($this->fid, 'country_code', true);

            $query = "SELECT a_image_url FROM " . $this->table_profile_detail. " WHERE user_id=".$this->fid."";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
            $user_img_url = $stmt->fetch(PDO::FETCH_ASSOC); 
            
            $user_data['image_url'] = $user_img_url[a_image_url];

            if($this->address ==1){
                $query = "SELECT a_title,a_floor,a_apartment,a_street,a_area,a_zip_code,a_paci_number FROM " . $this->table_addressmeta . " WHERE user_id=".$this->fid."";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $user_address = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                
                $user_data['address'] = $user_address;
            }

            return $user_data;
        } else {
            return false;
        }
    }

    function newaddress(){ 
        if(isset( $this->uid)){
            $query = "SELECT * FROM " . $this->table_name . " WHERE ID=".$this->uid ."";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            $stmt->execute();
            //print_r($stmt);
            if($stmt->rowCount() > 0){
                    $query = "SELECT * FROM " . $this->table_addressmeta . " LIMIT 1 ";
                    // prepare query statement
                    $stmt = $this->conn->prepare($query);
                    // execute query
                    $stmt->execute();
                    if($stmt->rowCount() == 0){
                        //echo 'test';
                        $query = "CREATE TABLE ya_users_addressmeta ( user_id INT(100) NOT NULL , a_title VARCHAR(100) NOT NULL, a_apartment VARCHAR(40) , a_floor VARCHAR(100) , a_street VARCHAR(100) NOT NULL, a_area VARCHAR(100) NOT NULL,a_zip_code INT(15) NOT NULL, a_gender VARCHAR(10) NOT NULL,a_paci_number VARCHAR(15) NOT NULL,a_avenue VARCHAR(15))";
                        $stmt = $this->conn->prepare($query);
                        //print_r($stmt);
                        $stmt->execute();
                    }else{
                        //echo 'table already exist';
                    }
                    $add_line_1 = $this->a_paci_number.','.$this->a_apartment.','.$this->a_floor;
                    $add_line_2 = $this->avenue.','.$this->a_street.','.$this->a_area;
                    $shipping_postcode = $this->a_zip_code;

                        if($this->a_status=="insert"){                       

                                $query = "SELECT * FROM " . $this->table_addressmeta . " WHERE a_title='".$this->a_title ."' AND user_id=".$this->uid ."";
                                // prepare query statement
                                $stmt = $this->conn->prepare($query);
                                // execute query
                                $stmt->execute();
                                if($stmt->rowCount() == 0){

                                    update_user_meta($this->uid,'shipping_address_1',$add1);
                                    update_user_meta($this->uid,'shipping_address_2',$add_line_2);
                                    update_user_meta($this->uid,'shipping_postcode',$shipping_postcode);


                                    $address_value = 0;
                                    for ($i=1; $i<=50; $i++) { 
                                        $get_user_meta = get_user_meta($this->uid,'title'.$i);
                                        if(empty($get_user_meta)){
                                            $address_value = $i;
                                            break;
                                        }
                                    }
                                    add_user_meta( $this->uid, 'title'.$address_value, $this->a_title );
                                    add_user_meta( $this->uid, 'address'.$address_value.'_line_1', $add_line_1 );
                                    add_user_meta( $this->uid, 'address'.$address_value.'_line_2',$add_line_2 );
                                    add_user_meta( $this->uid, 'postcode'.$address_value, $shipping_postcode );

                                   $query = "INSERT INTO " . $this->table_addressmeta . "( user_id , a_title , a_apartment , a_floor , a_street , a_area ,a_zip_code , a_gender ,a_paci_number,a_avenue ) VALUES (" . $this->uid . ",'" . $this->a_title . "','" . $this->a_apartment . "','" . $this->a_floor . "','" . $this->a_street . "','" . $this->a_area . "'," . $this->a_zip_code . ",'" . $this->a_gender . "','" . $this->a_paci_number . "','".$this->avenue."') ";
                                    $stmt = $this->conn->prepare($query);
                                    // execute query
                                    $stmt->execute();
                                    $query = "SELECT user_email FROM " . $this->table_name . " WHERE ID=".$this->uid ."";
                                    $stmt = $this->conn->prepare($query);
                                    $stmt->execute();
                                    $email = $stmt->fetch(PDO::FETCH_ASSOC); 

                                    $query = "SELECT meta_value FROM ".$this->table_namemeta." WHERE user_id=".$this->uid." AND meta_key='shipping_first_name'";
                                    $stmt = $this->conn->prepare($query);
                                    $stmt->execute();  
                                    if($stmt->rowCount() == 0) { 
                                        $query = "SELECT meta_key,meta_value FROM ".$this->table_namemeta." WHERE user_id =".$this->uid." AND meta_key IN ('first_name','last_name')";
                                        $stmt = $this->conn->prepare($query);
                                        $stmt->execute();
                                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);  

                                        $query = "UPDATE ".$this->table_namemeta.
                                        " SET meta_value=(CASE WHEN meta_key='billing_first_name' THEN '".$row[0][meta_value]."'".
                                        " WHEN meta_key='billing_last_name' THEN '".$row[1][meta_value]."'".
                                        " WHEN meta_key='billing_company' THEN ' '".
                                        " WHEN meta_key='billing_address_1' THEN '".$this->a_apartment.','.$this->a_floor."'".
                                        " WHEN meta_key='billing_address_2' THEN '".$this->a_street.','.$this->a_area."'".
                                        " WHEN meta_key='billing_city' THEN ' '".
                                        " WHEN meta_key='billing_postcode' THEN '".$this->a_zip_code."'".
                                        " WHEN meta_key='billing_country' THEN ' '".
                                        " WHEN meta_key='billing_state' THEN ' '".
                                      //  " WHEN meta_key='billing_phone' THEN '".$this->billing_phone."'".
                                        " WHEN meta_key='billing_email' THEN '".$email[user_email]."'".
                                        " WHEN meta_key='shipping_first_name' THEN '".$row[0][meta_value]."'".
                                        " WHEN meta_key='shipping_last_name' THEN '".$row[1][meta_value]."'".
                                        " WHEN meta_key='shipping_company' THEN ' '".
                                        " WHEN meta_key='shipping_address_1' THEN '".$this->a_floor.','.$this->a_apartment."'".
                                        " WHEN meta_key='shipping_address_2' THEN '".$this->a_street.','.$this->a_area."'".
                                        " WHEN meta_key='shipping_city' THEN ' '".
                                        " WHEN meta_key='shipping_postcode' THEN '".$this->a_zip_code."'".
                                        " WHEN meta_key='shipping_country' THEN ' '".
                                        " WHEN meta_key='shipping_state' THEN ' '".
                                        " WHEN meta_key='shipping_method' THEN ' '".
                                        " END)" . " WHERE user_id=".$this->uid." AND 
                                        meta_key IN('billing_first_name','billing_last_name','billing_company','billing_address_1','billing_address_2','billing_city','billing_postcode','billing_country','billing_state','billing_phone','billing_email','shipping_first_name','shipping_last_name','shipping_company','shipping_address_1','shipping_address_2','shipping_city','shipping_postcode','shipping_country','shipping_state','shipping_method')";
                                        $stmt = $this->conn->prepare($query);
                                        $stmt->execute();// print_r($stmt);
                                    }   

                                    $stmt="Insert data successfully.. !";
                                    return $stmt;
                                }else{
                                    //echo "Data already exists.. !";
                                    $stmt="Data already exists.. !";
                                    return $stmt;
                                }
                        }
                            elseif ($this->a_status=="update") {
                                $query = "SELECT * FROM " . $this->table_addressmeta . " WHERE a_title='".$this->a_title ."' AND user_id=".$this->uid."";
                                // prepare query statement
                                $stmt = $this->conn->prepare($query);
                                // execute query
                                $stmt->execute();  // print_r($stmt);
                                if($stmt->rowCount() > 0){
                                    /*
                                    update_user_meta($this->uid,'shipping_address_1',$add1);
                                    update_user_meta($this->uid,'shipping_address_2',$add_line_2);                                    
                                    update_user_meta($this->uid,'shipping_postcode',$shipping_postcode); */

                                    $address_value = 0;
                                    for ($i=1; $i<=50; $i++) { 
                                        $get_user_meta = get_user_meta($this->uid,'title'.$i); 
                                        if( $get_user_meta[0] == $this->a_title ){
                                            $address_value = $i;
                                            break;
                                        }
                                    }
                                    update_user_meta( $this->uid, 'title'.$address_value, $this->a_title );
                                    update_user_meta( $this->uid, 'address'.$address_value.'_line_1', $add_line_1 );
                                    update_user_meta( $this->uid, 'address'.$address_value.'_line_2',$add_line_2 );
                                    update_user_meta( $this->uid, 'postcode'.$address_value, $shipping_postcode );

                                   $query = "UPDATE " . $this->table_addressmeta . " SET  a_apartment= '" . $this->a_apartment . "', a_floor= '" . $this->a_floor . "', a_street= '" . $this->a_street . "', a_area= '" . $this->a_area . "',a_zip_code= " . $this->a_zip_code . ", a_gender= '" . $this->a_gender . "',a_paci_number= '" . $this->a_paci_number . "',a_avenue= '" . $this->avenue ."' WHERE user_id=".$this->uid." AND a_title='" . $this->a_title ."'";
                                    // prepare query statement
                                    $stmt = $this->conn->prepare($query);
                                    $stmt->execute();
                                    $stmt = "Update data successfully.. !";
                                    return $stmt;
                                } else {
                                    $stmt = "Data Address Name Not found.. !";
                                    return $stmt;
                                }
                            } else {   
								$stmt = "Error in inserting/updating of data!";
                                return $stmt;
                            }
        }
            return false;
        } else {
            return false;
        }
    }
    function deletewishlist(){
            //$query = "SELECT * FROM " . $this->table_name . " WHERE ID=".$this->fid.;
            //echo $query;
            $query1 = "SELECT user_id FROM " . $this->table_namemeta . " WHERE user_id=".$this->fid."";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->execute();
            if($stmt1->rowCount() > 0) {
                // query to delete wishlisted item 'DELETE FROM tutorials_tbl WHERE tutorial_id = 3';
                $query = "DELETE FROM " . $this->table_wishlist . " WHERE author=" .$this->fid. " AND product_id=" .$this->wishlistid. "" ;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
               // print_r($stmt);
                return $stmt;
            } else {
                return false;
            }
            return $stmt;
    }
     function deleteorder(){
            //$query = "SELECT * FROM " . $this->table_name . " WHERE ID=".$this->fid.;
            //echo $query;
            $query1 = "SELECT user_id FROM " . $this->table_namemeta . " WHERE user_id=".$this->fid."";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->execute();
            //print_r($stmt1);
            if($stmt1->rowCount() > 0 && ( $this->status=="on-hold" || $this->status=="processing")) {
                $query = "SELECT order_item_name FROM " . $this->table_order . " WHERE order_id=" .$this->orderid. " AND order_item_id=" .$this->orderitemid. "" ;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                //print_r($stmt);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $order_item_name= $row[order_item_name];
                $query = "SELECT order_item_id FROM " . $this->table_order . " WHERE  order_id=" .$this->orderid. " ORDER BY order_item_id DESC  LIMIT 1" ;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                //print_r($stmt); 
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $order_item_id=$row[order_item_id];
                $query = "SELECT meta_value FROM " . $this->table_ordermeta . " WHERE order_item_id=" . $order_item_id . " AND meta_key='Items'" ;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                //print_r($stmt);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $meta_value = $row[meta_value];
                $pieces = explode(",",$meta_value);
                $newarray=array();
                foreach ($pieces as $key => $value) {
                    $frontstring=strstr($value, '&', true);
                    $rearstring= strstr($value, '&');
                    if(trim($frontstring) != $order_item_name)
                        array_push($newarray,$value);
                } 
                $pieces = implode(',', $newarray);          
                $query = "UPDATE " . $this->table_ordermeta . " SET meta_value =  '" . $pieces . "'  WHERE order_item_id=" . $order_item_id . " AND meta_key='Items'" ;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                //print_r($stmt);
                // query to delete wishlisted item 'DELETE FROM tutorials_tbl WHERE tutorial_id = 3';
                $query = "DELETE FROM " . $this->table_order . " WHERE order_id=" .$this->orderid. " AND order_item_id=" .$this->orderitemid. "" ;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                //print_r($stmt);
                $query2 = "DELETE FROM " . $this->table_ordermeta . " WHERE order_item_id=" .$this->orderitemid. "";
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->execute();
                //print_r($stmt);
                return $stmt;
            } 
                return false;
    }
    function deleteaddress(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE ID='".$this->uid ."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if($stmt->rowCount() > 0){
        $query = "SELECT * FROM " . $this->table_addressmeta . " WHERE user_id=".$this->uid ." AND a_title='".$this->a_title ."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if($stmt->rowCount() > 0){
        $query = "DELETE FROM " . $this->table_addressmeta . " WHERE user_id=".$this->uid ." AND a_title='".$this->a_title ."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt="Delete Address Title successfully.. !";
        return $stmt;
        }
        $stmt="User Id or Address Title Not found.. !";
        return $stmt;
        }
        return false;
    }
    function addwishlist(){ 
            //$query = "SELECT * FROM " . $this->table_name . " WHERE ID=".$this->fid.;
            //echo $query;
            $query1 = "SELECT user_id FROM " . $this->table_namemeta . " WHERE user_id=".$this->fid."";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->execute();
            if($stmt1->rowCount() > 0) {
                // check for user in wishlist table
                $query2 = "SELECT wishlist_id FROM " . $this->table_wishlist . " WHERE author=".$this->fid."";
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->execute();

                if(empty($this->quantity)){
                        $this->quantity = 1;
                    }
                if(empty($this->variationid)){
                        $this->variationid = 0;
                    }
                
                if($stmt2->rowCount() > 0) {
                    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                    $row_wishlistid = $row['wishlist_id'];
                    //print_r($row_wishlistid);
                     //Insert item into wishlist
                    $query2 = "SELECT product_id FROM " . $this->table_wishlist . " WHERE author=".$this->fid."";  
                    $stmt2 = $this->conn->prepare($query2);
                    $stmt2->execute();
                    $w_product = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                    //print_r($w_product);
                    foreach ($w_product as $key => $value) {    
                            if($this->productid == $value[product_id]){
                                $wish_l = 1;
                                break;
                            } else{
                                $wish_l = 0;
                            }
                        }
                        //print_r($wish_l);
                    if($wish_l == 0){
                        $query ="INSERT INTO " .$this->table_wishlist. " (wishlist_id, product_id, variation_id, formdata,author, date, quantity, price, in_stock) VALUES (" .$row_wishlistid. "," .$this->productid. ",'" .$this->variationid. "',' '," .$this->fid. ",'2019-01-24 13:20:18'," .$this->quantity. ",'" .$this->price. "'," .$this->in_stock. ")";
                        $stmt = $this->conn->prepare($query);
                        $stmt->execute();
                        $status = "Item added to the wishlist!";
                    }   else {
                            $status = "Item already exists!";
                        }
                    
                    //print_r($status);
                    return $status;
                } else {
                    $query = "SELECT wishlist_id FROM " .$this->table_wishlist. " ORDER BY ID DESC LIMIT 1";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    if($stmt->rowCount() > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $row_wishlistid = $row['wishlist_id']+1;
                    } else {
                        $row_wishlistid = 1;
                    }
                    $query ="INSERT INTO " .$this->table_wishlist. " (wishlist_id, product_id, variation_id, formdata,author, date, quantity, price, in_stock) VALUES (" .$row_wishlistid. "," .$this->productid. "," .$this->variationid. ",' '," .$this->fid. ",'2019-01-24 13:20:18'," .$this->quantity. ",'" .$this->price. "'," .$this->in_stock. ")";
                        //print_r($row_wishlistid);
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    $status = "Item added to the wishlist!";
                    return $status;
                    //print_r($query);
                    //return false;
                    }
            } else {
                return false;
                }
            return $stmt;
    }
    function product() {
        $query1 = "SELECT ID FROM " . $this->table_name . " WHERE ID=".$this->uid."";
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->execute();
        if($stmt1) {
            $query1 = "SELECT * FROM " . $this->table_posts . " WHERE ID=".$this->productid."";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->execute();
            if($stmt1->rowCount() > 0) {
                $query ="SELECT post_title,post_content,post_excerpt FROM " .$this->table_posts." WHERE ID=" .$this->productid." AND post_status='publish'";
                //print_r($query);
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                //print_r($stmt);
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($row);
                $description=array();
                $description['p_title']=($row[0][post_title]); 
                $description['p_detail']=($row[0][post_content]);
                $description['p_excerpt']=($row[0][post_excerpt]);
                $query ="SELECT meta_key,meta_value FROM " .$this->table_postmeta." WHERE post_id=" .$this->productid;
                //print_r($query);
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                //print_r($stmt);
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $alldetail=array();
                foreach ($row as $key => $value) {
                    $alldetail[$value[meta_key]]= $value[meta_value];
                    if($value[meta_key] == "_regular_price" || $value[meta_key] == "_sale_price" || $value[meta_key] == "_price"){
                        $alldetail[$value[meta_key]]= $value[meta_value].get_option('woocommerce_currency');
                    }
                }
                //print_r($alldetail[_children]);
                $arr1 = str_split($alldetail[_children]);
                //print_r($arr1);
                
                $output = ($description+$alldetail );
                //print_r($output);

                $query ="SELECT term_taxonomy_id FROM " .$this->table_term_relationships." WHERE object_id=" .$this->productid;
                //print_r($query);
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($row);
                foreach ($row as $key => $value) {
                    //print_r($value[term_taxonomy_id]);
                    $query ="SELECT term_id,taxonomy FROM " .$this->table_term_taxonomy." WHERE term_taxonomy_id=" .$value[term_taxonomy_id];
                    //print_r($query);
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    $row1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    //print_r($row1);
                    foreach ($row1 as $key => $value) {
                        //print_r($value[term_id]);
                        $query ="SELECT name,term_id FROM " .$this->table_terms." WHERE term_id=" .$value[term_id];
                        //print_r($query);
                        $stmt = $this->conn->prepare($query);
                        $stmt->execute();
                        $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        $i=0;
                        foreach ($row1 as $key => $value) {
                            if($value[taxonomy] == 'product_cat'){ //print_r($value[term_id]);
                                $product_cat_id = $value[term_id]; 
                                //print_r($product_cat_id);
                            }
                            //print_r($value);
                            $products_terms[$i] = $value; $i++;
                        }
                        $i=0;
                        foreach ($row2 as $key => $value) {
                            if($value[term_id] == $product_cat_id){
                                $related_prod_key[0] = $value[name]; //print_r($related_prod_key);
                            }
                            //print_r($value);
                            $products_termtaxonomy[$i] = $value; $i++;
                        }
                    } 
                    //print_r($products_terms);print_r($products_termtaxonomy);
                        $i=0; $j=0;
                        foreach($products_termtaxonomy as $key=>$value){  
                            foreach($products_terms as $key1=>$value1){ //print_r($value);print_r($value1);
                                if($value[term_id] == $value1[term_id]){
                                $description2[$i][$j] = ($value+$value1); $j++;
                                }
                            }
                          $i++;  
                        }
                       // print_r($description2);
                $i=0;
                foreach($description2 as $key=>$value){ //print_r($value);
                    foreach($value as $key1=>$value1){  
                        $result[$i] = ($value1); 
                    } 
                 $i++;  
                } //print_r($result);
                $i=0;       
                foreach($result as $key1=>$value1){//print_r($value1);//print_r($value1[name]); print_r($value1[taxonomy]);
                    if (array_key_exists($value1[taxonomy],$result1[$i])){
                        $result1[$i][$value1[taxonomy]] = (($result1[$i][$value1[taxonomy]]) . "," .$value1[name]);
                    }else{
                        $result1[$i][$value1[taxonomy]] = ($value1[name]); 
                    }   $i++;
                    }  
                } //print_r($result1);
                $i=0; //print_r($result1);
                foreach($result1 as $key=>$value) {
                    $result2[$i] = ($output+($result1[$i])); 
                    $i++;
                } //print_r($result2);
                //return $result2;
                //print_r($this->productid);
                if(!empty($this->cart[cart_items])){
                foreach($this->cart[cart_items] as $key => $value){
                    if($value['product_id'] == $this->productid)
                        $result2[0]['_stock'] = $result2[0]['_stock'] - $value['quantity'];
                }
                }

                $product_img_url[0][img_links] = (wp_get_attachment_url( get_post_thumbnail_id($this->productid) )); 

                        $gallery_thumbnail_id = get_post_meta($this->productid,'_product_image_gallery');  
                        $gallery_thumbnail_id = explode(',', $gallery_thumbnail_id[0]); 
                        if (!empty($gallery_thumbnail_id[0])) {
                            if (!empty($product_img_url[0]))
                                $p=1;
                             else 
                                $p=0;
                        
                            foreach ($gallery_thumbnail_id as $gallery_thumbnail_id_key => $gallery_thumbnail_id_value) {
                                $product_img_url[$p][img_links] = wp_get_attachment_url($gallery_thumbnail_id_value);
                                $p++;
                            }   
                        }
                    //print_r($product_img_url); 
                $results = $result2[0];
                $results[img_links] = $product_img_url;
                //print_r($results);
                //return $results;
                if($results[product_type] == "variable"){
                    $query = "SELECT ID,post_title FROM ".$this->table_posts." WHERE post_parent=".$this->productid." AND post_type='product_variation'";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    if($stmt->rowCount() > 0) { 
                        $variation_ids = $stmt->fetchAll(PDO::FETCH_ASSOC); //print_r($variation_ids);
                        foreach ($variation_ids as $key => $value) { 
                           $query = "SELECT meta_key,meta_value FROM ".$this->table_postmeta." WHERE post_id=".$value[ID]."";
                           $stmt = $this->conn->prepare($query);
                           $stmt->execute();
                           $variation_metadata = $stmt->fetchAll(PDO::FETCH_ASSOC);  //print_r($variation_metadata);
                           $a=0;
                           foreach ($variation_metadata as $key1 => $value1) { //print_r($value1[meta_key]);
                               $variation_data[$value[ID]][$value1[meta_key]] = $value1[meta_value]; //print_r($variation_data);
                               if($value1[meta_key] == "_regular_price" || $value1[meta_key] == "_sale_price" || $value1[meta_key] == "_price"){
                                    $variation_data[$value[ID]][$value1[meta_key]] = $value1[meta_value].get_option('woocommerce_currency');
                                }
                               $a++;
                           } //   $print_r($variation_data);
                            //print_r( wp_get_attachment_url($variation_data[$value[ID]][_thumbnail_id]) );

                        $v_product_img_url[0][img_links] = (wp_get_attachment_url( get_post_thumbnail_id($value[ID]) )); 
                        if(empty($v_product_img_url[0][img_links]))
                            $v_product_img_url = [];
                            $variation_img[$value[ID]] = $v_product_img_url;
                        } //print_r($variation_data);
                        //print_r($results);
                        foreach ($variation_ids as $key => $value) {
                            foreach ($variation_data as $key2 => $value2) {
                                $variation_ids[$key] = $variation_ids[$key] + $variation_data[$value[ID]];
                            }
                        $variation_ids[$key][img_links] = $variation_img[$value[ID]];
                        } //print_r($variation_ids);
                        $results[variation_products] = $variation_ids;
                        //print_r($results);
                    }   else {
                        $results[variation_products] = [];
                    }
                }
                
                if (($alldetail[_children])== ""){ 
                    if($this->display_full == 1){ //print_r($related_prod_key);
                        $display = $this->productcategorylisting($this->uid,$related_prod_key); //print_r($display);
                        $results[related_prducts] = $display;
                    } return $results;
                }
                $results3[]=array();
                $kkk=$ai=$cu=0;
                //$results1=$results;
                $results3[$kkk]= $results;$kkk++;
                    //print_r($results3);
                        $rae="";
                       for($jj=$cu;$arr1[$jj]!="}";$jj++){
                            if($arr1[$jj]=="i"){
                                if($ai%2==1){
                               // echo $arr1[$j]."</br>";
                                //echo $j."</br>";                            
                                    for($kj=$jj+2;$arr1[$kj]!=";";$kj++){
                                        $rae .= $arr1[$kj]; 
                                    }
                                                    //print_r($result111);
                                                    //print_r($alldetail[_children]);
                                    //print_r($this->getallproduct($rae));
                                    //echo $rae."</br>";
                                    if($rae!=""){
                                    $query1 = "SELECT * FROM " . $this->table_posts . " WHERE ID=".$rae."";
                                                $stmt1 = $this->conn->prepare($query1);
                                                $stmt1->execute();
                                                if($stmt1->rowCount() > 0) {
                                                    $query ="SELECT post_title,post_content,post_excerpt FROM " .$this->table_posts." WHERE ID=" .$rae." AND post_status='publish'";
                                                    //print_r($query);
                                                    $stmt = $this->conn->prepare($query);
                                                    $stmt->execute();
                                                    //print_r($stmt);
                                                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    //print_r($row);
                                                    $description1=array();
                                                    $description1['p_title']=($row[0][post_title]); 
                                                    $description1['p_detail']=($row[0][post_content]);
                                                    $description1['p_excerpt']=($row[0][post_excerpt]);
                                                    $query ="SELECT meta_key,meta_value FROM " .$this->table_postmeta." WHERE post_id=" .$rae;
                                                    //print_r($query);
                                                    $stmt = $this->conn->prepare($query);
                                                    $stmt->execute();
                                                    //print_r($stmt);
                                                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    //print_r($row);
                                                    $alldetail1=array();
                                                    foreach ($row as $key => $value) {
                                                        $alldetail1[$value[meta_key]]= $value[meta_value];
                                                        if($value[meta_key] == "_regular_price" || $value[meta_key] == "_sale_price" || $value[meta_key] == "_price"){
                                                            $alldetail1[$value[meta_key]]= $value[meta_value].get_option('woocommerce_currency');
                                                        }
                                                    }
                                                    //print_r($alldetail[_children]);
                                                    $output11 = ($description1+$alldetail1 );
                                                    //print_r($output11);
                                                    $query ="SELECT term_taxonomy_id FROM " .$this->table_term_relationships." WHERE object_id=" .$rae;
                                                    //print_r($query);
                                                    $stmt = $this->conn->prepare($query);
                                                    $stmt->execute();
                                                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    //print_r($row);

                                                            foreach ($row as $key => $value) {
                                                                //print_r($value[term_taxonomy_id]);
                                                                $query ="SELECT term_id,taxonomy FROM " .$this->table_term_taxonomy." WHERE term_taxonomy_id=" .$value[term_taxonomy_id];
                                                                //print_r($query);
                                                                $stmt = $this->conn->prepare($query);
                                                                $stmt->execute();
                                                                $row1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                                //print_r($row1);
                                                                foreach ($row1 as $key => $value) {
                                                                    //print_r($value[term_id]);
                                                                    $query ="SELECT name,term_id FROM " .$this->table_terms." WHERE term_id=" .$value[term_id];
                                                                    //print_r($query);
                                                                    $stmt = $this->conn->prepare($query);
                                                                    $stmt->execute();
                                                                    $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                                    
                                                                    $i=0;
                                                                    foreach ($row1 as $key => $value) {
                                                                        //print_r($value);
                                                                        $products_terms[$i] = $value; $i++;
                                                                    }
                                                                    $i=0;
                                                                    foreach ($row2 as $key => $value) {
                                                                        //print_r($value);
                                                                        $products_termtaxonomy[$i] = $value; $i++;
                                                                    }
                                                                }
                                                                //print_r($products_terms);print_r($products_termtaxonomy);
                                                                    $i=0; $j=0;
                                                                    foreach($products_termtaxonomy as $key=>$value){  
                                                                        foreach($products_terms as $key1=>$value1){ //print_r($value);print_r($value1);
                                                                            if($value[term_id] == $value1[term_id]){
                                                                            $description2[$i][$j] = ($value+$value1); $j++;
                                                                            }
                                                                        }
                                                                      $i++;  
                                                                    }
                                                                   // print_r($description2);
                                                            $i=0;
                                                            foreach($description2 as $key=>$value){ //print_r($value);
                                                                foreach($value as $key1=>$value1){  
                                                                    $result[$i] = ($value1); 
                                                                } 
                                                             $i++;  
                                                            } //print_r($result);
                                                            $i=0;      
                                                            foreach($result as $key1=>$value1){//print_r($value1);//print_r($value1[name]); print_r($value1[taxonomy]);
                                                                if (array_key_exists($value1[taxonomy],$result1[$i])){
                                                                    $result1[$i][$value1[taxonomy]] = (($result1[$i][$value1[taxonomy]]) . "," .$value1[name]);
                                                                }else{
                                                                    $result1[$i][$value1[taxonomy]] = ($value1[name]); 
                                                                }   $i++;
                                                                }  
                                                            } $i=0; //print_r($result1);
                                                            foreach($result1 as $key=>$value) {
                                                                $result2[$i] = ($output11+($result1[$i])); 
                                                                $i++;
                                                            } //print_r($result2);
                                                            //return $result2;
                                                            //print_r($this->productid);
                                                            $a_product_img_url[0][img_links] = (wp_get_attachment_url( get_post_thumbnail_id($rae) ));  
                                                        /*    $query = "SELECT DISTINCT guid as img_links FROM ".$this->table_posts." WHERE post_parent=".$rae." AND post_type='attachment'";
                                                            $stmt = $this->conn->prepare($query);
                                                            $stmt->execute(); //print_r($query);
                                                            $post_img_links = $stmt->fetchAll(PDO::FETCH_ASSOC);*/
                                                            //print_r($post_img_links);
                                                            $results = $result2[0];
                                                            $results[img_links] = $a_product_img_url;
                                                            //print_r($results);
                                                            //return $results;
                                                            $query = "SELECT ID,post_title FROM ".$this->table_posts." WHERE post_parent=".$rae." AND post_type='product_variation'";
                                                            $stmt = $this->conn->prepare($query);
                                                            $stmt->execute();
                                                           if($stmt->rowCount() > 0) {
                                                                $variation_ids = $stmt->fetchAll(PDO::FETCH_ASSOC); //print_r($variation_ids);
                                                                foreach ($variation_ids as $key => $value) { 
                                                                   $query = "SELECT meta_key,meta_value FROM ".$this->table_postmeta." WHERE post_id=".$value[ID]."";
                                                                   $stmt = $this->conn->prepare($query);
                                                                   $stmt->execute();
                                                                   $variation_metadata = $stmt->fetchAll(PDO::FETCH_ASSOC);  //print_r($variation_metadata);
                                                                   $a=0;
                                                                   foreach ($variation_metadata as $key1 => $value1) { //print_r($value1[meta_key]);
                                                                       $variation_data[$value[ID]][$value1[meta_key]] = $value1[meta_value]; 
                                                                       if($value1[meta_key] == "_regular_price" || $value1[meta_key] == "_sale_price" || $value1[meta_key] == "_price"){
                                                                            $variation_data[$value[ID]][$value1[meta_key]] = $value1[meta_value].get_option('woocommerce_currency');
                                                                        }
                                                                       $a++;
                                                                   }
                                                                   $b_product_img_url[0][img_links] = (wp_get_attachment_url( get_post_thumbnail_id($value[ID]) ));
                                                                   $variation_img[$value[ID]] = $b_product_img_url;
                                                                /*   $query1 = "SELECT DISTINCT guid FROM ".$this->table_posts." WHERE post_parent=".$value[ID]." AND post_type='attachment'";
                                                                   $stmt1 = $this->conn->prepare($query1);
                                                                   $stmt1->execute();
                                                                   if($stmt1->rowCount() > 0){
                                                                        $variation_img[$value[ID]] = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                                                                   } */ //print_r($variation_img);
                                                                   //print_r($variation_data);
                                                                } //print_r($variation_data);
                                                                //print_r($results);
                                                                foreach ($variation_ids as $key => $value) {
                                                                    foreach ($variation_data as $key2 => $value2) {
                                                                        $variation_ids[$key] = $variation_ids[$key] + $variation_data[$value[ID]];
                                                                    }
                                                                $variation_ids[$key][img_links] = $variation_img[$value[ID]];
                                                                } //print_r($variation_ids);
                                                                $results[variation_products] = $variation_ids;
                                                            }
                                                                //print_r($results);

                                                            $results3[$kkk]= $results;$kkk++;
                                                                //print_r($results3);
                                                                //$results12 = ($results12+($results)); 
                                                    //print_r($result111);
                                                    //print_r($alldetail[_children]);
                                    //print_r($this->getallproduct($rae));
                                                }
                                            }
                                    $rae="";
                                }
                                $ai=$ai+1; 
                            }
                            $cu=$cu+1;  
                        } //print_r($results3);

                        //print_r($results3);
            if($this->display_full == 1){ //print_r($related_prod_key);
                        $display = $this->productcategorylisting($this->uid,$related_prod_key); //print_r($display);
                        $results3[related_prducts] = $display;
                    } return $results3;
            } 
            return false;
        }
         return false;
    }
    
    function productfilter(){  

        $tax_key = 0;
        $meta_key = 0;

        $data_query = array();
        $data_query['post_type'] = array('product');
        $data_query['post_status'] = array('publish');
        $data_query['posts_per_page'] = -1;

        if(!empty($this->minprice) || !empty($this->maxprice)) {

            $this->minprice = (isset($this->minprice) && !empty($this->minprice)) ? $this->minprice : 0 ;
            $this->maxprice = (isset($this->maxprice) && !empty($this->maxprice)) ? $this->maxprice : 0 ;

            $data_query['meta_query'] = array();
            $data_query['meta_query']['relation'] = 'OR';

            $data_query['meta_query'][$meta_key]['relation'] = 'AND';

            $data_query['meta_query'][$meta_key][0]['key'] = '_regular_price';
            $data_query['meta_query'][$meta_key][0]['value'] = $this->minprice;
            $data_query['meta_query'][$meta_key][0]['compare'] = '>=';

            $data_query['meta_query'][$meta_key][1]['key'] = '_regular_price';
            $data_query['meta_query'][$meta_key][1]['value'] = $this->maxprice;
            $data_query['meta_query'][$meta_key][1]['compare'] = '<='; 

            $meta_key = $meta_key+1;  

            $data_query['meta_query'][$meta_key]['relation'] = 'AND';

            $data_query['meta_query'][$meta_key][0]['key'] = '_sale_price';
            $data_query['meta_query'][$meta_key][0]['value'] = $this->minprice;
            $data_query['meta_query'][$meta_key][0]['compare'] = '>=';

            $data_query['meta_query'][$meta_key][1]['key'] = '_sale_price';
            $data_query['meta_query'][$meta_key][1]['value'] = $this->maxprice;
            $data_query['meta_query'][$meta_key][1]['compare'] = '<='; 

            $meta_key = $meta_key+1;

            $data_query['meta_query'][$meta_key]['relation'] = 'AND';

            $data_query['meta_query'][$meta_key][0]['key'] = '_price';
            $data_query['meta_query'][$meta_key][0]['value'] = $this->minprice;
            $data_query['meta_query'][$meta_key][0]['compare'] = '>=';

            $data_query['meta_query'][$meta_key][1]['key'] = '_price';
            $data_query['meta_query'][$meta_key][1]['value'] = $this->maxprice;
            $data_query['meta_query'][$meta_key][1]['compare'] = '<=';

            $meta_key = $meta_key+1;
        }

        if((!empty($this->color) && count($this->color) != 0) || (!empty($this->brand) && count($this->brand) != 0) || (!empty($this->size) && count($this->size) != 0)) {
            $data_query['tax_query'] = array();
            $data_query['tax_query']['relation'] = 'AND';
        }
        
        if(!empty($this->color) && count($this->color) != 0) {
            $data_query['tax_query'][$tax_key]['taxonomy'] = 'pa_color';
            $data_query['tax_query'][$tax_key]['field'] = 'slug';
            $data_query['tax_query'][$tax_key]['terms'] = $this->color;
            $data_query['tax_query'][$tax_key]['operator'] = 'IN';

            $tax_key = $tax_key+1; 
        }

        if(!empty($this->brand) && count($this->brand) != 0) {
            $data_query['tax_query'][$tax_key]['taxonomy'] = 'pa_brand';
            $data_query['tax_query'][$tax_key]['field'] = 'slug';
            $data_query['tax_query'][$tax_key]['terms'] = $this->brand;
            $data_query['tax_query'][$tax_key]['operator'] = 'IN';

            $tax_key = $tax_key+1; 
        }

        if(!empty($this->size) && count($this->size) != 0) {
            $data_query['tax_query'][$tax_key]['taxonomy'] = 'pa_size';
            $data_query['tax_query'][$tax_key]['field'] = 'slug';
            $data_query['tax_query'][$tax_key]['terms'] = $this->size;
            $data_query['tax_query'][$tax_key]['operator'] = 'IN';
        }
        
        $results = new WP_Query( $data_query );

        $results = $results->posts;
        
        if($this->popularity == 'most_sold') {
            $i=0;
            foreach ($results as $key => $value) {
                $total[$i]['key'] = $value->ID;
                $total_sales = get_post_meta($value->ID,'total_sales');
                $total[$i]['total_sales'] = $total_sales[0];
                $i++;
            } 

            $sort_sold = array_column($total, 'total_sales');

            array_multisort($sort_sold, SORT_DESC, $total); 

            $j=0;
            foreach ($total as $key1 => $value1) {
                $data[$j] = $value1['key'];
                $j++; 
            }  

            return $data;
        } else {
            $j=0;
            foreach ($results as $key1 => $value1) {
                $data[$j] = $value1->ID;
                $j++; 
            } 
            return $data;
        }
    }

   public function getallproduct($productid){ 
            $query1 = "SELECT * FROM " . $this->table_posts . " WHERE ID=".$productid."";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->execute();
            if($stmt1->rowCount() > 0) {
                $query ="SELECT ID,post_title,post_content,post_excerpt FROM " .$this->table_posts." WHERE ID=" .$productid." AND post_status='publish'";
                //print_r($query);
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                //print_r($stmt);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($row);
                $details = $this->productlist($rows);
                $i=0; $j=0;
                return $details;
                //$result = 
                /*foreach ($details as $key => $value) { //print_r($value);
                    foreach ($value as $key1 => $value1) { 
                    $result[][$i] = $value;
                    
                    }
                 $i++;  
                }
                print_r($result);*/
            }
            return false;
    }
    function productlisting() { 
        $query1 = "SELECT ID FROM " . $this->table_name . " WHERE ID=".$this->uid."";
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->execute();

        if(!empty($stmt1)) {
            $data_query = array();
            $data_query['post_type'] = array('product');
            $data_query['post_status'] = array('publish');
            $data_query['posts_per_page'] = 12;
            $data_query['paged'] = $this->page;
            $data_query['order'] = 'DESC';
            $data_query['orderby'] = 'ID';

            if(!empty($this->find)) {
                $data_query['s'] = $this->find;
            }

            $products = new WP_Query( $data_query );

            $results['total_record'] = $products->found_posts;
            $results['total_pages'] = $products->max_num_pages;

            if(!empty($products->posts)) {
                $i=0;
                foreach ($products->posts as $key => $value) {
                    $results['data'][$i]['p_id'] = $value->ID;
                    $results['data'][$i]['p_title'] = $value->post_title;
                    $results['data'][$i]['_brand_author'] = get_user_meta($value->post_author,'billing_company',true);
                    $brand = '';
                    $brand = get_post_meta($value->ID, '_product_attributes', true );
                    $brand = maybe_unserialize($brand);
                    if($brand[pa_brand][value] != "undefined"){
                        $results['data'][$i]['_brand'] = $brand[pa_brand][value];
                    } else {
                        $results['data'][$i]['_brand'] = '';
                    }
                    $results['data'][$i]['_price'] = get_post_meta($value->ID, '_price', true ).get_option('woocommerce_currency');
                    $results['data'][$i]['_regular_price'] = get_post_meta($value->ID, '_regular_price', true ).get_option('woocommerce_currency');
                    $results['data'][$i]['_sale_price'] = get_post_meta($value->ID, '_sale_price', true ).get_option('woocommerce_currency');

                    $j=0;
                    $results['data'][$i]['img_links'][$j]['img_links'] = wp_get_attachment_url( get_post_thumbnail_id($value->ID) );
                    $i++;
                }
            }
            
            return $results;
        }
    }
    function productcategorylisting($uid,$categories){
        //print_r($this->categories);
        $ip=0;
        $query = "SELECT term_id FROM ".$this->table_terms." WHERE name IN ('";
        foreach ($categories as $key => $value) {
            if($ip==1){
                $query = $query.",'";
            }
            $query = $query.$value."'" ;
            $ip=1;
        }
        $query = $query.")";    //print_r($query);
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);        

        $ip=0;
        $query = "SELECT term_taxonomy_id FROM ".$this->table_term_taxonomy." WHERE term_id IN (";
        foreach ($row as $key => $value) {
            if($ip==1){
                $query = $query.",";
            }
            $query = $query.$value[term_id]."" ;
            $ip=1;
        }
        $query = $query.")";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row1 = $stmt->fetchAll(PDO::FETCH_ASSOC);        

        $ip=0;
        $query = "SELECT object_id FROM ".$this->table_term_relationships." WHERE term_taxonomy_id IN (";
        foreach ($row1 as $key => $value) {
            if($ip==1){
                $query = $query.",";
            }
            $query = $query.$value[term_taxonomy_id]."" ;
            $ip=1;
        }
        $query = $query.")";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);        
        
        $ip=0;
        $query = "SELECT post_title,ID FROM ".$this->table_posts." WHERE ID IN (";
        foreach ($row2 as $key => $value) {
            if($ip==1){
                $query = $query.",";
            }
            $query = $query.$value[object_id]."" ;
            $ip=1;
        }
        $query = $query.")";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);        
        //print_r($row2);
        $result = $this->productlist($rows);
        //print_r($result);
        return $result;
    }
    function productlist($row) {
            $description=array();
                $ids=array();
                $i=0;
                foreach($row as $key=>$value){
                    $description[$i]['p_title']=($value[post_title]); 
                    $description[$i]['p_id']=($value[ID]);
                    $description[$i]['post_author']=($value[post_author]);
                    $ids[$i] = $value[ID];
                   $i++;     
                } 
                $i=0; $j=0;
                if(!empty($this->find)){ 
                    $find_string = strtolower($this->find);
                    $find_string = str_replace('-', '', $find_string);
                    $find_string = str_replace(' ', '', $find_string);
                    $find_string = preg_replace('/[^A-Za-z0-9\-]/', '', $find_string);
                    foreach($description as $key=>$value){
                        $description_title1[$i] = ($value[p_title]);
                        $title1[$i] = strtolower($value[p_title]);
                        $title[$i] = str_replace('-', '', $title1[$i]);
                        $title[$i] = str_replace(' ', '', $title[$i]);
                        $title[$i] = preg_replace('/[^A-Za-z0-9\-]/', '', $title[$i]); 
                        
                        if (strpos($title[$i], $find_string) !== false) {
                            $id[$i] = $value[p_id];
                            $description_title[p_title] = ($value[p_title]);
                            $desc_post_author[post_author]=($value[post_author]);  

                        }  
                        $description[$i]['p_title'] = $description_title[p_title];
                        $description[$i]['p_id'] =  $id[$i];
                        $description[$i]['post_author'] =  $desc_post_author[post_author];  
                        $i++;
                       
                    }  
                    $ids = $id;
                } 
                $i = 0;
                foreach($description as $key=>$value){ 
                    if(!empty($value['p_id'])) { 
                        $description1[$i]['p_id'] = ($value[p_id]);
                        $description1[$i]['p_title'] = ($value[p_title]);
                        $description1[$i]['_brand_author'] = get_user_meta($value[post_author],'billing_company',true);
                        $i++;
                    }
                    
                } $description = $description1;
                $row=array();
                $i=0; 
                foreach($ids as $key=>$value){
                    $query ="SELECT meta_key,meta_value,post_id FROM " .$this->table_postmeta." WHERE post_id=" .$value." AND meta_key IN ('_price','_regular_price','_sale_price','_product_attributes')"; 
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    $row[$i] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $i++;
                } 
                $i=0;
                foreach ($row as $key => $value) {
                    foreach($value as $key1=>$value1){
                        $brand = '';
                        if($value1[meta_key] == '_product_attributes'){
                            $meta_val = maybe_unserialize($value1[meta_value]);
                            if($meta_val[pa_brand][value] != "undefined"){
                                $alldetail['_brand'] = $meta_val[pa_brand][value];
                            } else {
                                $alldetail['_brand'] = '';
                            }
                        } else {
                        $alldetail[$value1[meta_key]]= $value1[meta_value].get_option('woocommerce_currency');
                        }
                        $alldetail[p_id]= $value1[post_id];

                    } //print_r($alldetail);
                    foreach($alldetail as $key2=>$value2){ //print_r($value2);
                        $productdetails[$i][$key2] = $value2;
                    }$i++;
                } //print_r($productdetails);
                $i=0; $j=0;
                foreach($description as $key=>$value){  
                    foreach($productdetails as $key1=>$value1){ 
                        if($value[p_id] == $value1[p_id]){
                        $description2[$i][$j] = ($value+$value1); $j++;
                        }
                    }
                  $i++;  
                }
                $i=0;
                foreach($description2 as $key=>$value){ //print_r($value);
                    foreach($value as $key1=>$value1){
                        $result[$i] = ($value1); 
                    } 
                 $i++;  
                } 
			$i=0; 
		foreach($result as $key1 => $value1) {
			$j=0;
			$result[$i]['img_links'][$j]['img_links'] = wp_get_attachment_url( get_post_thumbnail_id($value1['p_id']) );
			$gallery_thumbnail_id = get_post_meta($value1['p_id'],'_product_image_gallery');
			if(!empty($gallery_thumbnail_id[0])){				
				$gallery_thumbnail_id = explode(',', $gallery_thumbnail_id[0]);
				foreach($gallery_thumbnail_id as $gallery_thumbnail_id_key => $gallery_thumbnail_id_value) {
					$j++;
					$result[$i]['img_links'][$j]['img_links'] = wp_get_attachment_url($gallery_thumbnail_id_value);				
				}
			}			
			if(empty($result[$i]['img_links'][0]['img_links']))
				$result[$i]['img_links'] = array();
			$i++;
		}
        return $result;            
    }
    function productstatuslisting(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE ID=".$this->uid ."";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        //print_r($stmt);
        if($stmt->rowCount() > 0){
            //echo 88888; die;
            $woocommerce=$this->woocommerce;
            if($this->orderstatus == 'on-hold') {
                $status = 'on-hold';
            } else if($this->orderstatus == 'processing'){
                $status = 'processing';
            } else if($this->orderstatus == 'completed'){
                $status = 'completed';
            } else if($this->orderstatus == 'cancelled'){
                $status = 'cancelled';
            } else if($this->orderstatus == 'pending') {
                $status = 'pending';
            } else if($this->orderstatus == 'refunded') {
                $status = 'refunded';
            } else if($this->orderstatus == 'failed') {
                $status = 'failed';
            } else if($this->orderstatus == 'all') {
                $status = array( 'failed','refunded','pending','cancelled' );
                foreach($status as $val){
                    $order_des = array();
                    $query = [
                        'customer' => $this->uid,
                        'per_page' =>100,
                        'filter[meta]' => true,
                        'status' => $val
                    ];
                    $order_des = $woocommerce->get('orders',$query);
                    if(empty($order) && !empty($order_des[0]->id))
                        $order = $order_des;
                    else if(!empty($order_des[0]->id))
                    $order = array_merge($order_des, $order);
                }
            }
            if($this->orderstatus != 'all') {
            $query = [
                'customer' => $this->uid,
                'per_page' =>100,
                'filter[meta]' => true,
                'status' => $status
            ];
            $order = $woocommerce->get('orders',$query); 
            }
            //echo "<pre>"; print_r($order); die;
            foreach ($order as $key => $value) { 
                if($value->total){
                    $value->total = $value->total . get_option('woocommerce_currency');
                }
                if($value->line_items){
                    foreach ($value->line_items as $key1 => $value1) {
                        if($value1->subtotal){
                            $value1->subtotal = $value1->subtotal . get_option('woocommerce_currency');
                        }
                        if($value1->subtotal_tax ){
                            $value1->subtotal_tax = $value1->subtotal_tax . get_option('woocommerce_currency');                        
                        }
                        if($value1->total){
                            $value1->total = $value1->total . get_option('woocommerce_currency');
                        }
                        if($value1->total_tax){
                            $value1->total_tax = $value1->total_tax . get_option('woocommerce_currency');
                        }
                        if($value1->price){
                            $value1->price = $value1->price . get_option('woocommerce_currency');
                        } 
                        if($value1->product_image_url){
                            $value1->product_image_url = $value1->product_image_url->link;
                        }

                        }
                    }
                }   return $order;
            }
            
        
        return false;
    }
  function home(){    
        $offer = $this->offers;
        $category = $this->category;
        $newarrival = $this->newarrival;
        $brand=$this->brand;
        //  offers
        //print_r($offer);
        $query =  "SELECT p.`ID`, 
        p.`post_title`   AS coupon_code, 
        p.`post_excerpt` AS coupon_description, 
        MAX(CASE WHEN pm.meta_key = 'discount_type'      AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS discount_type,          -- Discount type 
        MAX(CASE WHEN pm.meta_key = 'coupon_amount'      AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS coupon_amount,          -- Coupon amount 
        MAX(CASE WHEN pm.meta_key = 'free_shipping'      AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS free_shipping,          -- Allow free shipping 
        MAX(CASE WHEN pm.meta_key = 'expiry_date'        AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS expiry_date,                -- Coupon expiry date 
        MAX(CASE WHEN pm.meta_key = 'minimum_amount'     AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS minimum_amount,         -- Minimum spend 
        MAX(CASE WHEN pm.meta_key = 'maximum_amount'     AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS maximum_amount,         -- Maximum spend 
        MAX(CASE WHEN pm.meta_key = 'individual_use'     AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS individual_use,         -- Individual use only 
        MAX(CASE WHEN pm.meta_key = 'exclude_sale_items' AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS exclude_sale_items,         -- Exclude sale items 
        MAX(CASE WHEN pm.meta_key = 'product_ids'    AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS product_ids,                -- Products 
        MAX(CASE WHEN pm.meta_key = 'exclude_product_ids'AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS exclude_product_ids,        -- Exclude products 
        MAX(CASE WHEN pm.meta_key = 'product_categories' AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS product_categories,             -- Product categories 
        MAX(CASE WHEN pm.meta_key = 'exclude_product_categories' AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS exclude_product_categories,-- Exclude Product categories 
        MAX(CASE WHEN pm.meta_key = 'customer_email'     AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS customer_email,         -- Email restrictions 
        MAX(CASE WHEN pm.meta_key = 'usage_limit'    AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS usage_limit,                -- Usage limit per coupon 
        MAX(CASE WHEN pm.meta_key = 'usage_limit_per_user'   AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS usage_limit_per_user,   -- Usage limit per user 
        MAX(CASE WHEN pm.meta_key = 'usage_count'    AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS total_usaged                   -- Usage count 
        FROM   `ya_posts` AS p 
               INNER JOIN `ya_postmeta` AS pm ON  p.`ID` = pm.`post_id` 
        WHERE  p.`post_type` = 'shop_coupon' 
               AND p.`post_status` = 'publish' 
        GROUP  BY p.`ID` 
        ORDER  BY coupon_amount DESC";
        /*if($offer != 1){
            $query = $query." LIMIT 1";
        }*/
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //print_r($row);
        $i=0;
        foreach ($row1 as $key => $value) { 
            $query = "SELECT DISTINCT guid as img_link FROM ".$this->table_posts." WHERE post_parent='".$value[ID]."' AND post_type='attachment'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); //print_r($stmt);
            $img[img_link] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //  print_r($coupon);  
            $coupon_e = strtotime($value[expiry_date]); // print_r($coupon_e); echo '<br>';
            $today = strtotime(date('Y-m-d')); 
            $time = time(); // echo '<br>';   print_r($time); echo '<br>';
            if(($coupon_e > $today)){ 
                $row[$i] = $row1[$key] + $img;
                $i++;
            }
        }        
        if($offer == 1){  
            
            foreach ($row as $key => $value) {  //print_r($value);
                if($value[ID] == $this->offer_id){     
                    $product_categories = get_post_meta($this->offer_id, 'exclude_product_categories' );  
                    if(!empty($product_categories[0])) {
                        $offer_cat = get_post_meta($this->offer_id, 'product_categories' );
                        $i=0;
                        foreach ($offer_cat[0] as $key1 => $value1) {
                               $cat = get_term_by('id',$value1,'product_cat');       
                               $cat_name[$i] = $cat->name;           
                               $i++;
                        } 
                        $row1 = $this->productcategorylisting($uid,$cat_name ); 
                          //print_r($row1);
                    }   
                   if(!empty($value[product_ids])) { 
                        $offer_product_id = get_post_meta($this->offer_id, 'product_ids' );
                        $offer_prod_id = explode(',', $offer_product_id[0]);
                        $i=0; $p=0;
                        foreach ($offer_prod_id as $key2 => $value2) {  //print_r($value2); 
                            $product[$i][p_id] = $value2;
                            $product[$i][p_title] = get_the_title($value2); 
                            $price = get_post_meta($value2,'_price');
                            if(empty($price)){
                                $price[0] = " ";
                            }
                            $product[$i][_price] = $price[0].get_option('woocommerce_currency');
                            $r_price = get_post_meta($value2,'_regular_price');
                            if(empty($r_price)){
                                $r_price[0] = " ";
                            }
                            $product[$i][_regular_price] = $r_price[0].get_option('woocommerce_currency');
                            $s_price = get_post_meta($value2,'_sale_price');
                            if (empty($s_price)) {
                                $s_price[0] = " ";
                            }
                            $product[$i][_sale_price] = $s_price[0].get_option('woocommerce_currency');
                            $product[$i][img_links][0][img_links] = (wp_get_attachment_url( get_post_thumbnail_id($value2) ));
                            $gallery_thumbnail_id = get_post_meta($value2,'_product_image_gallery');  
                            $gallery_thumbnail_id = explode(',', $gallery_thumbnail_id[0]); 
                            if (!empty($gallery_thumbnail_id[0])) {
                                if (!empty($product[$i][img_links][0][img_links]))
                                    $p=1;
                                 else 
                                    $p=0;
                                foreach ($gallery_thumbnail_id as $gallery_thumbnail_id_key => $gallery_thumbnail_id_value) {
                                    $product[$i][img_links][$p][img_links] = wp_get_attachment_url($gallery_thumbnail_id_value);
                                    $p++;
                                }   
                            }
                            $i++;
                        }
                        if(!empty($row1)){
                             $row1 =  $product + $row1 ;
                         } else {
                            $row1 =  $product;
                         }
                        //print_r($row10);
                    }   
                    if(!empty($value[exclude_product_ids])) {
                        $offer_product_id = get_post_meta($this->offer_id, 'exclude_product_ids' );
                        $offer_prod_id = explode(',', $offer_product_id[0]);
                        $i = 0;
                        foreach ($offer_prod_id as $key3 => $value3) {
                            $j=0;
                            if($i == 0){  //print_r($row1);     
                                if(empty($row1)){
                                        $row1 = $this->home_row();    
                                }
                                foreach ($row1 as $key4 => $value4) {  
                                        $row_info[$i][$j] = $value4;   // print_r($value3);
                                        if($value3 != $value4[p_id]) { 
                                            $j++;
                                        }
                                    }
                                
                            } elseif ($i>0) {  
                                $j=0;
                                foreach ($row_info[$i-1] as $key5 => $value5) {
                                        $row_info[$i-1][$j] = $value5;
                                        if($value3 != $value5[p_id]) {  
                                        $j++;
                                        }
                                }
                            }
                            
                            $i++;
                        }// print_r($row_info);
                       $row1 = $row_info;
                    }   
                    if(!empty($value[exclude_sale_items] == 'yes')){
                        $i=0;
                        if(empty($row1)){
                            $row1 = $this->home_row();
                        }
                        foreach ($row1 as $key6 => $value6) {
                            $j=0;
                            foreach ($value6 as $key7 => $value7) { //print_r($value7);
                                $row_exclude_sale[$i][$j] = $value7;
                                if($value7[_sale_price] == "KWD" || $value7[_sale_price] == "0KWD" || $value7[_sale_price] == " " || empty($value7[_sale_price])){ 
                                    $j++;
                                }
                            }
                        $i++;    
                        } 
                        $row1 = $row_exclude_sale;  
                    }   
                    $exclude_product_categories = get_post_meta($this->offer_id, 'exclude_product_categories' );
                    if(!empty($exclude_product_categories[0])) {    
                        $offer_cat_exclude = get_post_meta($this->offer_id, 'exclude_product_categories' ); 
                        $i=0;
                        foreach ($offer_cat_exclude[0] as $key1 => $value1) {   
                            $pages = get_posts(array(
                                                      'post_type' => 'product',
                                                      'numberposts' => -1,
                                                      'tax_query' => array(
                                                        array(
                                                          'taxonomy' => 'product_cat',
                                                          'field' => 'id',
                                                          'terms' => $value1, 
                                                          'include_children' => false
                                                        )
                                                      )
                                                    ));
                        }
                        $i = 0;
                        foreach ($pages as $key3 => $value3) {
                            $j=0;
                            if($i == 0){ 
                                if(empty($row1)){  
                                    $row1 = $this->home_row();
                                }
                                foreach ($row1 as $key4 => $value4) {  
                                    $row_info[$i][$j] = $value4;  
                                    if($value3->ID != $value4[p_id]) { 
                                        $j++;
                                    }
                                }
                            } elseif ($i>0) {  
                                $j=0;
                                foreach ($row_info[$i-1] as $key5 => $value5) {
                                        $row_info[$i-1][$j] = $value5;
                                        if($value3->ID != $value5[p_id]) { 
                                        $j++;
                                        }
                                }
                            }
                            $i++;
                        }    
                       $row1 = $row_info;
                    }   
                }
            }   
            if(empty($row1)){
                $query = "SELECT post_title,ID FROM ".$this->table_posts." WHERE post_type='product' AND post_status='publish'";
                //print_r($query);
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                //print_r($stmt);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($row);
                $result = $this->productlist($rows);
                $i=0;
                foreach ($result as $key => $value) {
                     $results[$i][p_id] = $value[p_id];
                     $results[$i][p_title] = $value[p_title];

                     $results[$i][_price] = $value[_price];
                     /*if(empty($value[_price])){
                        $results[$i][_price] = "0.000KD";
                     }*/
                     $results[$i][_regular_price] = $value[_regular_price];
                     /*if(empty($value[_regular_price])){
                        $results[$i][_regular_price] = "0.000KD";
                     }*/
                     $results[$i][_sale_price] = $value[_sale_price];
                     /*if(empty($value[_sale_price])){
                        $results[$i][_sale_price] = "0.000KD";
                     }*/
                     $results[$i][img_links] = $value[img_links];
                     $i++;
                 }  $row1 = $results;
            }
            //
        }
        // categories
        if($category != 1){
        $categories = array('product_cat'); // ,'pa_brand'
        } else {
            $categories = array($this->category_name);
        }
        //print_r($categories);
        $cat_names1 = $this->brand_name($categories); //print_r($categories);
        //print_r($cat_names1);
        $c=0;
        foreach ($cat_names1 as $key => $value) {
            $query = "SELECT term_id FROM ".$this->table_terms." WHERE name='".$value[category_name]."'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); //print_r($stmt);
            $img_term_id = $stmt->fetch(PDO::FETCH_ASSOC);

            $query = "SELECT meta_value FROM ".$this->table_termmeta." WHERE term_id=".$img_term_id[term_id]." AND meta_key='thumbnail_id'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); //print_r($stmt);
            $thumbnail_id = $stmt->fetch(PDO::FETCH_ASSOC);

            $query = "SELECT DISTINCT guid FROM ".$this->table_posts." WHERE ID=".$thumbnail_id[meta_value]." AND post_type='attachment'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); //print_r($stmt);
            $cat_img_url = $stmt->fetch(PDO::FETCH_ASSOC);
            //print_r($cat_img_url);
            if($value[category_name] != "Uncategorized"){
                $cat_names1[$c][category_name] = $value[category_name];
                if(empty($cat_names1[$c][img_link])){
                    $cat_names1[$c][img_link] = $cat_img_url[guid];
                }
                $c++;    
            }
        } //print_r($cat_names1);

        $query = "SELECT term_id FROM ".$this->table_terms." WHERE name='".$categories[0]."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(); //print_r($stmt);
        $term_id = $stmt->fetch(PDO::FETCH_ASSOC);
        //print_r($term_id);
        $query = "SELECT term_taxonomy_id FROM ".$this->table_term_taxonomy." WHERE term_id='".$term_id[term_id]."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $term_taxonomy_id = $stmt->fetch(PDO::FETCH_ASSOC);
        //print_r($term_taxonomy_id);
        $query = "SELECT object_id FROM ".$this->table_term_relationships." WHERE term_taxonomy_id='".$term_taxonomy_id[term_taxonomy_id]."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $object_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //print_r($object_id);
        $ip=0;
        $query = "SELECT post_title,ID FROM ".$this->table_posts." WHERE ID IN (";
        foreach ($object_id as $key => $value) { //print_r($value[object_id]);
            if($ip==1){
                $query = $query.",";
            }
            $query = $query.$value[object_id]."" ;
            $ip=1;
        }
        $query = $query.")";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);        
        //print_r($rows);
        $res_categories = $this->productlist($rows);
        // New Arrivals
        $query = "SELECT post_title,ID,post_author FROM ".$this->table_posts." WHERE post_type='product' AND post_status='publish' ORDER BY post_date DESC";
        if($newarrival == 0){
            $query = $query." LIMIT 12";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $res_newarrival = $this->productlist($rows); //print_r($res_newarrival);
        if($offer == 1){
            $category = 0;
            $newarrival = 0;
            $brand=0;
        } else if($category == 1){
            $offer = 0; //print_r($offer);
            $newarrival = 0;
            $brand=0;
        } else if($newarrival == 1){
            $offer = 0;
            $category = 0;
            $brand=0;
        } else if($brand == 1){
            $offer = 0;
            $category = 0;
            $newarrival = 0;
        } else {
            $offer = 0;
            $category = 0;
            $newarrival = 0;
            $brand=0;
        }
        if($offer == 1){
            //print_r($row); 
            return $row1;
        }
        if($category == 1){
            //print_r($res_categories); 
            return $res_categories;
        }
        if($newarrival == 1){
            //print_r($res_newarrival);
            return $res_newarrival;
        }
                $query1 =  "SELECT p.`ID`, 
                p.`post_title`   AS coupon_code, 
                p.`post_excerpt` AS coupon_description, 
                MAX(CASE WHEN pm.meta_key = 'exclude_product_categories' AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS exclude_product_categories,-- Exclude Product categories 
                FROM   `ya_posts` 
                GROUP  BY p.`ID` 
                ORDER  BY coupon_amount DESC";
                $stmt1 = $this->conn->prepare($query1);
                $stmt1->execute();
                // categories
                if($brand == 1 || $brand == 0 ){
                $categories = array('pa_brand');
                } 
                //print_r($categories);
                $cat_names2 = $this->brand_name($categories);
                //print_r($cat_names1);
                    if($brand==1){
                            $result[brand] = $cat_names2;
                            //print_r($result);
                            return $result;
                    }

        if($offer==0){
            if($category==0){
                if($newarrival==0){
                    if($brand==0){
                        //$result = ($row + $cat_names + $res_newarrival);
                        $result[offer] = $row;
                        $result[categories] = $cat_names1;
                        $result[new_arrivals] = $res_newarrival;
                        $result[trending_brand] = $cat_names2;
                        //print_r($result);
                        return $result;
                    }
                }
            }
        }
    }
    function product_pagination($results,$page){
        $products_per_page = 12;
                    $total_products = count($results);  
                    $totalPages = ceil($total_products / $products_per_page);   
                        if($page < 1){
                            $page = 1;
                        } else if($page > $totalPages){
                            $emptyArray = []; 
                            return $emptyArray;
                            } 

                        $start_porduct = ($page - 1) * $products_per_page;
                        $j=0;
                        for($i=$start_porduct; $i<($start_porduct+$products_per_page); $i++){ 
                            if($i == $total_products){
                                break;
                            }
                            $output[$j] = $results[$i];
                            $j++;
                        }   
                        return $output;
    }
    function home_row(){
         $query = "SELECT post_title,ID FROM ".$this->table_posts." WHERE post_type IN ('product') AND post_status='publish'";
                                        //print_r($query);
                                        $stmt = $this->conn->prepare($query);
                                        $stmt->execute();
                                        //print_r($stmt);
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        //print_r($row);
                                        $result = $this->productlist($rows);
                                        $i=0;
                                        foreach ($result as $key => $value) {
                                             $results[$i][p_id] = $value[p_id];
                                             $results[$i][p_title] = $value[p_title];
                                             $results[$i][_price] = $value[_price];
                                             $results[$i][_regular_price] = $value[_regular_price];
                                             $results[$i][_sale_price] = $value[_sale_price];
                                             $results[$i][img_links] = $value[img_links];
                                             $i++;
                                         } return $results;
    }
    function brandslisting(){   
        /*$woocommerce = $this->$woocommerce;
        print_r($woocommerce->get('products/attributes/3/terms'));*/
        $brand_name = $this->brand_name;
        if($brand_name==""){
                $query =  "SELECT p.`ID`, 
                p.`post_title`   AS coupon_code, 
                p.`post_excerpt` AS coupon_description, 
                MAX(CASE WHEN pm.meta_key = 'exclude_product_categories' AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS exclude_product_categories,-- Exclude Product categories 
                FROM   `ya_posts` 
                GROUP  BY p.`ID` 
                ORDER  BY coupon_amount DESC";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                // categories
                if($category != 1){
                $categories = array('pa_brand');
                } else {
                    $categories = array($this->category_name);
                }
                //print_r($categories);
                $cat_names = $this->brand_name($categories);
                if($category == 1){
                    //print_r($res_categories); 
                    return $res_categories;
                }
                    if($category==0){
                        $i=0;
                        foreach ($cat_names as $key => $value) {
                            $result[brands][$i][brand_name] = $value[category_name];
                            $result[brands][$i][img_link] = $value[img_link];
                            $i++;
                        }
                            return $result;
                    }

        } else {
            if(empty($brand_name)){
            $categories = array('pa_brand');
            } else{
               $categories[] = $brand_name; 
            }
            $cat_names = $this->brand_name($categories);
            $res_categories[$brand_name] = $this->single_category_product_list($cat_names,$categories);
            return $res_categories;
        }
    }
    function brand_name($categories){
        $i=0;
        foreach ($categories as $key => $value) {
            $query = "SELECT term_id,term_taxonomy_id FROM ".$this->table_term_taxonomy." WHERE taxonomy IN ('".$value."')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $rows[$i] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $i++;
        }
        $j=0;
        foreach ($rows as $key => $value) { $i=0;
            foreach ($value as $key1 => $value1) { //print_r($value1);
                $query = "SELECT name as category_name FROM ".$this->table_terms." WHERE term_id IN (".$value1[term_id].")";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $cat_name[$i][$j][category_name] = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $query1 = "SELECT meta_value FROM ".$this->table_termmeta." WHERE term_id IN (".$value1[term_id].") AND meta_key='attribute_image'";
                $stmt1 = $this->conn->prepare($query1);
                $stmt1->execute();   //print_r($stmt1->fetchAll(PDO::FETCH_ASSOC));
                $img_id = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                //print_r($img_id[0][meta_value]);
                $query2 = "SELECT DISTINCT guid as img_link FROM ".$this->table_posts." WHERE ID=".$img_id[0][meta_value].""; //print_r($query2);
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->execute();   //print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
                $cat_name[$i][$j][img_link] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                $i++;
            }
        $j++;
        }
        $i=0;
            foreach ($cat_name as $key => $value) {
            foreach ($value as $key1 => $value1) { 
                foreach ($value1 as $key2 => $value2) { 
                    $cat_names[$i][category_name] = $value1[category_name][0][category_name]; 
                    $cat_names[$i][img_link] = $value1[img_link][0][img_link];
                } $i++;
            }
        } //print_r($cat_names);
        return $cat_names;
    }
    public function brandslisting_copy($brand_name=null)
        {  
           $brand_name = $this->brand_name;
        if($brand_name==""){
                $query =  "SELECT p.`ID`, 
                p.`post_title`   AS coupon_code, 
                p.`post_excerpt` AS coupon_description, 
                MAX(CASE WHEN pm.meta_key = 'exclude_product_categories' AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS exclude_product_categories,-- Exclude Product categories 
                FROM   `ya_posts` 
                GROUP  BY p.`ID` 
                ORDER  BY coupon_amount DESC";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                // categories
                if($brand_name != 1){
                $categories = array('product_cat');
                } else {
                    $categories = array($brand_name);
                }
                //print_r($categories);
                $cat_names = $this->brand_name($categories);
        
        $c=0;
        foreach ($cat_names as $key => $value) {
            $query = "SELECT term_id FROM ".$this->table_terms." WHERE name='".$value[category_name]."'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); //print_r($stmt);
            $img_term_id = $stmt->fetch(PDO::FETCH_ASSOC);

            $query = "SELECT meta_value FROM ".$this->table_termmeta." WHERE term_id=".$img_term_id[term_id]." AND meta_key='thumbnail_id'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); //print_r($stmt);
            $thumbnail_id = $stmt->fetch(PDO::FETCH_ASSOC);

            $query = "SELECT DISTINCT guid FROM ".$this->table_posts." WHERE ID=".$thumbnail_id[meta_value]." AND post_type='attachment'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); //print_r($stmt);
            $cat_img_url = $stmt->fetch(PDO::FETCH_ASSOC);
            //print_r($cat_img_url);
            if($value[category_name] != "Uncategorized"){
                $cat_names1[$c][category_name] = $value[category_name];
                $cat_names1[$c][img_url] = $cat_img_url[guid];
                $c++; 
            }
            
        } 
        $result[category] = $cat_names1;
        //print_r($result); 
        return $result; 
        }
        else {
            if(empty($brand_name)){
            $categories = array('product_cat');
            } else{
               $categories[] = $brand_name; 
            }
            $cat_names = $this->brand_name($categories);
            $res_categories[$brand_name] = $this->single_category_product_list($cat_names,$categories);
            return $res_categories;
        }
    }
    function single_category_product_list($cat_names,$categories){ 
        $query = "SELECT term_id FROM ".$this->table_terms." WHERE name='".$categories[0]."'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $term_id = $stmt->fetch(PDO::FETCH_ASSOC);
            //print_r($term_id);
            $query = "SELECT term_taxonomy_id FROM ".$this->table_term_taxonomy." WHERE term_id='".$term_id[term_id]."'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $term_taxonomy_id = $stmt->fetch(PDO::FETCH_ASSOC);
            //print_r($term_taxonomy_id);
            $query = "SELECT object_id FROM ".$this->table_term_relationships." WHERE term_taxonomy_id='".$term_taxonomy_id[term_taxonomy_id]."'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $object_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //print_r($object_id);
            $ip=0;
            $query = "SELECT post_title,ID FROM ".$this->table_posts." WHERE ID IN (";
            foreach ($object_id as $key => $value) { //print_r($value[object_id]);
                if($ip==1){
                    $query = $query.",";
                }
                $query = $query.$value[object_id]."" ;
                $ip=1;
            }
            $query = $query.")";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);        
            //print_r($rows);
            $res_categories = $this->productlist($rows);
            return $res_categories;
    }
    function wishlist_listing(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE ID=".$this->uid ."";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        //print_r($stmt);
        if($stmt->rowCount() > 0){
            $query = "SELECT product_id FROM ".$this->table_wishlist." WHERE author=".$this->uid."";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $productids = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //print_r($productids);
            $ip=0;
            $query = "SELECT post_title,ID FROM ".$this->table_posts." WHERE ID IN (";
            foreach ($productids as $key => $value) { //print_r($value[object_id]);
                if($ip==1){
                    $query = $query.",";
                }
                $query = $query.$value[product_id]."" ;
                $ip=1;
            }
            $query = $query.")";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);        
            //print_r($rows);
            $res_categories = $this->productlist($rows);
            $i=0;
            foreach ($res_categories as $key => $value) {   
                $check_term = wp_get_object_terms($value[p_id],'pa_brand'); 
                $res_categories[$i][brand] = $check_term[0]->name;
                $i++;
            }
        } 
        return $res_categories;
    }
    function policy_pages($page_id) {
        $post = get_post( $page_id );
        
        return $post;
    }
    function privacy_policy(){
        $query = "SELECT post_content FROM " . $this->table_posts . " WHERE post_type='page' and ID=3";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    function terms_condition(){
        $query = "SELECT post_content FROM " . $this->table_posts . " WHERE post_type='page' and ID=18";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;     
    }
     function vendor_signup(){ 
		 
		 $log  = '__________'.PHP_EOL.'first_name:'.$this->first_name.PHP_EOL.'username:'.$this->username.PHP_EOL.'last_name:'.$this->last_name.PHP_EOL.'email:'.$this->email.PHP_EOL.'mobile:'.$this->mobile.PHP_EOL.'brand_name:'.$this->brand_name.PHP_EOL.'civil_id:'.$this->civil_id.PHP_EOL.'gender:'.$this->a_gender.PHP_EOL.'birthdate:'.$this->a_birthdate.PHP_EOL.'address:'.$this->a_address.PHP_EOL.'show_location:'.$this->show_location.PHP_EOL.'device_id:'.$this->device_id.PHP_EOL.'device_type:'.$this->device_type.PHP_EOL;
		
		file_put_contents(dirname(__FILE__).'/log/'.date("y-m-d").'-'.$this->username.'.log', $log, FILE_APPEND);		 
		 
        $response = $this->create();
        if($response == 1){ 

            $query = "SELECT ID FROM ".$this->table_name." ORDER BY ID DESC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $user_id = $stmt->fetch(PDO::FETCH_ASSOC);
            //print_r($user_id[ID]);
            $query = "INSERT INTO ".$this->table_namemeta." (meta_key,meta_value,user_id) VALUES ('first_name','".$this->first_name."',".$user_id[ID]."),('last_name','".$this->last_name."',".$user_id[ID]."),('billing_email','".$this->email."',".$user_id[ID]."),('billing_phone','".$this->mobile."',".$user_id[ID]."),('billing_company','".$this->brand_name."',".$user_id[ID]."),('_wcpv_vendor_approval','yes',".$user_id[ID].")";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $query = "INSERT INTO ".$this->table_terms." (name,slug) VALUES ('".$this->username."','".strtolower($this->username)."')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $query = "SELECT term_id FROM ".$this->table_terms." ORDER BY term_id DESC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $vendor_id = $stmt->fetch(PDO::FETCH_ASSOC);
            $vendor_info[vendor_id] = $vendor_id[term_id];
            $vendor_info[user_id] = $user_id[ID];
            $vendor_info[first_name] = $this->first_name;
            $vendor_info[last_name] = $this->last_name;
            $vendor_info[email] = $this->email;
            $vendor_info[mobile] = $this->mobile;
            $vendor_info[brand_name] = $this->brand_name;
            $vendor_info[username] = $this->username;
			
            $vendor_info[device_id] = $this->device_id;   
            $vendor_info[device_type] = $this->device_type;
			
            //$vendor_info[gender] = $this->a_gender;
			//$vendor_info[civil_id] = $this->civil_id;
            //$vendor_info[birthdate] = $this->a_birthdate;
           // $vendor_info[address] = $this->a_address; 
            //print_r($vendor_id[term_id]);
            $vendor_data = 'a:12:{s:5:"notes";s:0:"";s:4:"logo";s:0:"";s:7:"profile";s:0:"";s:5:"email";s:'.strlen($this->email).':"'.$this->email.'";s:6:"admins";a:1:{i:0;i:'.$user_id[ID].';}s:10:"commission";s:0:"";s:15:"commission_type";s:10:"percentage";s:6:"paypal";s:0:"";s:8:"timezone";s:5:"UTC+3";s:15:"enable_bookings";s:2:"no";s:20:"per_product_shipping";s:2:"no";s:14:"instant_payout";s:2:"no";}';
            
            $query = "INSERT INTO ".$this->table_termmeta." (term_id,meta_key,meta_value) VALUES (".$vendor_id[term_id].",'vendor_data','".$vendor_data."')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $query = "INSERT INTO ".$this->table_term_taxonomy." (term_id,taxonomy,description,count) VALUES (".$vendor_id[term_id].",'wcpv_product_vendors','',1)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $query = "INSERT INTO ".$this->table_profile_detail." (user_id,a_civil_id,a_gender,a_birthdate,a_latitude,a_longitude,show_location,a_address) VALUES (".$user_id[ID].",'".$this->civil_id."','".$this->a_gender."','".$this->a_birthdate."',".$this->lat.",".$this->long.",".$this->show_location.",'".$this->a_address."')";
            $stmt = $this->conn->prepare($query);   // print_r($query); 
            $stmt->execute(); //print_r($stmt);  
			
            $display_name = $this->first_name." ".$this->last_name;
            //print_r($display_name); //die();
            $query = "UPDATE ".$this->table_name." SET display_name='".$display_name."' WHERE ID=".$user_id[ID]."";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            wp_insert_term( $this->brand_name, 'pa_brand');

            $query = "SELECT * FROM ".$this->table_device_info. " ";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            if($stmt->rowCount() == 0){ 
                $query = "CREATE TABLE " . $this->table_device_info . "(user_id INT(100) NOT NULL,device_id VARCHAR(700) ,device_type VARCHAR(10))";
                            $stmt = $this->conn->prepare($query);
                            $stmt->execute();
            }
            update_user_meta($user_id[ID],'country_code',$this->country_code);
            $query = "INSERT INTO " . $this->table_device_info . "(user_id,device_id,device_type) VALUES (".$user_id[ID] .",'".$this->device_id."','".$this->device_type."')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
			
			$query = "SELECT * FROM ".$this->table_profile_detail." WHERE user_id=".$user_id[ID];
			$stmt = $this->conn->prepare($query);
            $stmt->execute();
			$vendor_detail = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$vendor_info['civil_id'] = $vendor_detail[0]['a_civil_id'];
			$vendor_info['gender'] = $vendor_detail[0]['a_gender'];
			$vendor_info['birthdate'] = $vendor_detail[0]['a_birthdate'];
			$vendor_info['address'] = $vendor_detail[0]['a_address'];
            $vendor_info['country_code'] = get_user_meta($user_id[ID],'country_code',true);
			/*$vendor_info['birthdate'] = $vendor_detail[0]['a_birthdate'];
			$vendor_info['birthdate'] = $vendor_detail[0]['a_birthdate'];*/
            return $vendor_info;
        }
        return $response;
    }
    function vendor_profile(){  //print_r($this->v_id);
        if($this->update == 0) {
            $query = "SELECT ID,display_name,user_login,user_email FROM ".$this->table_name." WHERE ID=".$this->v_id."";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            if(empty($stmt)){
                return false;
            } else {
                $vendor_detail = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($vendor_detail);
                $query = "SELECT meta_key,meta_value FROM ".$this->table_namemeta." WHERE user_id=".$this->v_id." AND meta_key IN ('billing_company','billing_phone')"; 
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $vendor_detail1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($vendor_detail1);
                if(!empty($vendor_detail1)){
                    foreach ($vendor_detail1 as $key => $value) {
                    $vendor_detail[0][$value[meta_key]] = $value[meta_value];
                    }
                } else {
                        $vendor_detail[0][billing_company] = "";
                        $vendor_detail[0][billing_phone] = "";
                    } 
                $query = "SELECT * FROM ".$this->table_profile_detail." WHERE user_id=".$this->v_id.""; 
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                //print_r($query);
                $vendor_detail2 = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                //print_r($vendor_detail2);
                if(!empty($vendor_detail2)){
                   foreach ($vendor_detail2 as $key => $value) {
                    $vendor_detail[0][a_civil_id] = $value[a_civil_id];
                    $vendor_detail[0][a_gender] = $value[a_gender];
                    $vendor_detail[0][a_birthdate] = $value[a_birthdate];
                    $vendor_detail[0][a_image_url] = $value[a_image_url];
                    $vendor_detail[0][latitude] = $value[a_latitude];
                    $vendor_detail[0][longitude] = $value[a_longitude];
                    $vendor_detail[0][show_location] = $value[show_location];
                    $vendor_detail[0][address] = $value[a_address];
                    }
                } else {
                        $vendor_detail[0][a_civil_id] = "";
                        $vendor_detail[0][a_gender] = "";
                        $vendor_detail[0][a_birthdate] = "";
                        $vendor_detail[0][a_image_url] = "";
                        $vendor_detail[0][latitude] = "";
                        $vendor_detail[0][longitude] = "";
                        $vendor_detail[0][show_location] = "";
                        $vendor_detail[0][address] = "";
                    }            
                $vendor_detail[0][country_code] = get_user_meta( $this->v_id, 'country_code', true);
                
                //print_r($vendor_detail); 
                return $vendor_detail;
            }
        } elseif ($this->update == 1) { 
            //$user_info = get_user_by('email',$this->user_email);   // print_r($user_info);
            $query = "SELECT * FROM ".$this->table_name." WHERE user_email='".$this->user_email."'";  
            $stmt = $this->conn->prepare($query);
            $stmt->execute();   
            $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC);  //print_r($user_info);   
            if(count($user_info) <= 1){ 
                    for ($i=1; $i < 100; $i++) { 
                    if(!empty($this->image[$i])){
                        //print_r($this->image[$i]);
                        $upload_dir       = wp_upload_dir(); 
                        $unique_file_name = wp_unique_filename( $upload_dir['path'], $this->image[$i]["name"] ) ;
                        $filename         = basename( $unique_file_name );
                        //print_r($filename);

                        $file1 = $upload_dir['url'] . '/' . $filename;
                        if( wp_mkdir_p( $upload_dir['path'] ) ) {
                            $file = $upload_dir['path'] . '/' . $filename;
                        } else {
                            $file = $upload_dir['basedir'] . '/' . $filename;
                        }   //print_r($file);

                       $wp_filetype = wp_check_filetype( $filename, null );    //print_r($wp_filetype);
                                    // print_r( file_get_contents($_FILES["image"]["tmp_name"]) );
                                    $data = file_get_contents($this->image[$i]["tmp_name"]); //print_r($data);
                                    //echo $upload_dir[basedir]."/images/".$this->image[$i]['name'];
                                    move_uploaded_file($this->image[$i]["tmp_name"],$upload_dir[basedir]."/images/".$this->image[$i]['name']);
                                    $data = file_get_contents($upload_dir[basedir]."/images/".$this->image[$i]['name']);
                                    //print_r($upload_dir[basedir].'/images'); 
                                    file_put_contents( $file, $data );
                        
                       
                    /*  $wp_filetype = wp_check_filetype( $filename, null );    //print_r($wp_filetype);
                        // print_r( file_get_contents($_FILES["image"]["tmp_name"]) );
                        $data = file_get_contents($this->image[$i]["tmp_name"]); //print_r($data);
                        # code...
                        file_put_contents( $file, $data );*/
                        $org_img[0] = $file1;
                    }
                }
    //print_r($org_img);
                $query = "UPDATE ".$this->table_name." SET display_name='".$this->display_name."',user_email='".$this->user_email."' WHERE ID=".$this->uid." ";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                
                $query1 = "UPDATE ".$this->table_namemeta." SET meta_value='".$this->billing_company."' WHERE meta_key='billing_company' AND user_id=".$this->uid."";
                $stmt1 = $this->conn->prepare($query1);
                $stmt1->execute(); 
                $query2 = "UPDATE ".$this->table_namemeta." SET meta_value='".$this->billing_phone."' WHERE meta_key='billing_phone' AND user_id=".$this->uid."";
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->execute();
                
                $query4 = "SELECT * FROM ".$this->table_profile_detail." WHERE user_id=".$this->uid." ";
                $stmt4 = $this->conn->prepare($query4);
                $stmt4->execute();
                $vendor_profile_detail = $stmt4->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($vendor_profile_detail)){  
                    $query3 = "UPDATE ".$this->table_profile_detail." SET a_civil_id='".$this->a_civil_id."',a_gender='".$this->a_gender."',a_birthdate='".$this->a_birthdate."',a_latitude=".$this->lat.",a_longitude=".$this->long.",show_location=".$this->show_location.",a_address='".$this->a_address."' WHERE user_id=".$this->uid." ";   // a_image_url='".$org_img[0]."',
                    $stmt3 = $this->conn->prepare($query3); 
                    $result = $stmt3->execute();
                    if(!empty($org_img[0])){
                        $query4 = "UPDATE ".$this->table_profile_detail." SET a_image_url='".$org_img[0]."' WHERE user_id=".$this->uid." ";   
                    $stmt4 = $this->conn->prepare($query4); 
                    $stmt4->execute();
                    }
                } else{
                    $query5 = "INSERT INTO ".$this->table_profile_detail." (user_id,a_civil_id,a_gender,a_birthdate,a_image_url,a_latitude,a_longitude,show_location,a_address) VALUES (".$this->uid.",'".$this->a_civil_id."','".$this->a_gender."','".$this->a_birthdate."','".$org_img[0]."',".$this->lat.",".$this->long.",".$this->show_location.",'".$this->a_address."')";
                    $stmt5 = $this->conn->prepare($query5); print_r($stmt5);
                    $result = $stmt5->execute();
                }  
                if(!empty($this->country_code)){
                    update_user_meta( $this->uid, 'country_code', $this->country_code);
                }
                 
                /*
                $vendor_data = 'a:12:{s:5:"notes";s:0:"";s:4:"logo";s:0:"";s:7:"profile";s:0:"";s:5:"email";s:'.strlen($this->user_email).':"'.$this->user_email.'";s:10:"commission";s:0:"";s:15:"commission_type";s:10:"percentage";s:6:"paypal";s:0:"";s:8:"timezone";s:5:"UTC+3";s:15:"enable_bookings";s:2:"no";s:20:"per_product_shipping";s:2:"no";s:14:"instant_payout";s:2:"no";s:6:"admins";a:0:{}}';
                // s:6:"admins";a:1:{i:0;i:'.$this->uid.';}
                
                $query = "SELECT meta_value FROM ".$this->table_termmeta." WHERE term_id=".$term_id." AND meta_key='vendor_data'";
                $stmt = $this->conn->prepare($query);   //print_r($query);
                $stmt->execute();
                $vendor_info = $stmt->fetch(PDO::FETCH_ASSOC);  //print_r($vendor_info[meta_value]);
                $v_email_length = strpos($vendor_info[meta_value],'email')+9;   // print_r($v_email);
                $replace_length = substr_replace($vendor_info[meta_value],strlen($this->user_email),$v_email_length,2);
                //print_r($replace_length);
                $v_emailid_length = strpos($replace_length,'email')+13;

                $mail_last = strpos($replace_length,'"',$v_emailid_length);
                $emailid_l = ($mail_last - $v_emailid_length);

                $emailid_replace = substr_replace($replace_length,$this->user_email,$v_emailid_length,$emailid_l);
                print_r($emailid_replace);

                $query = "UPDATE ".$this->table_termmeta." SET meta_value='".$emailid_replace."' WHERE term_id=".$term_id." AND meta_key='vendor_data'";
                $stmt = $this->conn->prepare($query);   print_r($query);
                $stmt->execute();
                */
                $user_info = get_user_by( 'ID', $this->uid );  // print_r($user_info->user_login);
                $term = get_term_by('name',$user_info->user_login,'wcpv_product_vendors');  //print_r($term);  
                $term_id = $term->term_id;  //print_r($term_id); 
                //print_r($term_id);
                $vendor_meta = get_term_meta($term_id,'vendor_data');
                //print_r($vendor_meta);  //print_r(count($vendor_meta[0][admins]));
                $vendor_meta[0][email] = $this->user_email;
                $flag = 0;
                foreach ($vendor_meta[0][admins] as $key => $value) {
                    if($value == $this->uid){
                        $flag = 1;
                        break;
                    } 
                }
                if($flag == 0){
                    if( count($vendor_meta[0][admins]) == 0){
                        $vendor_meta[0][admins][0] =  $this->uid;
                    } elseif( count($vendor_meta[0][admins]) > 0) {
                        $count = count($vendor_meta[0][admins])+1;  //print_r($count);
                        $vendor_meta[0][admins][$count] =  $this->uid;                    
                    }
                }    
                //print_r($vendor_meta);
                update_term_meta($term_id,'vendor_data', $vendor_meta[0]);
                //print_r(get_term_meta($term_id,'vendor_data'));

                //print_r($stmt3);
                //wp_insert_term( $this->brand_name, 'pa_brand');
                /*
                $query4 = "SELECT meta_value FROM $this->table_namemeta WHERE meta_key='billing_company' AND user_id=".$this->uid."";
                $stmt4 = $this->conn->prepare($query4);
                $stmt4->execute(); 
                $company = $stmt4->fetch(PDO::FETCH_ASSOC);             // print_r($query4 );

                $term = get_term_by('name',$company[meta_value],'pa_brand');    
                $term_id = $term->term_id;          print_r($term_id);
                $value = get_field( "_attribute_image", 34);         print_r($value);                                   
                $value = update_field( '_attribute_image', $org_img[0], $term_id );   */ 


                if($result){
                    return $result;
                } return false;
            }
            return false;
        }
    }
    function vendor_dashboard(){
        /*$query = "SELECT user_login FROM $this->table_name WHERE ID=".$this->uid."";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if($stmt){
            $vendor_name = $stmt->fetch(PDO::FETCH_ASSOC);
            $query = "SELECT term_id FROM $this->table_terms WHERE name='".$vendor_name[user_login]."'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $vendor_term_id = $stmt->fetch(PDO::FETCH_ASSOC); */
            $vendor_term_id = $this->vid;
            $query = "SELECT term_taxonomy_id FROM ".$this->table_term_taxonomy." WHERE term_id=".$vendor_term_id."";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $vendor_term_tax_id = $stmt->fetch(PDO::FETCH_ASSOC);
                $query = "SELECT object_id FROM ".$this->table_term_relationships." WHERE term_taxonomy_id=".$vendor_term_tax_id[term_taxonomy_id]."";
                $stmt = $this->conn->prepare($query);
                $stmt->execute(); 
                $vendor_object_id = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                $query = "SELECT ID FROM ".$this->table_posts." WHERE post_status='wc-processing'";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $post_processing_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $vendor_prod_counts[product_processing_count] = $this->vendor_counts($post_processing_id,$vendor_object_id);
                $query = "SELECT ID FROM ".$this->table_posts." WHERE post_status='wc-on-hold'";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $post_processing_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $vendor_prod_counts[product_on_hold_count] = $this->vendor_counts($post_processing_id,$vendor_object_id);
                $query = "SELECT ID FROM ".$this->table_posts." WHERE post_status='wc-completed'";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $post_processing_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $vendor_prod_counts[product_completed_count] = $this->vendor_counts($post_processing_id,$vendor_object_id);  
				
							$product_completed_count = $this->vendor_counts($post_processing_id,$vendor_object_id,1); 
				
                $query = "SELECT ID FROM ".$this->table_posts." WHERE post_status='wc-cancelled'";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $post_processing_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $vendor_prod_counts[product_cancelled_count] = $this->vendor_counts($post_processing_id,$vendor_object_id);
                $query = "SELECT ID FROM ".$this->table_posts."";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $post_processing_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $vendor_prod_counts[product_order_count] = $this->vendor_counts($post_processing_id,$vendor_object_id);
                $query = "SELECT ID FROM ".$this->table_posts." WHERE post_status='wc-pending'";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $post_processing_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $product_pending_payment_count = $this->vendor_counts($post_processing_id,$vendor_object_id,1);
                //print_r($product_pending_payment_count);
                $vendor_prod_counts[product_pending_payment_count] = count($product_pending_payment_count);
                $i=0;
                $product_pending_payment_count = array_unique($product_pending_payment_count,SORT_REGULAR);
                //$product_pending_payment_count = array_unique($product_pending_payment_count);
                foreach ($product_pending_payment_count as $key => $value) {
                    $query = "SELECT meta_value FROM ".$this->table_postmeta." WHERE meta_key='_price' AND post_id=".$value[product_id].""; //print_r($query);
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute(); 
                    $pending_amount[$i] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $i++;
                }
               // print_r($pending_amount);
                foreach ($pending_amount as $key1 => $value1) {
                    foreach ($value1 as $key => $value) {
                        $pending_amount_value = $pending_amount_value + $value[meta_value];
                    }
                }
                if(empty($pending_amount_value)){
                    $pending_amount_value = 0;
                }
                $vendor_prod_counts[product_pending_payment_amount] = $pending_amount_value.get_option('woocommerce_currency');
                $query = "SELECT ID FROM ".$this->table_posts." WHERE post_status='wc-refunded'";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $post_processing_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($post_processing_id);
                $product_refunded_payment_count = $this->vendor_counts($post_processing_id,$vendor_object_id,1);
                //print_r($product_pending_payment_count);
              //  $vendor_prod_counts[product_refunded_payment_count] = count($product_refunded_payment_count);
                $vendor_prod_counts['product_payment_count'] = count($product_completed_count);
                $i=0;
            //    $product_refunded_payment_count = array_unique($product_refunded_payment_count,SORT_REGULAR);
                //$product_pending_payment_count = array_unique($product_pending_payment_count);
                /**foreach ($product_refunded_payment_count as $key => $value) {
                    $query = "SELECT product_amount FROM $this->table_wcpv_commissions WHERE vendor_id=".$vendor_term_id." AND product_id=".$value[product_id]."";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute(); 
                    $refunded_amount[$i] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $i++;
                }*/
				foreach ($product_completed_count as $key => $value) {
                    $query = "SELECT product_amount FROM ".$this->table_wcpv_commissions." WHERE vendor_id=".$vendor_term_id." AND product_id=".$value[product_id]."";
                    //echo "<pre>"; print_r($query); echo "<br/>";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute(); 
                    $payment_amount[$i] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $i++;
                }//echo "<pre>"; print_r($payment_amount); echo "<br/>";
                //print_r($pending_amount);
                foreach ($payment_amount as $key1 => $value1) {
                    foreach ($value1 as $key => $value) {
                        $payment_amount_value = $payment_amount_value + $value[product_amount];
                    }
                }
                if(empty($payment_amount_value)){
                    $payment_amount_value = 0;
                }
                $vendor_prod_counts['product_payment_amount'] = $payment_amount_value.get_option('woocommerce_currency');
                //print_r($vendor_prod_counts);
                    return $vendor_prod_counts;
            } return false; 
    }
    function vendor_counts($post_processing_id,$vendor_object_id,$vendor_account=null){   //print_r($post_processing_id);
            $v=0;
             //print_r($value[ID]);
                $query = "SELECT order_item_id FROM ".$this->table_order." WHERE order_item_type='line_item' AND order_id IN(";
                foreach ($post_processing_id as $key => $value) {
                    if($v==1){
                        $query = $query . ",";
                    }
                    $query = $query.$value[ID];
                    $v=1;
                }
                $query = $query . ")";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $order_item_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($order_item_id);
            $v=0;
             //print_r($value[ID]);
                $query = "SELECT meta_value as product_id FROM ".$this->table_ordermeta." WHERE meta_key='_product_id' AND order_item_id IN(";
                foreach ($order_item_id as $key => $value) {
                    if($v==1){
                        $query = $query . ",";
                    }
                    $query = $query.$value[order_item_id];
                    $v=1;
                }
                $query = $query . ")";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $order_product_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($order_product_id);
                $i=0;
                foreach ($vendor_object_id as $key => $value) { 
                    foreach ($order_product_id as $key1 => $value1) { 
                        //print_r($value1[product_id]);
                        if ($value[object_id] == $value1[product_id]) {
                            //print_r($value1[product_id]);
                            $vendor_processing_product_id[$i][product_id] = $value1[product_id];
                            $i++;
                        }
                    } 
                }
                //print_r($vendor_processing_product_id); 
                $vendor_processing_count = $i;
                if($vendor_account==1){
                    return $vendor_processing_product_id;
                } else {
                    return $vendor_processing_count;
                }
                
    }
function vender_product_list($vid) {
        $query = "SELECT term_taxonomy_id FROM ".$this->table_term_taxonomy." WHERE term_id=".$vid." AND taxonomy='wcpv_product_vendors'"; 
        $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $id=$stmt[0]['term_taxonomy_id'];
            //print_r($id);
        $query = "SELECT object_id FROM ".$this->table_term_relationships." WHERE term_taxonomy_id=".$id."";
        $stmt = $this->conn->prepare($query);
            $stmt->execute();
            //echo "<pre>";  print_r($stmt); die;
            if($stmt->rowCount() > 0){
                $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //echo "<pre>"; print_r($stmt); die;
                $product_data  =array();
                $n=0;
                foreach ($stmt as $key => $value) {
                    $query = "SELECT ID,post_title,post_content FROM ".$this->table_posts." WHERE ID=".$value['object_id']." AND post_type='product' AND post_status='publish'";//post_title;
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    //print_r($stmt);
                    $product_data[$value['object_id']]['ID']=$stmt[0]['ID'];
                    $product_data[$value['object_id']]['post_title']=$stmt[0]['post_title'];
                    $product_data[$value['object_id']]['post_desc']=$stmt[0]['post_content'];
					$quantity = get_post_meta($value['object_id'],'_stock');
					$product_data[$value['object_id']]['quantity']=$quantity[0];
                    $product_img_url[$n][0]['img_links'] = (wp_get_attachment_url( get_post_thumbnail_id($value['object_id']) )); 
                        $gallery_thumbnail_id = get_post_meta($value['object_id'],'_product_image_gallery');  
                        $gallery_thumbnail_id = explode(',', $gallery_thumbnail_id[0]); 
                        if (!empty($gallery_thumbnail_id[0])) {
                            if (!empty($product_img_url[$n][0]['img_links']))
                                $p=1;
                             else 
                                $p=0;
                        
                            foreach ($gallery_thumbnail_id as $gallery_thumbnail_id_key => $gallery_thumbnail_id_value) {
                                $product_img_url[$n][$p]['img_links'] = wp_get_attachment_url($gallery_thumbnail_id_value);
                                $p++;
                            }  
                            foreach ($product_img_url[$n] as $key => $value1) { 
                                $product_data[$value['object_id']]['img_link'][$key]= $value1['img_links'];
                            }
                            $n++;  
                        }  // print_r($product_img_url[$n]);
                     
                    if(empty($product_data[$value['object_id']]['img_link'][$key])){
                        if(!empty($product_img_url[$n][0]['img_links'])){
                            $product_data[$value['object_id']]['img_link'] = array($product_img_url[$n][0]['img_links']);
                        } else {
                            $product_data[$value['object_id']]['img_link'] = [];
                        }
                    }

                    
                    $query = "SELECT meta_key,meta_value FROM ".$this->table_postmeta." WHERE post_id=".$value['object_id']." AND  meta_key IN ('_price','_regular_price','_sale_price','_sku')";//post_title;
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    //print_r($stmt);
                    $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    //print_r($stmt);
                    foreach ($stmt as $key => $value2) {
                        $product_data[$value['object_id']][$value2['meta_key']]=$value2['meta_value'] ;
                        if($value2['meta_key'] == "_regular_price" || $value2['meta_key'] == "_sale_price" || $value2['meta_key'] == "_price"){
                            $product_data[$value['object_id']][$value2['meta_key']]=$value2['meta_value'].get_option('woocommerce_currency');                                         
                         }
                    }

                    $categories =  wp_get_object_terms( $value['object_id'],  'product_cat' );
                    $j=0;
                    foreach ($categories as $key3 => $value3) { 
                        $product_data[$value['object_id']]['product_cat'][$j]['term_id'] = $value3->term_id ;
                        $product_data[$value['object_id']]['product_cat'][$j]['name'] = $value3->name ;
                        $j++;
                    }
                    if(empty($categories)){
                        $product_data[$value['object_id']]['product_cat'] = [] ;
                    }

                    $brand =  wp_get_object_terms( $value['object_id'],  'pa_brand' );
                    $j=0;
                    foreach ($brand as $key3 => $value3) { 
                        //$product_data[$value['object_id']][pa_brand][$j][term_id] = $value3->term_id ;
                        $product_data[$value['object_id']]['pa_brand'][$j] = $value3->name ;
                        $j++;
                    }
                    if(empty($brand)){
                        $product_data[$value['object_id']]['pa_brand'] = [] ;
                    }

                    $color =  wp_get_object_terms( $value['object_id'],  'pa_color' );
                    $j=0;
                    foreach ($color as $key3 => $value3) { 
                        //$product_data[$value['object_id']][pa_color][$j][term_id] = $value3->term_id ;
                        $product_data[$value['object_id']]['pa_color'][$j] = $value3->name ;
                        $j++;
                    }
                    if(empty($color)){
                        $product_data[$value['object_id']]['pa_color'] = [] ;
                    }

                    $size =  wp_get_object_terms( $value['object_id'],  'pa_size' );
                    $j=0;
                    foreach ($size as $key3 => $value3) { 
                        //$product_data[$value['object_id']][pa_size][$j][term_id] = $value3->term_id ;
                        $product_data[$value['object_id']]['pa_size'][$j] = $value3->name ;
                        $j++;
                    }
                    if(empty($size)){
                        $product_data[$value['object_id']]['pa_size'] = [] ;
                    } //echo 87998798798; die;
					$product_data[$value['object_id']]['sold_count'] = $this->add_product_sold_count($this->woocommerce,$value['object_id']);

                }
                //print_r($product_data) ;
                $i=0;
                foreach ($product_data as $key => $value) {
                             if(!empty($value[ID])){
								$prod_data[$i] = $value; 
								 $i++; 
                        	}
                         }  
				//$prod_data = $this->add_product_sold_count($this->woocommerce,$prod_data);
                //echo "<pre>"; print_r($prod_data); die;    
                return $prod_data;
            }
            else
                return false;
        }
	function add_product_sold_count($woocommerce,$prod_id) {
		//print_r($woocommerce); die;
		$query = [
			"per_page" => 100
		];
		$orders = $woocommerce->get('orders',$query);
		//print_r($orders); die;
		$count=0;
		//foreach($prod_data as $product_key => $product_value){
			foreach($orders as $order_key => $order_value){
				foreach($order_value->line_items as $line_items_key => $line_items_value){
					if($prod_id == $line_items_value->product_id){
						$count++;
					}
				}
			}
		//}
		return $count;
	}
    function add_images($img,$new_folder_month,$post_id){ //print_r($img);
            $files = scandir($new_folder_month, SCANDIR_SORT_DESCENDING); //print_r($files);
            $newest_file = $files[0];
              //print_r($newest_file);
            $file_name = explode('-',$newest_file);  //print_r($file_name);
            //$img_name = explode('.',$file_name[0]);
            //$img_name = $img_name[0] + 1;  //print_r($img_name);
            //$file_name = $file_name + 1; 
            //$img_name = $file_name[0]; print_r($img_name);
        $m = 0;
        foreach ($img as $key => $value) { //print_r($value1);

            foreach($value as $key1 => $value1){
                $source_file = $value1; //print_r($source_file); 
                //print_r($source_file);
                $find = substr($value1,strrpos($value1,'/')+1); //print_r($find);
                /* $check = explode('-',$find); print_r(count($check));
                if(count($check) > 1){
                    $find1 = explode('-',$find);
                    $find1[0] = $img_name;
                    $find2 = implode('-',$find1); //print_r($find2);
                } else {  // echo "test";
                    $find1 = explode('.',$find);
                    $find1[0] = $img_name;
                    $find2 = implode('.',$find1); //print_r($find2);
                }*/
                    $destination_path = $new_folder_month.'/'.$find; 
                    $i=1;
                    $set = 0;
                    while(file_exists($destination_path)){
                       $exists = explode('.', $find);
                       if($set == 1){
                                $find1 = explode('(',$find); //print_r($find1);   // find1[0]
                                $find2 = explode(').',$find1[1]); //print_r($find2[1]);  //  $find2[1]
                                $new = $find1[0]."(".$i.").".$find2[1];
                                
                                $destination_path = $new_folder_month.'/'.$new;

                                $i++;
                        
                       } else {
                           $new = $exists[0]."(".$i.")";
                           $find = $new .".".$exists[1] ; //print_r($find);
                           
                           $destination_path = $new_folder_month.'/'.$find;
                           $i++; 
                       }
                       
                       $set = 1;
                    } //print_r($find);
                    chmod($destination_path, 0777);
                    //print_r($destination_path); echo "</br>";
                    file_put_contents($destination_path, file_get_contents($source_file));
                    $img_items[$m] = $destination_path;
                    $m++;    
            }
            $img_name++;
        }    //print_r($img_items); 
        return $img_items;
    }
    function vendor_account(){
        $vendor_term_id = $this->vid;
            $query = "SELECT term_taxonomy_id FROM ".$this->table_term_taxonomy." WHERE term_id=".$vendor_term_id."";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        if($stmt->rowCount() > 0){
            $vendor_term_tax_id = $stmt->fetch(PDO::FETCH_ASSOC);
            $query = "SELECT object_id FROM ".$this->table_term_relationships." WHERE term_taxonomy_id=".$vendor_term_tax_id[term_taxonomy_id]."";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
            $vendor_object_id = $stmt->fetchAll(PDO::FETCH_ASSOC); //print_r($vendor_object_id);
            $query = "SELECT * FROM ".$this->table_wcpv_commissions." WHERE vendor_id=".$vendor_term_id."";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $vendor_account_detail = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //print_r($vendor_account_detail);
            foreach($vendor_account_detail as $key => $value){
                $vendor_account_details[items_sold] = $vendor_account_details[items_sold] + $value[product_quantity];
                $vendor_account_details[total_income] = $vendor_account_details[total_income]+$value[product_amount]+$value[product_shipping_amount]+$value[product_shipping_tax_amount]+$value[product_shipping_tax_amount];
                $vendor_account_details[sales_amount] = $vendor_account_details[sales_amount] + $value[product_amount];
            }
            if(empty($vendor_account_detail)){
                $vendor_account_details[items_sold] = 0;
                $vendor_account_details[total_income] = 0;
                $vendor_account_details[sales_amount] = 0;
            }
            $query = "SELECT ID FROM ".$this->table_posts." WHERE post_status='wc-refunded'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $post_refund_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //print_r($post_refund_id);
            $refund_amount_id = $this->vendor_counts($post_refund_id,$vendor_object_id,1);
            //print_r($refund_amount_id);
            $v=0;
             //print_r($value[ID]);
                $query = "SELECT product_amount FROM ".$this->table_wcpv_commissions." WHERE vendor_id=".$vendor_term_id." AND order_id IN(";
                foreach ($post_refund_id as $key => $value) {
                    if($v==1){
                        $query = $query . ",";
                    }
                    $query = $query.$value[ID];
                    $v=1;
                }
                $query = $query . ") AND product_id IN(";
                $v=0;
                foreach ($refund_amount_id as $key => $value) {
                    if($v==1){
                        $query = $query . ",";
                    }
                    $query = $query.$value[product_id];
                    $v=1;
                }
                $query = $query . ")";
                $stmt = $this->conn->prepare($query);
                $stmt->execute(); 
                $refund_amt = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($refund_amt);
                foreach ($refund_amt as $key => $value) {
                    $vendor_account_details[refund_amount] = $vendor_account_details[refund_amount] + $value[product_amount];
                } //print_r($vendor_account_details);
                if(empty($post_refund_id)){
                    $vendor_account_details[refund_amount] = 0;
                }

            $query = "SELECT ID FROM ".$this->table_posts." WHERE post_status IN ('wc-on-hold','wc-processing','wc-completed')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $post_sales_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //print_r($post_sales_id);
            $sales_amount_id = $this->vendor_counts($post_sales_id,$vendor_object_id,1);
            //print_r($sales_amount_id);
                $v=0;
                $query = "SELECT product_id,product_amount FROM ".$this->table_wcpv_commissions." WHERE vendor_id=".$vendor_term_id." AND order_id IN(";
                foreach ($post_sales_id as $key => $value) {
                    if($v==1){
                        $query = $query . ",";
                    }
                    $query = $query.$value[ID];
                    $v=1;
                }
                $query = $query . ") AND product_id IN(";
                $v=0;
                foreach ($sales_amount_id as $key => $value) {
                    if($v==1){
                        $query = $query . ",";
                    }
                    $query = $query.$value[product_id];
                    $v=1;
                }
                $query = $query . ")";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                //print_r($query); 
                $net_sales_amt = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($net_sales_amt);
                foreach ($net_sales_amt as $key => $value) {
                    $vendor_account_details[net_sales_amt] = $vendor_account_details[net_sales_amt] + $value[product_amount];
                } //print_r($vendor_account_details);
                if(empty($net_sales_amt)){
                   $vendor_account_details[net_sales_amt] = 0; 
                }
                $v=0; $i=0;
                //print_r(($sales_amount_id));
                $ids = array_unique($sales_amount_id,SORT_REGULAR);
                $query = "SELECT order_id,post_id FROM ".$this->table_wcpv_commissions." WHERE meta_key='_regular_price' AND post_id=".$value[product_id]."";
                $stmt = $this->conn->prepare($query);
                $stmt->execute(); 
                $gross_sales_amt[$i] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //print_r($net_sales_amt);

                $i=0;
                foreach ($net_sales_amt as $key => $value) {
                    $query = "SELECT post_id,meta_value FROM ".$this->table_postmeta." WHERE meta_key='_regular_price' AND post_id=".$value[product_id]."";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute(); 
                    $gross_sales_amt[$i] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $i++;
                } //print_r($gross_sales_amt);

                foreach ($gross_sales_amt as $key => $value) { //print_r($value);
                    $vendor_account_details[gross_sales_amt] = $vendor_account_details[gross_sales_amt] + $value[0][meta_value];
                }
                if(empty($gross_sales_amt)){
                   $vendor_account_details[gross_sales_amt] = 0; 
                } 
                //print_r($vendor_account_details);
                //print_r($vendor_object_id);
                foreach ($vendor_object_id as $key => $value) {
                    $query = "SELECT order_item_id FROM ".$this->table_ordermeta." WHERE meta_key='_product_id' AND meta_value=".$value[object_id]."";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    $coupon_amount_order_item_id = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                    //print_r($coupon_amount_order_item_id);
                    foreach ($coupon_amount_order_item_id as $key1 => $value1) {
                        $query = "SELECT meta_value FROM ".$this->table_ordermeta." WHERE meta_key='discount_amount' AND order_item_id IN (SELECT order_item_id FROM ".$this->table_order." WHERE order_item_type='coupon'  AND order_id IN (SELECT order_id FROM ".$this->table_order." WHERE order_item_id=".$value1[order_item_id].") )";
                        $stmt = $this->conn->prepare($query);
                        $stmt->execute();
                        $coupon_amount = $stmt->fetch(PDO::FETCH_ASSOC); 
                        $coupons_amount = $coupons_amount + $coupon_amount[meta_value]; //print_r($coupons_amount);
                    }
                    
                }
                $vendor_account_details[coupon_amount] = $coupons_amount;
                if(empty($coupons_amount)){
                    $vendor_account_details[coupon_amount] = 0;
                }
                
                return $vendor_account_details;
        } return false;
    }
    function vendor_order_details() {

        $woocommerce = $this->woocommerce;
        $order_details = ($woocommerce->get('orders/'.$this->order_id.''));

        $order = get_post_meta( $this->order_id );   
        $i=0;	
		$query = "SELECT term_taxonomy_id FROM ".$this->table_term_taxonomy." WHERE term_id=".$this->vid." AND taxonomy='wcpv_product_vendors'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $id=$stmt[0]['term_taxonomy_id'];
        //print_r($id);
        $query1 = "SELECT object_id FROM ".$this->table_term_relationships." WHERE term_taxonomy_id=".$id."";  
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->execute();
        $v_products = $stmt1->fetchAll(PDO::FETCH_ASSOC);
		
        foreach ($order_details->line_items as $key => $value) {             
            foreach ($v_products as $v_prod_key => $v_prod_value) {
                if($v_prod_value['object_id'] == $value->product_id) {
                    $line_item[$i]['id'] = $value->id;
                    $line_item[$i]['name'] = $value->name;
                    $line_item[$i]['product_id'] = $value->product_id;
                    $line_item[$i]['variation_id'] = $value->variation_id;
                    $line_item[$i]['quantity'] = $value->quantity;
                    $line_item[$i]['tax_class'] = $value->tax_class;
                    $line_item[$i]['subtotal'] = $value->subtotal.get_option('woocommerce_currency');
                    $line_item[$i]['subtotal_tax'] = $value->subtotal_tax.get_option('woocommerce_currency');
                    $line_item[$i]['total'] = $value->total.get_option('woocommerce_currency');
                    $line_item[$i]['total_tax'] = $value->total_tax.get_option('woocommerce_currency');
                    $line_item[$i]['taxes'] = $value->taxes;
                    $line_item[$i]['meta_data'] = $value->meta_data;
                    $line_item[$i]['sku'] = $value->sku;
                    $line_item[$i]['price'] = $value->price;
                    $line_item[$i]['product_image_url'] = $value->product_image_url->link;
                    $line_item[$i]['product_gallery_image_url'] = $value->product_gallery_image_url;
                    $i++;
                }
            }

        } //print_r($line_item);

        $order_info['id'] = $order_details->id;
        $order_info['date_created'] = $order_details->date_created;
        $order_info['date_modified'] = $order_details->date_modified;
        $order_info['total'] = $order_details->total.get_option('woocommerce_currency');
        $order_info['status'] = $order_details->status;
        $order_info['line_items'] = $line_item;

        $order_info['_customer_user'] = $order['_customer_user'][0];
        $order_info['_payment_method'] = $order['_payment_method'][0];
        $order_info['_payment_method_title'] = $order['_payment_method_title'][0];
        $order_info['_transaction_id'] = $order['_transaction_id'][0];
       // $order_info[_date_paid] = $order[_date_paid][0];
        $order_info['_paid_date'] = $order['_paid_date'][0];
        $order_info['_billing_first_name'] = $order['_billing_first_name'][0];
        $order_info['_billing_last_name'] = $order['_billing_last_name'][0];
        $order_info['_billing_company'] = $order['_billing_company'][0];
        $order_info['_billing_address_1'] = $order['_billing_address_1'][0];
        $order_info['_billing_address_2'] = $order['_billing_address_2'][0];
        $order_info['_billing_city'] = $order['_billing_city'][0];
        $order_info['_billing_state'] = $order['_billing_state'][0];
        $order_info['_billing_postcode'] = $order['_billing_postcode'][0];
        $order_info['_billing_country'] = $order['_billing_country'][0];
        $order_info['_billing_email'] = $order['_billing_email'][0];
        $order_info['_billing_phone'] = $order['_billing_phone'][0];
        $order_info['_shipping_first_name'] = $order['_shipping_first_name'][0];
        $order_info['_shipping_last_name'] = $order['_shipping_last_name'][0];
        $order_info['_shipping_company'] = $order['_shipping_company'][0];
        $order_info['_shipping_address_1'] = $order['_shipping_address_1'][0];
        $order_info['_shipping_address_2'] = $order['_shipping_address_2'][0];
        $order_info['_shipping_city'] = $order['_shipping_city'][0];
        $order_info['_shipping_state'] = $order['_shipping_state'][0];
        $order_info['_shipping_postcode'] = $order['_shipping_postcode'][0];
        $order_info['_shipping_country'] = $order['_shipping_country'][0];
        $order_info['_billing_address_index'] = $order['_billing_address_index'][0];
        $order_info['_shipping_address_index'] = $order['_shipping_address_index'][0];
		
        return $order_info;
        /*
        //print_r($this->vid); print_r($this->order_id);
        $query = "SELECT product_id,product_name,product_amount,order_date FROM $this->table_wcpv_commissions WHERE vendor_id=".$this->vid." AND order_id=".$this->order_id."";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $vendor_order_detail = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //print_r($vendor_order_detail);
            $i=0;
            foreach ($vendor_order_detail as $key => $value) {
                //print_r($value[product_id]);
                $vendor_order_details[product_details][$i][product_name] = $value[product_name];
                $query = "SELECT meta_value FROM $this->table_postmeta WHERE post_id=".$value[product_id]." AND meta_key='_sku'";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $sku[$i] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $vendor_order_details[product_details][$i][_sku] = $sku[$i][0][meta_value];
                $vendor_order_details[product_details][$i][price] = $value[product_amount].get_option('woocommerce_currency');

                $query1 = "SELECT DISTINCT guid FROM $this->table_posts WHERE post_parent=".$value[product_id]."";
                $stmt1 = $this->conn->prepare($query1);
                $stmt1->execute(); 
                $img_links[$i] = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                $vendor_order_details[product_details][$i][img_links] = $img_links[$i];
                $order_d = $value[order_date];
                $total_amt = $total_amt + $value[product_amount];
                $i++;
            } 
            $vendor_order_details[order_details][order_id] = $this->order_id;
            $vendor_order_details[order_details][order_date] = $order_d;
            $vendor_order_details[order_details][total_amt] = $total_amt.get_option('woocommerce_currency');
            //print_r($vendor_order_details);
            $query = "SELECT post_author FROM $this->table_posts WHERE ID=".$this->order_id."";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
            $user_id = $stmt->fetch(PDO::FETCH_ASSOC);
            $query = "SELECT meta_key,meta_value FROM $this->table_namemeta WHERE user_id=".$user_id[post_author]." AND meta_key IN ('shipping_first_name','shipping_last_name','shipping_address_1','shipping_address_2','shipping_city','shipping_postcode','shipping_country')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
            $customer_detail = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //print_r($customer_detail);
            $i=0;
            foreach ($customer_detail as $key => $value) {
                //print_r($value[meta_key]); print_r($value[meta_value]);
                $vendor_order_details[customer_detail][$value[meta_key]] = $value[meta_value];
                $i++;
            } //print_r($vendor_order_details);
            $query = "SELECT a_paci_number FROM $this->table_addressmeta WHERE user_id=".$user_id[post_author]."";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
            $paci_id = $stmt->fetch(PDO::FETCH_ASSOC);
            $vendor_order_details[customer_detail][paci_no] = $paci_id[a_paci_number];
            //print_r($vendor_order_details);
            return $vendor_order_details;
        } */
    }
    function vendor_orders_received(){ 
		ini_set('max_execution_time', 0);
		ini_set("memory_limit","1024M");
		//ini_set(“max_execution_time”,300);
        $vendor_productlist = $this->vender_product_list($this->vid); //print_r($vendor_productlist);
        
        /*$flag = 0;
        $i = 1;
        do{
            $query = [
                "per_page" => 2,
                "page" => $i
            ];
            $order = $woocommerce->get('orders',$query);
            if($order){
                $j=0;
                foreach($order as $key => $value){
                    $orders[$j] = $orders;
                    $j++;
                }                   
            } else {
                $flag = 1;
            }
        $i++;   
        } while($flag != 1)
        
        print_r($orders); die;*/
        
        $query = [
                "per_page" =>100,
                //"page" => $i
            ];
		$woocommerce = $this->woocommerce;
        $orders = $woocommerce->get('orders',$query);
        //print_r($orders);     die; 
        $term_detail = get_term_by('id',$this->vid,'wcpv_product_vendors'); 
        $user_details = get_user_by('login',$term_detail->name);
        //print_r($user_details);
        //print_r($this->vid); print_r($this->sort);
        $query = "SELECT DAY(CURDATE()) as day";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(); 
        $cur_day = $stmt->fetch(PDO::FETCH_ASSOC);
        //print_r($cur_day);

        $query = "SELECT MONTH(CURDATE()) as month";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(); 
        $cur_month = $stmt->fetch(PDO::FETCH_ASSOC);
        //print_r($cur_month[month]);

        $query = "SELECT YEAR(CURDATE()) as year";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(); 
        $cur_year = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "SELECT WEEK(CURDATE()) as week";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(); 
        $cur_week = $stmt->fetch(PDO::FETCH_ASSOC);
        //print_r($cur_year[year]);
        $query = "SELECT WEEK(CURDATE()) as week";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $none = 0;
        if($this->sort == 'daily'){ 
            $format = "M d,Y";
            $search = date("M d,Y");
            //$query = "SELECT DATE_FORMAT(order_date,'%b %d,%Y') as order_date,DATE_FORMAT(order_date,'%b %d,%Y') as orderdate,order_id,product_id,product_amount FROM ".$this->table_wcpv_commissions." WHERE vendor_id=".$this->vid." ORDER BY DAY(order_date)";   
        } 
        elseif($this->sort == 'weekly'){
            $format = "M d,Y";
             $date = date('Y-m-d h:i:s');       
            $before = (strtotime('-7 days', strtotime($date)));
            $today = time();
            //echo $before."test".$today;
            
            //die;
            $search = $cur_week[week]; //print_r($search);
            //$search = date("M,Y");
           // $query = "SELECT DATE_FORMAT(order_date,'%U') as order_date,DATE_FORMAT(order_date,'%b %d,%Y') as orderdate,order_id,product_id,product_amount FROM ".$this->table_wcpv_commissions." WHERE vendor_id=".$this->vid." ORDER BY DAY(order_date)";
        }
        elseif($this->sort == 'monthly'){
            $format = "M,Y";
            $search = date("M,Y");
            //$query = "SELECT DATE_FORMAT(order_date,'%b,%Y') as order_date,DATE_FORMAT(order_date,'%b %d,%Y') as orderdate,order_id,product_id,product_amount FROM ".$this->table_wcpv_commissions." WHERE vendor_id=".$this->vid." ORDER BY DAY(order_date) DESC";
        }
        elseif($this->sort == 'yearly'){
            $format = "Y";
            $search = date("Y");
            //$query = "SELECT DATE_FORMAT(order_date,'%Y') as order_date,order_id,DATE_FORMAT(order_date,'%b %d,%Y') as orderdate,product_id,product_amount FROM ".$this->table_wcpv_commissions." WHERE vendor_id=".$this->vid." ORDER BY DAY(order_date) DESC";
        } else {
            $none = 1;
            $format = "M d,Y";
        }
        
        $i=0; 
        foreach($orders as $order_key => $order_value) { 
            $date=date_create($order_value->date_created); //print_r($order_value->date_created); die;
            $order_date = date_format($date,$format); 
            foreach($vendor_productlist as $v_product_key => $v_product_value){
                if($this->sort == 'weekly'){        
                    $date=date_create($order_value->date_created);   
                    //echo date_format($order_value->date_created,"Y/m/d H:i:s"); 
                    $order_date = strtotime($order_value->date_created); 
                    if(($before <= $order_date) && ($today >= $order_date)) {
						//set_time_limit(1);
						
                        $order_date = $search = 1; 
                    } else {
						//set_time_limit(1);
                        $order_date = 0;
                        $search = 1;
                    }                   
                } 
                if($order_date == $search){
                    $j=0;
                    foreach($order_value->line_items as $order_line_item_key => $order_line_item_value){ //print_r($order_line_item_value);
                        if($v_product_value['ID'] == $order_line_item_value->product_id){
							
							//set_time_limit(1);
                            if($this->sort == 'weekly') {
								//set_time_limit(1);
								
                                $date=date_create($order_value->date_created); //print_r($date); die;
                                $order_date = date_format($date,$format);                               
                            } //print_r($order_line_item_value->product_image_url); die;
                            $results[$i][$this->sort][$j]['order_date'] = $order_date;
                            $results[$i][$this->sort][$j]['order_id'] = $order_value->id;
                            $results[$i][$this->sort][$j]['product_id'] = $order_line_item_value->product_id;
                            $results[$i][$this->sort][$j]['product_amount'] = $order_line_item_value->total.get_option('woocommerce_currency');
                            $results[$i][$this->sort][$j]['img_links'] = $order_line_item_value->product_image_url->link;
							$results[$i][$this->sort][$j]['order_status'] = $order_value->status;
                            $post_id[$i][$j] = $order_line_item_value->product_id;
                            $ord_id[$i][$j] = $order_value->id;
                            $j++;
                        }
                    }       // print_r($results); die;          
                } elseif( $none == 1 ) {
                    $j=0;
                    foreach($order_value->line_items as $order_line_item_key => $order_line_item_value){ 
                        if($v_product_value['ID'] == $order_line_item_value->product_id){
                            
							//set_time_limit(1);
							
                                $date=date_create($order_value->date_created); 
                                $order_date = date_format($date,$format);                               
                            
                            $results[$i][$this->sort][$j]['order_date'] = $order_date;
                            $results[$i][$this->sort][$j]['order_id'] = $order_value->id;
                            $results[$i][$this->sort][$j]['product_id'] = $order_line_item_value->product_id;
                            $results[$i][$this->sort][$j]['product_amount'] = $order_line_item_value->total.get_option('woocommerce_currency');
                            $results[$i][$this->sort][$j]['img_links'] = $order_line_item_value->product_image_url->link;
							$results[$i][$this->sort][$j]['order_status'] = $order_value->status;
                            $post_id[$i][$j] = $order_line_item_value->product_id;
                            $ord_id[$i][$j] = $order_value->id;
                            $j++;
                        }
                    }
                }               
            } $i++;
        } 
        $i=0;
        foreach($results as $key => $value) {
			set_time_limit(1);
            $result[$i] = $value;
            $i++;
        }
        return $result;
    }
         /** * Get header Authorization  **/
        public function getAuthorizationHeader(){
                $headers = null;
                if (isset($_SERVER['Authorization'])) {
                    $headers = trim($_SERVER["Authorization"]);
                }
                else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                    $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
                } elseif (function_exists('apache_request_headers')) {
                    $requestHeaders = apache_request_headers();
                    // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                    $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                    //print_r($requestHeaders);
                    if (isset($requestHeaders['Authorization'])) {
                        $headers = trim($requestHeaders['Authorization']);
                    }
                } 
                return $headers;
            }
        /**
         * get access token from header
         **/ 
        public function getBearerToken() {
            $headers = $this->getAuthorizationHeader();
            // HEADER: Get the access token from the header
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) { //print_r($matches[1]);
                    return $matches[1]; 
                }
            }
            return null;
        }
	
	public function upload_product_image(){
        $vendor_term_id = $this->vid; 
        $vendor_name = get_term_by('id',$this->vid,'wcpv_product_vendors'); //print_r($vendor_name->name);
        //$user = get_user_by('login', 'Lata'); print_r($user);
        
        $query = "SELECT term_taxonomy_id FROM ".$this->table_term_taxonomy." WHERE term_id=".$vendor_term_id."";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(); 
        if(empty($this->p_id)){
            $this->p_id = 0;
			$post_status = "draft";
        } else {
            $post_status = "publish";
        }	
        if($stmt->rowCount() > 0){ 
            $vendor_detail = get_term_by('id',$this->vid,'wcpv_product_vendors');
            $v_user_info = get_user_by('login',$vendor_detail->name); 
			if(empty($this->p_id)){
				$post = array(
				  'ID'           => $this->p_id,
				  'post_author'  => $v_user_info->ID,
				  'post_title'   => ' ',
				  'post_name'    => ' ',
				  'post_content' => ' ',
				  'post_type'    => 'product',
				  'post_status'  => $post_status,
				);
				$post_id = wp_insert_post( $post, $wp_error ); // print_r($post_id);
			} else {
				$post_id = $this->p_id;
			}
            // Update the post into the database
            
            if($post_id){ //print_r($post_id);
                $attach_id = get_post_meta($product->parent_id, "_thumbnail_id", true);
                add_post_meta($post_id, '_thumbnail_id', $attach_id);
            }
            if(empty($this->p_id)){
                $status == 'add_product';
                $this->p_id = $post_id;
            }
            //wp_update_post( $my_post );

            $host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]"; 
            $args = array ( 
                    'post_parent' => $this->p_id,
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image/jpeg'
                );

                $posts = get_posts( $args ); 

                if (is_array($posts) && count($posts) > 0) {

                    // Delete all the Children of the Parent Page
                    foreach($posts as $post){
                        wp_delete_post($post->ID, true);
                    }

                }  
            delete_post_meta( $this->p_id, '_product_image_gallery');
            //print_r($this->image);
            $query = "DELETE FROM ".$this->table_posts." WHERE post_parent=".$this->p_id." AND post_type='attachment' AND post_mime_type IN ('image/jpeg','image/png')"; 
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 

            wp_set_object_terms( $this->p_id,$vendor_name->name,'wcpv_product_vendors' );
            $count = count($this->image);  
            for ($i=1; $i < ($count+1); $i++) {     
                                if(!empty($this->image[$i])){   
                                    $upload_dir       = wp_upload_dir(); 
                                    $unique_file_name = wp_unique_filename( $upload_dir['path'], $this->image[$i]["name"] ) ;
                                    $filename         = basename( $unique_file_name );
                                    //print_r($filename);
                                    $file1 = $upload_dir['url'] . '/' . $filename;
									$uploaded_files[] = $file1;
                                    if( wp_mkdir_p( $upload_dir['path'] ) ) {
                                        $file = $upload_dir['path'] . '/' . $filename;
                                    } else {
                                        $file = $upload_dir['basedir'] . '/' . $filename;
                                    }   //print_r($file);

                                    $wp_filetype = wp_check_filetype( $filename, null );    //print_r($wp_filetype);
                                    // print_r( file_get_contents($_FILES["image"]["tmp_name"]) );
                                    $data = file_get_contents($this->image[$i]["tmp_name"]); //print_r($data);
                                    //echo $upload_dir[basedir]."/images/".$this->image[$i]['name'];
                                    move_uploaded_file($this->image[$i]["tmp_name"],$upload_dir[basedir]."/images/".$this->image[$i]['name']);
                                    $data = file_get_contents($upload_dir[basedir]."/images/".$this->image[$i]['name']);
                                    //print_r($upload_dir[basedir].'/images'); 
                                    file_put_contents( $file, $data );

                // Check image file type
                $wp_filetype = wp_check_filetype( $filename, null );
                //print_r($post->ID);
                // Set attachment data
                $attachment = array(
                    'guid'           => $upload_dir['url'] . '/' . $filename,
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title'     => sanitize_file_name( $filename ),
                    'post_content'   => '',
                    'post_status'    => 'inherit',
                    'post_author'    => $v_user_info->ID,
                );

                // Create the attachment
                $attach_id = wp_insert_attachment( $attachment, $file, $this->p_id ); 
                // Include image.php
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                // Define attachment metadata
                $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

                // Assign metadata to attachment
                wp_update_attachment_metadata( $attach_id, $attach_data );

                        if($i == 1){ //print_r($attach_id); print_r($post);
                                $flag = set_post_thumbnail( $this->p_id, $attach_id );

                        } else {
                                    if ($i >= 2) { 
                                        if($i == 2){
                                            $attach_id1 = $attach_id;
                                            $flag = 1; 
                                        } else {
                                            $attach_id1 = $attach_id1 . "," . $attach_id;
                                        } //print_r($attach_id1); echo "</br>";
                                        $flag = update_post_meta($this->p_id, '_product_image_gallery', $attach_id1);
                                    } 
                            }                                   
                    } 
            }
            wp_set_object_terms($post_id, 'simple', 'product_type');

            if( $status == "add_product" ) {
                update_post_meta( $this->p_id, 'total_sales', '0');
                update_post_meta( $post_id, '_downloadable', 'yes');
                update_post_meta( $post_id, '_purchase_note', "" );
                update_post_meta( $post_id, '_featured', "no" );
                update_post_meta( $post_id, '_weight', "" );
                update_post_meta( $post_id, '_length', "" );
                update_post_meta( $post_id, '_width', "" );
                update_post_meta( $post_id, '_height', "" );
                update_post_meta( $post_id, '_sale_price_dates_from', "" );
                update_post_meta( $post_id, '_sale_price_dates_to', "" );
                update_post_meta( $post_id, '_price', "" );
                update_post_meta( $post_id, '_sold_individually', "" );
                update_post_meta( $post_id, '_backorders', "no" );
                update_post_meta( $post_id, '_stock_status', "instock" );
                update_post_meta( $post_id, '_downloadable_files', $_file_paths);
                update_post_meta( $post_id, '_download_limit', '');
                update_post_meta( $post_id, '_download_expiry', '');
                update_post_meta( $post_id, '_download_type', '');
            } 
            if ($flag == 'true' || !empty($flag)) {
				$response['prouct_id'] = $this->p_id;
				$response['img_url'] = $uploaded_files;				
                return $response;
            } /*elseif ($flag == 'false' || empty($flag)) {
                return false;
            }*/
        } return false;
    }
	
    public function update_prod() {  
        $vendor_term_id = $this->vid; 
        $vendor_name = get_term_by('id',$this->vid,'wcpv_product_vendors'); //print_r($vendor_name->name);
        //$user = get_user_by('login', 'Lata'); print_r($user);
        
        $query = "SELECT term_taxonomy_id FROM ".$this->table_term_taxonomy." WHERE term_id=".$vendor_term_id."";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(); //print_r($stmt);
        
        if($stmt->rowCount() > 0){
            $vendor_detail = get_term_by('id',$this->vid,'wcpv_product_vendors');
            $v_user_info = get_user_by('login',$vendor_detail->name);   
            $post = array(
              'ID'           => $this->p_id,
              'post_author'  => $v_user_info->ID,
              'post_title'   => $this->p_name,
              'post_name'    => $this->p_name,
              'post_content' => $this->p_desc,
              'post_type'    => 'product',
              'post_status'  => 'publish'
            );
            // Update the post into the database
            $post_id = wp_insert_post( $post, $wp_error );
            if($post_id){ //print_r($post_id);
                $attach_id = get_post_meta($product->parent_id, "_thumbnail_id", true);
                add_post_meta($post_id, '_thumbnail_id', $attach_id);
            }
            if(empty($this->p_id)){
                //$status = "add_product";
                $this->p_id = $post_id;
            }
            //wp_set_object_terms( $this->p_id,$vendor_name->name,'wcpv_product_vendors' );

            if($this->regular_price){
                update_post_meta($this->p_id, '_regular_price', $this->regular_price);
            }
            if($this->sale_price){
                update_post_meta($this->p_id, '_sale_price', $this->sale_price);
            }
            if($this->sale_price) {
                update_post_meta($this->p_id, '_price', $this->regular_price - $this->sale_price);
            } else {
                update_post_meta($this->p_id, '_price', $this->regular_price);
            }
            if($this->sku){
                update_post_meta($this->p_id, '_sku', $this->sku);
            }
            // update Categories
            if(!empty($this->categories[0])){
                $i=0; 
                foreach ($this->categories as $key => $value) {    
                    $cat = get_term_by('name',$value,'product_cat');
                    $term_id[$i] = $cat->term_id;
                    $i++;
                }
                wp_set_object_terms( $this->p_id, $term_id, 'product_cat' );   
            }
                
            // update color
            if(!empty($this->pa_color[0])){
                $i=0;
                foreach ($this->pa_color as $key => $value) {
                    $cat1 = get_term_by('name',$value,'pa_color');
                    if(empty($cat1)){
                        wp_insert_term( $value, 'pa_color');
                    $cat1 = get_term_by('name',$value,'pa_color');
                    }
                    $color_id[$i] = $cat1->term_id;
                    $i++;
                }         //  print_r($color_id); //print_r($this->p_id);

                wp_set_object_terms( $this->p_id, $color_id,'pa_color' );   
            
                foreach ($this->categories as $key => $value) {
                $attr = 'pa_color';
                $thedata[sanitize_title($attr)] = Array(
                                                'name' => wc_clean($attr),
                                                'value' => $value,
                                                'postion' => '0',
                                                'is_visible' => '1',
                                                'is_variation' => '1',
                                                'is_taxonomy' => '1'
                                        );
                                    }
                update_post_meta($this->p_id, '_product_attributes', $thedata);    
            }
            
            // update brand
            if(!empty($this->pa_brand[0])){
                $i=0;
                 foreach ($this->pa_brand as $key => $value) {
                    $check_term = get_term_by('name',$value,'pa_brand'); 
                    if(empty($check_term)){
                        wp_insert_term( $value, 'pa_brand');
                    }
                 }

                foreach ($this->pa_brand as $key => $value) {
                    $cat1 = get_term_by('name',$value,'pa_brand');
                    $color_id[$i] = $cat1->term_id;
                    $i++;
                }          // print_r($color_id); //print_r($this->p_id);
                wp_set_object_terms( $this->p_id, $color_id,'pa_brand' );

                foreach ($this->categories as $key => $value) {
                $attr = 'pa_brand';
                $thedata[sanitize_title($attr)] = Array(
                                                'name' => wc_clean($attr),
                                                'value' => $value,
                                                'postion' => '0',
                                                'is_visible' => '1',
                                                'is_variation' => '0',
                                                'is_taxonomy' => '1'
                                        );
                                    }           
                
                update_post_meta($this->p_id, '_product_attributes', $thedata);
            }
            

            // update size
            if(!empty($this->pa_size[0])){
                $i=0;
                foreach ($this->pa_size as $key => $value) {
                    $cat2 = get_term_by('name',$value,'pa_size');
                    if(empty($cat2)){
                        wp_insert_term( $value, 'pa_size');
                    $cat2 = get_term_by('name',$value,'pa_size');
                    }
                    $size_id[$i] = $cat2->term_id;
                    $i++;
                }
                wp_set_object_terms( $this->p_id, $size_id, 'pa_size' );

                foreach ($this->categories as $key => $value) {
                $attr = 'pa_size';
                $thedata[sanitize_title($attr)] = Array(
                                                'name' => wc_clean($attr),
                                                'value' => $value,
                                                'postion' => '0',
                                                'is_visible' => '1',
                                                'is_variation' => '1',
                                                'is_taxonomy' => '1'
                                        );
                                    }
                update_post_meta($this->p_id, '_product_attributes', $thedata);
            }
            //wp_set_object_terms( $post_id, 'Races', 'product_cat' );
            wp_set_object_terms($post_id, 'simple', 'product_type');

            update_post_meta( $this->p_id, '_visibility', 'visible' );
            if($this->quantity > 0){
                    $stock = "instock";
                } else {
                    $stock = "outofstock";
                }
            update_post_meta( $this->p_id, '_stock_status', $stock);
            update_post_meta( $post_id, '_manage_stock', "yes" );
            $flag = update_post_meta( $post_id, '_stock', $this->quantity );
            
            
            $args = array(
                         'post_type' => 'product_variation',
                         'post_parent' => $this->p_id,
                         'posts_per_page' => -1
                          );

            $variation_products = get_posts( $args );
            
            if(!empty($variation_products)){
                foreach ($variation_products as $key => $value) {    //   print_r($variation_products[$key]->ID);
                    $this->woocommerce->delete('products/'.$this->p_id.'/variations/'.$variation_products[$key]->ID.'', ['force' => true]);
                }
            }
            if(!empty($this->pa_size[0]) || !empty($this->pa_color[0])) { 
                //$this->add_product_variation($this->pa_color,$this->pa_size,$this->p_id,$this->woocommerce);
            }   
            if($post_id){
                $pid[product_id]=$this->p_id;
                return $pid;
            } else {
               wp_delete_attachment( $this->p_id, true );
               wp_delete_post( $this->p_id, true );
               return false;
            }
        } return false;
    } 
function map() { 
        $query = "SELECT user_id,a_latitude,a_longitude FROM ".$this->table_profile_detail." WHERE show_location=1 ";
        if($this->radius == 1){
            $query = $query . " AND ". "(((ACOS(SIN((".$this->lat."*PI()/180)) * SIN((a_latitude*PI()/180))+COS((".$this->lat."*PI()/180)) * COS((a_latitude*PI()/180))  * COS(((".$this->long."-a_longitude) * PI()/180))))*180/PI())*60*1.1515) <=8";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute(); //print_r($stmt);
        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC); //print_r($locations);
        $i=0;
        foreach ($locations as $key => $value) { 
                $query = "SELECT meta_value FROM ".$this->table_namemeta." WHERE user_id=".$value[user_id]." AND meta_key='billing_company'";
                $stmt = $this->conn->prepare($query);
                $stmt->execute(); 
                $brand_name = $stmt->fetch(PDO::FETCH_ASSOC);
                $locations[$i][brand_name]=$brand_name[meta_value]; //print_r($brand_name[meta_value]);
                $b_name[] = $brand_name[meta_value];
                $brand_img = $this->brand_name($b_name); 
                $categories = array('pa_brand');
            
            $cat_names = $this->brand_name($categories);
            foreach ($cat_names as $key => $value) {
                if($value[category_name] == $brand_name[meta_value]){
                    $locations[$i][img_link]=$value[img_link];
                }
            }
            $i++;
        } //print_r($locations);
        $i=0;
        foreach ($locations as $key => $value) { 
                $query = "SELECT a_address FROM ".$this->table_profile_detail." WHERE user_id=".$value[user_id].""; 
               // $query = "SELECT meta_value FROM $this->table_name WHERE user_id=".$value[user_id]." AND meta_key='description'"; 
                $stmt = $this->conn->prepare($query);
                $stmt->execute(); 
                $desc = $stmt->fetch(PDO::FETCH_ASSOC);
                $locations[$i][description]=$desc[a_address];
                $i++;
        } //print_r($locations);
        if(!empty($locations)){
			$i=0;
			foreach ($locations as $key => $value) {
                $query = "SELECT * FROM ".$this->table_name. " WHERE ID=".$value['user_id'];	
				$stmt = $this->conn->prepare($query);
				$stmt->execute();
				if(!empty($stmt->rowCount())){ 
					$location[$i] = $value;
					$i++;
				}
            }            
			if(!empty($location)){
				return $location;
			} 
			return false;
        } 
        return false;
    }
    function checkout(){
            //$woocommerce = $this->woocommerce;
            $query = "SELECT display_name FROM ".$this->table_name." WHERE ID=".$this->uid.""; 
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $full_name = $stmt->fetch(PDO::FETCH_ASSOC); //print_r($full_name[display_name]);

            /*$add_1 = get_user_meta( $this->uid,'shipping_address_1'); 
            $add_2 = get_user_meta( $this->uid,'shipping_address_2'); 
            $shipping_postcode = get_user_meta( $this->uid,'shipping_postcode'); */
            wp_set_object_terms($post_id, 'simple', 'product_type');
            $query = "SELECT * FROM ".$this->table_addressmeta." WHERE user_id=".$this->uid.""; 
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $address = $stmt->fetch(PDO::FETCH_ASSOC); 

            $checkout_info[full_name] = $full_name[display_name];
            $checkout_info[area] = $address[a_area];
            $checkout_info[house_no] = $address[a_apartment];
            $checkout_info[block] = $address[a_floor];          //.$address[a_apartment]
            $checkout_info[street] = $address[a_street];
            $checkout_info[paci_no] = $address[a_paci_number];
            $checkout_info[avenue] = $address[a_avenue];
            $checkout_info[zip_code] = $address[a_zip_code];
            $checkout_info[special_direction] = $address[special_direction];
            //$checkout_info[shipping_cost] = $this->calculate_shipping_cost($checkout_info[zip_code],$this->woocommerce).get_option('woocommerce_currency');
            if($_SERVER['REQUEST_METHOD'] == "POST"){  
                $query = "UPDATE " . $this->table_name . " SET display_name='" .$this->fullname. "' WHERE ID=".$this->uid."";  
                $stmt = $this->conn->prepare($query);
                $stmt->execute();   

                $query = "SELECT a_title FROM ".$this->table_addressmeta." WHERE user_id=".$this->uid.""; 
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                if(empty($stmt->rowCount())){                                                  //  a_apartment ,
                    $query = "INSERT INTO " . $this->table_addressmeta . "( user_id , a_title , a_apartment, a_floor , a_street ,a_area,a_zip_code ,a_gender,a_paci_number,a_avenue ) VALUES (" . $this->uid . ",'Address','".$this->house_no."','" . $this->block . "','" . $this->street . "','".$this->street." '," . $this->zip_code . ",' ','" . $this->paci_no . "','".$this->avenue."') "; 
                     $stmt = $this->conn->prepare($query);
                     $stmt->execute();      
                } else{
                    $address = $stmt->fetch(PDO::FETCH_ASSOC);
                    $query = "UPDATE " . $this->table_addressmeta . " SET a_apartment='".$this->house_no."', a_floor='" .$this->block. "',a_street='".$this->street."',a_zip_code='".$this->zip_code."',a_paci_number='".$this->paci_no."',a_avenue='".$this->avenue."' WHERE user_id=".$this->uid." AND a_title='".$address[a_title]."'";  
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                }
                $query = "SELECT display_name FROM ".$this->table_name." WHERE ID=".$this->uid.""; 
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $full_name = $stmt->fetch(PDO::FETCH_ASSOC); //print_r($full_name[display_name]);
                
                $query = "SELECT * FROM ".$this->table_addressmeta." WHERE user_id=".$this->uid.""; 
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $address = $stmt->fetch(PDO::FETCH_ASSOC); 

                $checkout_info[full_name] = $full_name[display_name];
                $checkout_info[area] = $address[a_area];
                $checkout_info[house_no] = $address[a_apartment];
                $checkout_info[block] = $address[a_floor];          //.$address[a_apartment]
                $checkout_info[street] = $address[a_street];
                $checkout_info[paci_no] = $address[a_paci_number];
                $checkout_info[avenue] = $address[a_avenue];
                $checkout_info[zip_code] = $address[a_zip_code];
                $checkout_info[special_direction] = $address[special_direction];
                //$checkout_info[shipping_cost] = $this->calculate_shipping_cost($checkout_info[zip_code],$this->woocommerce).get_option('woocommerce_currency');
                return $checkout_info;
            }
            
            return $checkout_info;
    }
    function calculate_shipping_cost($zip_code,$woocommerce){
        $query = "SELECT zone_id FROM ".$this->table_woocommerce_shipping_zone_locations." WHERE location_code=".$zip_code.""; // AND location_type="postcode"
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $zone_id = $stmt->fetch(PDO::FETCH_ASSOC);  

        $query = "SELECT instance_id FROM ".$this->table_woocommerce_shipping_zone_methods." WHERE zone_id=".$zone_id[zone_id].""; // AND location_type="postcode"
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $instance_id = $stmt->fetch(PDO::FETCH_ASSOC);   
        if(!empty($instance_id)) {
            $shipping_price = ($woocommerce->get('shipping/zones/'.$zone_id[zone_id].'/methods/'.$instance_id[instance_id].''));
            $cost = $shipping_price->settings->cost->value;
        }  else {
            $cost = 0;
        }
        /*$coupon_meta = get_post_meta($coupon_id);
        if($coupon_meta[free_shipping][0] == "yes"){
            $cost = 0;
        }*/
        return $cost;

    }
    function add_token($token,$user_info){
        $query = "SELECT * FROM ".$this->table_api_token.""; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if(empty($stmt->rowCount())){ 
            $query = "CREATE TABLE " . $this->table_api_token . " (user_id INT(100),vendor_id INT(100),token VARCHAR(1000))";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
        }
        $query = "SELECT * FROM ".$this->table_api_token." WHERE user_id=".$user_info[user_id].""; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if(empty($stmt->rowCount())){

            if(!empty($user_info[vendor_id])){
               $query = "INSERT INTO " . $this->table_api_token . " (user_id,vendor_id,token) VALUES (".$user_info[user_id].",".$user_info[vendor_id].",'".$token."')"; 
            } else {
                $query = "INSERT INTO " . $this->table_api_token . " (user_id,token) VALUES (".$user_info[user_id].",'".$token."')";
                }
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
        } else {
                $query = "UPDATE " . $this->table_api_token . " SET token='" .$token. "' WHERE user_id=".$user_info[user_id]."";  
                $stmt = $this->conn->prepare($query);
                $stmt->execute(); 
            }
    }
    function verify_token($user_id,$vendor_id,$token){  
        if(!empty($user_id)){  
            $query = "SELECT token FROM ".$this->table_api_token." WHERE user_id=".$user_id.""; 
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
            $org_token = $stmt->fetch(PDO::FETCH_ASSOC);
            if($token == $org_token[token]){
                return true;
            } else {
                return true; //false;
            }
        } elseif(!empty($vendor_id)) {  
            $query = "SELECT token FROM ".$this->table_api_token." WHERE vendor_id=".$vendor_id.""; 
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $org_token = $stmt->fetch(PDO::FETCH_ASSOC);
            if($token == $org_token['token']){
                return true;
            } else {
                return true;//false;
            }
        } else {
            return true; //false;
        }
    }
    function cart() {
        if($_SERVER['REQUEST_METHOD'] == "GET"){
            if( (empty($this->add_coupon)) && (empty($this->remove_coupon))){
                $request = new WP_REST_Request( 'GET', '/wc/v2/cart' );
                $response = rest_do_request( $request );
                $server = rest_get_server();
                $data[cart_items] = $server->response_to_data( $response, false );
                
                 
                $request = new WP_REST_Request( 'GET', '/wc/v2/cart/totals' );
                $response = rest_do_request( $request );
                $server = rest_get_server();
                $data1[calculate_cart_total] = $server->response_to_data( $response, false ); 
                //$cart = $cart_session + $data;

                $request = new WP_REST_Request( 'GET', '/wc/v2/cart/totals' );
                $response = rest_do_request( $request );
                $server = rest_get_server();
                $data[cart_total] = $server->response_to_data( $response, false ); 
                $data = $this->process_cart_item($data);
                $cart = $data;    
            }

            if(!empty($this->add_coupon)) {
                $cart = $this->add_coupon_cart($this->add_coupon,$cart_session);
            }
            if(!empty($this->remove_coupon)) {
                $cart = $this->remove_coupon_cart($this->remove_coupon);
            }
            $request = new WP_REST_Request( 'GET', '/wc/v2/cart/count-items' );
            $response = rest_do_request( $request );
            //echo "<pre>"; print_r($response); echo "<br/>";
            $server = rest_get_server();
            //echo "<pre>"; print_r($server); echo "<br/>";
            $data[cart_count] = $server->response_to_data( $response, false ); 
            $cart = $cart + $data; 
            return $cart;     
        }
        if($_SERVER['REQUEST_METHOD'] == "POST"){    
            if(empty($this->data[cart_item_key])){
                //echo 657658758; echo "<br/>";
                $request = new WP_REST_Request( 'POST', '/wc/v2/cart/add' );
                $query = array(
                                  'product_id' => $this->data[product_id],
                                  'quantity' => $this->data[quantity],
                                  'variation_id' =>$this->data[variation_id],
                                  'variation' => $this->data[variation],
                                  'cart_item_data' => $this->data[cart_item_data]
                                );   // print_r( wp_json_encode($query) );

                $request->set_query_params(($query));
                $response = rest_do_request( $request );
                //echo "<pre>"; print_r($response); echo "<br/>";
                $server = rest_get_server();
                //echo "<pre>"; print_r($server); echo "<br/>";
                $data = $server->response_to_data( $response, false );
                //echo "<pre>"; print_r($data); echo "<br/>";
                unset( $data['data'] );
                return $data;    
            } else{
                //echo 897987987; echo "<br/>";
                $request = new WP_REST_Request( 'POST', '/wc/v2/cart/cart-item' );
                $query = array(
                                  'cart_item_key' => $this->data[cart_item_key],
                                  'quantity' => $this->data[quantity]
                                );   // print_r( wp_json_encode($query) );

                $request->set_query_params(($query));
                $response = rest_do_request( $request );
                $server = rest_get_server();
                $data = $server->response_to_data( $response, false ); 
                $data = str_replace('"',' ', $data);
                return $data;
            }
            
        }
        if ($_SERVER['REQUEST_METHOD'] == "DELETE") {   
            $request = new WP_REST_Request( 'DELETE', '/wc/v2/cart/cart-item' );  
            $query = array(
                              "cart_item_key" => $this->data[cart_item_key]
                            ); 
            $request->set_query_params(($query));
            $response = rest_do_request( $request );    
            $server = rest_get_server();
            $data = $server->response_to_data( $response, false ); 
            return $data;
        }
    }
    public function remove_coupon_cart($coupon_id){    
            //$applied_coupons = wc()->cart->get_applied_coupons();  
            $coupon_code = new WC_Coupon($coupon_id); 

            if ( WC()->cart->has_discount( $coupon_code->code ) ) 
            wc()->cart->remove_coupons( $coupon_code->code );
                         // wc_print_notices();
            // Recalculate cart         wc()->cart->calculate_totals();
            $request = new WP_REST_Request( 'POST', '/wc/v2/cart/calculate' );
            $response = rest_do_request( $request );
            $server = rest_get_server();
            $server->response_to_data( $response, false );

            $request = new WP_REST_Request( 'GET', '/wc/v2/cart' );
            $response = rest_do_request( $request );
            $server = rest_get_server();
            $data[cart_items] = $server->response_to_data( $response, false );

            $request = new WP_REST_Request( 'GET', '/wc/v2/cart/totals' );
            $response = rest_do_request( $request );
            $server = rest_get_server();
            $data[cart_total] = $server->response_to_data( $response, false );
            $data = $this->process_cart_item($data);
            return $data;
    }
    public function add_coupon_cart($coupon_id,$cart){ //print_r($cart);
        $coupon_meta = get_post($coupon_id);   
        $coupon_code = new WC_Coupon($coupon_id);   // print_r($coupon_code);
        wc()->cart->remove_coupon( $coupon_code->code );

        if ( WC()->cart->has_discount( $coupon_code->code ) ) 
            wc()->cart->remove_coupon( $coupon_code->code );
            wc()->cart->calculate_totals();

            WC()->cart->add_discount( $coupon_code->code );
        // calculate cart            
        $request = new WP_REST_Request( 'POST', '/wc/v2/cart/calculate' );
        $response = rest_do_request( $request );
        $server = rest_get_server();
        $server->response_to_data( $response, false );

        $request = new WP_REST_Request( 'GET', '/wc/v2/cart' );
        $response = rest_do_request( $request );
        $server = rest_get_server();
        $data[cart_items] = $server->response_to_data( $response, false );
        
        
        $request = new WP_REST_Request( 'GET', '/wc/v2/cart/totals' );
        $response = rest_do_request( $request );
        $server = rest_get_server();
        $data[cart_total] = $server->response_to_data( $response, false );
        $data = $this->process_cart_item($data);
        return $data;
    }
    function process_cart_item($data){
        foreach ($data[cart_items] as $key => $value) {
                    unset( $data[cart_items][ $key ]['data'] );
                    $data[cart_items][ $key ]['line_subtotal'] = $data[cart_items][ $key ]['line_subtotal'] . get_option('woocommerce_currency');
                    $data[cart_items][ $key ]['line_subtotal_tax'] = $data[cart_items][ $key ]['line_subtotal_tax'] . get_option('woocommerce_currency');
                    $data[cart_items][ $key ]['line_total'] = $data[cart_items][ $key ]['line_total'] . get_option('woocommerce_currency');
                    $data[cart_items][ $key ]['line_tax'] = $data[cart_items][ $key ]['line_tax'] . get_option('woocommerce_currency');
        }   
        $data[cart_total][subtotal] = $data[cart_total][subtotal] . get_option('woocommerce_currency');
        $data[cart_total][subtotal_tax] = $data[cart_total][subtotal_tax] . get_option('woocommerce_currency');
        $data[cart_total][shipping_total] = $data[cart_total][shipping_total] . get_option('woocommerce_currency');
        $data[cart_total][shipping_tax] = $data[cart_total][shipping_tax] . get_option('woocommerce_currency');
        $data[cart_total][discount_total] = $data[cart_total][discount_total] . get_option('woocommerce_currency');
        $data[cart_total][discount_tax] = $data[cart_total][discount_tax] . get_option('woocommerce_currency');
        $data[cart_total][cart_contents_total] = $data[cart_total][cart_contents_total] . get_option('woocommerce_currency');
        $data[cart_total][cart_contents_tax] = $data[cart_total][cart_contents_tax] . get_option('woocommerce_currency');
        $data[cart_total][fee_total] = $data[cart_total][fee_total] . get_option('woocommerce_currency');
        $data[cart_total][fee_tax] = $data[cart_total][fee_tax] . get_option('woocommerce_currency');
        $data[cart_total][total] = $data[cart_total][total] . get_option('woocommerce_currency');
        $data[cart_total][total_tax] = $data[cart_total][total_tax] . get_option('woocommerce_currency');
        return $data;
    }
    function orderconfirm(){  		
		$log = 'order details:'.print_r($this->data,true);
		file_put_contents(dirname(__FILE__).'/log/'.date("y-m-d").'-orderdetail.log', $log, FILE_APPEND);
		//die;
      //echo "<pre>"; print_r($this->data); echo "<br/>";		
      $woocommerce = $this->woocommerce;
      //echo "<pre>"; print_r($woocommerce); echo "<br/>"; 		
      $order_confirm = $woocommerce->post('orders', $this->data);
       //echo 8798798; die;
       //echo "<pre>"; print_r($order_confirm); die;  
			$order_detail[id] = $order_confirm->id;	
      if($order_confirm){
        $response = $this->push_notification($this->customer_id,$order_confirm);
        //echo "<pre>"; print_r($response); die;
        //$order_confirm['push_notification'] =  $response; 
        return $order_detail;
      } 
      return false;
    }
    function push_notification($customer_id,$order_detail) {  //print_r($customer_id);
        // push notification for customer
        //$customer_id = 407;
        $user = get_user_by( 'ID', $customer_id );      
        $custom_message = "".$user->display_name.", Your order #".$order_detail->id." is on its way!!"; 
        // API access key from Google FCM App Console
        define( 'API_ACCESS_KEY', 'AAAAhJ63EUE:APA91bE5Of01l9GX1zIbn8nWSlNQTsbKDodXPn8DGnAW0dQKc3aWb9thDyKdUKbYuhed3CGgnsf8BAYDZg5u2DfFp36VEzWyLgqGAhyvi1m-DkcflKCnoj3JORglsodhBtaTf5Uh0IS0' );  //    AIzaSyDtY_FUGSOSWD_vkmcmhmyVh8kmt31pA5s
        $query = "SELECT device_id FROM ".$this->table_device_info." WHERE user_id=".$customer_id.""; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute(); 
        $device_id = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        $i=0;
        foreach ($device_id as $key => $value) {
            $device_ids[$i] = $value[device_id];
            $i++;
        }
        $singleID = $device_id[0][device_id] ; 
        $registrationIDs = ($device_ids);     //print_r($registrationIDs); die;

        $fcmMsg = array(
            'body' => $custom_message, 
            'title' => 'your order placed',   
            //'sound' => "default",
            //'color' => "#203E78" 
        );  //print_r($fcmMsg);
        

        // 'to' => $singleID ;  // expecting a single ID
        // 'registration_ids' => $registrationIDs ;  // expects an array of ids
        // 'priority' => 'high' ; // options are normal and high, if not set, defaults to high.
        
        $fcmFields = array(
            'content_available'=> true,
            'registration_ids' => $registrationIDs,
            'priority' => 'high',
            'notification' => $fcmMsg
        ); //print_r(($fcmFields)); die;
		
		// log
	$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
                "User: ".($customer_id).PHP_EOL.
                "registration_ids: ".print_r($fcmFields,true).PHP_EOL.
                "-------------------------".PHP_EOL;
                //Save string to log, use FILE_APPEND to append.
        file_put_contents(dirname(__FILE__).'/log/'.date("y-m-d").'-pushnotification.log', $log, FILE_APPEND);
		//print_r(dirname(__FILE__).'/log/'.date("y.m.d-$customer_id").'.log'); die;
        $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );
         
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
        $response1 = curl_exec($ch );
        curl_close( $ch );
        //print_r($response1); die;
        // push notification for vendors
        
        foreach ($order_detail->line_items as $key => $value) {	
            $vendor_detail = wp_get_object_terms( $value->product_id,$vendor_detail = 'wcpv_product_vendors' );
			//print_r($vendor_detail);
            //print_r($vendor_detail->term_id);
			
            $query = "SELECT ID FROM ".$this->table_name." WHERE user_login='".$vendor_detail[0]->name."'";  
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
            $vendor_uid = $stmt->fetch(PDO::FETCH_ASSOC);
			//print_r($vendor_uid);
                 
            $custom_message = "".$vendor_detail->name.", You’ve received an order #".$order_detail->id." for product ".$value->name.""; 
			//$vendor_uid[ID] = 398;
            //print_r($vendor_uid[ID]);
            $query = "SELECT device_id FROM ".$this->table_device_info." WHERE user_id=".$vendor_uid[ID].""; 
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
            $device_id = $stmt->fetchAll(PDO::FETCH_ASSOC);   //print_r($device_id); die;
            $i=0;
            foreach ($device_id as $key => $value) {
                $device_ids[$i] = $value[device_id];
                $i++;
            }
            $singleID = $device_id[0][device_id] ; 
            $registrationIDs = ($device_ids);    // print_r($registrationIDs);
			/*$registrationIDs = '';
        $registrationIDs[] = "c-jGO4RoWio:APA91bHcE4yUMCBI6qF8xhhz6xAs-SkC_2JgUxvrdZbA2mJfXUqDYw9-3xBxpI11F4jELu0nGkxCE6PucRdzPUz7DFEUHgHXz8IXl37YrLz4IrULK4zN2PtK0Zcxj8w3x8-pwIVk7V4f";*/
            $fcmMsg = array(
                'body' => $custom_message, 
                'title' => 'You’ve received an order',   
                //'sound' => "default",
                //'color' => "#203E78" 
            );  //print_r($fcmMsg);
            $fcmFields = array(
                'content_available'=> true,
                'registration_ids' => $registrationIDs,
                'priority' => 'high',
                'notification' => $fcmMsg
            ); // print_r($fcmFields); die;
			
			$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "Orderid: ".$order_detail->id.PHP_EOL.
            "input: ".print_r($fcmFields,true).PHP_EOL.
            "-------------------------".PHP_EOL;
            //Save string to log, use FILE_APPEND to append.
            file_put_contents(dirname(__FILE__).'/log/'.date("y-m-d").'-'.$order_detail->id.'.log', $log, FILE_APPEND);	
			

            $headers = array(
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-Type: application/json'
            );
			
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
            $response2 = curl_exec($ch ); //print_r($response2);  die;
            curl_close( $ch );
            $response = $response1 + $response2;
            return $response;
           // print_r($response2);
        }
    }
    public function category_listing() {   
            $woocommerce = $this->woocommerce;	
            $brand_name = $this->category_id;
            $query = [
                'parent' => $this->category_id,    // 17
                'per_page' => 100
            ];
           
        if($this->category_id == ""){
            $query2 = [
                        'per_page' => 100
                    ];
            $results = $woocommerce->get('products/categories',$query2);  
            $i=0;
            foreach ($results as $key => $value) {
                if($value->name != "Uncategorized"){
                    if($value->parent == 0){
                        $result[$i] = $value;
                        $i++;
                    }
                }
            }   
            $j=0;
            foreach ($results as $key => $value) {
                if($value->name != "Uncategorized"){
                    if($value->parent != 0){
                        foreach ($result as $key1 => $value1) {
                            if($value->parent == $value1->id){ 
                                if(empty($value1->subcategory_count)){
                                    $value1->subcategory_count = 0;
                                }
                                $value1->subcategory[$value1->subcategory_count] = $value;
                                $value1->subcategory_count++;
                                $j++;
                            }
                        }
                    }
                }   
            }   return $result;
        }
        else {
            $result = $woocommerce->get('products/categories',$query);
        }   
        $i=0;
        $ids = array();
        foreach ($result as $key => $value) {  
            $ids[$i] = $output[$i][id] = $value->id;
            $output[$i][name] = $value->name;
            $output[$i][slug] = $value->slug;
            $output[$i][parent] = $value->parent;
            $output[$i][description] = $value->description;
            $output[$i][display] = $value->display;
            $output[$i][image] = $value->image->src;
            $output[$i][menu_order] = $value->menu_order;
            $output[$i][count] = $value->count;
            $i++;
        }
        asort($ids);
        array_multisort($ids, SORT_ASC, SORT_NATURAL | SORT_FLAG_CASE, $output);
             return $output;
    }   
    public function order_update(){  
        $query = "SELECT * FROM ".$this->table_wcpv_commissions." WHERE vendor_id=".$this->data[vid]." AND order_id=".$this->data[order_id]."";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();  
        if($stmt->execute()) { 
            $woocommerce = $this->woocommerce;
            $order_update = $woocommerce->put('orders/'.$this->data[order_id], $this->data); 
            if($order_update){
                $order = wc_get_order( $this->data[order_id] );
                $customer_message = array(
                                        'title' => 'Thank you for shopping with us',
                                        'body' => 'Your order #'.$this->data[order_id].' is '.$order->get_status().'!!'
                                        );
                $vendor_message = array(
                                        'title' => 'Your order status updated successfully',
                                        'body' => 'Your order #'.$this->data[order_id].'status is updated to '.$order->get_status().''
                                    );
                $customer_id = $order->get_user_id();
                $i=0;
                foreach ($order->get_items() as $item_key => $item ): 
                    $product_id   = $item->get_product_id();
                    $vendor_detail = wp_get_object_terms( $product_id,'wcpv_product_vendors' ); //print_r($vendor_detail);
                    $vendor_id[$i] = $vendor_detail[0]->term_id;
                    $i++;
                endforeach;  
                $response = $this->push_notify($customer_id,$vendor_id,$customer_message,$vendor_message);
                return true;
            }   else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function add_product_variation($color,$size,$pid,$woocommerce) { 
        //wp_set_object_terms($pid, 'simple', 'product_type');    
        wp_set_object_terms($pid, 'variable', 'product_type');
        //print_r(wp_get_object_terms($pid,'product_type'));
        $regular_price = get_post_meta($pid, '_regular_price');
        $sale_price = get_post_meta($pid, '_sale_price');
        $brand = wp_get_object_terms($pid,'pa_brand'); 

        $color_att_id = wc_attribute_taxonomy_id_by_name('pa_color');
        $size_att_id = wc_attribute_taxonomy_id_by_name('pa_size');
        $brand_att_id = wc_attribute_taxonomy_id_by_name('pa_brand');

        if(!empty($color) && !empty($size)){
           foreach ($color as $key => $value) {
               foreach ($size as $key1 => $value1) {
                    $data = [
                        'regular_price' => $regular_price[0],
                        'sale_price' => $sale_price[0],
                        /*'image' => [
                            'id' => 423
                        ],*/
                        /*'image'         => [
                            'src' => 'https://shop.local/path/to/image_size_l.jpg',
                        ],*/
                        'attributes' => [
                            [
                                'id' => $color_att_id,
                                'option' => $value
                            ],
                            [
                                'id' => $size_att_id,
                                'option' => $value1
                            ]/*,
                            [
                                'id' => $brand_att_id,
                                'option' => $brand[0]->name
                            ]*/
                        ]
                    ];
                    $woocommerce->post('products/'.$pid.'/variations', $data);
               }
           }
        }
    }
    function push_notify($customer_id,$vendor_id,$customer_message,$vendor_message){
        // API access key from Google FCM App Console
        define( 'API_ACCESS_KEY', 'AAAAhJ63EUE:APA91bE5Of01l9GX1zIbn8nWSlNQTsbKDodXPn8DGnAW0dQKc3aWb9thDyKdUKbYuhed3CGgnsf8BAYDZg5u2DfFp36VEzWyLgqGAhyvi1m-DkcflKCnoj3JORglsodhBtaTf5Uh0IS0' );  // AIzaSyDtY_FUGSOSWD_vkmcmhmyVh8kmt31pA5s

        // push notification for customer
        if(!empty($customer_id)){   
            $user = get_user_by( 'ID', $customer_id ); 
            
            $query = "SELECT device_id FROM ".$this->table_device_info." WHERE user_id=".$customer_id.""; 
            $stmt = $this->conn->prepare($query);
            $stmt->execute(); 
            $device_id = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            $i=0;
            foreach ($device_id as $key => $value) {
                $device_ids[$i] = $value[device_id];
                $i++;
            }
            $singleID = $device_id[0][device_id] ; 
            $registrationIDs = ($device_ids);     //print_r($registrationIDs);

            $fcmMsg = array(
                'title' => $customer_message[title],
                'body' => $customer_message[body],
                //'sound' => "default",
                //'color' => "#203E78" 
            );  //print_r($fcmMsg);

            $fcmFields = array(
                'content_available'=> true,
                'registration_ids' => $registrationIDs,
                'priority' => 'high',
                'notification' => $fcmMsg
            ); 

            $headers = array(
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-Type: application/json'
            );
             
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
            $response1 = curl_exec($ch );
            curl_close( $ch );
            //print_r($response1); 
        }
        
        // push notification for vendors
        if(!empty($vendor_id)){     //print_r($vendor_id);
            foreach ($vendor_id as $key => $value) {   // print_r($value);
                $vendor_detail = get_term_by('id',$value,'wcpv_product_vendors');
                $v_user_info = get_user_by('login',$vendor_detail->name);   
                
                $query = "SELECT device_id FROM ".$this->table_device_info." WHERE user_id=".$v_user_info->ID."";   
                $stmt = $this->conn->prepare($query);
                $stmt->execute(); 
                $device_id = $stmt->fetchAll(PDO::FETCH_ASSOC); //print_r($device_id);
                $i=0;
                foreach ($device_id as $key => $value) {
                    $device_ids[$i] = $value[device_id];
                    $i++;
                }
                $singleID = $device_id[0][device_id] ; 
                $registrationIDs = $device_ids;     //print_r($registrationIDs);

                $fcmMsg = array(
                    'title' => $vendor_message[title], 
                    'body' => $vendor_message[body],   
                    //'sound' => "default",
                    //'color' => "#203E78" 
                );  //print_r($fcmMsg);
                $fcmFields = array(
                    'content_available'=> true,
                    'registration_ids' => $registrationIDs,
                    'priority' => 'high',
                    'notification' => $fcmMsg
                ); 

                $headers = array(
                    'Authorization: key=' . API_ACCESS_KEY,
                    'Content-Type: application/json'
                );
                 
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
                $response2 = curl_exec($ch );
                curl_close( $ch );
                //return $response2;
                //print_r($response2);
            }    
        }
    }
    
    public function vendor_order_update(){  
        $query = "SELECT * FROM ".$this->table_wcpv_commissions." WHERE vendor_id=".$this->data[vid]." AND order_id=".$this->data[order_id]."";
        $stmt = $this->conn->prepare($query);   
        $stmt->execute();  
        if($stmt->rowCount() > 0) {
            if($this->data[status] == "cancel"){
                $fulfillment_status = "unfulfilled";
            } elseif ($this->data[status] == "process") {
                $fulfillment_status = "fulfilled";
            }
            $woocommerce = $this->woocommerce;
            $order_details = ($woocommerce->get('orders/'.$this->data[order_id].''));
            $order = ($woocommerce->get('orders/'.$this->data[order_id].''));
            foreach ($order->{'line_items'} as $line_item) {
                if($line_item->{'product_id'} == $this->data[product_id]) {
                    //print_r($line_item); 
                    $response = wc_update_order_item_meta($line_item->{'id'}, '_fulfillment_status', $fulfillment_status);
                }
            }   //return $response;
        }
    }
	public function vendor_validate() {  
        if ( username_exists( $this->username ) ) {  
            $response['status'] = 'false';
            if ( email_exists( $this->email ) ) {   
                $response['message'] = "username and user email already exist";
            } else {
                $response['message'] = "username already exist";
            }
        }
        elseif ( email_exists( $this->email ) ) { 
            $response['status'] = 'false';  
            $response['message'] = "user email already exist";            
        } elseif ($this->mobile) { 
            $query = "SELECT * FROM ".$this->table_namemeta." WHERE meta_key='billing_phone' AND meta_value='".$this->mobile."'"; 
            $stmt = $this->conn->prepare($query); 
            $stmt->execute();
            if($stmt->rowCount() > 0) { 
                $response['status'] = 'false';  
                $response['message'] = "user mobile number already exist"; 
            } else {
				$response['status'] = 'true';
            	$response['message'] = "user doesn't exist";
			}
        } else { 
            $response['status'] = 'true';
            $response['message'] = "user doesn't exist";            
        }
        return $response;
    }
}
