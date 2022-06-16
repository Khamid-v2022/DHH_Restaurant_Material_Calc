<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voorraadtelling extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('leverancierslijst_m');
        $this->load->model('voorraadtelling_m');
        $this->load->model('function_m');
        $this->load->library("excel");
    }

	public function index()
	{
		$data['primary_menu'] = 'Voorraadtelling';
		$data['statiegelds'] = $this->function_m->get_list("basic_statiegeld", "statiegeld");
		$data['locaties'] = $this->function_m->get_list("basic_locatie", "name");
		
		$this->load->view('header', $data);
		$this->load->view('voorraadtelling', $data);
		$this->load->view('template/footer');
	}

	public function get_list(){
		$list = $this->voorraadtelling_m->get_list("voorraadtelling");
		
		$data = [];
		$index = 0;
		
		for($index = 0; $index < count($list); $index++){
			// $stock = "<button type='button' class='btn border-success text-success btn-flat btn-icon' onclick='stock_modal(" . $list[$index]['id'] . ")' title='voorraadtelling'><i class='icon-calendar2'></i></button>";

			$export = "<button type='button' class='btn border-success text-success btn-flat btn-icon' onclick='excel_download(" . $list[$index]['id'] . ")' title='excel download'><i class='icon-file-excel'></i></button>";
			$edit = "<button type='button' class='btn border-info text-info btn-flat btn-icon' onclick='edit_item(" . $list[$index]['id'] . ", \"" . $list[$index]['name']  . "\")' title='edit'><i class='icon-pencil'></i></button>";
			$start = "<button type='button' class='btn border-primary text-primary btn-flat btn-icon' onclick='start_item(" . $list[$index]['id'] . ")' title='start'><i class='icon-play4'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon' onclick='delete_item(" . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";

			$name = "<a href='" . base_url() . "voorraadtelling/view_sel_leverancierslijst/" . $list[$index]['id'] . "'>" . $list[$index]['name'] . "</a>";

			$array_item = array( $name, $export . $edit . $start . $bin);
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

	public function update_info(){
		$req = $this->input->post();
		switch($req['action_type']){
			case 'add':
				$info['name'] = $req['name'];
				$exist_item = $this->voorraadtelling_m->get_item('voorraadtelling', $info);
				if($exist_item){
					$this->generate_json("This value already exists", false);
					return;
				}

				$added_id = $this->voorraadtelling_m->add_item('voorraadtelling', $info);
				
				$leve_list = $this->voorraadtelling_m->get_list("leverancierslijst");
				for($i = 0; $i < count($leve_list); $i++){
					$leve_list[$i]['voorraadtelling_id'] = $added_id;
					$leve_list[$i]['main_leverancierslijst_id'] = $leve_list[$i]['id'];
					$leve_list[$i]['statiegeld_los'] = $leve_list[$i]['statiegeld_id'];
					$leve_list[$i]['statiegeld_eenheid2'] = $leve_list[$i]['statiegeld_price'];
					unset($leve_list[$i]['id']);
				}

				$this->voorraadtelling_m->add_list("leverancierslijst_copy", $leve_list);

				$this->generate_json("Saved");
				break;
			case 'edit':
				$where['id'] = $req['sel_id'];
				$info['name'] = $req['name'];

				$exist_item = $this->voorraadtelling_m->get_item('voorraadtelling', $info);
				if($exist_item){
					$this->generate_json("This value already exists", false);
					return;
				}


				$this->voorraadtelling_m->update_item('voorraadtelling', $info, $where);
				$this->generate_json("Updated");
				break;
		}
	}

	public function get_statiegeld_info($id){
		$item = $this->voorraadtelling_m->get_item('basic_statiegeld', array('id'=>$id));
		$this->generate_json($item);
	}

	public function delete_item($id){
		$this->voorraadtelling_m->delete_item('leverancierslijst_copy', array("voorraadtelling_id"=>$id));
		$this->voorraadtelling_m->delete_item("voorraadtelling", array("id"=>$id));
		$this->generate_json("");
	}

	public function excel($id){
		$name = $this->voorraadtelling_m->get_item("voorraadtelling", array("id"=>$id))['name'];
		$list = $this->voorraadtelling_m->get_list_join("", "geef_productnaam ASC", array('voorraadtelling_id'=>$id));

        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        
        $table_columns = array("Productnaam", "Leveranciersnaam", "Bar/Keuken/Kantoor", "Inkoopcategorie", "Artikelnummer leverancier", "Prijs van omdoos", "Aantal verpakkingen in omdoos", "Eenheid van verpakking", "Statiegeld groep", "Inhoud van verpakking", "Eenheid KG/L/Stuks", "Prijs per eenheid in verpakking", "Kleinste eenheid", "Netto Stuks prijs", "Verpakking", "Statiegeld", "Waarde voorraad", "Waarde statiegeld", "Aantal geteld");

        $column = 0;

        foreach($table_columns as $field)
        {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $excel_row = 2;

        foreach($list as $row)
        {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row['geef_productnaam']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['leveranciers']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['locatie']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['inkoopcategorien']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row['artikelnummer']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row['prijs_van']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row['aantal_verpakkingen']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row['eenheid']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row['prijs_per']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row['inhoud_van']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row['eenheden']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row['prijs_per_eenheid']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row['kleinste']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row['netto_stuks_prijs']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row['verpakking']);
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $row['statiegeld']);

            $voorraad = '';
			$statiegeld = '';
			if($row['aantal_geteld'] > 0 && $row['statiegeld'] && $row['inhoud_van']){
				$voorraad = number_format($row['aantal_geteld'] * $row['statiegeld'] * $row['inhoud_van'], 7);
			}
			if($row['aantal_geteld'] > 0 && $row['statiegeld']){
				$statiegeld = number_format($row['aantal_geteld'] * $row['statiegeld'], 7);
			}
			$object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $voorraad);
			$object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $statiegeld);
            $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, $row['aantal_geteld']);
            $excel_row++;
        }

        // $object->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '_Benodigde informatie voor voorraad telling.xlsx"');

        header('Cache-Control: max-age=0');

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
        // ob_end_clean();
        $object_writer->save('php://output');
        exit;
	}



	public function view_sel_leverancierslijst($id){
		$data['primary_menu'] = 'Edit Voorraadtelling';

		$data['statiegelds'] = $this->function_m->get_list("basic_statiegeld", "statiegeld");
		$data['sel_id'] = $id;
		
		$this->load->view('header', $data);
		$this->load->view('sel_leverancierslijst', $data);
		$this->load->view('template/footer');
	}

	public function get_leverancierslijst($id){
		// $list = $this->voorraadtelling_m->get_list_join("", "geef_productnaam ASC", array('voorraadtelling_id'=>$id));
		$list = $this->voorraadtelling_m->get_stock_list($id);
		$data = [];
		$index = 0;
		
		for($index = 0; $index < count($list); $index++){
			// if($list[$index]['aantal_geteld'] > 0 && $list[$index]['statiegeld']){
			// 	$statiegeld = number_format($list[$index]['aantal_geteld'] * $list[$index]['statiegeld'], 7);
			// }

			$stock = "<button type='button' class='btn border-success text-success btn-flat btn-icon' onclick='stock_modal(" . $list[$index]['id'] . ")' title='voorraadtelling'><i class='icon-calendar2'></i></button>";
			
			$additional_stock = "<button type='button' class='btn border-info text-info btn-flat btn-icon' onclick='stock_modal(" . $list[$index]['id'] . ")' title='voorraadtelling'><i class='icon-calendar2'></i></button>";
			$actions = $stock;
			if($list[$index]['num'] > 0){
				$actions = $stock . $additional_stock;
			}

			$link = "<a href='" . site_url() . "voorraadtelling/view_stock_history/" . $list[$index]['id'] . "' target='_black'>" . $list[$index]['geef_productnaam'] . "</a>";

			$array_item = array($actions, $link, $list[$index]['leveranciers'],  $list[$index]['locatie'],  $list[$index]['inkoopcategorien'],  $list[$index]['artikelnummer'],  '€  ' . number_format($list[$index]['prijs_van'], 7, ',', '.'),  $list[$index]['aantal_verpakkingen'],  $list[$index]['eenheid'],  '€  ' . number_format($list[$index]['prijs_per'], 7, ',', '.'),  $list[$index]['inhoud_van'],  $list[$index]['eenheden'],  '€  ' . number_format($list[$index]['prijs_per_eenheid'], 7, ',', '.'),  $list[$index]['kleinste'],  $list[$index]['netto_stuks_prijs'],  $list[$index]['verpakking'], $list[$index]['statiegeld']==0 ? '-' : '€  ' . number_format($list[$index]['statiegeld'], 2, ',', '.'), $list[$index]['waarde_voorraad'], $list[$index]['waarde_voorraad2'], $list[$index]['waard_statiegeld'], $list[$index]['aantal_omdozen'], $list[$index]['aantal_geteld']);
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

	public function get_leverancierslijst_by_locatie($voorraadtelling_id, $locatie_id){
		// $list = $this->voorraadtelling_m->get_list_where("leverancierslijst_copy", array("voorraadtelling_id"=>$voorraadtelling_id, "locatie_id" => $locatie_id));

		$list = $this->voorraadtelling_m->get_stock_list_by_location($voorraadtelling_id, $locatie_id);

		$data = [];
		$index = 0;
		
		for($index = 0; $index < count($list); $index++){

			$stock = "<button type='button' class='btn border-success text-success btn-flat btn-icon' onclick='stock_modal(" . $list[$index]['id'] . ")' title='voorraadtelling'><i class='icon-calendar2'></i></button>";
			
			$additional_stock = "<button type='button' class='btn border-info text-info btn-flat btn-icon' onclick='stock_modal(" . $list[$index]['id'] . ")' title='voorraadtelling'><i class='icon-calendar2'></i></button>";
			$actions = $stock;
			if($list[$index]['num'] > 0){
				$actions = $stock . $additional_stock;
			}

			$link = "<a href='" . site_url() . "voorraadtelling/view_stock_history/" . $list[$index]['id'] . "' target='_black'>" . $list[$index]['geef_productnaam'] . "</a>";

			$array_item = array($actions, $link);
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

	public function get_copy_info($id){
		$info = $this->voorraadtelling_m->get_item('leverancierslijst_copy', array('id' => $id));
		$this->generate_json($info);
	}

	// public function save_copy_stock(){
	// 	$req = $this->input->post();
		
	// 	$where['id'] = $req['id'];
	// 	$this->voorraadtelling_m->update_item('leverancierslijst_copy', $req, $where);
	// 	$this->generate_json("");
	// }

	public function save_copy_stock(){
		$req = $this->input->post();
		$req['created_at'] = date("Y-m-d H:i:s");
		$this->voorraadtelling_m->add_item('voorraadteling_puchase', $req);
		$this->generate_json("");
	}



	// Stock History
	public function view_stock_history($id){
		$data['primary_menu'] = 'Voorraadtelling History';

		$data['product_info'] = $this->voorraadtelling_m->get_item('leverancierslijst_copy', array('id' => $id));
		$data['sel_id'] = $id;
		$data['statiegelds'] = $this->function_m->get_list("basic_statiegeld", "statiegeld");
		$data['locaties'] = $this->function_m->get_list("basic_locatie", "name");
		
		$this->load->view('header', $data);
		$this->load->view('stock_history', $data);
		$this->load->view('template/footer');
	}

	public function get_stock_histories($leve_copy_id){
		$list = $this->voorraadtelling_m->get_stock_histories($leve_copy_id);

		$data = [];
		$index = 0;
		
		for($index = 0; $index < count($list); $index++){

			$item = $list[$index];
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon' onclick='delete_item(" . $item['id'] . ")' title='delete'><i class='icon-bin'></i></button>";

			$array_item = array($bin, $item['created_at'], $item['inhoud_van'], $item['prijs_van'], $item['statiegeld'], $item['statiegeld_price'], $item['aantal_omdozen'], $item['lege_omdozen'], $item['omdoos_statiegeld_totaal'], $item['statiegeld2'], $item['statiegeld_eenheid2'], $item['losse_geteld'], $item['lege_geteld'], $item['statiegeld_losse_flessen'], $item['prijs_per_eenheid'], $item['waard_statiegeld'], $item['waarde_voorraad'], $item['waarde_voorraad2']);
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

	public function delete_stock_history($history_id){
		$this->voorraadtelling_m->delete_item('voorraadteling_puchase', array("id"=>$history_id));
		$this->generate_json("");
	}
}
?>