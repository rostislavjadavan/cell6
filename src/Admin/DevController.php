<?php

namespace Admin;

class DevController extends \System\MVC\Controller {

	protected $db;

	function __construct(\Database\DB $db) {
		$this->db = $db;
	}

	public function index() {
		$gridData = $this->db->select('users')->fetchAll();

		$grid = new \Gui\Grid();
		$grid->add(new \Gui\Grid\Button('', 'Edit'))->setId('edit-btn-{id}');
		$grid->add(new \Gui\Grid\Link('', 'Preview link'))->setLink('http://localhost/{id}');
		$grid->add(new \Gui\Grid\Text('ID', '{id}'));
		$grid->add(new \Gui\Grid\Text('User', '{username} ({email})'));

		$data = array('pageTitle' => 'Dev', 'out' => $grid->render($gridData));
		return $this->template('\Admin\views\dev', '\Admin\templates\main', $data);
	}

	public function grid() {

		$grid = new \Gui\Grid('users', new \Gui\Data\DataSourceDatabase($this->db->select('users')));
		$grid->add(new \Gui\Grid\Button('', 'Edit'))->setId('edit-btn-{id}');
		$grid->add(new \Gui\Grid\Text('ID', '{id}'));
		$grid->add(new \Gui\Grid\Text('User', '{member_nick} ({email})'));

		/*$grid2 = new \Gui\Grid('shop_orders', new \Gui\Data\DataSourceDatabase($this->db->select('shop_orders')));
		$grid2->add(new \Gui\Grid\Button('', 'Edit'))->setId('edit-btn-{id}');
		$grid2->add(new \Gui\Grid\Text('ID', '{id}'));
		$grid2->add(new \Gui\Grid\Text('Number', '{number}'));*/

		$data = array(
			'pageTitle' => 'Dev',
			'out' => $grid->render(),
			//'out2' => $grid2->render()
		);

		return $this->template('\Admin\views\dev', '\Admin\templates\main', $data);
	}

	public function form() {
		$form = new \Gui\Form('form', array(
		    'text1' => 'Text1 content',
            'textarea1' => "Text Area 1 content"
        ));
		$form->add(new \Gui\Form\Text("text1", "Text1"));
		$form->add(new \Gui\Form\Date("date1", "Date1"))->setToday();
		$form->add(new \Gui\Form\Text("text2", "Text2"));
		$form->add(new \Gui\Form\Password("password1", "Password1"));
		$form->add(new \Gui\Form\TextArea("textarea1", "TextArea1"));
		$form->add(new \Gui\Form\Select("select1", "Select1"))->setOptions(array("yes" => "Yes", "no" => "No"));
		$form->add(new \Gui\Form\Button("save", "Save"));
		$form->add(new \Gui\Form\Button("send", "Test send"));
        $form->add(new \Gui\Form\Button("redirect", "Test redirect"));

        $form->onClick('save', function($data) {
            
        });

		$data = array(
			'pageTitle' => 'Dev',
			'out' => $form->render()
		);

		return $this->template('\Admin\views\dev', '\Admin\templates\main', $data);
	}
}
