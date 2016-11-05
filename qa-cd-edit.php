<?php
class qa_cd_edit_page
{
	function match_request($request)
	{
		$parts=explode('/',$request);
		return $parts[0]=='edit-cd';
	}

	function process_request($request)
	{		

		$qa_content=qa_content_prepare();
		$cat=qa_post_text('catid');
		if($cat == null){
			if(isset($_GET['catid']))
				$cat=$_GET['catid'];
		}

		if($cat == null)
			return $qa_content;
		$select = "select title from ^categories where categoryid = #";
		$titlerow = qa_db_query_sub($select, $cat);
		$title = qa_db_read_one_value($titlerow);
		$qa_content['title']=qa_lang_html_sub('plugin_cat_desc/edit_desc_for_x', qa_html($title));

		if (qa_user_permit_error('plugin_cat_desc_permit_edit')) {
			$qa_content['error']=qa_lang_html('users/no_permission');
			return $qa_content;
		}

		require_once QA_INCLUDE_DIR.'qa-db-metas.php';

		if (qa_clicked('dosave')) {
			require_once QA_INCLUDE_DIR.'qa-util-string.php';

			qa_db_categorymeta_set($cat, 'description', qa_post_text('catdesc'));
			$select = "select  backpath from ^categories where categoryid = #";
			$tagrow = qa_db_query_sub($select, $cat);
			$backpath = qa_db_read_one_value($tagrow);
			qa_redirect('questions/' . implode( '/', array_reverse(explode('/', $backpath)) ));
		}

		$qa_content['form']=array(
				'tags' => 'METHOD="POST" ACTION="'.qa_self_html().'"',

				'style' => 'tall', // could be 'wide'


				'fields' => array(
					array(
						'label' => $title.' Description:',
						'type' => 'text',
						'rows' => 4,
						'tags' => 'NAME="catdesc" ID="catdesc"',
						'value' => qa_html(qa_db_categorymeta_get($cat, 'description')),
					     ),

					),
				'hidden' => array(
					array(
						'tags' => 'Name="catid"',
						'value' => $cat,
					     )
					),
				'buttons' => array(
						array(
							'tags' => 'NAME="dosave"',
							'label' => qa_lang_html('plugin_cat_desc/save_desc_button'),
						     ),
						),
				);
		$qa_content['focusid']='catdesc';
		return $qa_content;

		/* END of main */

	}
}
?>
