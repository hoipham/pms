<?php 

	return array(
		'name' => array(Zynas_Filter_Input::ALLOW_EMPTY => false),
		'email' => array(Zynas_Filter_Input::ALLOW_EMPTY => false, array('EmailAddress')),
			'sbt_pos' =>array(Zynas_Filter_Input::ALLOW_EMPTY=>false),
			'token' =>array(Zynas_Filter_Input::ALLOW_EMPTY=>false),
			'dep' =>array(Zynas_Filter_Input::ALLOW_EMPTY=>false)
	);

?>                                                                                                                                                                