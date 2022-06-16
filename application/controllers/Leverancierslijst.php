<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leverancierslijst extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('leverancierslijst_m');
        $this->load->model('function_m');
        $this->load->model('calculatie_m');
        $this->load->model('calculatie_re_m');
        $this->load->library("excel");
    }

	public function index()
	{
		$data['primary_menu'] = 'Leverancierslijst';
		$data['leveranciers'] = $this->function_m->get_list("basic_leveranciers", "name");
		$data['locaties'] = $this->function_m->get_list("basic_locatie", "name");
		$data['inkoopcategoriens'] = $this->function_m->get_list("basic_inkoopcategorien", "name");
		$data['eenheids'] = $this->function_m->get_list("basic_eenheid", "name");
		$data['eenhedens'] = $this->function_m->get_list("basic_eenheden", "name");
		$data['kleinstes'] = $this->function_m->get_list("basic_kleinste", "name");
		$data['statiegelds'] = $this->function_m->get_list("basic_statiegeld", "statiegeld");
		
		$this->load->view('header', $data);
		$this->load->view('leverancierslijst', $data);
		$this->load->view('template/footer');
	}

	public function get_list(){
		$list = $this->leverancierslijst_m->get_list("");
		
		$data = [];
		$index = 0;
		
		for($index = 0; $index < count($list); $index++){

			$edit = "<button type='button' class='btn border-info text-info-600 btn-flat btn-icon' onclick='edit_info(" . $list[$index]['id'] . ")' title='edit'><i class='icon-pencil'></i></button>";
			$copy = "<button type='button' class='btn border-primary text-primary btn-flat btn-icon' onclick='copy_info(" . $list[$index]['id'] . ")' title='copy'><i class='icon-copy3'></i></button>";
			$bin = "<button type='button' class='btn border-warning text-warning-600 btn-flat btn-icon' onclick='delete_info(" . $list[$index]['id'] . ")' title='delete'><i class='icon-bin'></i></button>";

			$array_item = array($edit . $copy . $bin, $list[$index]['geef_productnaam'], $list[$index]['leveranciers'],  $list[$index]['locatie'],  $list[$index]['inkoopcategorien'],  $list[$index]['artikelnummer'],  '€  ' . number_format($list[$index]['prijs_van'], 7, ',', '.'),  $list[$index]['aantal_verpakkingen'],  $list[$index]['eenheid'],  '€  ' . number_format($list[$index]['prijs_per'], 7, ',', '.'),  $list[$index]['inhoud_van'],  $list[$index]['eenheden'],  '€  ' . number_format($list[$index]['prijs_per_eenheid'], 7, ',', '.'),  $list[$index]['kleinste'],  $list[$index]['netto_stuks_prijs'],  $list[$index]['verpakking'], $list[$index]['statiegeld']==0 ? '-' : '€  ' . number_format($list[$index]['statiegeld'], 2, ',', '.'));
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

	public function get_info($id){
		$item = $this->leverancierslijst_m->get_item('leverancierslijst', array('id'=>$id));
		$this->generate_json($item);
	}

	public function get_copy_info($id){
		$item = $this->leverancierslijst_m->get_item('leverancierslijst', array('id'=>$id));
		$item['artikelnummer'] = $this->calculatie_re_m->get_max_receptId()['max_recepten_id'] + 1;
		$this->generate_json($item);
	}

	public function get_statiegeld_info($id){
		$item = $this->leverancierslijst_m->get_item('basic_statiegeld', array('id'=>$id));
		$this->generate_json($item);
	}

	public function get_initial_info(){
		$req = $this->input->post();
		$item = $this->leverancierslijst_m->get_item('basic_statiegeld', array('id'=>$req['id']));
		$max_number = $this->calculatie_re_m->get_max_receptId()['max_recepten_id'] + 1;
		$this->generate_json(array('price'=>$item['price'], 'max_number'=>$max_number));
	}

	// 
	public function update_info(){
		$req = $this->input->post();
		if($req['action_type'] == 'add' || $req['action_type'] == 'copy'){
			unset($req['action_type']);
			unset($req['sel_id']);
			$this->leverancierslijst_m->add_item('leverancierslijst', $req);
			$this->generate_json("Added!");
		} else if($req['action_type'] == 'edit'){
			$where['id'] = $req['sel_id'];

			$old_info = $this->leverancierslijst_m->get_item('leverancierslijst', $where);

			// update price of product have this material
			if($old_info['prijs_per_eenheid'] != $req['prijs_per_eenheid']){
				// set new price
				$this->update_product_price($old_info, $req['prijs_per_eenheid'], $req['prijs_van']);

				// re-calculate ticket items(ex: sub_total1, sub_total_2, total, advies_verkoopprijs....)
				$changed_count = $this->recalculate_ticket_info($old_info['id']);
			}


			unset($req['action_type']);
			unset($req['sel_id']);
			$this->leverancierslijst_m->update_item('leverancierslijst', $req, $where);
			$this->generate_json("Udated!");
		}
	}

	private function update_product_price($old_info, $new_price_per, $changed_prijs_van_omdoos){

		$products = $this->leverancierslijst_m->get_list_where('ticket_inkoopartikelens_disposables', array("leverancierslijst_id" => $old_info['id']));

		foreach($products as $item){
			$where['id'] = $item['id'];
			$update_info['eenheid_kleinste'] = $new_price_per;
			$update_info['kostprijs'] = number_format($new_price_per * $item['benodigde_hoeveelheid'], 7);

			$this->leverancierslijst_m->update_item('ticket_inkoopartikelens_disposables', $update_info, $where);
		}

		$products_recepten =  $this->leverancierslijst_m->get_list_where('recepten_ticket_inkoopatikelen_disposable', array("leverancierslijst_id" => $old_info['id']));
		foreach($products_recepten as $item){
			$where['id'] = $item['id'];
			$update_info['eenheid_kleinste'] = $new_price_per;
			$update_info['kostprijs'] = number_format($new_price_per * $item['benodigde'], 7);

			$this->leverancierslijst_m->update_item('recepten_ticket_inkoopatikelen_disposable', $update_info, $where);
		}
	}

	private function recalculate_ticket_info($leverancierslijst_id){

		$changed_count = 0;
		// VERKOOPPRODUCTEN
		// get tickets to be updated
		$ticket_ids = $this->calculatie_m->get_tickets_by_leverancierId($leverancierslijst_id);
		foreach($ticket_ids as $item){

			$ticket_info = $this->calculatie_m->get_detailed_ticket_info($item['ticket_id']);

			$sub_total1 = $this->calculatie_m->get_subTotal($item['ticket_id'], "1")['sub_total'];
			$sub_total2 = $this->calculatie_m->get_subTotal($item['ticket_id'], "2")['sub_total'];
			$total = $sub_total1 + $sub_total2;

			$new_advies_verkoopprijs = $ticket_info['marge1'] * $sub_total1 + $ticket_info['marge2'] * $sub_total2 * $ticket_info['bezorgfee'] * $ticket_info['btw_factor'];

			$chage_rate = abs($new_advies_verkoopprijs - $ticket_info['advies_verkoopprijs']) / $ticket_info['advies_verkoopprijs'] * 100;

			$update_info['advies_verkoopprijs'] = $new_advies_verkoopprijs;
			$update_info['sub_total1'] = $sub_total1;
			$update_info['sub_total2'] = $sub_total2;
			$update_info['total'] = $total;
			$update_info['updated_at'] = date("Y-m-d H:i:s");

			if($chage_rate >= 5){
				$update_info['is_checked'] = "0";
				$update_info['old_price'] = $ticket_info['advies_verkoopprijs'];
				$changed_count++;
			}

			$this->calculatie_m->update_item('ticket', $update_info, array('id'=>$item['ticket_id']));
		}


		// RECEPTEN
		$re_ticket_ids = $this->calculatie_re_m->get_tickets_by_leverancierId($leverancierslijst_id);
		foreach($re_ticket_ids as $item){

			$re_ticket_info = $this->calculatie_re_m->get_item('recepten_ticket', array('id'=>$item['ticket_id']));

			$sub_total1 = $this->calculatie_re_m->get_subTotal($item['ticket_id'], "1")['sub_total'];
			$sub_total2 = $this->calculatie_re_m->get_subTotal($item['ticket_id'], "2")['sub_total'];
			$new_total = $sub_total1 + $sub_total2;


			$chage_rate = abs($new_total - $re_ticket_info['total']) / $re_ticket_info['total'] * 100;

			$re_update_info['sub_total1'] = $sub_total1;
			$re_update_info['sub_total2'] = $sub_total2;
			$re_update_info['total'] = $new_total;
			$re_update_info['updated_at'] = date("Y-m-d H:i:s");

			if($chage_rate >= 5){
				$re_update_info['is_checked'] = "0";
				$re_update_info['old_price'] = $re_ticket_info['total'];

				$changed_count++;
			}

			$this->calculatie_re_m->update_item('recepten_ticket', $re_update_info, array('id'=>$item['ticket_id']));
		}
		return $changed_count;

	}

	public function delete_info($id){
		$this->leverancierslijst_m->delete_item('leverancierslijst', array('id'=>$id));
		$this->generate_json("Deleted!");
	}

	public function excel(){
		$year = $this->input->post('m_year');
		$list = $this->leverancierslijst_m->get_list("");

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
        	if($row['jaar'] == $year){

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

	            $excel_row++;
	        }
        }

        // $object->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $year . '_Benodigde informatie voor voorraad telling.xlsx"');

        header('Cache-Control: max-age=0');

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
        // ob_end_clean();
        $object_writer->save('php://output');
        exit;
	}

	public function upload_data_from_excel(){
		$inputFileName = APPPATH . 'data.xlsx';
		$object = PHPExcel_IOFactory::load($inputFileName);
		foreach($object->getWorksheetIterator() as $worksheet){
			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();
			for($row = 2; $row <= $highestRow; $row++){
				$item['id'] = $row - 1;
				$item['geef_productnaam'] = $worksheet->getCellByColumnAndRow(0, $row)->getValue();

				$leveranciersnaam = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
				$temp = $this->function_m->get_item("basic_leveranciers", array("name"=>$leveranciersnaam));
				if(!empty($temp))
					$item['leveranciers_id'] = $temp['id'];

				$locatie = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
				$temp = $this->function_m->get_item("basic_locatie", array("name"=>$locatie));
				if(!empty($temp))
					$item['locatie_id'] = $temp['id'];

				$inkoopcategorien = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
				$temp = $this->function_m->get_item("basic_inkoopcategorien", array("name"=>$inkoopcategorien));
				if(!empty($temp))
					$item['inkoopcategorien_id'] = $temp['id'];
				

				$item['artikelnummer'] = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
				$item['prijs_van'] = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
				$item['aantal_verpakkingen'] = $worksheet->getCellByColumnAndRow(6, $row)->getValue();

				$eenheid = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
				$temp = $this->function_m->get_item("basic_eenheid", array("name"=>$eenheid));
				if(!empty($temp)){
					$item['eenheid_id'] = $temp['id'];
				}				

				$item['prijs_per'] = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
				$item['inhoud_van'] = $worksheet->getCellByColumnAndRow(9, $row)->getValue();

				$eenheden = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
				if(!empty($eenheden))
					$item['eenheden_id'] = $this->function_m->get_item("basic_eenheden", array("name"=>$eenheden))['id'];

				$item['prijs_per_eenheid'] = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

				$kleinste = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
				$temp = $this->function_m->get_item("basic_kleinste", array("name"=>$kleinste));
				if(!empty($temp))
					$item['kleinste_eenheid_id'] = $temp['id'];

				$item['netto_stuks_prijs'] = $worksheet->getCellByColumnAndRow(13, $row)->getValue();

				$statiegeld = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
				$statiegeld_info = $this->function_m->get_item("basic_statiegeld", array("statiegeld"=>$statiegeld));
				if(!empty($statiegeld_info)){
					$item['statiegeld_id'] = $statiegeld_info['id'];
					$item['statiegeld_price'] = $statiegeld_info['price'];
				}

				$data[] = $item;
			}
		}

		$count = $this->leverancierslijst_m->add_list('leverancierslijst', $data);
		$this->generate_json($count);
	}
}
?>