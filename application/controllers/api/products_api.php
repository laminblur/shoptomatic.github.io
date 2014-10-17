<?php
require APPPATH.'/libraries/REST_Controller.php';
class products_api extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->helper('Ultils');
	}

	function products_get() 
	{ 
		$first=$this->input->get('first');
		$offset=$this->input->get('offset');
		$where=array();
		$like=array();
		$order=array('products.updated_at'=>'DESC');
		$categories_id=$this->input->get('categories_id');
		$sort_by=$this->input->get('sort_by');
		$where['users.activated']=1;
		$where['products.activated']=1;

		if($categories_id!=null){
			$where['categories_id']=$categories_id;
		}

		$county_id=$this->input->get('county_id');
		if($county_id!=null){
			$where['products.county_id']=$county_id;
		}

		$cities_id=$this->input->get('cities_id');
		if($cities_id!=null){
			$where['products.cities_id']=$cities_id;
		}

		$product_id=$this->input->get('product_id');
		if($product_id!=null){
			$where['products.id']=$product_id;	
		}

		if($sort_by!=null){
			$order=array('products.updated_at'=>$sort_by);
		}

		$id=$this->input->get('pull');
		if($id!=null){
			$product=$this->product_model->get_by_id($id);
			$where['products.id <>']=$id;
			$where['products.created_at >=']= date('Y-m-d H:i:s',strtotime($product[0]->created_at));
		}

		$title=$this->input->get('title');
		if($title!=null){
			$like['title']=$title;
		}

		$aim=$this->input->get('aim');
		if($aim!=null){
			$where['aim']=$aim;
		}
		$select='*,
		products.created_at as created_at,
		products.updated_at as updated_at,
		categories.name as categories_name,
		county.name as county_name,
		cities.name as cities_name,
		products.id as id,
		products.user_id as user_id';

		$user_id=$this->input->get('user_id');
		if($user_id!=null){
			$where['products.user_id']=$user_id;
		}
		$data=$this->product_model->get($select,$where,$like,$first,$offset,$order);
		if($data!=null){
			$this->response($data); 
		}else{
			$this->response(array("empty"=>1));
		}
	} 



	function products_post() 
	{ 
		$title=preg_replace('/[\r\n]+/', "", $this->post('title')); 
		$content=preg_replace('/[\r\n]+/', "", $this->input->post('content'));
		$price=$this->post('price');
		$aim=$this->post('aim');
		$categories=$this->post('categories');
		$county=$this->post('county');
		$fb_id=$this->post('fb_id');
		$user_id=$this->post('user_id');
		$city=$this->post('city');
		$images=array();
		$data=array(
			'title'=>$title,
			'price'=>$price,
			'content'=>$content,
			'aim'=>$aim,
			'categories_id'=>$categories,
			'county_id'=>$county,
			'fb_id'=>$fb_id,
			'user_id'=>$user_id,
			'cities_id'=>$city
			);

		$insert_id = $this->product_model->insert($data);
		$image_path=null;
		$thumb=null;

		if(isset($_FILES)){
			$config['upload_path'] = 'uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg|JPG|JPEG|GIF|PNG';
			$config['max_size']	= '2000';
			$this->load->library('upload', $config);
		}

		if(isset($_FILES['photo1'])){
			$filename=$_FILES['photo1']['name'];
			$_FILES['photo1']['name']=rename_upload_file($filename);	
			if ($this->upload->do_upload('photo1'))
			{
				$image_path='uploads/'.$_FILES['photo1']['name'];
				$thumb=$_FILES['photo1']['name'];
				array_push($images, $image_path);
			}
		}

		if(isset($_FILES['photo2'])){
			$filename=$_FILES['photo2']['name'];
			$_FILES['photo2']['name']=rename_upload_file($filename);	
			if ($this->upload->do_upload('photo2'))
			{
				$image_path ='uploads/'.$_FILES['photo2']['name'];
				$thumb=$_FILES['photo2']['name'];
				array_push($images, $image_path);
			}
		}

		if(isset($_FILES['photo3'])){
			$filename=$_FILES['photo3']['name'];
			$_FILES['photo3']['name']=rename_upload_file($filename);	
			if ($this->upload->do_upload('photo3'))
			{

				$image_path= 'uploads/'.$_FILES['photo3']['name'];
				$thumb=$_FILES['photo3']['name'];
				array_push($images,$image_path);

			}
		}


		if($insert_id!=0){
			if($image_path!=null){
				$config=array(
                            "source_image" => $image_path, //get original image
							"new_image" =>  "uploads/thumbs", //save as new image //need to create thumbs first
							"maintain_ratio" => true,
							"width" => 200,
							"height" => 200
							);
				$this->load->library('image_lib',$config);
				$this->image_lib->resize();
				$thumb= 'uploads/thumbs/'.$thumb;
				$this->product_model->update(array('image_path'=>$thumb), array('id'=>$insert_id));	
				$this->load->model('images_model');
				for ($i=0; $i < count($images); $i++) { 
					$this->images_model->insert(array('path'=>$images[$i],'product_id'=>$insert_id));
				}
			}
			$this->response(array('ok'=>'1'));
		}else{
			$this->response(array('ok'=>'0'));
		}
	} 

	function products_put() 
	{ 
		$data = array('this not available'); 
		$this->response($data); 
	} 

	function products_delete() 
	{ 
		$data = array('this not available');
		$this->response($data); 
	}  

	function update_post(){
		$user_id=$this->post('user_id');
		if($user_id!=null){
			$query= 'update products SET updated_at="'.date('Y-m-d H:i:s',time()).'" where DATE(updated_at) < "'.date('Y-m-d',time()).'" AND user_id='.$user_id;
			$this->product_model->update_query($query);
		}
	}

	function rate_post(){
		$user_id=$this->post('user_id');
		$product_id=$this->post('product_id');
		$point=$this->post('point');
		if($user_id!=null && $product_id!=null){
			$this->load->model('rating_model');
			$rate=$this->rating_model->get_by_user_id_and_product_id($user_id,$product_id);
			if($rate!=null){
				$where['user_id']=$user_id;
				$where['product_id']=$product_id;
				$this->rating_model->update(array('point'=>$point), $where);
			}else{
				$this->rating_model->insert(array('point'=>$point,'user_id'=>$user_id,'product_id'=>$product_id));
			}
			$rating=$this->rating_model->get_rate_by_product_id($product_id);
			//$this->product_model->update(array('rate'=>$rating),array('id'=>$product_id));
		}
	}

	function avg_rate_get(){
		$product_id=$this->get('product_id');
		$this->load->model('rating_model');
		$rating = $this->rating_model->get_rate_by_product_id($product_id);
		if($rating!=null){
			$this->response(array("rating"=>$rating));
		}else{
			$this->response(array("rating"=>null));
		}
	}

	function delete_post(){
		if(isset($_POST['id'])){
			$id=$this->input->post('id');
			$product=$this->product_model->get_by_id($id);
			if($product!=null){
				$this->load->model('images_model');
				$images=$this->images_model->get_by_product_id($id);
				foreach ($images as $r) {
					try {
						unlink($r->path);
						$this->images_model->remove_by_id($r->id);
					} catch (Exception $e) {
						
					}
				}
				try {
					unlink($product[0]->image_path);
				} catch (Exception $e) {
					
				}
			}
			$this->product_model->remove_by_id($id);
		}
	}
}
?>