<?php

/**
 * CookingNutritiousTools
 *
 * Utulities for use with the CookingNutritious Response from client
 *
 * @version 1.0
 * @author jgreathouse
 */

namespace cookingnutritious\CookingNutritiousClient;


class CookingNutritiousTools
{
    
    public static function getNutritionKeys()
    {
        return array(
            'servings',
            'serving_size',
            'calories',
            'calories_from_fat',
            'total_fat',
            'saturated_fat',
            'trans_fat',
            'cholesterol',
            'sodium',
            'potassium',
            'carbohydrate',
            'fiber',
            'sugars',
            'protein',
            'vitamin_a',
            'vitamin_c',
            'calcium',
            'iron',
            'vitamin_d',
            'vitamin_e',
            'vitamin_k',
            'thiamin',
            'riboflavin',
            'niacin',
            'vitamin_b6',
            'folate',
            'vitamin_b12',
            'biotin',
            'pantothenic_acid',
            'phosphorus',
            'iodine',
            'magnesium',
            'zinc',
            'selenium',
            'copper',
            'manganese',
            'chromium',
            'molybdenum',
            'chloride'
        );
    }
    
    public static function getDailyValues()
    {
        return array(
            'total_fat' =>	65,
            'saturated_fat' =>	20,
            'cholesterol'	=> 300,
            'sodium' =>	2400,
            'potassium' => 3500,
            'carbohydrate' => 300,
            'fiber'	=> 25,
            'protein' => 50,
            'vitamin_a' =>	5000,
            'vitamin_c' => 60,
            'calcium' => 1000,
            'iron' => 18,
            'vitamin_d' => 400,
            'vitamin_e' => 30,
            'vitamin_k' =>	80,
            'thiamin' => 1.5,
            'riboflavin' =>	1.7,
            'niacin' => 20,
            'vitamin_b6' =>	2,
            'folate' => 400,
            'vitamin_b12' => 6,
            'biotin' => 300,
            'pantothenic_acid' => 10,
            'phosphorus' =>	1000,
            'iodine' =>	150,
            'magnesium' =>	400,
            'zinc' => 15,
            'selenium' => 70,
            'copper' =>	2,
            'manganese' => 2,
            'chromium' => 120,
            'molybdenum' =>	75,
            'chloride' =>	3400
        );
    }
    
    public static function getNutritionTable($data) 
    {
        $dv = self::getDailyValues();
        $keys = self::getNutritionKeys();
        $tableStr = file_get_contents(realpath(dirname(__FILE__)).'/data/nutrition_facts_table.html');
        foreach($keys as $key) {
            if (isset($data->{$key})) {
                $tableStr = preg_replace('/__' . $key . '__/', $data->{$key}, $tableStr);
                if (FALSE !== (strpos($tableStr , '__' . $key . '_pdv__'))) {
                    $tableStr = preg_replace('/__' . $key . '_pdv__/',  sprintf("%.2f%%", ($data->{$key}/$dv[$key]) * 100), $tableStr);
                }
            }
        }
        return $tableStr;
    }

}
