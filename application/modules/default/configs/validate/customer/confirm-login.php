<?php

return array(
	'mailAdress' => array(Zynas_Filter_Input::ALLOW_EMPTY => false),
	'password' => array(Zynas_Filter_Input::ALLOW_EMPTY => false),
	'confirmPass' => array(
		Zynas_Filter_Input::ALLOW_EMPTY => false,
		array('Identical', false, array('token' => 'password'), Zynas_Validate_Identical::NOT_SAME => 'Not same')
	)
);