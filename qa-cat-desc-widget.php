<?php

class qa_cat_descriptions_widget {

	function allow_template($template)
	{
		return ($template==='questions'
				|| $template==='unanswered'
				|| $template==='activity'
				|| $template==='hot'
				|| $template==='qa'
		       );
	}

	function allow_region($region)
	{
		return true;
	}

	function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
	{
		if(qa_is_mobile_probably()) return;
		require_once QA_INCLUDE_DIR.'qa-db-metas.php';

		$parts=explode('/', $request);
		$category = null;
		$category=$parts[count($parts) - 1];
		if($category == null || count($parts) == 1 && $template != 'qa' )
			return;
		$result = qa_db_query_sub("select categoryid from ^categories where tags like $",$category);
		$catid = qa_db_read_one_value($result, true);
		$description=qa_db_categorymeta_get($catid, 'description');
		if (!(qa_opt('plugin_cat_desc_html'))) $description=qa_html($description);
		$param['catid'] = $catid;
		$editurlhtml=qa_path('edit-cd', $param);

		$allowediting=!qa_user_permit_error('plugin_cat_desc_permit_edit');

		if (strlen($description)) {
			//echo '<SPAN CLASS="qa-cat-description" STYLE="font-size:'.(int)qa_opt('plugin_cat_desc_font_size').'px;">';
			echo '<SPAN CLASS="qa-cat-description">';
			echo $description;
			echo '</SPAN>';
			if ($allowediting)
				echo ' - <A HREF="'.$editurlhtml.'">edit</A>';

		} elseif ($allowediting)
		echo '<A HREF="'.$editurlhtml.'">'.qa_lang_html('plugin_cat_desc/create_desc_link').'</A>';
	}

	function option_default($option)
	{
		if ($option=='plugin_cat_desc_font_size')
			return 18;
		if ($option=='plugin_cat_desc_html')
			return 1;
		if ($option=='plugin_cat_desc_permit_edit') {
			require_once QA_INCLUDE_DIR.'qa-app-options.php';
			return QA_PERMIT_EXPERTS;
		}

		return null;
	}

	function admin_form(&$qa_content)
	{
		require_once QA_INCLUDE_DIR.'qa-app-admin.php';
		require_once QA_INCLUDE_DIR.'qa-app-options.php';

		$permitoptions=qa_admin_permit_options(QA_PERMIT_USERS, QA_PERMIT_SUPERS, false, false);

		$saved=false;

		if (qa_clicked('plugin_cat_desc_save_button')) {
			qa_opt('plugin_cat_desc_font_size', (int)qa_post_text('plugin_cat_desc_fs_field'));
			qa_opt('plugin_cat_desc_permit_edit', (int)qa_post_text('plugin_cat_desc_pe_field'));
			qa_opt('plugin_cat_desc_html', (bool)qa_post_text('plugin_cat_desc_sh_check'));
			$saved=true;
		}

		return array(
				'ok' => $saved ? 'Category descriptions settings saved' : null,

				'fields' => array(
					array(
						'label' => 'Starting font size:',
						'type' => 'number',
						'value' => (int)qa_opt('plugin_cat_desc_font_size'),
						'suffix' => 'pixels',
						'tags' => 'NAME="plugin_cat_desc_fs_field"',
					     ),

					array(
						'label' => 'Allow editing:',
						'type' => 'select',
						'value' => @$permitoptions[qa_opt('plugin_cat_desc_permit_edit')],
						'options' => $permitoptions,
						'tags' => 'NAME="plugin_cat_desc_pe_field"',
					     ),

				    array(
						'label' => 'Allow html in description',
						'type' => 'checkbox',
						'value' => (bool)qa_opt('plugin_cat_desc_html'),
						'tags' => 'NAME="plugin_cat_desc_sh_check"',
					     ),
					),

				'buttons' => array(
						array(
							'label' => 'Save Changes',
							'tags' => 'NAME="plugin_cat_desc_save_button"',
						     ),
						),
				);
	}

}
