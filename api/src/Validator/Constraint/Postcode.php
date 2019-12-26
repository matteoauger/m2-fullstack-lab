<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Postcode extends Constraint {
    public $message = 'The string "{{ string }}" must be a french postcode (5 digit integer).';
    
    public function validatedBy() {
        return \get_class($this).'Validator';    
    } 
}
?>