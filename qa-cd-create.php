<?php
class qa_cd_create_page
{
	function match_request($request)
	{
		$parts=explode('/',$request);
		return $parts[0]=='create-cd';
	}

	private $directory;
	private $urltoroot;


	public function load_module($directory, $urltoroot)
	{
		$this->directory=$directory;
		$this->urltoroot=$urltoroot;
	}


	public function suggest_requests() // for display in admin interface
	{
		return array(
				array(
					'title' => 'Create CD',
					'request' => 'create-cd',
					'nav' => 'M', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
				     ),
			    );
	}




	function process_request($request)
	{

		$qa_content=qa_content_prepare(); /* function to create the initial page description in $qa_content, including navigation and widgets*/
		if (qa_user_permit_error('plugin_cat_desc_permit_edit')) {
			$qa_content['error']=qa_lang_html('users/no_permission');
			return $qa_content;
		}

		/*set page title*/
		$qa_content['title']='Add Category Description';


		$cat_query='select title, categoryid from ^categories';

		$cat_query_response=qa_db_query_sub($cat_query);

		foreach ($cat_query_response as $row){
			$key = $row['categoryid'];
			$value = $row['title'];
			$cat_names[$key] = $value;
		}
		$qa_content['form']=array(
				'tags' => 'METHOD="POST" ACTION="'.qa_path_html('edit-cd').'"',

				'style' => 'tall', // could be 'wide'


				'fields' => array(
					array(
						'label' => 'Categories:',
						'type' => 'select',
						'options' => $cat_names,
						'tags' => 'NAME="catid" ID="catid" onchange="this.form.submit()"',
					     ),
					),
				);

		$qa_content['focusid']='catid';


		return $qa_content;
	}
}
?>
