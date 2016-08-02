<?php
	function generate_password($number) 
	{
		$result = '';
		
		$arr = ['a','b','c','d','e','f',
				'g','h','i','j','k','l',
				'm','n','o','p','r','s',
				't','u','v','x','y','z',
				'T','U','V','X','Y','Z',
				'1','2','3','4','5','6',
				'7','8','9','0'];
		
		for($i = 0; $i < $number; $i++) 
		{
			$index = rand(0, count($arr) - 1);
			
			$result .= $arr[$index];
		}
		
		return $result;
	}
	
	function max_value($value, $max_value) 
	{
		$value = $value < 0 ? 0 : $value;
		
		if ($max_value == -1)
		{
			return $value;
		}
		
		return $value > $max_value ? $max_value : $value;
	}
	
	function declOfNum($number, $titles)  
	{  
		$cases = [2, 0, 1, 1, 1, 2];
		return $titles[($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)]];
	}
