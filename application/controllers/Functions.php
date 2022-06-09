<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'controllers/Base_Controller.php';

class Functions extends Base_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('function_m');
    }

	public function index() {
		$data['primary_menu'] = 'Functions';
		$leveranciernaam = $this->function_m->get_list('basic_leveranciernaam');
		if(count($leveranciernaam) > 0){
			$data['leveranciernaam'] = $leveranciernaam[0];
		}
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/functions', $data);
		$this->load->view('template/footer');
	}

	public function update_leveranciernaam(){
		$req = $this->input->post();
		$info = $this->function_m->get_list('basic_leveranciernaam');
		if(count($info) > 0){
			$where['id'] = $info[0]['id'];
			$this->function_m->update_item('basic_leveranciernaam', $req, $where);
			$this->generate_json("Updated!");
		}else{
			$this->function_m->add_item('basic_leveranciernaam', $req);
			$this->generate_json("Added!");
		}
	}

	public function get_eenhedens(){
		$list = $this->function_m->get_list('basic_eenheden', "name");

		$data = [];
		
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_basic_item(\"eenheden\", \"" . $list[$index]['name'] . "\", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_basic_item(\"eenheden\", " . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['name'], $edit . $bin);

			$data[] = $array_item;
		}

		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );

	    echo json_encode($result);
	    exit();
	}

	public function get_kleinste(){
		$list = $this->function_m->get_list('basic_kleinste', "name");

		$data = [];
		
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_basic_item(\"kleinste\", \"" . $list[$index]['name'] . "\", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_basic_item(\"kleinste\", " . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['name'], $edit . $bin);

			$data[] = $array_item;
		}

		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );

	    echo json_encode($result);
	    exit();
	}

	public function get_leveranciers(){
		$list = $this->function_m->get_list('basic_leveranciers', "name");

		$data = [];		
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_basic_item(\"leveranciers\", \"" . $list[$index]['name'] . "\", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_basic_item(\"leveranciers\", " . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['name'], $edit . $bin);

			$data[] = $array_item;
		}
		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );
	    echo json_encode($result);
	    exit();
	}

	public function get_inkoopcategorien(){
		$list = $this->function_m->get_list('basic_inkoopcategorien', "name");

		$data = [];
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_basic_item(\"inkoopcategorien\", \"" . $list[$index]['name'] . "\", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_basic_item(\"inkoopcategorien\", " . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['name'], $edit . $bin);

			$data[] = $array_item;
		}
		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );
	    echo json_encode($result);
	    exit();
	}

	public function get_margegroepen(){
		$list = $this->function_m->get_list('basic_margegroepen', "margegroepen");

		$data = [];
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_margegroepen(\"" . $list[$index]['margegroepen'] . "\", " . $list[$index]['marge'] . ", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_margegroepen(" . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['margegroepen'], $list[$index]['marge'], $edit . $bin);

			$data[] = $array_item;
		}
		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );
	    echo json_encode($result);
	    exit();
	}

	public function get_verkoopgroepen(){
		$list = $this->function_m->get_list('basic_verkoopgroepen', 'name');

		$data = [];
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_basic_item(\"verkoopgroepen\", \"" . $list[$index]['name'] . "\", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_basic_item(\"verkoopgroepen\", " . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['name'], $edit . $bin);

			$data[] = $array_item;
		}
		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );
	    echo json_encode($result);
	    exit();
	}

	public function get_btw(){
		$list = $this->function_m->get_list('basic_btw', 'btw');

		$data = [];
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_btw(" . $list[$index]['btw'] . ", " . $list[$index]['btw_factor'] . ", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_btw(" . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['btw'], $list[$index]['btw_factor'], $edit . $bin);

			$data[] = $array_item;
		}
		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );
	    echo json_encode($result);
	    exit();
	}

	public function get_omzetgroepen(){
		$list = $this->function_m->get_list('basic_omzetgroepen', 'name');

		$data = [];
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_basic_item(\"omzetgroepen\", \"" . $list[$index]['name'] . "\", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_basic_item(\"omzetgroepen\", " . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['name'], $edit . $bin);

			$data[] = $array_item;
		}
		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );
	    echo json_encode($result);
	    exit();
	}

	public function get_bezorging(){
		$list = $this->function_m->get_list('basic_bezorging', 'bezorging');

		$data = [];
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_bezorging(\"" . $list[$index]['bezorging'] . "\", " . $list[$index]['bezorgfee'] . ", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_bezorging(" . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['bezorging'], $list[$index]['bezorgfee'], $edit . $bin);

			$data[] = $array_item;
		}
		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );
	    echo json_encode($result);
	    exit();
	}

	public function get_statiegeld(){
		$list = $this->function_m->get_list('basic_statiegeld', 'statiegeld');

		$data = [];
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_statiegeld(\"" . $list[$index]['statiegeld'] . "\", " . $list[$index]['price'] . ", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_statiegeld(" . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['statiegeld'], '€  ' . number_format($list[$index]['price'], 2, ',', '.'), $edit . $bin);

			$data[] = $array_item;
		}
		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );
	    echo json_encode($result);
	    exit();
	}

	public function get_eenheid(){
		$list = $this->function_m->get_list('basic_eenheid', 'name');

		$data = [];
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_basic_item(\"eenheid\", \"" . $list[$index]['name'] . "\", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_basic_item(\"eenheid\", " . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['name'], $edit . $bin);

			$data[] = $array_item;
		}
		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );
	    echo json_encode($result);
	    exit();
	}

	public function get_locatie(){
		$list = $this->function_m->get_list('basic_locatie', 'name');

		$data = [];
		for($index = 0; $index < count($list); $index++){
			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_basic_item(\"locatie\", \"" . $list[$index]['name'] . "\", " . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon position-right' onclick='delete_basic_item(\"locatie\", " . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";
			$array_item = array($index + 1, $list[$index]['name'], $edit . $bin);

			$data[] = $array_item;
		}
		$result = array(      
	        "recordsTotal" => count($list),
	        "recordsFiltered" => count($list),
	        "data" => $data
	    );
	    echo json_encode($result);
	    exit();
	}



	public function submit_basic_info(){
		$req = $this->input->post();
		switch($req['action_type']){
			case 'add':
				$info['name'] = $req['name'];
				$exist_item = $this->function_m->get_item('basic_' . $req['table_name'], $info);
				if($exist_item){
					$this->generate_json("This value already exists", false);
					return;
				}

				$this->function_m->add_item('basic_' . $req['table_name'], $info);
				$this->generate_json("Saved");
				break;
			case 'edit':
				$where['id'] = $req['sel_id'];
				$info['name'] = $req['name'];

				$exist_item = $this->function_m->get_item('basic_' . $req['table_name'], $info);
				if($exist_item){
					$this->generate_json("This value already exists", false);
					return;
				}


				$this->function_m->update_item('basic_' . $req['table_name'], $info, $where);
				$this->generate_json("Updated");
				break;
		}
	}

	public function submit_margegroepen(){
		$req = $this->input->post();
		switch($req['action_type']){
			case 'add':
				$info['margegroepen'] = $req['margegroepen'];
				$exist_item = $this->function_m->get_item('basic_margegroepen', $info);
				if($exist_item){
					$this->generate_json("This value already exists", false);
					return;
				}

				$info['marge'] = $req['marge'];
				$this->function_m->add_item('basic_margegroepen', $info);
				$this->generate_json("Saved");
				break;
			case 'edit':
				$where['id'] = $req['sel_id'];
				$info['margegroepen'] = $req['margegroepen'];
				$info['marge'] = $req['marge'];

				$this->function_m->update_item('basic_margegroepen', $info, $where);
				$this->generate_json("Updated");
				break;
		}
	}

	public function submit_btw(){
		$req = $this->input->post();
		switch($req['action_type']){
			case 'add':
				$info['btw'] = $req['btw'];
				$exist_item = $this->function_m->get_item('basic_btw', $info);
				if($exist_item){
					$this->generate_json("This value already exists", false);
					return;
				}

				$info['btw_factor'] = $req['btw_factor'];
				$this->function_m->add_item('basic_btw', $info);
				$this->generate_json("Saved");
				break;
			case 'edit':
				$where['id'] = $req['sel_id'];
				$info['btw'] = $req['btw'];
				$info['btw_factor'] = $req['btw_factor'];

				$this->function_m->update_item('basic_btw', $info, $where);
				$this->generate_json("Updated");
				break;
		}
	}

	public function submit_bezorging(){
		$req = $this->input->post();
		switch($req['action_type']){
			case 'add':
				$info['bezorging'] = $req['bezorging'];
				$exist_item = $this->function_m->get_item('basic_bezorging', $info);
				if($exist_item){
					$this->generate_json("This value already exists", false);
					return;
				}

				$info['bezorgfee'] = $req['bezorgfee'];
				$this->function_m->add_item('basic_bezorging', $info);
				$this->generate_json("Saved");
				break;
			case 'edit':
				$where['id'] = $req['sel_id'];
				$info['bezorging'] = $req['bezorging'];
				$info['bezorgfee'] = $req['bezorgfee'];

				$this->function_m->update_item('basic_bezorging', $info, $where);
				$this->generate_json("Updated");
				break;
		}
	}

	public function submit_statiegeld(){
		$req = $this->input->post();
		switch($req['action_type']){
			case 'add':
				$info['statiegeld'] = $req['statiegeld'];
				$exist_item = $this->function_m->get_item('basic_statiegeld', $info);
				if($exist_item){
					$this->generate_json("This value already exists", false);
					return;
				}

				$info['price'] = $req['price'];
				$this->function_m->add_item('basic_statiegeld', $info);
				$this->generate_json("Saved");
				break;
			case 'edit':
				$where['id'] = $req['sel_id'];
				$info['statiegeld'] = $req['statiegeld'];
				$info['price'] = $req['price'];

				$this->function_m->update_item('basic_statiegeld', $info, $where);
				$this->generate_json("Updated");
				break;
		}
	}



	public function delete_basic_info(){
		$req = $this->input->post();

		$this->function_m->delete_item('basic_' . $req['table_name'], array('id'=>$req['sel_id']));
		$this->generate_json("Deleted!");
	}

	public function delete_margegroepen(){
		$req = $this->input->post();

		$this->function_m->delete_item('basic_margegroepen', array('id'=>$req['sel_id']));
		$this->generate_json("Deleted!");
	}

	public function delete_btw(){
		$req = $this->input->post();

		$this->function_m->delete_item('basic_btw', array('id'=>$req['sel_id']));
		$this->generate_json("Deleted!");
	}

	public function delete_bezorging(){
		$req = $this->input->post();

		$this->function_m->delete_item('basic_bezorging', array('id'=>$req['sel_id']));
		$this->generate_json("Deleted!");
	}

	public function delete_statiegeld(){
		$req = $this->input->post();

		$this->function_m->delete_item('basic_statiegeld', array('id'=>$req['sel_id']));
		$this->generate_json("Deleted!");
	}
}
