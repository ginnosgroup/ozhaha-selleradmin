<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Croppic_crop_img extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

	public function index()
	{
		$this->load->library('session');
		$this->load->library('waimai_seller');
		$seller_id =  $this->waimai_seller->check_login();
		$post_data =     $this->input->post(NULL,true);
		$imgUrl = $post_data['imgUrl'];
		//echo $imgUrl;
		// original sizes
		$imgInitW =  $post_data['imgInitW'];
		$imgInitH =   $post_data['imgInitH'];
		// resized sizes
		$imgW = $post_data['imgW'];
		$imgH = $post_data['imgH'];
		// offsets
		$imgY1 = $post_data['imgY1'];
		$imgX1 = $post_data['imgX1'];
		// crop box
		$cropW = $post_data['cropW'];
		$cropH = $post_data['cropH'];
		// rotation angle
		$angle = $post_data['rotation'];
		$jpeg_quality = 100;



		if ($imgUrl)
		{
			$output_filename = dirname($imgUrl)."/croppedImg".rand();
			$what =         getimagesize($imgUrl);
			switch(strtolower($what['mime']))
			{
				case 'image/png':
				$img_r = imagecreatefrompng($imgUrl);
				$source_image = imagecreatefrompng($imgUrl);
				$type = '.png';
				break;
				case 'image/jpeg':
				$img_r = imagecreatefromjpeg($imgUrl);
				$source_image = imagecreatefromjpeg($imgUrl);
				error_log("jpg");
				$type = '.jpeg';
				break;
				case 'image/gif':
				$img_r = imagecreatefromgif($imgUrl);
				$source_image =  imagecreatefromgif($imgUrl);
				$type =   '.gif';
				break;
				default: die('image type not supported');
			}


		 // resize the original image to size of editor
			$resizedImage = imagecreatetruecolor($imgW, $imgH);
			imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
    // rotate the rezized image
			$rotated_image = imagerotate($resizedImage, -$angle, 0);
    // find new width & height of rotated image
			$rotated_width = imagesx($rotated_image);
			$rotated_height = imagesy($rotated_image);
    // diff between rotated & original sizes
			$dx = $rotated_width - $imgW;
			$dy = $rotated_height - $imgH;
    // crop rotated image to fit into original rezized rectangle
			$cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
			imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
			imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
	// crop image into selected area
			$final_image = imagecreatetruecolor($cropW, $cropH);
			imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
			imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
	// finally output png image
	//imagepng($final_image, $output_filename.$type, $png_quality);
			imagejpeg($final_image, $output_filename.$type, $jpeg_quality);
			
			if ($imgUrl && file_exists('./'.$imgUrl)) unlink('./'.$imgUrl);
				$response = Array(
					"status" => 'success',
					"url" => $output_filename.$type
					);

				exit(json_encode($response));
			}

		}

	}
	?>